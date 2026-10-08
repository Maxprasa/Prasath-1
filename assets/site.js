// kuvadoo.fi — small progressive enhancements. Everything works without this file:
// video cards are links to YouTube, gallery photos are links to the large image.
(() => {
  'use strict';

  // 1) Click-to-load YouTube (privacy: no request to YouTube before the click).
  document.addEventListener('click', (ev) => {
    const a = ev.target.closest('a[data-yt]');
    if (!a || ev.ctrlKey || ev.metaKey || ev.shiftKey) return;
    ev.preventDefault();
    const id = a.dataset.yt;
    if (!/^[\w-]{11}$/.test(id)) return;
    const f = document.createElement('iframe');
    f.src = `https://www.youtube-nocookie.com/embed/${id}?autoplay=1&rel=0&modestbranding=1`;
    const t = a.closest('figure') && a.closest('figure').querySelector('.video-title');
    f.title = (t ? t.textContent : a.textContent).trim();
    f.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
    f.allowFullscreen = true;
    f.referrerPolicy = 'strict-origin-when-cross-origin';
    const box = document.createElement('div');
    box.className = 'video-play';
    box.appendChild(f);
    a.replaceWith(box);
    f.focus();
  });

  document.documentElement.classList.remove('no-js');
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const fine = matchMedia('(hover: hover) and (pointer: fine)').matches;
  const lerp = (a, b, t) => a + (b - a) * t;
  const fi = document.documentElement.lang === 'fi';

  // 3) Scroll effects: scroll-scrubbed hero (photo grows from a card to full width),
  //    giant outline text, images inside album cards.
  const scrub = reduce ? null : document.querySelector('[data-scrub]');
  const header = document.querySelector('.site-header');
  const ghost = document.querySelector('.ghost');
  const inners = [...document.querySelectorAll('[data-inner] img')];
  if (scrub) scrub.classList.add('is-scrub');
  if (!reduce && (scrub || ghost || inners.length)) {
    let queued = false;
    const update = () => {
      queued = false;
      const vh = innerHeight;
      if (scrub) {
        const hdr = header ? header.offsetHeight : 0;
        document.documentElement.style.setProperty('--hdr', hdr + 'px');
        const r = scrub.getBoundingClientRect();
        const room = r.height - (vh - hdr);
        const t = room > 0 ? Math.min(1, Math.max(0, (hdr - r.top) / (room * 0.85))) : 1;
        scrub.style.setProperty('--p', (t * t * (3 - 2 * t)).toFixed(4));
      }
      if (ghost) { const r = ghost.parentElement.getBoundingClientRect(); ghost.style.transform = `translate3d(${(r.top - vh) * 0.35}px,-50%,0)`; }
      inners.forEach(img => {
        const r = img.parentElement.getBoundingClientRect();
        if (r.bottom < 0 || r.top > vh) return;
        const p = (r.top + r.height / 2 - vh / 2) / vh;
        img.style.transform = `translate3d(0,${p * -8}%,0) scale(1.14)`;
      });
    };
    addEventListener('scroll', () => { if (!queued) { queued = true; requestAnimationFrame(update); } }, { passive: true });
    addEventListener('resize', update);
    update();
  }

  // 4) 3D photo ring: drag / swipe / buttons; slow auto-spin unless reduced motion.
  const ring = document.querySelector('.ring');
  if (ring) {
    const stage = ring.parentElement, items = [...ring.children], n = items.length, step = 360 / n;
    let angle = 0, target = 0, vel = 0, dragging = false, moved = 0, lastX = 0, idle = 0, radius = 0, visible = true;
    const place = () => {
      radius = Math.round(ring.offsetWidth / 2 / Math.tan(Math.PI / n)) + 28;
      items.forEach((it, i) => { it.style.transform = `rotateY(${i * step}deg) translateZ(${radius}px)`; });
    };
    const render = () => {
      ring.style.transform = `translateZ(${-radius}px) rotateY(${angle}deg)`;
      items.forEach((it, i) => {
        const a = (((i * step + angle) % 360) + 540) % 360 - 180;
        it.style.filter = `brightness(${1 - Math.min(Math.abs(a) / 180, 1) * 0.55})`;
      });
    };
    place(); addEventListener('resize', place);
    new IntersectionObserver(es => { visible = es[0].isIntersecting; }).observe(stage);
    const tick = () => {
      requestAnimationFrame(tick);
      if (!visible || document.hidden) return;
      if (!dragging) {
        if (Math.abs(vel) > 0.02) { target += vel; vel *= 0.94; }
        else if (!reduce && performance.now() - idle > 2500) target -= 0.05;
      }
      angle = lerp(angle, target, dragging ? 0.35 : 0.08);
      render();
    };
    stage.addEventListener('pointerdown', e => { dragging = true; moved = 0; lastX = e.clientX; vel = 0; stage.classList.add('drag'); stage.setPointerCapture(e.pointerId); });
    stage.addEventListener('pointermove', e => { if (!dragging) return; const dx = e.clientX - lastX; lastX = e.clientX; moved += Math.abs(dx); target += dx * 0.3; vel = dx * 0.3; idle = performance.now(); });
    const end = () => { if (!dragging) return; dragging = false; stage.classList.remove('drag'); idle = performance.now(); if (Math.abs(vel) < 0.5) target = Math.round(target / step) * step; };
    stage.addEventListener('pointerup', end); stage.addEventListener('pointercancel', end);
    // A drag must not open the album; a tap/click does
    stage.addEventListener('click', e => { if (moved > 6) e.preventDefault(); }, true);
    stage.addEventListener('dragstart', e => e.preventDefault());
    document.querySelectorAll('[data-ring]').forEach(b => b.addEventListener('click', () => {
      vel = 0; idle = performance.now(); target = Math.round(target / step) * step + step * +b.dataset.ring;
    }));
    requestAnimationFrame(tick);
  }

  // 5) Tilt cards with a light reflection (mouse and touch).
  if (!reduce) document.querySelectorAll('.tilt').forEach(c => {
    const set = e => {
      const r = c.getBoundingClientRect(), x = (e.clientX - r.left) / r.width, y = (e.clientY - r.top) / r.height;
      c.classList.add('live');
      c.style.setProperty('--ry', `${(x - 0.5) * 14}deg`); c.style.setProperty('--rx', `${(0.5 - y) * 10}deg`);
      c.style.setProperty('--gx', `${x * 100}%`); c.style.setProperty('--gy', `${y * 100}%`); c.style.setProperty('--ga', 1);
    };
    const reset = () => { c.classList.remove('live'); c.style.setProperty('--rx', '0deg'); c.style.setProperty('--ry', '0deg'); c.style.setProperty('--ga', 0); };
    c.addEventListener('pointermove', set); ['pointerleave', 'pointerup', 'pointercancel'].forEach(t => c.addEventListener(t, reset));
  });

  // 6) Buttons: fill starts where you press; magnetic pull with a mouse.
  document.querySelectorAll('.btn').forEach(b => {
    const at = e => { const r = b.getBoundingClientRect(); b.style.setProperty('--fx', `${e.clientX - r.left}px`); b.style.setProperty('--fy', `${e.clientY - r.top}px`); return r; };
    b.addEventListener('pointerdown', at);
    b.addEventListener('pointerenter', at);
    if (fine && !reduce) {
      b.addEventListener('pointermove', e => { const r = at(e); b.style.setProperty('--mx', `${(e.clientX - r.left - r.width / 2) * 0.2}px`); b.style.setProperty('--my', `${(e.clientY - r.top - r.height / 2) * 0.3}px`); });
      b.addEventListener('pointerleave', () => { b.style.setProperty('--mx', '0px'); b.style.setProperty('--my', '0px'); });
    }
  });

  // 7) Nav pill that slides under the hovered link (mouse).
  const navUl = document.querySelector('.site-nav ul');
  if (navUl && fine) {
    const pill = document.createElement('span'); pill.className = 'nav-pill'; pill.setAttribute('aria-hidden', 'true'); navUl.prepend(pill);
    navUl.querySelectorAll('a').forEach(a => a.addEventListener('pointerenter', () => { const li = a.parentElement; pill.style.left = li.offsetLeft + 'px'; pill.style.width = li.offsetWidth + 'px'; pill.style.opacity = 1; }));
    navUl.addEventListener('pointerleave', () => { pill.style.opacity = 0; });
  }

  // 2) Lightbox for gallery photos.
  const dlg = document.querySelector('dialog.lightbox');
  const items = [...document.querySelectorAll('a[data-lb]')];
  if (!dlg || !items.length || typeof dlg.showModal !== 'function') return;
  const img = dlg.querySelector('.lb-img');
  const cap = dlg.querySelector('.lb-cap');
  let i = 0;
  let opener = null;

  const show = (n) => {
    i = (n + items.length) % items.length;
    const a = items[i];
    const thumb = a.querySelector('img');
    img.src = a.dataset.lb;
    img.srcset = a.dataset.lbSrcset || '';
    img.sizes = '100vw';
    img.alt = thumb ? thumb.alt : '';
    cap.textContent = `${img.alt ? img.alt + ' – ' : ''}${i + 1} / ${items.length}`;
    // Preload the next one
    const next = items[(i + 1) % items.length];
    if (next) { const p = new Image(); p.src = next.dataset.lb; }
  };

  items.forEach((a, n) => a.addEventListener('click', (ev) => {
    if (ev.ctrlKey || ev.metaKey || ev.shiftKey) return;
    ev.preventDefault();
    opener = a;
    show(n);
    dlg.showModal();
  }));
  dlg.querySelector('.lb-close').addEventListener('click', () => dlg.close());
  dlg.querySelector('.lb-prev').addEventListener('click', () => show(i - 1));
  dlg.querySelector('.lb-next').addEventListener('click', () => show(i + 1));
  dlg.addEventListener('close', () => { img.removeAttribute('src'); img.removeAttribute('srcset'); if (opener) opener.focus(); });
  dlg.addEventListener('click', (ev) => { if (ev.target === dlg || ev.target.classList.contains('lb-figure')) dlg.close(); });
  dlg.addEventListener('keydown', (ev) => {
    if (ev.key === 'ArrowLeft') show(i - 1);
    if (ev.key === 'ArrowRight') show(i + 1);
  });
  // Swipe on phones
  let x0 = null;
  dlg.addEventListener('touchstart', (ev) => { x0 = ev.touches[0].clientX; }, { passive: true });
  dlg.addEventListener('touchend', (ev) => {
    if (x0 === null) return;
    const dx = ev.changedTouches[0].clientX - x0;
    if (Math.abs(dx) > 50) show(i + (dx < 0 ? 1 : -1));
    x0 = null;
  });
})();
