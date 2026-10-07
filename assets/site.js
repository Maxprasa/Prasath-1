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
