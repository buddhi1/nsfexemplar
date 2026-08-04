<?php
/* =============================================================================
   Adoption / contact form — validation, spam traps and delivery.

   No database, no third-party service, no CAPTCHA. Three cheap traps stop the
   overwhelming majority of automated submissions:

     1. a honeypot field a human never sees and never fills in,
     2. a minimum fill time — bots post the instant they parse the page,
     3. a per-session token, which also blocks cross-site posting.

   Delivery is plain mail(). The envelope From is the site's own address, not
   the visitor's, because sending as the visitor fails SPF at most hosts and
   gets the message dropped silently. Their address goes in Reply-To, which is
   what you actually want when you hit reply.
   ========================================================================== */

const CONTACT_EMAIL   = 'contact@nsfexemplar.cdercenter.org';
const CONTACT_MIN_SEC = 3;      // faster than this is not a person
const CONTACT_MAX_LEN = 4000;

/** Course options, value => label. */
function contact_courses(): array {
    return ['CS1' => 'CS1', 'CS2' => 'CS2', 'Systems' => 'Systems', 'Other' => 'Other'];
}

/** Start a session if one is not already running (header.php usually has). */
function contact_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params(['lifetime' => 0, 'httponly' => true, 'samesite' => 'Lax']);
        @session_start();
    }
}

/** Token for this session, created on first use. */
function contact_token(): string {
    contact_session();
    if (empty($_SESSION['contact_token'])) {
        $_SESSION['contact_token'] = bin2hex(random_bytes(16));
    }
    /* When the form is rendered, so the fill time can be checked on post. */
    $_SESSION['contact_shown'] = time();
    return $_SESSION['contact_token'];
}

/** Strip anything that could inject a second header line. */
function contact_header_safe(string $s): string {
    return trim(str_replace(["\r", "\n", "\0"], ' ', $s));
}

/** Single-line body values, so a pasted newline cannot fake the report layout. */
function contact_line(string $s): string {
    return preg_replace('/\s+/u', ' ', trim($s)) ?? '';
}

/**
 * Domain for the envelope From. The Host header can carry a port, and locally
 * it is an address rather than a name; neither is a usable mail domain.
 */
function contact_mail_domain(): string {
    $host = strtolower(contact_header_safe($_SERVER['HTTP_HOST'] ?? ''));
    $host = preg_replace('/:\d+$/', '', $host);
    return preg_match('/^[a-z0-9.-]+\.[a-z]{2,}$/', $host) ? $host : 'localhost';
}

/**
 * Handle a POST if there is one.
 *
 * @return array{status:string, errors:array<string,string>, values:array<string,mixed>}
 *         status is '' (nothing submitted), 'ok', 'undelivered' or 'error'.
 */
function contact_handle(): array {
    $values = [
        'name' => '', 'email' => '', 'institution' => '', 'courses' => [],
        'language' => '', 'term' => '', 'interest' => '',
        'consultation' => false, 'followup' => false,
    ];
    /* Always, and before the page prints anything: the token is minted while
       the form renders, and session_start() cannot set its cookie once output
       has begun. Relying on the visit counter to have started the session does
       not work — it skips anything with a crawler user-agent. */
    contact_session();

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        return ['status' => '', 'errors' => [], 'values' => $values];
    }

    $errors = [];
    $post   = static fn(string $k): string => trim((string) ($_POST[$k] ?? ''));

    $values['name']         = $post('name');
    $values['email']        = $post('email');
    $values['institution']  = $post('institution');
    $values['language']     = $post('language');
    $values['term']         = $post('term');
    $values['interest']     = $post('interest');
    $values['consultation'] = isset($_POST['consultation']);
    $values['followup']     = isset($_POST['followup']);
    $values['courses']      = array_values(array_intersect(
        array_keys(contact_courses()),
        (array) ($_POST['courses'] ?? [])
    ));

    /* --- traps. These fail silently as a generic error: a bot learns nothing,
       and a person who somehow trips one still sees the fallback address. --- */
    if ($post('website') !== '') {
        return ['status' => 'error', 'errors' => [], 'values' => $values];
    }
    $shown = (int) ($_SESSION['contact_shown'] ?? 0);
    if ($shown === 0 || time() - $shown < CONTACT_MIN_SEC) {
        return ['status' => 'error', 'errors' => [], 'values' => $values];
    }
    if (!hash_equals((string) ($_SESSION['contact_token'] ?? ''), $post('token'))) {
        return ['status' => 'error', 'errors' => [], 'values' => $values];
    }

    /* --- validation --- */
    if ($values['name'] === '')        $errors['name'] = 'Please give a name we can reply to.';
    if ($values['institution'] === '') $errors['institution'] = 'Please name your institution.';
    if ($values['email'] === '') {
        $errors['email'] = 'We need an address to reply to.';
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'That does not look like an email address.';
    }
    if (mb_strlen($values['interest']) > CONTACT_MAX_LEN) {
        $errors['interest'] = 'Please keep this under ' . number_format(CONTACT_MAX_LEN) . ' characters.';
    }
    if ($errors) {
        return ['status' => 'error', 'errors' => $errors, 'values' => $values];
    }

    /* --- compose --- */
    $lines = [
        'Name:          ' . contact_line($values['name']),
        'Email:         ' . contact_line($values['email']),
        'Institution:   ' . contact_line($values['institution']),
        'Course:        ' . ($values['courses'] ? implode(', ', $values['courses']) : '—'),
        'Language:      ' . ($values['language'] !== '' ? contact_line($values['language']) : '—'),
        'Possible term: ' . ($values['term'] !== '' ? contact_line($values['term']) : '—'),
        '',
        'Consultation requested:  ' . ($values['consultation'] ? 'yes' : 'no'),
        'May follow up on use:    ' . ($values['followup'] ? 'yes' : 'no'),
        '',
        'Of interest',
        '-----------',
        $values['interest'] !== '' ? $values['interest'] : '(nothing given)',
        '',
        '-- ',
        'Sent from the adoption form at ' . contact_header_safe($_SERVER['HTTP_HOST'] ?? 'the project site'),
    ];

    $subject = 'Adoption enquiry — ' . contact_header_safe($values['institution']);
    $headers = implode("\r\n", [
        'From: CDER exemplar site <no-reply@' . contact_mail_domain() . '>',
        'Reply-To: ' . contact_header_safe($values['name']) . ' <' . contact_header_safe($values['email']) . '>',
        'Content-Type: text/plain; charset=utf-8',
        'MIME-Version: 1.0',
        'X-Mailer: PHP/' . PHP_VERSION,
    ]);

    $sent = @mail(CONTACT_EMAIL, $subject, implode("\n", $lines), $headers);

    /* One submission per token, so a refresh cannot re-send. */
    unset($_SESSION['contact_token'], $_SESSION['contact_shown']);

    return [
        /* 'undelivered' is not a failure the visitor caused — the page offers
           the address directly so the enquiry is not simply lost. */
        'status' => $sent ? 'ok' : 'undelivered',
        'errors' => [],
        'values' => $values,
    ];
}
