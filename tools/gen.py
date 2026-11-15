"""Rewrites CHAPTERS[] in assets/data.js from that extraction.

Run from the site root, with the current PDF in place:
    python3 tools/extract.py && python3 tools/gen.py

Both take an optional path for the intermediate JSON (default /tmp/chapters.json).

Page ranges, sections and abstracts come from the PDF (tools/extract.py). What
the PDF cannot supply is kept here: the short labels the site uses, authors with
their affiliations, the part each chapter belongs to, and its repository folder.
A chapter added to the book needs one line in each table below.

Then check the abstracts for words fused by de-hyphenation before
committing — see the chapter data section of the README.
"""
import json, sys

d = json.load(open(sys.argv[1] if len(sys.argv) > 1 else '/tmp/chapters.json'))

# Titles and the volume's own subtitles, verbatim. Short labels match the ones
# used on the Research page findings cards.
META = {
 0: ('Chapter 0 — An Overview and Roadmap', 'Project rationale and methods',
     'Project Rationale, Methods, and Adoption Pathways'),
 1: ('Chapter 1 — Common CS1 Activities and Lessons', 'Shared CS1 activity families',
     'Shared Activity Families, Adoption Guidance, and Evidence'),
 2: ('Chapter 2 — CS1: Tennessee Technological University', 'Large-section C++ infusion',
     'Large-Section C++ Infusion with Unplugged Activities, OpenMP, and Remote Data'),
 3: ('Chapter 3 — CS1: Knox College', 'Java liberal-arts adaptation',
     'Java-Based Liberal-Arts Adaptation with Flag Maker, Greenfoot, and Knoxcraft'),
 4: ('Chapter 4 — CS1: University of Southern Indiana', 'Structured Java adoption',
     'Structured Java Activity Adoption with Evidence-Rich Unplugged and Plugged-In Modules'),
 5: ('Chapter 5 — CS1: University of Nebraska–Lincoln', 'Codeless and low-code modules',
     'Scalable Codeless and Low-Code PDC Modules for Large and Online CS1 Contexts'),
 6: ('Chapter 6 — CS1: Webster University', 'Visualization-first C++',
     'Visualization-First C++ Infusion through Animations, Simulations, and Game-Based Activities'),
 7: ('Chapter 7 — CS1: Casper College', 'Community-college adoption',
     'Community College Adoption across Unplugged, OpenMP, Remote-Data, and Physical-Computing Activities'),
 8: ('Chapter 8 — CS1: Hawai‘i Pacific University', 'Low-preparation activities',
     'Low-Preparation Unplugged and Plugged-in PDC Activities for Small Classes'),
 9: ('Chapter 9 — CS1: Montclair State University', 'Minimal-infusion model',
     'Focused Java Minimal-Infusion Model Centered on Flag Maker and Data Parallelism'),
 10: ('Chapter 10 — Common CS2 Activities and Lessons', 'Shared CS2 activity families',
      'Shared Activity Families, Adoption Guidance, and Evidence'),
 11: ('Chapter 11 — CS2: Tennessee Technological University', 'Large-section C++ with OpenMP',
      'Large-Section C++ Infusion with Plugged-In Activities using OpenMP'),
 12: ('Chapter 12 — CS2: Knox College', 'Fork–Join in Java',
      'Java-Based Liberal-Arts Adaptation using the Fork-Join Framework'),
 13: ('Chapter 13 — CS2: University of Southern Indiana', 'Events, Fork–Join and images',
      'Structured Java Infusion through Event-Driven Programming, Fork–Join, and Parallel Image Processing'),
 14: ('Chapter 14 — CS2: University of Nebraska–Lincoln', 'Minimal-change Java integration',
      'Minimal-Change Java Integration through Connection Pooling and Parallel Sorting'),
 15: ('Chapter 15 — CS2: Webster University (C++)', 'Parallel merge sort in C++',
      'C++ Infusion through Parallel Merge Sort, OpenMP, and Performance Analysis'),
 16: ('Chapter 16 — CS2: Webster University (Python)', 'Python multiprocessing and performance',
      'Python-Based Infusion through Multiprocessing, Parallel Image Processing, and Performance Engineering'),
 17: ('Chapter 17 — CS2: Casper College', 'Community-college CS2 adoption',
      'Community College Adoption across Unplugged, OpenMP, and Greenfoot Activities'),
 18: ('Chapter 18 — CS2: Montclair State University', 'Greenfoot events and image processing',
      'Java Infusion through Event-Driven Greenfoot Game and Parallel Image Processing'),
}

# Authors exactly as the title pages give them, with each person's own affiliation.
TEAM = 'Every development, testing and backbone team'
AUTHORS = {
 0: ('Sushil K. Prasad · Alan Sussman · Neena Thota · Ramachandran Vaidyanathan · Charles Weems',
     'UT San Antonio · Maryland · UMass Amherst · LSU'),
 1: ('April R. Crockett · Srishti Srivastava · David P. Bunde · Xiaoyuan Suo · Charlotte Gruner · '
     'Jaime Spacco · Mary L. Smith · Jiayin Wang · Chris Bourke · Michelle Zhu · Peter Maher · '
     'Gerald C. Gannod · Alan Sussman · Neena Thota · Charles Weems · Ramachandran Vaidyanathan · '
     'Sushil K. Prasad', TEAM),
 2: ('April R. Crockett · Gerald C. Gannod', 'Tennessee Technological University'),
 3: ('David P. Bunde · Jaime Spacco', 'Knox College'),
 4: ('Srishti Srivastava', 'University of Southern Indiana'),
 5: ('Chris Bourke', 'University of Nebraska–Lincoln'),
 6: ('Xiaoyuan Suo · Peter Maher', 'Webster University'),
 7: ('Charlotte Gruner', 'Casper College'),
 8: ('Mary L. Smith', 'Hawai’i Pacific University'),
 9: ('Jiayin Wang · Michelle Zhu', 'Montclair State University · Kennesaw State University'),
 10: ('David P. Bunde · April R. Crockett · Srishti Srivastava · Michelle Zhu · Jaime Spacco · '
      'Mary L. Smith · Charlotte Gruner · Jiayin Wang · Chris Bourke · Xiaoyuan Suo · Peter Maher · '
      'Gerald C. Gannod · Alan Sussman · Neena Thota · Charles Weems · Ramachandran Vaidyanathan · '
      'Sushil K. Prasad', TEAM),
 11: ('April R. Crockett · Gerald C. Gannod', 'Tennessee Technological University'),
 12: ('David P. Bunde · Jaime Spacco', 'Knox College'),
 13: ('Srishti Srivastava', 'University of Southern Indiana'),
 14: ('Chris Bourke', 'University of Nebraska–Lincoln'),
 15: ('Peter Maher', 'Webster University'),
 16: ('Xiaoyuan Suo', 'Webster University'),
 17: ('Charlotte Gruner', 'Casper College'),
 18: ('Michelle Zhu · Jiayin Wang', 'Kennesaw State University · Montclair State University'),
}

def part(ch):
    return 'Front' if ch == 0 else ('Part I · CS1' if ch <= 9 else 'Part II · CS2')

REPO = 'https://github.com/CDER-Center/CS1-CS2_Exemplar_Ebook/tree/main'
DIR = {2: 'CS1/chapter2-TTU', 3: 'CS1/chapter3-Knox', 4: 'CS1/chapter4-USI', 5: 'CS1/chapter5-UNL',
       6: 'CS1/chapter6-Webster', 7: 'CS1/chapter7-Casper', 8: 'CS1/chapter8-HPU', 9: 'CS1/chapter9-MSU',
       10: 'CS2/chapter10-CDER', 11: 'CS2/chapter11-TTU', 12: 'CS2/chapter12-Knox',
       13: 'CS2/chapter13-USI', 14: 'CS2/chapter14-UNL', 15: 'CS2/chapter15-Webster',
       16: 'CS2/chapter16-Webster', 17: 'CS2/chapter17-Casper', 18: 'CS2/chapter18-MSU'}

# De-hyphenating the extracted text rejoins words that were legitimately
# hyphenated across a line break, and the PDF loses the odd space after a
# comma. Repair here so re-running the pipeline stays correct.
REPAIR = {
    'institutionspecific': 'institution-specific',
    'handson': 'hands-on',
    'eventdriven': 'event-driven',
    'CSIT 111,Fundamentals': 'CSIT 111, Fundamentals',
}
def repair(t):
    for a, b in REPAIR.items():
        t = t.replace(a, b)
    return t

def q(s): return "'" + str(s).replace('\\', '\\\\').replace("'", "\\'") + "'"

def emit(nodes, indent):
    out = []
    for s in nodes:
        pad = ' ' * indent
        kids = s['sub']
        if kids and any(k['sub'] for k in kids):
            inner = emit(kids, indent + 2)
            out.append(f"{pad}{{ n: {q(s['n'])}, t: {q(s['t'])}, sub: [\n{inner}\n{pad}] }},")
        elif kids and s['n'].count('.') >= 2:
            leaves = ', '.join(f"[{q(k['n'])}, {q(k['t'])}]" for k in kids)
            out.append(f"{pad}{{ n: {q(s['n'])}, t: {q(s['t'])}, sub: [{leaves}] }},")
        elif kids:
            inner = emit(kids, indent + 2)
            out.append(f"{pad}{{ n: {q(s['n'])}, t: {q(s['t'])}, sub: [\n{inner}\n{pad}] }},")
        else:
            out.append(f"{pad}{{ n: {q(s['n'])}, t: {q(s['t'])}, sub: [] }},")
    return '\n'.join(out)

chs = {int(k): v for k, v in d['chapters'].items()}
missing = sorted(set(chs) - set(META))
if missing:
    sys.exit(f'Chapters {missing} are in the PDF but not in META/AUTHORS — add them first.')

first_ch = min(c['first'] for c in chs.values())
recs = [f"""  {{
    title: 'Front matter and contents', part: 'Front',
    subShort: '', subFull: '',
    pages: {q(f"1–{first_ch - 1}")}, start: 1,
    authors: '', inst: '',
    excerpt: 'The title page with the editorial, development and testing teams, how to cite the e-book, and the three-page contents. For shortcuts into the volume, see “How to Read this Volume” in Chapter 0’s abstract (p. 5).',
    sections: [],
  }},"""]

for ch in sorted(chs):
    c = chs[ch]
    title, short, full = META[ch]
    au, inst = AUTHORS[ch]
    mat = f",\n    materials: {q(REPO + '/' + DIR[ch])}" if ch in DIR else ''
    recs.append(f"""  {{
    title: {q(title)}, part: {q(part(ch))},
    subShort: {q(short)}, subFull: {q(full)},
    pages: {q(f"{c['first']}–{c['last']}")}, start: {c['first']},
    authors: {q(au)}, inst: {q(inst)},
    excerpt: {q(repair(c['abstract']))},
    sections: [
{emit(c['toc'], 6)}
    ]{mat},
  }},""")

for b in d.get('back', []):
    recs.append(f"""  {{
    title: {q(b['title'])}, part: 'Back',
    subShort: '', subFull: '',
    pages: {q(f"{b['first']}–{b['last']}")}, start: {b['first']},
    authors: '', inst: '',
    excerpt: '',
    sections: [],
  }},""")

src = open('assets/data.js', encoding='utf-8').read()
i = src.index('const CHAPTERS'); j = src.index('\n];', i)
open('assets/data.js', 'w', encoding='utf-8').write(
    src[:i] + 'const CHAPTERS = [\n' + '\n'.join(recs) + src[j:])
print(f'CHAPTERS regenerated from the PDF: {len(recs)} rows')
