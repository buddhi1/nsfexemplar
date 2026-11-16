# Modern Course Exemplars — project website

Website for *Modern Course Exemplars infused with Parallel and Distributed Computing for the
Introductory Computing Course Sequence* (CDER Center, NSF Award
[#2321015](https://www.nsf.gov/awardsearch/showAward?AWD_ID=2321015)), publishing the e-book
*Toward Modern Models of Introductory Computing Courses*.

No build step, no framework, no dependencies. Six PHP pages sharing one header and one footer, one
stylesheet, two scripts. Upload the folder to any host with PHP and it works.

---

## Contents

- [File structure](#file-structure)
- [Adding a news item](#adding-a-news-item)
- [Deploying](#deploying)
- [Running it locally](#running-it-locally)
- [Editing the header, footer or project name](#editing-the-header-footer-or-project-name)
- [Institution cards and logos](#institution-cards-and-logos)
- [Download and visit counters](#download-and-visit-counters)
- [Deep links into the eBook explorer](#deep-links-into-the-ebook-explorer)
- [Editing the exemplar and activity data](#editing-the-exemplar-and-activity-data)
- [Updating the PDF](#updating-the-pdf)
- [Chapter data](#chapter-data)
- [Activity facets](#activity-facets)
- [Publishing and citing the book](#publishing-and-citing-the-book)

---

## File structure

```
index.php · project.php · ebook.php · team.php · research.php · resources.php
                        the six pages, one per nav item
contact.php             adoption / contact form — linked from the project page and
                        the footer, deliberately not a nav item
download.php            counted download endpoint — every PDF link goes through it
stats.php               private download statistics: by version, country, region; CSV export

partials/
  header.php            nav bar, <head>, project name   ← the only place these live
  footer.php            footer, sponsor logos, colophon

content/                ← everything you are likely to edit
  news.json             home-page news items
  institutions.json     the eight partner cards

assets/
  site.css              design system: tokens, layout, components, print styles
  site.js               theme, nav, search explorer, hero carousel, news pager
  data.js               exemplars, activities, chapter manifest
  logos/                NSF, CDER, TCPP marks
  logos/institutions/   partner logos (see the README in there)
  book_cover/           the front cover shown in "Featured e-book" on the home page

lib/counters.php        download and visit counters (JSON tally + CSV log, file-locked)
lib/contact.php         contact form: validation, spam traps, mail()
data/counters.json      the live tally; created automatically, never in git
data/downloads.csv      one row per download (time, IP, file, page); never in git
data/geo-cache.json     IP → location lookups made by stats.php; never in git
data/admin.php          the stats.php password; create it on the server, never in git
data/counters_example.json  the shape of the tally, all zeros — tracked, for reference
data/admin_example.php  template for data/admin.php — tracked

cder_exemplar_cs1_cs2_first_edition.pdf   the First Edition (Nov. 2026, CS1 and CS2) — the volume of record
cder_exemplar_cs1_cs2_early_release.pdf   the early release (Aug. 2026, CS1 only), kept for anyone who cited it

.htaccess               pretty URLs, protects internals — ships ready to use
deploy/
  apache-pdc.conf       for serving this working tree from local Apache
```

The six pages match the agreed sitemap one-to-one, and so does the navigation bar.

---

## Adding a news item

Edit **`content/news.json`** and add an object to the top of `items` (newest first):

```json
{
  "date":  "2026-08",
  "label": "Aug 2026",
  "title": "Headline goes here",
  "body":  "One or two sentences. Plain text — no HTML.",
  "link":  { "href": "ebook.php#chapters", "text": "Read more" }
}
```

| Field | Notes |
|---|---|
| `date` | ISO-ish, used by `<time datetime>`. `2026-08` or `2026-08-14` both work |
| `label` | What readers see, e.g. `Aug 2026` |
| `title` | Headline |
| `body` | One or two sentences |
| `link` | Optional. Use `null` for none. Internal (`ebook.php#cs2`) or external |

Save, upload the file, refresh. Nothing else to touch — no rebuild, no code change.

The home page groups items **three to a page** and steps through them every 7 seconds, with dots and
an `n–m of total` counter. It pauses on hover, on keyboard focus, when scrolled out of view, and when
the tab is hidden. With three items or fewer the pager hides itself. To change the grouping, edit
`PER_PAGE` in the news block of `assets/site.js`.

**Validate before uploading** — one stray comma and the news list renders empty:

```bash
python3 -m json.tool content/news.json > /dev/null && echo OK
```

---

## Deploying

### First deploy to cPanel

1. Upload the **contents** of this folder into `public_html` (not the folder itself). If the site
   lives under a path, upload into that subdirectory instead — all internal links are relative and
   the rewrite rules derive the base from the request, so both work unchanged.
2. **Make sure `.htaccess` came across.** It is a dotfile, so most upload tools and cPanel's File
   Manager hide it by default — in File Manager, tick Settings → "Show Hidden Files (dotfiles)".
   This one file is what gives you `/ebook` instead of `/ebook.php`. Without it the site still
   works, but only at the `.php` addresses.
3. Make sure PHP can write `data/`. It is created on first request; if the host blocks that,
   `chmod 755 data` after uploading.
4. Once `https://` loads correctly, enable the HTTPS redirect: uncomment the four lines in the
   "Force HTTPS" block near the bottom of `.htaccess`. It ships disabled on purpose — turning it on
   before a certificate exists redirects every request to an address that does not answer.
5. Load the site and confirm the home page shows figures under "Usage".

Nothing else needs configuring. Every cPanel host runs PHP, and the site uses no extension beyond
the defaults.

### If URLs still need `.php`

Almost always one of these:

- **`.htaccess` did not upload.** Check for hidden files; this is by far the most common cause.
- **`AllowOverride` is off.** Rare on shared cPanel, but if the host disables `.htaccess` overrides
  the rules are ignored silently. Ask support to enable `AllowOverride All` for the directory.
- **`mod_rewrite` is unavailable.** Also rare. The rules are wrapped in `<IfModule>`, so they are
  skipped rather than erroring — which looks identical to the file being missing.

To confirm the file is being read at all, add a deliberate typo as the first line and reload: a
500 error means Apache is reading it, and any other result means it is not.

### Updating

Pull or re-upload the changed files. **Never upload `data/counters.json`** — it is the live tally,
and is git-ignored for exactly that reason. Leave `ebook_copy.php` (a local backup, git-ignored) out of
any File Manager upload, or it becomes a public page.

**Caching.** `.htaccess` lets browsers keep static files (CSS, JS, images, PDFs) for one day, so a
redeploy reaches everyone by the next day. The stylesheet, both scripts and the book cover are also
requested as `file?v=<modification time>` (`asset_url()` in `partials/header.php`), so a new upload of
those is picked up on the very next page load. Pages themselves are never cached.

**Retired file name.** `cder_exemplar_cs1_cs2.pdf` (the early release, before the first edition got its
own name) redirects to `cder_exemplar_cs1_cs2_early_release.pdf`, so old bookmarks still work.

---

## Running it locally

```bash
php -S 127.0.0.1:8129
# open http://127.0.0.1:8129/
```

PHP's built-in server is enough for everything. `python3 -m http.server` will **not** work — it
serves the `.php` files as text instead of running them — and opening a page from the filesystem
does not work at all, since `file://` cannot run PHP.

Two differences from production worth knowing: the dev server ignores `.htaccess`, so extensionless
URLs are unavailable and `data/counters.json` is fetchable; and your own `curl` requests will not
move the counters, because `curl` is in the crawler filter.

To test against Apache instead, see `deploy/apache-pdc.conf`.

---

## Editing the header, footer or project name

Both live in `partials/`. Change one file and every page updates.

Each page is a thin wrapper:

```php
<?php
$PAGE       = 'ebook';            // which nav item is highlighted
$PAGE_TITLE = 'The eBook';        // project name is appended automatically
$DESC       = 'Search eight CS1 exemplars and …';
include 'partials/header.php';
?>

  <main id="content" tabindex="-1"> … page content … </main>

<?php include 'partials/footer.php'; ?>
```

- **Nav item** — add a row to `$NAV` in `partials/header.php` and give the new page a matching
  `$PAGE`. The current page is highlighted server-side.
- **Footer links** — `$FOOTER_COLS` in `partials/footer.php`, a plain array of columns and links.
- **Contact / repository / Discord** — `$CONTACT`, `$REPO`, `$DISCORD` at the top of
  `partials/footer.php`.
- **Project name** — `$PROJECT_NAME`, `$PROJECT_SHORT`, `$PROJECT_TAG` at the top of
  `partials/header.php`. Masthead, page titles and footer all follow.
- **Book filenames** — `BOOK_FILES` in `lib/counters.php` (one entry per version, with its
  `page_offset`) and `SITE.book.pdf` in `assets/data.js`.
- **Citations** — `$CITATION` (first edition) and `$CITATION_EARLY` in `partials/header.php`,
  built from the wording each volume prints on its title page; `$BIBTEX` and `$BIBTEX_EARLY`
  beside them. Every copy button reads from these.
- **DOIs** — `$DOI` (first edition, `10.14809/20.500.12588/7992.2`) and `$DOI_EARLY`
  (`10.14809/4mcf-5jmt`) in `partials/header.php`. `doi_badge($DOI, $DOI_URL, '', 'First edition')`
  renders a badge with the edition named in it; both badges appear in the home and eBook heroes and
  the eBook citation card.
- **Book cover** — `assets/book_cover/`. The page serves the WebP and falls back to the PNG,
  so replace both. After dropping in a new `book_cover.png`, regenerate the WebP with:

  ```bash
  python3 -c "from PIL import Image; \
    Image.open('assets/book_cover/book_cover.png').convert('RGB') \
      .save('assets/book_cover/book_cover.webp','WEBP',quality=90,method=6)"
  ```

  Quality 90 keeps the author list on the cover legible; lower settings smear it. If the new
  art is not 576×864, update the `width`/`height` on the `<img>` in `index.php` to match, or the
  browser will reserve the wrong space while it loads.

The copyright year comes from `date('Y')`, so it never goes stale.

---

## Institution cards and logos

The eight partner cards on the home page come from **`content/institutions.json`**. Each card
deep-links to that institution's entry in the eBook explorer, so keep the `id` values matching the
`EX[]` ids in `assets/data.js`.

Logos live in `assets/logos/institutions/` — see the README there for filenames, the shape that fits
the slot, and a note on brand permissions. Set `"logo": null` and the card draws the `mono` monogram
instead, so a missing logo never looks broken.

---

## Download and visit counters

Three numbers on the eBook page — site visits, first-edition downloads and early-release downloads —
rendered by `partials/usage.php`, plus a private statistics page at `/stats`.

`lib/counters.php` keeps two files in `data/`, each written under an exclusive `flock` so
concurrent hits cannot corrupt them. No database, no extra extension.

- **`data/counters.json`** is the running tally the site displays: visits, and downloads per
  version (`v1`, `early`).
- **`data/downloads.csv`** logs every download: UTC time, version, link key, page, whether it was
  counted, IP address, referrer and user agent. It opens directly in Excel.
- **Downloads** go through `download.php?f=<key>`, which records the hit then redirects to the
  real PDF so the browser still handles range requests and resuming. `f=book` (and `f=v1`) is the
  first edition — counted under the key `v1`, labelled "First edition"; `f=early` is the early
  release. The old per-chapter keys (`f=03-cs1-tntech`) resolve to the first edition, so links
  published before chapters were merged keep working.
- **Repeats** — the same address fetching the same file again within 30 minutes (`REPEAT_WINDOW`)
  is logged but not counted, so one reader clicking through chapter links counts once.
- **Before the split** there was one tally per link key. All of it was the early release, so on
  first read those totals are folded into `early` and kept under `legacy` for the record. They
  predate the log, so they have no time or location.
- **Visits** are counted once per browser session, not per page view. This sets a session cookie.
  Visits are a tally only — no IP address or other per-visitor record is kept for them.
- **Crawlers** are filtered by user agent — including `curl`, `wget` and headless Chrome. Edit the
  pattern in `counters_is_bot()`. Crawler hits are neither counted nor logged.
- **If `data/` is unwritable** the site carries on and simply stops counting. No errors, no broken
  pages.
- **Resetting** — delete `data/counters.json` and `data/downloads.csv`; they are recreated on the
  next request.

### Statistics page (`/stats`)

Downloads per version, by country and by region, the latest 200 downloads with time, IP and
location, and an **Export CSV** link that returns the whole log with country, region and city added.

- **Setup** — create `data/admin.php` in cPanel's File Manager with
  `<?php return ['password' => 'a-long-password'];` (see `data/admin_example.php`). Until it
  exists the page shows only these instructions.
- **Locations** are looked up when the page is opened, not when a file is downloaded, so a
  download never waits on an outside service. New addresses go to ip-api.com's free batch
  service (country, region, city; no key needed), and the results are cached in
  `data/geo-cache.json`, so each address is looked up once. Needs outbound HTTP from the host
  (cURL or `allow_url_fopen`); if it is blocked, the page still works and lists locations as
  "Not yet looked up".
- **Behind a proxy** — the IP is `REMOTE_ADDR`. If the site is ever put behind Cloudflare or
  another proxy, every visitor will show the proxy's address; change `counters_client_ip()`.

### Privacy

`data/downloads.csv` holds IP addresses, which count as personal data under GDPR and similar
laws. It is never served (`data/.htaccess`) and never committed. Say on the site that downloads are
logged with IP address for usage statistics, and trim old rows from the log when they are no
longer needed. Visits are not logged.

`data/counters.json` is deliberately **not** tracked: it is live state, so committing it would
mean every deploy overwrote real figures with whatever numbers the last developer happened to
have locally. `data/counters_example.json` is tracked in its place — same structure, every count
zero — so the format is documented in the repo without any figure in git ever being mistaken for
a real one. It is reference only; nothing reads it at runtime, and the live file is created on
first request whether or not it exists.

---

## Deep links into the eBook explorer

Anywhere on the site can open the explorer in a particular state:

| Link | Result |
|---|---|
| `ebook.php#activities` | explorer in **by activity** mode |
| `ebook.php#institutions` | explorer in **by institution** mode |
| `ebook.php?activity=flag-maker#explorer` | activity mode with that activity's detail sheet open |
| `ebook.php?institution=casper#explorer` | institution mode with that exemplar's sheet open |

Activity ids are the `id` fields in `ACTIVITIES[]`; institution ids are the `id` fields in `EX[]`,
both in `assets/data.js`. The six cards in "What Actually Gets Taught" use the `?activity=` form.

---

## Editing the exemplar and activity data

`assets/data.js` holds the material the eBook explorer searches:

- `EX[]` — the eight institutions. Drives the search results, the detail sheets and the team cards
  on `team.php`. The top-level descriptor fields (`textbook`, `prereq`, `context`, `topics`,
  `outcomes`, `evidence`, `cs1`) are the CS1 chapter's; `cs1lang` overrides `lang` where CS2 added a
  language. `cs2d[]` holds each CS2 package's descriptor elements (Webster has two), `cs2` its
  activities, `ch` every chapter with its printed start page, and `repo2`/`repo3` the CS2 folders.
- `ACTIVITIES[]` — the 31 classroom activities: 17 CS1 from Tables 1.1 and 1.2 of Chapter 1, 14 CS2
  from Tables 10.1 and 10.2 of Chapter 10, each tagged with `course`.
- `CHAPTERS[]` — the chapter download table. Page numbers are printed page numbers.
- `SITE` — repository URL, contact address and book filename.

Prose specific to one page is written directly in that page's PHP file.

---

## Updating the PDF

The **First Edition** is the published volume: `cder_exemplar_cs1_cs2_first_edition.pdf`, 517 PDF
pages, printed pages 1–515. The early release stays alongside it as
`cder_exemplar_cs1_cs2_early_release.pdf`. Chapters are not published separately — a chapter cut out
of the volume loses the cross-references and the shared bibliography (pp. 509–515), so the site offers
chapter-level access by opening the full file at the right page instead.

**Page numbers on the site are the book's printed page numbers** — what its own contents pages give
and what a PDF viewer's page box shows (the PDF carries page labels). A link writes the printed number,
`download.php?f=book&p=28`, and `download.php` adds the file's `page_offset` (2 for the first edition:
the cover and the title page come before printed page 1) to open `…pdf#page=30`. No link has to know
the offset.

To drop in a corrected build of the same edition:

1. Replace `cder_exemplar_cs1_cs2_first_edition.pdf`, keeping the filename. The counters and every
   link on the site point at that name.
2. Regenerate the chapter data (below). It reads page ranges, sections and abstracts from the PDF
   itself, so nothing is typed in.
3. If any chapter moved, update the hand-written page links: the `ch` entries in `EX[]` in
   `assets/data.js`, and the `p=` links in `index.php`, `ebook.php`, `research.php`,
   `resources.php`, `project.php` and `team.php`. `grep -n "p=[0-9]" *.php assets/*.js` lists them.
4. Check `page_offset` still holds: open the PDF and compare a printed page number with its position
   in the file.

For a **new edition**, also add it to `BOOK_FILES` (or point `v1` at the new file and give the old one
its own key, as the early release has), add its DOI and citation to `partials/header.php`, and extract
the new cover with `pdfimages -f 1 -l 1 -png <pdf> cover` (see *Book cover* above).

### Chapter-level links

`download.php?f=book&p=28` counts the download and redirects to `…pdf#page=30`, which PDF viewers
honour.

**On the site these links open in a reader overlay** rather than replacing the page (the "PDF reader"
block at the end of `assets/site.js`). The overlay shows the browser's own PDF viewer at the requested
page, with a chapter list built from `CHAPTERS[]`, plus *Open in new tab*, *Download* and Close (Esc or
a click outside also close it). Opening it loads the link itself, so the download is counted once per
opening; jumping between chapters inside it is not counted again. Browsers that cannot show a PDF
inline (`navigator.pdfViewerEnabled === false`, e.g. Android Chrome) get plain links in a new tab
instead, and Ctrl/⌘-click or middle-click always behaves as an ordinary link. The file names and page
offsets reach the script through `window.BOOK_FILES`, printed by `partials/footer.php` from
`BOOK_FILES` in `lib/counters.php`. The old per-chapter keys (`?f=03-cs1-tntech`) still resolve to the volume rather than 404ing,
so links published before this change keep working.

---

## Chapter data

`CHAPTERS[]` in `assets/data.js` is generated from the PDF, not written by hand:

```bash
python3 tools/extract.py && python3 tools/gen.py
```

`extract.py` reads the structure from the PDF's own bookmarks: each top-level bookmark whose page
starts "Chapter N" is a chapter, running to the page before the next top-level bookmark; its sections
are the bookmarks beneath it, numbered by position and checked against the headings in the text (a
heading the book does not number keeps its title and loses the number). `gen.py` adds what the PDF
cannot supply. Per chapter:

| Field | Source |
|---|---|
| `title` | `META` in `tools/gen.py` — the site's short form of the chapter title |
| `subShort` / `subFull` | `META` — a short label for the table, the printed subtitle on hover |
| `authors` / `inst` | `AUTHORS` in `gen.py` — the title page byline, with affiliations |
| `part` | Front, Part I · CS1 (chapters 1–9), Part II · CS2 (chapters 10–18), Back |
| `excerpt` | the PDF — the opening sentences of the chapter abstract |
| `sections` | the PDF bookmarks, three levels deep |
| `pages` / `start` | the PDF — printed page numbers from its page labels |
| `materials` | `DIR` in `gen.py` — the chapter's folder under `CS1/` or `CS2/` in the repository |

A chapter added to the book needs one line in each of `META`, `AUTHORS` and `DIR`; `gen.py` stops
with a message if one is missing. Abstracts are read with poppler's `pdftotext` where it is installed
(`apt install poppler-utils`), because pypdf drops the spaces on some pages of this volume. And
**de-hyphenating extracted text rejoins words that were legitimately hyphenated across a line break**,
so check the abstracts for fused words (`institutionspecific`, `handson`) and add any new ones to
`REPAIR` in `gen.py` before committing.

---

## Activity facets

The activity finder filters on eight fields, defined in `ACT_FACETS` in `assets/data.js`. Most are
bucket values every activity carries, so a new activity needs all of them or it will not appear under
any filter:

| Field | Kind | Values |
|---|---|---|
| `course` | single | CS1 · CS2 — CS1 activities come from Chapter 1, CS2 ones from Chapter 10 |
| `kind` | single | Unplugged · Plugged-in · Visualization · Codeless · Assessment |
| `time` | single | Under 30 minutes · One class · Multi-session · Asynchronous · Course-wide |
| `prep` | single | No preparation · Classroom materials · Browser only · Software setup · Hardware |
| `lang` | list | Language-independent · Java · C++ · C · Python |
| `topic` | list | Data parallelism · Task parallelism · Distributed data access · Event-driven processing · Performance & speedup · Synchronization |
| `anchor` | list | Loops · Arrays & collections · Variables & expressions · Functions · Objects & classes · Conditionals · Graphics & output · Libraries & data · Recursion · Sorting & searching · Data structures · Algorithm analysis |
| `used` | list | institution ids, rendered as their short names |

`used` is **taken from Tables 1.1 and 1.2 of Chapter 1** (CS1) and **Tables 10.1 and 10.2 of
Chapter 10** (CS2), which are the volume's own record of which institution ran which activity. When a new build of the PDF lands, re-read those two tables before
changing anything here — the site had drifted from them (MSU credited with the shared animations,
Casper and MSU with Greenfoot) because the values had been copied from an earlier edition.

`where` carries that table's "Duration / where used" column verbatim, so the claim on the page can be
traced back to a section number in the book.

The same two tables are what the Resources page links for activity timings — they hold the duration for
every activity in one place, which no single institutional chapter does.

`time` and `prep` are buckets for the prose in `duration` and `setup`, which are still shown in full on
the card and in the detail sheet — the bucket is for filtering, the prose is for reading. Adding a value
that is not in `ACT_FACETS` means the activity is simply never matched by that filter, silently, so keep
the two in step.

Each activity also carries `materials`, a repository URL. Activities run at one institution point at
that institution's chapter folder; ones run at several point at `CS1/` or `CS2/` (the common chapters 1 and 10 have no folder), and
the detail sheet then links each institution's own chapter for the same course through `EX[].ch`.

---

## The adoption / contact form

`contact.php` posts to itself and re-renders server-side, so it works with JavaScript off. Delivery is
plain `mail()` to `CONTACT_EMAIL` in `lib/contact.php` — the one place the address is written; the
footer and every page read it from there.

Three spam traps, no CAPTCHA and no third-party service:

| Trap | What it catches |
|---|---|
| Honeypot field | Anything that fills in a field positioned off-screen and hidden from assistive tech |
| Minimum fill time | Posts arriving less than `CONTACT_MIN_SEC` after the form was rendered |
| Per-session token | Replays and cross-site posts; also stops one submission being sent twice |

All three fail as the same generic message, so a bot learns nothing about which one caught it, and a
person who trips one still sees the email address.

**If mail is not going out**, the page says so and shows the address instead of pretending it worked.
Check with your host that `mail()` is enabled — it is on essentially every cPanel plan. The envelope
`From` is `no-reply@<your domain>`, never the visitor's address, because sending as the visitor fails
SPF and gets the message dropped silently; their address is in `Reply-To`.

To change the fields, edit the form in `contact.php` and the matching lines in `contact_handle()`.

---

## Publishing and citing the book

Cite the **page**, not the file: `https://yoursite.org/ebook`.

A page URL survives renames and new editions; a file URL does not — renaming the
PDF once already broke every link to the old name. The page also gives readers context, chapter-level
downloads and a route to the rest of the site, and it is the only path that counts downloads.

- **Cite:** `/ebook`
- **Download from it:** `download.php?f=book`
- The PDF stays reachable at its own URL for anyone who wants it; that route just is not counted

Freeze the filename from here on. If you want a prettier download URL, add a rewrite alias rather
than renaming the file again. Each edition has its own DOI (first edition
`10.14809/20.500.12588/7992.2`, early release `10.14809/4mcf-5jmt`) — those survive even a domain
change, which is what citations in published papers need.

---

## Notes

- **Accessibility** — skip link, visible focus rings, `aria-current` on the active nav item, live
  regions on search results and the news counter, `prefers-reduced-motion` respected throughout, and
  a `forced-colors` block.
- **Dark mode** — follows the system preference and can be toggled; the choice persists for the
  session and is applied before first paint, so there is no flash.
- **Without JavaScript** — every page renders and every link works. Header, footer, news and
  institution cards are server-rendered; the search explorer, chapter table and team cards degrade
  to a message pointing at the full PDF.
- **Print** — a print stylesheet strips the chrome and expands external link URLs.
- **Repo size** — the PDFs are committed so a clone is deployable as-is. If the volume is replaced
  often, consider Git LFS before the history grows large.
