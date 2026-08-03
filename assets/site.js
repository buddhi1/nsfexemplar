/* ==========================================================================
   Modern Course Exemplars — shared behaviours
     · theme toggle (respects system preference)
     · responsive nav disclosure + current-page marking
     · "/" focuses the primary search
     · Flag Maker hero animation (progressive enhancement)
     · exemplar / activity explorer (ebook.php)
     · partner table (project.php), team cards (team.php)
   No dependencies.
   ========================================================================== */

(() => {
  'use strict';

  const $  = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => [...root.querySelectorAll(sel)];
  /* PDF links go through download.php so they can be counted.
     'chapters/03-cs1-tntech.pdf' -> 'download.php?f=03-cs1-tntech' */
  const BOOK = (typeof SITE !== 'undefined' && SITE.book && SITE.book.pdf) || '';
  const dl = (path) => {
    if (path === BOOK) return 'download.php?f=book';
    const m = /^chapters\/(.+)\.pdf$/.exec(path);
    return m ? `download.php?f=${encodeURIComponent(m[1])}` : path;
  };
  /* Download tallies injected by ebook.php; absent elsewhere. */
  const DL_COUNTS = (typeof DOWNLOADS !== 'undefined' && DOWNLOADS) || {};
  const dlKey = (path) => (path === BOOK
    ? 'book' : (/^chapters\/(.+)\.pdf$/.exec(path) || [, ''])[1]);

  const esc = (s) => String(s).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));

  /* ------------------------------------------------------------ theming -- */
  const root = document.documentElement;
  const mq = (q) => (typeof window.matchMedia === 'function'
    ? window.matchMedia(q)
    : { matches: false, media: q, addEventListener() {}, removeEventListener() {} });
  const prefersDark = mq('(prefers-color-scheme: dark)');
  const reducedMotion = () => mq('(prefers-reduced-motion: reduce)').matches;

  const applyTheme = (mode) => {
    root.classList.toggle('dark', mode === 'dark');
    root.style.colorScheme = mode;
    const btn = $('[data-theme-toggle]');
    if (btn) btn.setAttribute('aria-label', mode === 'dark' ? 'Switch to light theme' : 'Switch to dark theme');
  };

  const stored = (() => { try { return sessionStorage.getItem('theme'); } catch { return null; } })();
  applyTheme(stored || (prefersDark.matches ? 'dark' : 'light'));
  if (stored) root.dataset.themeLocked = 'true';

  prefersDark.addEventListener('change', (e) => {
    if (!root.dataset.themeLocked) applyTheme(e.matches ? 'dark' : 'light');
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('[data-theme-toggle]')) return;
    const next = root.classList.contains('dark') ? 'light' : 'dark';
    root.dataset.themeLocked = 'true';
    try { sessionStorage.setItem('theme', next); } catch { /* private mode */ }
    applyTheme(next);
  });

  /* ---------------------------------------------------------------- nav -- */
  const nav = $('.primary-nav');
  const navToggle = $('.nav-toggle');
  const closeNav = () => {
    if (!nav) return;
    delete nav.dataset.open;
    navToggle?.setAttribute('aria-expanded', 'false');
  };
  navToggle?.addEventListener('click', () => {
    if (nav.hasAttribute('data-open')) closeNav();
    else { nav.dataset.open = ''; navToggle.setAttribute('aria-expanded', 'true'); }
  });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeNav(); });

  /* Current page is marked server-side in partials/header.php. This is only a
     fallback for when the extension is hidden by a rewrite and nothing matched. */
  if (!$('.nav-link[aria-current]')) {
    const here = (location.pathname.split('/').pop() || 'index').toLowerCase().replace(/\.php$/, '');
    $$('.nav-link').forEach((a) => {
      const target = (a.getAttribute('href') || '').split('#')[0].toLowerCase().replace(/\.php$/, '');
      if (target === (here || 'index')) a.setAttribute('aria-current', 'page');
    });
  }

  /* ---------------------------------------------- keyboard: "/" to search */
  document.addEventListener('keydown', (e) => {
    if (e.key !== '/' || e.metaKey || e.ctrlKey) return;
    if (/^(INPUT|TEXTAREA|SELECT)$/.test(document.activeElement?.tagName || '')) return;
    const input = $('[data-search-primary]');
    if (!input) return;
    e.preventDefault();
    input.focus();
    input.scrollIntoView({ block: 'center', behavior: reducedMotion() ? 'auto' : 'smooth' });
  });

  /* ------------------------------------------------- generic year stamp -- */
  $$('[data-year]').forEach((el) => { el.textContent = new Date().getFullYear(); });

  /* --------------------------------------------------------------- copy --
     Any [data-copy] button copies its value and confirms briefly. Falls back
     to a hidden textarea where the async clipboard API is unavailable, which
     includes pages served over plain http. */
  (() => {
    const buttons = $$('[data-copy]');
    if (!buttons.length) return;

    const legacyCopy = (text) => {
      const ta = document.createElement('textarea');
      ta.value = text;
      ta.setAttribute('readonly', '');
      ta.style.cssText = 'position:fixed;top:-1000px;opacity:0';
      document.body.appendChild(ta);
      ta.select();
      let ok = false;
      try { ok = document.execCommand('copy'); } catch { ok = false; }
      ta.remove();
      return ok;
    };

    buttons.forEach((btn) => {
      const label = btn.querySelector('span:not(.visually-hidden)');
      const original = label ? label.textContent : null;
      let timer = null;

      const confirm = (ok) => {
        clearTimeout(timer);
        btn.toggleAttribute('data-copied', ok);
        if (label) label.textContent = ok ? 'Copied' : 'Press Ctrl+C';
        btn.setAttribute('aria-label', ok ? 'Citation copied' : 'Copy failed — select the text instead');
        timer = setTimeout(() => {
          btn.removeAttribute('data-copied');
          if (label && original !== null) label.textContent = original;
          btn.setAttribute('aria-label', 'Copy citation');
        }, 2200);
      };

      btn.addEventListener('click', async () => {
        const text = btn.dataset.copy || '';
        if (navigator.clipboard && window.isSecureContext) {
          try { await navigator.clipboard.writeText(text); return confirm(true); }
          catch { /* fall through to the legacy path */ }
        }
        confirm(legacyCopy(text));
      });
    });
  })();

  /* ------------------------------------------------------------- reveal --
     One-shot: reveal on first sight, then stop watching. Never reverses. */
  (() => {
    const items = $$('.reveal');
    if (!items.length) return;
    if (reducedMotion() || !('IntersectionObserver' in window)) {
      items.forEach((el) => el.setAttribute('data-shown', ''));
      return;
    }
    const io = new IntersectionObserver((entries) => {
      entries.forEach((en) => {
        if (!en.isIntersecting) return;
        en.target.setAttribute('data-shown', '');
        io.unobserve(en.target);
      });
    }, { rootMargin: '0px 0px -8% 0px' });
    items.forEach((el) => io.observe(el));
  })();

  /* ------------------------------------------- scroll-past helper (shared) --
     Scroll events are unreliable to depend on (and are not delivered at all in
     some headless environments), so both the shrinking header and the
     back-to-top button watch a sentinel at the top of the document instead. */
  const watchScrollPast = (px, cb) => {
    if ('IntersectionObserver' in window) {
      const mark = document.createElement('div');
      mark.style.cssText = 'position:absolute;top:0;left:0;width:1px;height:1px;pointer-events:none';
      mark.setAttribute('aria-hidden', 'true');
      document.body.prepend(mark);
      new IntersectionObserver(([entry]) => cb(!entry.isIntersecting),
        { rootMargin: `${px}px 0px 0px 0px` }).observe(mark);
    } else {
      const sync = () => cb(window.scrollY > px);
      sync();
      addEventListener('scroll', sync, { passive: true });
    }
  };

  /* ------------------------------------------------------ header compaction */
  (() => {
    const header = $('.site-header');
    if (!header) return;
    /* 72px rather than a couple of pixels, so a nudge of the wheel does not
       toggle the header back and forth. */
    watchScrollPast(72, (past) => header.toggleAttribute('data-scrolled', past));
  })();

  /* --------------------------------------------------------- back to top -- */
  (() => {
    const btn = $('[data-to-top]');
    if (!btn) return;

    btn.removeAttribute('hidden');          // only offered when scripting works

    const THRESHOLD = 600;
    let shown = null;              // null so the first sync always applies
    const setShown = (want) => {
      if (want === shown) return;
      shown = want;
      btn.toggleAttribute('data-show', want);
      btn.tabIndex = want ? 0 : -1;
      btn.setAttribute('aria-hidden', String(!want));
    };

    watchScrollPast(THRESHOLD, setShown);

    btn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: reducedMotion() ? 'auto' : 'smooth' });
      /* move focus somewhere sensible once we are back at the top */
      const main = $('#content');
      if (main) main.focus({ preventScroll: true });
    });
  })();

  /* ============================================================= SHOWCASE
     Two animated exemplars from the volume, crossfading in the hero. Each
     demo exposes { run, stop, cycleLength } so the carousel can hand control
     back and forth without either demo knowing about the other.            */

  /* --- Flag Maker (Chapter 1, § 1.3.1) ---------------------------------
     A 12x8 grid standing in for the flag of Mauritius. Cells fill in the
     order N processors would colour them, so the speedup is shown rather
     than asserted. The static SVG stays put if this never runs.           */
  const makeFlagMaker = (stage) => {
    const COLS = 12, ROWS = 8, TOTAL = COLS * ROWS;
    const band = (r) => Math.floor(r / 2) + 1;              // 4 bands of 2 rows
    const MODES = [
      { procs: 1, label: '1 processor' },
      { procs: 2, label: '2 processors' },
      { procs: 4, label: '4 processors' },
    ];

    const grid = document.createElement('div');
    grid.className = 'fm-grid';
    grid.setAttribute('aria-hidden', 'true');
    const cells = [];
    for (let r = 0; r < ROWS; r++) {
      for (let c = 0; c < COLS; c++) {
        const d = document.createElement('div');
        d.className = 'fm-cell';
        d.dataset.band = band(r);
        grid.appendChild(d);
        cells.push(d);
      }
    }
    stage.replaceChildren(grid);

    const splits = [];
    const setSplits = (procs) => {
      splits.forEach((x) => x.remove());
      splits.length = 0;
      for (let i = 1; i < procs; i++) {
        const x = document.createElement('div');
        x.className = 'fm-split';
        x.style.top = `${(i / procs) * 100}%`;
        stage.appendChild(x);
        splits.push(x);
      }
    };

    const slide   = stage.closest('.showcase-slide') || document;
    const modeBox = slide.querySelector('[data-fm-modes]');
    const readout = slide.querySelector('[data-fm-readout]');

    let timer = null, hold = null, current = 0, onDone = null;
    const stop = () => { clearInterval(timer); clearTimeout(hold); timer = hold = null; };

    const buttons = MODES.map((m, i) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'fm-mode';
      b.textContent = m.label;
      b.setAttribute('aria-pressed', String(i === 0));
      b.addEventListener('click', () => { stop(); run(i, false); });
      modeBox?.appendChild(b);
      return b;
    });

    const paint = (mode, done) => {
      const rowsPer = ROWS / mode.procs;
      cells.forEach((cell, idx) => {
        const r = Math.floor(idx / COLS), c = idx % COLS;
        const proc = Math.floor(r / rowsPer);
        const step = ((r - proc * rowsPer) * COLS) + c;      // step within this processor
        if (step < done) cell.dataset.on = cell.dataset.band;
        else delete cell.dataset.on;
      });
    };

    function run(index, autoAdvance = true) {
      stop();
      current = index;
      const mode = MODES[index];
      buttons.forEach((b, i) => b.setAttribute('aria-pressed', String(i === index)));
      setSplits(mode.procs);

      const steps = TOTAL / mode.procs;
      const speedup = (TOTAL / steps).toFixed(1);
      const say = (done) => {
        if (readout) readout.innerHTML = `${done} / ${steps} steps &nbsp;·&nbsp; <b>${speedup}&times;</b> speedup`;
      };

      if (reducedMotion()) { paint(mode, steps); say(steps); return; }

      let done = 0;
      paint(mode, 0); say(0);
      timer = setInterval(() => {
        done += 1;
        paint(mode, done);
        say(done);
        if (done < steps) return;
        stop();
        if (!autoAdvance) return;
        const last = index === MODES.length - 1;
        hold = setTimeout(() => {
          if (last && onDone) onDone();            // hand back to the carousel
          else run((index + 1) % MODES.length);
        }, 2000);
      }, Math.max(34, 1500 / steps + 22));
    }

    return {
      /* With motion suppressed there is no cycle, so open on the four-processor
         partition — the state that actually carries the point. */
      start(done) { onDone = done; run(reducedMotion() ? 2 : 0); },
      stop,
      reset() { stop(); current = 0; },
    };
  };

  /* --- Penny Sorting (Chapter 1, § 1.3.2) -------------------------------
     Four worker lanes sorting one pile of coins. Total work never changes;
     only how it is divided. The imbalanced case is the lesson: the slowest
     lane sets the finish time, so speedup falls well short of ideal.      */
  const makePennySort = (stage) => {
    const TOTAL = 24, LANES = 4;
    const SCENARIOS = [
      { label: '1 worker',    dist: [24, 0, 0, 0],
        note: 'One worker sorts the whole pile.' },
      { label: '4 balanced',  dist: [6, 6, 6, 6],
        note: 'Split evenly, four workers finish in a quarter of the time.' },
      { label: '4 imbalanced', dist: [12, 6, 4, 2],
        note: 'Same total work, unevenly split — the slowest worker sets the pace.' },
    ];

    const lanes = [];
    stage.replaceChildren();
    for (let i = 0; i < LANES; i++) {
      const lane = document.createElement('div');
      lane.className = 'penny-lane';
      const who = document.createElement('span');
      who.className = 'who';
      who.textContent = `W${i + 1}`;
      const coins = document.createElement('div');
      coins.className = 'penny-coins';
      lane.append(who, coins);
      stage.appendChild(lane);
      lanes.push({ lane, coins, items: [] });
    }
    const note = document.createElement('p');
    note.className = 'penny-note';
    stage.appendChild(note);

    const slide   = stage.closest('.showcase-slide') || document;
    const modeBox = slide.querySelector('[data-penny-modes]');
    const readout = slide.querySelector('[data-penny-readout]');

    let timer = null, hold = null, current = 0, onDone = null;
    const stop = () => { clearInterval(timer); clearTimeout(hold); timer = hold = null; };

    const buttons = SCENARIOS.map((sc, i) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'fm-mode';
      b.textContent = sc.label;
      b.setAttribute('aria-pressed', String(i === 0));
      b.addEventListener('click', () => { stop(); run(i, false); });
      modeBox?.appendChild(b);
      return b;
    });

    const layout = (dist) => {
      lanes.forEach((L, i) => {
        L.items = [];
        L.coins.replaceChildren();
        L.lane.toggleAttribute('data-idle', dist[i] === 0);
        for (let k = 0; k < dist[i]; k++) {
          const c = document.createElement('span');
          c.className = 'penny-coin';
          L.coins.appendChild(c);
          L.items.push(c);
        }
      });
    };

    const paint = (dist, round) => {
      lanes.forEach((L) => {
        L.items.forEach((c, k) => {
          if (k < round) c.dataset.done = '';
          else delete c.dataset.done;
        });
      });
    };

    function run(index, autoAdvance = true) {
      stop();
      current = index;
      const sc = SCENARIOS[index];
      buttons.forEach((b, i) => b.setAttribute('aria-pressed', String(i === index)));
      layout(sc.dist);
      note.textContent = sc.note;

      const rounds = Math.max(...sc.dist);
      const speedup = (TOTAL / rounds).toFixed(1);
      const say = (done) => {
        if (readout) readout.innerHTML = `${done} / ${rounds} rounds &nbsp;·&nbsp; <b>${speedup}&times;</b> speedup`;
      };

      if (reducedMotion()) { paint(sc.dist, rounds); say(rounds); return; }

      let round = 0;
      paint(sc.dist, 0); say(0);
      timer = setInterval(() => {
        round += 1;
        paint(sc.dist, round);
        say(round);
        if (round < rounds) return;
        stop();
        if (!autoAdvance) return;
        const last = index === SCENARIOS.length - 1;
        hold = setTimeout(() => {
          if (last && onDone) onDone();
          else run((index + 1) % SCENARIOS.length);
        }, 2000);
      }, Math.max(105, 2100 / rounds));
    }

    return {
      start(done) { onDone = done; run(reducedMotion() ? 2 : 0); },
      stop,
      reset() { stop(); current = 0; },
    };
  };

  /* --- carousel --------------------------------------------------------- */
  (() => {
    const box = $('[data-showcase]');
    if (!box) return;

    const slides = $$('.showcase-slide', box);
    const dotBox = $('[data-showcase-dots]', box);
    const label  = $('[data-showcase-label]', box);
    if (!slides.length) return;

    const demos = slides.map((slide) => {
      const flag  = $('[data-flagmaker]', slide);
      const penny = $('[data-penny]', slide);
      if (flag)  return makeFlagMaker(flag);
      if (penny) return makePennySort(penny);
      return { start() {}, stop() {}, reset() {} };
    });

    let index = 0, visible = true, seen = false;

    const dots = slides.map((slide, i) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'dot';
      b.setAttribute('role', 'tab');
      b.setAttribute('aria-selected', String(i === 0));
      b.setAttribute('aria-label', slide.dataset.title || `Slide ${i + 1}`);
      b.addEventListener('click', () => show(i));
      dotBox?.appendChild(b);
      return b;
    });

    function show(next) {
      demos[index]?.stop();
      demos[index]?.reset();
      index = next;
      slides.forEach((s, i) => s.toggleAttribute('data-active', i === index));
      dots.forEach((d, i) => d.setAttribute('aria-selected', String(i === index)));
      if (label) label.textContent = slides[index].dataset.caption || '';
      if (visible) demos[index]?.start(advance);
    }
    const advance = () => show((index + 1) % slides.length);

    show(0);

    /* Pause while off-screen or on a hidden tab, but never let the observer's
       first callback kill an above-the-fold run before layout settles. */
    if ('IntersectionObserver' in window) {
      new IntersectionObserver((entries) => {
        entries.forEach((en) => {
          if (en.isIntersecting) {
            seen = true;
            if (!visible) { visible = true; demos[index]?.start(advance); }
          } else if (seen && visible) {
            visible = false;
            demos[index]?.stop();
          }
        });
      }, { threshold: 0.15 }).observe(box);
    }
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) demos[index]?.stop();
      else if (visible) demos[index]?.start(advance);
    });
  })();

  /* =============================================== EXEMPLAR / ACTIVITY EXPLORER */
  (() => {
    const form = $('#ex-form');
    if (!form || typeof EX === 'undefined') return;

    const results = $('#ex-results');
    const status  = $('#ex-status');
    const qInput  = $('#ex-q');
    const dialog  = $('#ex-dialog');
    const facetBox = $('#ex-facets');
    const byId = Object.fromEntries(EX.map((r) => [r.id, r]));

    let mode = 'inst';   // 'inst' | 'act'
    const has = (r, k, v) => (Array.isArray(r[k]) ? r[k].includes(v) : r[k] === v);
    const norm = (s) => String(s).toLowerCase().replace(/&[a-z]+;/g, ' ');

    /* ---- facets are rebuilt whenever the browse mode changes ---- */
    const buildFacets = () => {
      const defs = mode === 'inst' ? FACETS : ACT_FACETS;
      const rows = mode === 'inst' ? EX : ACTIVITIES;
      const labels = { lang: 'Language', setting: 'Institution type', style: 'Activity style', role: 'Team', kind: 'Activity type' };
      facetBox.innerHTML = Object.entries(defs).map(([key, vals]) => {
        const chips = vals.map((val, i) => {
          const n = rows.filter((r) => has(r, key, val)).length;
          if (!n) return '';
          const id = `x-${key}-${i}`;
          return `<label class="chip" for="${id}"><input type="checkbox" id="${id}" name="${key}" value="${esc(val)}"><span>${esc(val)}</span><span class="count">${n}</span></label>`;
        }).join('');
        if (!chips) return '';
        return `<fieldset class="facet"><legend>${esc(labels[key] || key)}</legend><div class="cluster">${chips}</div></fieldset>`;
      }).join('');
    };

    const instBlob = (r) => norm([
      r.name, r.short, r.role, r.place, r.setting, r.blurb, r.context, r.textbook, r.prereq, r.evidence,
      r.lang.join(' '), r.style.join(' '), r.team.join(' '), r.topics.join(' '), r.outcomes.join(' '),
      (r.cs1 || []).map((a) => a.join(' ')).join(' '), (r.cs2 || []).map((a) => a.join(' ')).join(' '),
    ].join(' '));

    const actBlob = (a) => norm([
      a.name, a.kind, a.family, a.desc, a.anchors, a.duration, a.setup, a.section,
      a.pdc.join(' '), a.used.map((id) => `${byId[id]?.short} ${byId[id]?.name}`).join(' '),
    ].join(' '));

    const instCard = (r) => `
      <article class="card">
        <div>
          <span class="pill pill--brand">${esc(r.role)}</span>
          <h3 style="margin-block-start:.5rem">${esc(r.name)}</h3>
          <p class="tiny faint">${esc(r.place)} · ${esc(r.setting)}</p>
        </div>
        <p>${r.blurb}</p>
        <div class="cluster">
          ${r.lang.map((l) => `<span class="pill pill--accent">${esc(l)}</span>`).join('')}
          ${r.style.slice(0, 3).map((s) => `<span class="pill">${esc(s)}</span>`).join('')}
        </div>
        <div class="cluster mt-3">
          <button class="btn btn--primary btn--sm" type="button" data-open-inst="${r.id}">Full details</button>
          ${r.ch.map(([label, href, pp]) => `<a class="btn btn--ghost btn--sm" href="${dl(href)}">${esc(label)} <span class="tiny faint">${esc(pp)}</span></a>`).join('')}
        </div>
        <p class="card-foot">${r.team.map(esc).join(' · ')}</p>
      </article>`;

    const actCard = (a) => `
      <article class="card">
        <div class="cluster">
          <span class="pill pill--brand">${esc(a.kind)}</span>
          <span class="pill">${esc(a.section)}</span>
        </div>
        <h3 style="margin-block-start:.4rem">${a.name}</h3>
        <p>${a.desc}</p>
        <dl class="deflist mt-3">
          <div><dt>PDC ideas</dt><dd>${a.pdc.slice(0, 4).map(esc).join(' · ')}</dd></div>
          <div><dt>CS1 anchors</dt><dd>${esc(a.anchors)}</dd></div>
          <div><dt>Duration</dt><dd>${esc(a.duration)}</dd></div>
        </dl>
        <div class="cluster mt-3">
          <button class="btn btn--primary btn--sm" type="button" data-open-act="${a.id}">Full details</button>
        </div>
        <p class="card-foot">Used at ${a.used.map((id) => esc(byId[id]?.short || id)).join(' · ')}</p>
      </article>`;

    const render = () => {
      const data = new FormData(form);
      const terms = norm(qInput.value).split(/\s+/).filter(Boolean);
      const defs = mode === 'inst' ? FACETS : ACT_FACETS;
      let rows = (mode === 'inst' ? EX : ACTIVITIES).slice();
      let active = 0;

      for (const key of Object.keys(defs)) {
        const vals = data.getAll(key);
        if (!vals.length) continue;
        active += vals.length;
        rows = rows.filter((r) => vals.some((v) => has(r, key, v)));
      }
      const blob = mode === 'inst' ? instBlob : actBlob;
      if (terms.length) rows = rows.filter((r) => { const h = blob(r); return terms.every((t) => h.includes(t)); });

      const total = mode === 'inst' ? EX.length : ACTIVITIES.length;
      const noun = mode === 'inst' ? 'institutional exemplars' : 'CS1 activities';
      status.innerHTML = rows.length
        ? `<strong>${rows.length}</strong> of ${total} ${noun}`
          + (terms.length ? ` matching &ldquo;${esc(qInput.value.trim())}&rdquo;` : '')
          + (active ? ` · ${active} filter${active > 1 ? 's' : ''} applied` : '')
        : `<strong>0</strong> ${noun} match`;

      results.innerHTML = rows.length
        ? rows.map(mode === 'inst' ? instCard : actCard).join('')
        : `<div class="empty" style="grid-column:1/-1"><p><strong>Nothing matches those filters.</strong></p>
           <p class="small">Try removing a filter — there are only ${total} ${noun} in this release.</p></div>`;
    };

    /* ---- detail dialogs ---- */
    const openSheet = (title, body) => {
      $('#ex-dialog-title').textContent = title;
      $('#ex-dialog-body').innerHTML = body;
      dialog.showModal();
    };
    const list = (items) => `<ul style="padding-inline-start:1.15rem; display:grid; gap:.45rem; margin:0">${items.map((i) => `<li>${i}</li>`).join('')}</ul>`;
    const acts = (arr) => (arr && arr.length
      ? `<dl style="display:grid; gap:.9rem; margin:0">${arr.map(([n, d]) => `<div><dt style="font-weight:600">${n}</dt><dd style="margin:.2rem 0 0; color:var(--fg-muted); font-size:.94rem">${d}</dd></div>`).join('')}</dl>`
      : '<p class="muted small">—</p>');

    const openInst = (id) => {
      const r = byId[id];
      if (!r) return;
      openSheet(`${r.name} (${r.short})`, `
        <div class="cluster mb-3">
          <span class="pill pill--brand">${esc(r.role)}</span>
          ${r.lang.map((l) => `<span class="pill pill--accent">${esc(l)}</span>`).join('')}
          <span class="pill">${esc(r.setting)}</span>
        </div>
        <p class="lede" style="font-size:1.02rem">${r.blurb}</p>

        <h4 class="mt-5">Descriptor elements</h4>
        <div class="table-wrap mt-2"><table class="data"><tbody>
          <tr><th scope="row" style="inline-size:11rem">Chapter authors</th><td>${r.team.map(esc).join(', ')}</td></tr>
          <tr><th scope="row">Location</th><td>${esc(r.place)}</td></tr>
          <tr><th scope="row">Language(s)</th><td>${r.lang.map(esc).join(', ')}</td></tr>
          <tr><th scope="row">Textbook</th><td>${r.textbook}</td></tr>
          <tr><th scope="row">Prerequisites</th><td>${r.prereq}</td></tr>
          <tr><th scope="row">Context for use</th><td>${r.context}</td></tr>
        </tbody></table></div>

        <h4 class="mt-5">PDC topics &amp; Bloom levels</h4><div class="mt-2">${list(r.topics)}</div>
        <h4 class="mt-5">Learning outcomes</h4><div class="mt-2">${list(r.outcomes)}</div>
        <h4 class="mt-5">CS1 activities</h4><div class="mt-2">${acts(r.cs1)}</div>
        <h4 class="mt-5">Evidence</h4><p class="muted mt-2">${r.evidence}</p>
        <h4 class="mt-5">Planned for the CS2 release</h4><div class="mt-2">${acts(r.cs2)}</div>

        <h4 class="mt-5">Get the material</h4>
        <div class="cluster mt-2">
          ${r.ch.map(([label, href, pp]) => `<a class="btn btn--primary btn--sm" href="${dl(href)}">${esc(label)} · ${esc(pp)}</a>`).join('')}
          <a class="btn btn--ghost btn--sm" href="${esc(r.repo)}">Repository</a>
        </div>
        ${r.repoNote ? `<p class="tiny faint mt-2">${esc(r.repoNote)}</p>` : ''}`);
    };

    const openAct = (id) => {
      const a = ACTIVITIES.find((x) => x.id === id);
      if (!a) return;
      const where = a.used.map((i) => byId[i]).filter(Boolean);
      openSheet(a.name.replace(/&amp;/g, '&'), `
        <div class="cluster mb-3">
          <span class="pill pill--brand">${esc(a.kind)}</span>
          <span class="pill">${esc(a.family)}</span>
          <span class="pill pill--accent">${esc(a.section)}</span>
        </div>
        <p class="lede" style="font-size:1.02rem">${a.desc}</p>

        <h4 class="mt-5">Primary PDC ideas</h4><div class="mt-2">${list(a.pdc.map(esc))}</div>

        <h4 class="mt-5">What it attaches to in CS1</h4><p class="muted mt-2">${esc(a.anchors)}</p>

        <h4 class="mt-5">Running it</h4>
        <div class="table-wrap mt-2"><table class="data"><tbody>
          <tr><th scope="row" style="inline-size:11rem">Duration</th><td>${esc(a.duration)}</td></tr>
          <tr><th scope="row">Setup</th><td>${esc(a.setup)}</td></tr>
          <tr><th scope="row">Described in</th><td>Chapter 1, ${esc(a.section)}</td></tr>
        </tbody></table></div>

        <h4 class="mt-5">Institutions that ran it</h4>
        <div class="grid grid--auto mt-2">
          ${where.map((r) => `<article class="card"><h4>${esc(r.short)}</h4>
            <p class="tiny faint">${esc(r.setting)} · ${r.lang.map(esc).join(', ')}</p>
            <p class="card-foot"><a href="${dl(r.ch[0][1])}">${esc(r.ch[0][0])} (PDF)</a></p></article>`).join('')}
        </div>`);
    };

    results.addEventListener('click', (e) => {
      const i = e.target.closest('[data-open-inst]');
      if (i) return openInst(i.dataset.openInst);
      const a = e.target.closest('[data-open-act]');
      if (a) return openAct(a.dataset.openAct);
    });
    dialog.addEventListener('click', (e) => { if (e.target === dialog) dialog.close(); });

    /* ---- mode switch (also deep-linkable as #activities) ---- */
    const setMode = (next) => {
      mode = next;
      $$('[data-mode]').forEach((b) => b.setAttribute('aria-selected', String(b.dataset.mode === next)));
      qInput.placeholder = next === 'inst'
        ? 'Search — e.g. “penny”, “OpenMP”, “community college”, “Greenfoot”'
        : 'Search — e.g. “unplugged”, “speedup”, “JSON”, “no setup”';
      form.reset();
      buildFacets();
      render();
    };
    $$('[data-mode]').forEach((btn) => btn.addEventListener('click', () => setMode(btn.dataset.mode)));

    const fromHash = () => {
      if (location.hash === '#activities') setMode('act');
      else if (location.hash === '#institutions') setMode('inst');
    };
    addEventListener('hashchange', fromHash);

    /* Deep links from elsewhere on the site:
         ebook.php?activity=flag-maker      → activity mode, that sheet open
         ebook.php?institution=tntech       → institution mode, that sheet open
       Both also accept the plain #activities / #institutions hashes above. */
    const fromQuery = () => {
      const q = new URLSearchParams(location.search);
      const act = q.get('activity');
      const inst = q.get('institution');
      if (act && ACTIVITIES.some((a) => a.id === act)) {
        setMode('act');
        requestAnimationFrame(() => openAct(act));
      } else if (inst && byId[inst]) {
        setMode('inst');
        requestAnimationFrame(() => openInst(inst));
      }
    };

    form.addEventListener('input', render);
    form.addEventListener('submit', (e) => e.preventDefault());
    $('#ex-clear')?.addEventListener('click', () => {
      form.reset(); qInput.value = ''; render(); qInput.focus();
    });

    buildFacets();
    render();
    fromHash();
    fromQuery();
  })();


  /* ======================================================== team cards (team) */
  (() => {
    const box = $('#team-cards');
    if (!box || typeof EX === 'undefined') return;
    /* first + last initial, so "April R. Crockett" reads AC rather than AR */
    const initials = (n) => {
      const w = n.replace(/\(.*?\)/g, '').trim().split(/\s+/).filter(Boolean);
      return ((w[0]?.[0] || '') + (w.length > 1 ? w[w.length - 1][0] : '')).toUpperCase();
    };
    box.innerHTML = EX.map((r) => `
      <article class="card">
        <div class="split"><h3>${esc(r.short)}</h3><span class="pill pill--brand push">${esc(r.role)}</span></div>
        <p class="tiny faint">${esc(r.name)} · ${esc(r.place)}</p>
        <div class="grid mt-3" style="gap:.75rem">
          ${r.team.map((p) => `<div class="person"><span class="avatar" aria-hidden="true">${initials(p)}</span><span><span class="who">${esc(p)}</span><span class="where">${r.lang.map(esc).join(' / ')} · ${esc(r.setting)}</span></span></div>`).join('')}
        </div>
        <p class="card-foot"><a href="ebook.php#chapters">${esc(r.ch[0][0])}</a> · <a href="${esc(r.repo)}">Repository</a></p>
      </article>`).join('');
  })();

  /* ================================================================= NEWS
     The list is rendered server-side from assets/news.json. This groups it
     into pages and steps through them, so there is a clear first page and
     the dots always say where you are — a continuous marquee gave neither. */
  (() => {
    const win = $('[data-news-window]');
    if (!win) return;
    const reel  = $('.news-reel', win);
    const list  = reel && $('.news-list', reel);
    const pager = $('[data-news-pager]');
    const dotBox = pager && $('[data-news-dots]', pager);
    const count  = pager && $('[data-news-count]', pager);
    if (!list) return;

    const items = [...list.children];
    const PER_PAGE = 3;
    if (items.length <= PER_PAGE) return;          // fits already; leave it alone

    /* Re-group the server-rendered <li>s into one list per page. */
    const pages = [];
    for (let i = 0; i < items.length; i += PER_PAGE) {
      const page = document.createElement('div');
      page.className = 'news-page';
      const ul = document.createElement('ul');
      ul.className = 'news-list';
      items.slice(i, i + PER_PAGE).forEach((li) => ul.appendChild(li));
      page.appendChild(ul);
      pages.push(page);
    }
    reel.replaceChildren(...pages);

    /* Every page is as tall as the tallest, so a step is always one window. */
    const pageHeight = Math.max(...pages.map((p) => p.getBoundingClientRect().height));
    pages.forEach((p) => { p.style.minBlockSize = `${pageHeight}px`; });
    win.style.blockSize = `${pageHeight}px`;
    win.dataset.paged = '';

    let index = 0, timer = null;
    const DWELL = 7000;

    const dots = pages.map((_, i) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'dot';
      b.setAttribute('role', 'tab');
      b.setAttribute('aria-selected', String(i === 0));
      b.setAttribute('aria-label', `News page ${i + 1} of ${pages.length}`);
      b.addEventListener('click', () => { show(i); restart(); });
      dotBox?.appendChild(b);
      return b;
    });

    function show(next) {
      index = (next + pages.length) % pages.length;
      reel.style.translate = `0 ${-index * pageHeight}px`;
      dots.forEach((d, i) => d.setAttribute('aria-selected', String(i === index)));
      if (count) {
        const from = index * PER_PAGE + 1;
        const to = Math.min(from + PER_PAGE - 1, items.length);
        count.textContent = `${from}–${to} of ${items.length}`;
      }
    }

    const stop = () => { clearInterval(timer); timer = null; };
    const play = () => {
      if (timer || reducedMotion()) return;
      timer = setInterval(() => show(index + 1), DWELL);
    };
    const restart = () => { stop(); play(); };

    pager?.removeAttribute('hidden');
    show(0);
    play();

    /* Hold still while the reader is actually looking at it. */
    ['pointerenter', 'focusin'].forEach((ev) => win.addEventListener(ev, stop));
    ['pointerleave', 'focusout'].forEach((ev) => win.addEventListener(ev, play));
    pager?.addEventListener('pointerenter', stop);
    pager?.addEventListener('pointerleave', play);
    document.addEventListener('visibilitychange', () => (document.hidden ? stop() : play()));

    if ('IntersectionObserver' in window) {
      let seen = false;
      new IntersectionObserver((entries) => {
        entries.forEach((en) => {
          if (en.isIntersecting) { seen = true; play(); }
          else if (seen) stop();
        });
      }, { threshold: 0.2 }).observe(win);
    }
  })();

  /* ------------------------------------------------------------ disclosure --
     One handler for the prior-work timeline rows and the FAQ. Click toggles
     [data-open] on the row; CSS animates the 0fr -> 1fr grid.               */
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-disclose]');
    if (!btn) return;
    const row = btn.parentElement;
    const open = !row.hasAttribute('data-open');
    row.toggleAttribute('data-open', open);
    btn.setAttribute('aria-expanded', String(open));
  });

  /* ----------------------------------------------------------- FAQ reveal --
     Progressive enhancement: without JS all questions are visible and there is
     no button. With JS, the tail collapses behind one.                       */
  (() => {
    const more = $('[data-faq-more]');
    const btn  = $('[data-faq-toggle]');
    if (!more || !btn) return;

    const extra = more.children.length;
    let open = false;
    const sync = () => {
      more.toggleAttribute('hidden', !open);
      btn.setAttribute('aria-expanded', String(open));
      btn.textContent = open ? 'Show fewer questions' : `Show ${extra} more questions`;
    };
    sync();
    btn.removeAttribute('hidden');

    btn.addEventListener('click', () => {
      open = !open;
      sync();
      if (open) more.querySelector('summary')?.focus({ preventScroll: true });
    });
  })();

  /* =================================================== chapter table (ebook)
     Each chapter is one row. A second row underneath holds the authors and the
     chapter's own contents, collapsed by default so the table stays short.   */
  (() => {
    const body = $('#chapter-rows');
    if (!body || typeof CHAPTERS === 'undefined') return;

    const chev = '<svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor"'
      + ' stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
      + '<path d="m9 6 6 6-6 6"/></svg>';

    body.innerHTML = CHAPTERS.map((c, i) => {
      const key = c.file;
      const count = DL_COUNTS[key] || 0;
      const hasDetail = c.sections.length || c.authors;

      /* A section with subsections becomes its own disclosure — chapter,
         section, subsection, collapsed at each step. */
      const secs = c.sections.map((s) => (s.sub.length ? `
        <li class="sec">
          <button class="sec-btn" type="button" aria-expanded="false" data-disclose>
            ${chev}<span class="sec-n">${esc(s.n)}</span><span class="sec-t">${esc(s.t)}</span>
          </button>
          <div class="disclose-panel"><div>
            <ol class="sec-subs">${s.sub.map((x) => `
              <li><span class="sec-n">${esc(x[0])}</span><span class="sec-t">${esc(x[1])}</span></li>`).join('')}
            </ol>
          </div></div>
        </li>` : `
        <li class="sec sec--leaf">
          <span class="sec-n">${esc(s.n)}</span><span class="sec-t">${esc(s.t)}</span>
        </li>`)).join('');

      return `
      <tr class="ch-row"${hasDetail ? ` id="ch-${i}"` : ''}>
        <th scope="row" style="font-weight:500">
          ${hasDetail
            ? `<button class="ch-toggle" type="button" aria-expanded="false"
                       aria-controls="ch-detail-${i}" data-ch="${i}">
                 ${chev}<span class="ch-title">${c.title}</span>
               </button>`
            : `<span class="ch-plain">${c.title}</span>`}
          <span class="ch-meta">${c.part} &middot; pp ${c.pages}</span>
        </th>
        <td class="small faint">${c.part}</td>
        <td class="small faint nowrap">${c.pages}</td>
        <td class="small faint nowrap dl-count">${count.toLocaleString()}</td>
        <td><a class="btn btn--ghost btn--sm" href="${dl('chapters/' + key + '.pdf')}">PDF <span class="tiny faint">${c.pp}</span></a></td>
      </tr>
      ${hasDetail ? `
      <tr class="ch-detail-row" hidden>
        <td colspan="5">
          <div class="ch-detail" id="ch-detail-${i}">
            ${c.authors ? `<p class="ch-authors">${esc(c.authors)}<span class="ch-inst">${esc(c.inst)}</span></p>` : ''}
            ${secs ? `<ol class="ch-sections">${secs}</ol>` : ''}
          </div>
        </td>
      </tr>` : ''}`;
    }).join('');

    /* Rows are siblings, not nested, so the toggle walks to the next row. */
    body.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-ch]');
      if (!btn) return;
      const row = btn.closest('tr');
      const detail = row.nextElementSibling;
      if (!detail || !detail.classList.contains('ch-detail-row')) return;
      const open = detail.hasAttribute('hidden');
      detail.toggleAttribute('hidden', !open);
      row.toggleAttribute('data-open', open);
      btn.setAttribute('aria-expanded', String(open));
    });
  })();

})();
