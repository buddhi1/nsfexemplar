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
- [When the CS2 release lands](#when-the-cs2-release-lands)
- [Publishing and citing the book](#publishing-and-citing-the-book)

---

## File structure

```
index.php · project.php · ebook.php · team.php · research.php · resources.php
                        the six pages, one per nav item
download.php            counted download endpoint — every PDF link goes through it

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

lib/counters.php        download and visit counters (JSON store, file-locked)
data/counters.json      the live tally; created automatically, never in git
data/counters_example.json  the shape of that file, all zeros — tracked, for reference

cder_exemplar_cs1_cs2.pdf   the full volume — the only published PDF

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
and is git-ignored for exactly that reason.

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
- **Book filename** — `BOOK_FILE` in `lib/counters.php` and `SITE.book.pdf` in `assets/data.js`.
- **Citation** — `$CITATION` in `partials/header.php`. The home page copy button and the
  citation card on the resources page both read from it.
- **DOI** — `$DOI` in `partials/header.php`. `doi_badge($DOI, $DOI_URL)` renders the badge;
  it appears in the featured e-book block on the home page and in the citation card on the
  eBook and resources pages.
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

Three numbers on the home page — site visits, full-volume downloads and chapter downloads — with a
per-chapter breakdown in the eBook chapter table.

`lib/counters.php` keeps a single JSON file at `data/counters.json`, updated under an exclusive
`flock` so concurrent hits cannot corrupt it. No database, no extra extension.

- **Downloads** are counted by `download.php?f=<key>`, which records the hit then redirects to the
  real PDF so the browser still handles range requests and resuming. `f=book` is the full volume;
  any other key is a chapter filename without the extension (`f=03-cs1-tntech`). Keys are validated
  against files that actually exist, so a crafted `?f=` cannot escape the site root.
- **Visits** are counted once per browser session, not per page view. This sets a session cookie.
- **Crawlers** are filtered by user agent — including `curl`, `wget` and headless Chrome. Edit the
  pattern in `counters_is_bot()`.
- **Privacy** — only totals are stored. No IP addresses, no user agents, no per-visitor records.
- **If `data/` is unwritable** the site carries on and simply stops counting. No errors, no broken
  pages.
- **Resetting** — delete `data/counters.json`; it is recreated empty on the next request. To seed
  historical figures, edit `visits` and `downloads` while the site is idle.

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

- `EX[]` — the eight institutional exemplars. Drives the search results, the detail sheets, the
  Partners table on `project.php` and the team cards on `team.php`.
- `ACTIVITIES[]` — the fourteen classroom activities, from Tables 1.1 and 1.2 of Chapter 1.
- `CHAPTERS[]` — the chapter download table. Page numbers are printed page numbers.
- `SITE` — repository URL, contact address and book filename.

Prose specific to one page is written directly in that page's PHP file.

---

## Updating the PDF

There is **one published PDF**: `cder_exemplar_cs1_cs2.pdf`. Chapters are not published separately —
a chapter cut out of the volume loses the cross-references and the shared bibliography (pp. 295–300),
so the site offers chapter-level access by opening the full file at the right page instead.

To drop in a new build:

1. Replace `cder_exemplar_cs1_cs2.pdf`, keeping the filename. The DOI, the counters and every link
   on the site point at that name.
2. Re-read the chapter start pages from the new file and update `pages` and `start` in `CHAPTERS[]`
   in `assets/data.js`, and the page numbers in `EX[]`'s `ch` entries:

   ```bash
   python3 -c "
   from pypdf import PdfReader
   r = PdfReader('cder_exemplar_cs1_cs2.pdf')
   for it in r.outline:
       if not isinstance(it, list):
           print(r.get_destination_page_number(it) + 1, it.title[:70])"
   ```

3. Check the chapter `excerpt` text still matches each abstract, and the `#chapters` note still
   quotes the right bibliography pages.

**Page counts are deliberately not stated anywhere on the site.** The figure moved from 288 to 291
to 301 across three builds; a stale count in a citation is worse than no count. If the volume is
frozen and a count is wanted, add it in one place and reference it, rather than typing it into copy.

### Chapter-level links

`download.php?f=book&p=64` counts the download and redirects to `…pdf#page=64`, which PDF viewers
honour. The old per-chapter keys (`?f=03-cs1-tntech`) still resolve to the volume rather than 404ing,
so links published before this change keep working.

---

## When the CS2 release lands

1. Replace `cder_exemplar_cs1_cs2.pdf` and update the page numbers as above.
2. Update `CHAPTERS[]` in `assets/data.js` with the new rows, page ranges and abstracts.
3. In each `EX[]` entry, add `'CS2'` to `course`, move the `cs2` activities from planned to shipped,
   and add the CS2 chapter to `ch`.
4. Add a `course` facet back to `FACETS` in `data.js` so visitors can filter CS1 vs CS2.
5. Replace the "in preparation" band in `ebook.php#cs2`, update the release note in the eBook hero,
   and update the counts on the home page.

---

## Publishing and citing the book

Cite the **page**, not the file: `https://yoursite.org/ebook`.

A page URL survives renames, new editions and the CS2 release; a file URL does not — renaming the
PDF once already broke every link to the old name. The page also gives readers context, chapter-level
downloads and a route to the rest of the site, and it is the only path that counts downloads.

- **Cite:** `/ebook`
- **Download from it:** `download.php?f=book`
- The PDF stays reachable at its own URL for anyone who wants it; that route just is not counted

Freeze the filename from here on. If you want a prettier download URL, add a rewrite alias rather
than renaming the file again. For something permanent, mint a DOI (Zenodo, or your library) pointing
at `/ebook` — that survives even a domain change, which is what citations in published papers need.

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
