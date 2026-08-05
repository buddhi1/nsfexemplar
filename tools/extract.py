"""Pulls chapter title blocks, contents trees and abstracts out of the volume.

Run from the site root, with the current PDF in place:
    python3 tools/extract.py && python3 tools/gen.py

Then check the abstracts for words fused by de-hyphenation before
committing — see the chapter data section of the README.
"""
from pypdf import PdfReader
import re, json

r = PdfReader('cder_exemplar_cs1_cs2.pdf')
TOC   = {0:6, 1:27, 2:64, 3:108, 4:123, 5:167, 6:183, 7:203, 8:237, 9:277}
TITLE = {0:5, 1:26, 2:63, 3:107, 4:122, 5:166, 6:182, 7:202, 8:236, 9:276}

def clean(t):
    t = re.sub(r'(\w)-\s*\n\s*(\w)', r'\1\2', t)      # rejoin hyphenated wraps
    t = re.sub(r'\s*\n\s*', ' ', t)
    return re.sub(r'\s+', ' ', t).strip()

def toc_entries(ch):
    """Numbered headings from a chapter's contents page(s), three levels deep."""
    txt = ''
    pg = TOC[ch]
    while True:
        t = r.pages[pg-1].extract_text() or ''
        cut = min([i for i in (t.find('Chapter figures'), t.find('Chapter tables')) if i >= 0] or [len(t)])
        txt += t[:cut] + '\n'
        if cut < len(t) or 'Abstract' in (r.pages[pg].extract_text() or '')[:200]: break
        pg += 1
        if pg > TOC[ch] + 2: break
    txt = clean(txt)
    txt = txt.split('Chapter contents', 1)[-1]
    # dot leaders + trailing page number end every entry
    txt = re.sub(r'\s*\.\s*(?:\.\s*)+\s*\d+', '\n', txt)
    out = []
    for line in txt.split('\n'):
        line = line.strip()
        m = re.match(r'^(\d+(?:\.\d+){0,3})\s+(.{2,120})$', line)
        if m:
            out.append((m.group(1), m.group(2).strip()))
    return out

def title_block(ch):
    t = r.pages[TITLE[ch]-1].extract_text() or ''
    head = t.split('This chapter appears')[0]
    head = re.sub(r'[ \t]+', ' ', head)
    lines = [l.strip() for l in head.split('\n') if l.strip() and l.strip() != '†']
    return lines

def abstract(ch):
    """First sentences of the chapter abstract."""
    start = TITLE[ch]
    for pg in range(start, start + 8):
        t = r.pages[pg-1].extract_text() or ''
        if re.search(r'^\s*Abstract\s*$', t, re.M) or 'Abstract' in t.split('\n')[0]:
            body = clean(t)
            m = re.search(r'Abstract\s*(.+)', body)
            if not m: continue
            body = re.split(r'\b(?:\d+\.\d+ Introduction|Keywords)\b', m.group(1))[0]
            parts = re.split(r'(?<=[.!?])\s+(?=[A-Z"“(])', body)
            out, total = [], 0
            for s in parts:
                out.append(s); total += len(s)
                if total > 430: break
            return ' '.join(out).strip()
    return ''

data = {}
for ch in range(10):
    lines = title_block(ch)
    data[ch] = {'titleLines': lines, 'toc': toc_entries(ch), 'abstract': abstract(ch)}

json.dump(data, open('/tmp/chapters.json', 'w'), indent=1)
for ch in (1, 5):
    d = data[ch]
    print(f'===== Chapter {ch} =====')
    print(' title lines:', d['titleLines'][:6])
    print(' toc entries:', len(d['toc']))
    for n, t in d['toc'][:10]: print(f'    {n:<9} {t[:64]}')
    print(' abstract:', d['abstract'][:150], '\n')
