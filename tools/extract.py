"""Pulls the chapter list, page ranges, contents trees and abstracts out of the volume.

Run from the site root, with the current PDF in place:
    python3 tools/extract.py && python3 tools/gen.py

Both take an optional path for the intermediate JSON (default /tmp/chapters.json):
    python3 tools/extract.py /some/dir/chapters.json && python3 tools/gen.py /some/dir/chapters.json

Everything structural comes from the PDF's own bookmarks, so a new edition needs
no page numbers edited here:
  * each top-level bookmark that starts "Chapter N" on its title page is a chapter;
  * its start page is the bookmark's page, its end the page before the next
    top-level bookmark (a Part divider, the next chapter or the bibliography);
  * its sections are the bookmarks beneath it, numbered by position (N.1, N.1.1,
    N.1.1.1), skipping the unnumbered ones — Abstract, Appendix, Acknowledgements.
Page numbers are the book's printed numbers (the PDF's page labels), which is
what the site shows; download.php converts them back to PDF pages.

The numbering is checked against the headings in the text and any disagreement
is printed. Then check the abstracts for words fused by de-hyphenation before
committing — see the chapter data section of the README.
"""
from pypdf import PdfReader
import json, re, shutil, subprocess, sys

PDF = 'cder_exemplar_cs1_cs2_first_edition.pdf'
OUT = sys.argv[1] if len(sys.argv) > 1 else '/tmp/chapters.json'
UNNUMBERED = {'abstract', 'appendix', 'acknowledgements', 'acknowledgments', 'chapter contents'}
DEPTH = 3   # section, subsection, subsubsection — the site shows three levels

r = PdfReader(PDF)
labels = r.page_labels

def printed(i):
    """Printed page number of 0-based PDF page i."""
    try: return int(labels[i])
    except (ValueError, IndexError): return i + 1

def clean(t):
    t = re.sub(r'(\w)-\s*\n\s*(\w)', r'\1\2', t)      # rejoin hyphenated wraps
    t = re.sub(r'\s*\n\s*', ' ', t)
    return re.sub(r'\s+', ' ', t).strip()

# ------------------------------------------------------------ the outline --
tops = []        # [(title, page index, children)]
for x in r.outline:
    if isinstance(x, list):
        if tops: tops[-1][2].extend(x)
    else:
        tops.append((x.title.strip(), r.get_destination_page_number(x), []))

def sections(children, prefix, depth=1):
    out, i = [], 0
    pending = None
    for x in children:
        if isinstance(x, list):
            if pending is not None and depth < DEPTH:
                pending['sub'] = sections(x, pending['n'], depth + 1)
            continue
        t = re.sub(r'\s+', ' ', x.title).strip()
        if t.lower() in UNNUMBERED:
            pending = None
            continue
        i += 1
        pending = {'n': f'{prefix}.{i}', 't': t, 'sub': [], 'page': r.get_destination_page_number(x)}
        out.append(pending)
    return out

chapters = []
for k, (title, page, kids) in enumerate(tops):
    head = (r.pages[page].extract_text() or '').lstrip()
    m = re.match(r'Chapter\s+(\d+)', head)
    if not m: continue
    nxt = tops[k + 1][1] if k + 1 < len(tops) else len(r.pages)
    chapters.append({'num': int(m.group(1)), 'outline': title, 'first': page, 'last': nxt - 1,
                     'kids': kids})

# ------------------------------------------------------ text and checking --
def chapter_text(c):
    return '\n'.join(r.pages[p].extract_text() or '' for p in range(c['first'], c['last'] + 1))

def check(nodes, text, bad):
    """A heading whose number is not printed in the book (a bookmarked but
    unnumbered paragraph heading, say) keeps its title and loses the number,
    rather than showing one the reader will not find."""
    for s in nodes:
        words = re.escape(' '.join(s['t'].split()[:2]))
        if not re.search(rf'(?m)^\s*{re.escape(s["n"])}\s+{words}', text):
            bad.append(f'{s["n"]} {s["t"][:60]}')
            s['n'] = ''
        check(s['sub'], text, bad)

def page_text(i):
    """Text of 0-based page i. Poppler's pdftotext where installed: pypdf drops
    the spaces on some pages of this volume (Chapter 12's abstract came out as
    "AtKnoxCollege, CS2iswherewebegin…"), pdftotext does not."""
    if shutil.which('pdftotext'):
        out = subprocess.run(['pdftotext', '-f', str(i + 1), '-l', str(i + 1), PDF, '-'],
                             capture_output=True, text=True)
        if out.returncode == 0 and out.stdout.strip():
            return out.stdout
    return r.pages[i].extract_text() or ''

def abstract(c):
    """First sentences of the chapter abstract."""
    for pg in range(c['first'], min(c['first'] + 8, c['last'] + 1)):
        t = page_text(pg)
        if re.search(r'^\s*Abstract\s*$', t, re.M) or 'Abstract' in t.split('\n')[0]:
            body = clean(t)
            m = re.search(r'Abstract\s*(.+)', body)
            if not m: continue
            body = re.split(rf'\b(?:{c["num"]}\.1 Introduction|Keywords|Descriptor Elements)\b', m.group(1))[0]
            parts = re.split(r'(?<=[.!?])\s+(?=[A-Z"“(])', body)
            out, total = [], 0
            for s in parts:
                out.append(s); total += len(s)
                if total > 430: break
            return ' '.join(out).strip()
    return ''

def strip(nodes):
    return [{'n': s['n'], 't': s['t'], 'sub': strip(s['sub'])} for s in nodes]

data = {'pdf': PDF, 'pages': len(r.pages), 'printedLast': printed(len(r.pages) - 1),
        'parts': [], 'back': [], 'chapters': {}}
for title, page, _ in tops:
    if title.upper().startswith('PART'):
        data['parts'].append({'title': title, 'page': printed(page)})
    elif title.lower().startswith('bibliography'):
        data['back'].append({'title': title, 'first': printed(page), 'last': printed(len(r.pages) - 1)})

problems = 0
for c in chapters:
    secs = sections(c['kids'], str(c['num']))
    bad = []
    check(secs, chapter_text(c), bad)
    if bad:
        problems += len(bad)
        print(f'Chapter {c["num"]}: number not printed in the book, shown without one:', bad)
    data['chapters'][c['num']] = {
        'outline': c['outline'], 'first': printed(c['first']), 'last': printed(c['last']),
        'toc': strip(secs), 'abstract': abstract(c),
    }

json.dump(data, open(OUT, 'w'), indent=1, ensure_ascii=False)
print(f'{len(chapters)} chapters, {data["pages"]} PDF pages (printed 1–{data["printedLast"]}), '
      f'{problems} numbering problem(s) -> {OUT}')
for n, d in data['chapters'].items():
    print(f'  {n:>2}  pp {d["first"]}–{d["last"]}  sections {len(d["toc"])}  abstract {len(d["abstract"])} chars')
