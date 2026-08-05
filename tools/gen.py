"""Rewrites CHAPTERS[] in assets/data.js from that extraction.

Run from the site root, with the current PDF in place:
    python3 tools/extract.py && python3 tools/gen.py

Then check the abstracts for words fused by de-hyphenation before
committing — see the chapter data section of the README.
"""
import json, re, subprocess

d = json.load(open('/tmp/chapters.json'))

# Titles and the volume's own subtitles, verbatim. Short labels match the ones
# already used on the Research page findings cards.
META = {
 0: ('Chapter 0 — An Overview and Roadmap', 'Project rationale and methods',
     'Project Rationale, Methods, and Adoption Pathways'),
 1: ('Chapter 1 — Common CS1 Activities and Lessons', 'Shared activity families',
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
     'Community-College Adoption across Unplugged, OpenMP, Remote-Data, and Physical-Computing Activities'),
 8: ('Chapter 8 — CS1: Hawai‘i Pacific University', 'Low-preparation activities',
     'Low-Preparation Unplugged and Plugged-in PDC Activities for Small Classes'),
 9: ('Chapter 9 — CS1: Montclair State University', 'Minimal-infusion model',
     'Focused Java Minimal-Infusion Model Centered on Flag Maker and Data Parallelism'),
}

# Authors exactly as the title pages give them, with each person's own affiliation.
AUTHORS = {
 0: ('Sushil K. Prasad · Alan Sussman · Neena Thota · Ramachandran Vaidyanathan · Charles Weems',
     'UT San Antonio · Maryland · UMass Amherst · LSU'),
 1: ('April R. Crockett · Srishti Srivastava · David P. Bunde · Xiaoyuan Suo · Charlotte Gruner · '
     'Jaime Spacco · Mary L. Smith · Jiayin Wang · Chris Bourke · Michelle Zhu · Peter Maher · '
     'Gerald C. Gannod · Alan Sussman · Neena Thota · Charles Weems · Ramachandran Vaidyanathan · '
     'Sushil K. Prasad',
     'Every development, testing and backbone team'),
 2: ('April R. Crockett · Gerald C. Gannod', 'Tennessee Technological University'),
 3: ('David P. Bunde · Jaime Spacco', 'Knox College'),
 4: ('Srishti Srivastava', 'University of Southern Indiana'),
 5: ('Chris Bourke', 'University of Nebraska–Lincoln'),
 6: ('Xiaoyuan Suo · Peter Maher', 'Webster University'),
 7: ('Charlotte Gruner', 'Casper College'),
 8: ('Mary L. Smith', 'Hawai’i Pacific University'),
 9: ('Jiayin Wang · Michelle Zhu', 'Montclair State University · Kennesaw State University'),
}

PAGES = {'front': ('1–4', 1), 0: ('5–24', 5), 1: ('26–62', 26), 2: ('63–106', 63), 3: ('107–121', 107),
         4: ('122–165', 122), 5: ('166–181', 166), 6: ('182–201', 182), 7: ('202–235', 202),
         8: ('236–275', 236), 9: ('276–295', 276)}
PART = {0: 'Front', 1: 'Part I · CS1', 2: 'Part I · CS1', 3: 'Part I · CS1', 4: 'Part I · CS1',
        5: 'Part I · CS1', 6: 'Part I · CS1', 7: 'Part I · CS1', 8: 'Part I · CS1', 9: 'Part I · CS1'}
CS1 = 'https://github.com/CDER-Center/CS1-CS2_Exemplar_Ebook/tree/main/CS1'
DIR = {2:'chapter2-TTU', 3:'chapter3-Knox', 4:'chapter4-USI', 5:'chapter5-UNL',
       6:'chapter6-Webster', 7:'chapter7-Casper', 8:'chapter8-HPU', 9:'chapter9-MSU'}

def tree(entries):
    """Flat numbered headings -> three nested levels."""
    root = []
    for num, title in entries:
        depth = num.count('.')
        if depth == 1:
            root.append({'n': num, 't': title, 'sub': []})
        elif depth == 2 and root:
            root[-1]['sub'].append({'n': num, 't': title, 'sub': []})
        elif depth == 3 and root and root[-1]['sub']:
            root[-1]['sub'][-1]['sub'].append([num, title])
    return root

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

recs = []
recs.append(f"""  {{
    title: 'Front matter, contents, figures &amp; tables', part: 'Front',
    subShort: '', subFull: '',
    pages: '1–4', start: 1,
    authors: '', inst: '',
    excerpt: 'Front matter, the contents, and “How to Use This E-book” (p. 3), which sets out how the volume is meant to be read: a guided collection of adoptable exemplars rather than a linear textbook.',
    sections: [],
  }},""")

for ch in range(10):
    title, short, full = META[ch]
    au, inst = AUTHORS[ch]
    rng, start = PAGES[ch]
    secs = tree(d[str(ch)]['toc'])
    def emit(nodes, indent):
        out = []
        for s in nodes:
            pad = ' ' * indent
            if s['sub'] and isinstance(s['sub'][0], dict):
                inner = emit(s['sub'], indent + 2)
                out.append(f"{pad}{{ n: {q(s['n'])}, t: {q(s['t'])}, sub: [\n{inner}\n{pad}] }},")
            elif s['sub']:
                leaves = ', '.join(f"[{q(x[0])}, {q(x[1])}]" for x in s['sub'])
                out.append(f"{pad}{{ n: {q(s['n'])}, t: {q(s['t'])}, sub: [{leaves}] }},")
            else:
                out.append(f"{pad}{{ n: {q(s['n'])}, t: {q(s['t'])}, sub: [] }},")
        return '\n'.join(out)
    body = emit(secs, 6)
    mat = f",\n    materials: {q(CS1 + '/' + DIR[ch])}" if ch in DIR else ''
    recs.append(f"""  {{
    title: {q(title)}, part: {q(PART[ch])},
    subShort: {q(short)}, subFull: {q(full)},
    pages: {q(rng)}, start: {start},
    authors: {q(au)}, inst: {q(inst)},
    excerpt: {q(repair(d[str(ch)]['abstract']))},
    sections: [
{body}
    ]{mat},
  }},""")

src = open('assets/data.js', encoding='utf-8').read()
i = src.index('const CHAPTERS'); j = src.index('\n];', i)
open('assets/data.js', 'w', encoding='utf-8').write(
    src[:i] + 'const CHAPTERS = [\n' + '\n'.join(recs) + src[j:])
print('CHAPTERS regenerated from the PDF')
