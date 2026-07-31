# Institution logos

Drop logo files here and name them in `content/institutions.json` (the `logo` field). Until a file is
present, the card draws the `mono` monogram instead — so the section looks finished either way and
you can add logos one at a time.

```
content/institutions.json          assets/logos/institutions/
  "logo": "tntech.svg"      ───▶    tntech.svg
  "logo": null              ───▶    (monogram "TT" is drawn)
```

## What to supply

| Institution | Suggested filename |
|---|---|
| Tennessee Technological University | `tntech.svg` |
| Knox College | `knox.svg` |
| University of Southern Indiana | `usi.svg` |
| University of Nebraska–Lincoln | `unl.svg` |
| Webster University | `webster.svg` |
| Casper College | `casper.svg` |
| Hawai‘i Pacific University | `hpu.svg` |
| Montclair State University | `montclair.svg` |

SVG is preferred; PNG with a transparent background also works. The slot is square
(3.25rem) and uses `object-fit: contain`, so wordmark-shaped logos will letterbox — a square or
near-square mark, seal or monogram version reads best. The slot has a light brand-tinted
background, so supply a mark that works on a light surface.

## Before adding them

These are trademarks. Each institution has brand guidelines, and most require permission for use by
third parties — including partner and grant-funded project sites. The specific variant matters too:
many universities forbid their primary lockup on tinted backgrounds, or require a particular
clear-space margin. Get the approved asset from each institution's communications office rather than
lifting one from their homepage, and keep a note of the approval.

This is why the site ships with monograms rather than scraped logos.
