# Animated creative websites: what makes them feel premium (October 2026)

Research for kuvadoo.fi, a photo and video studio site rendered by PHP, with several pages. Fonts are out of scope.
**Method note:** WebFetch reads HTML text, not motion. So the "what it does" notes come from each site's Awwwards
page (awards, listed tech, "highlighted elements") and from the site's own markup. Points marked *(inferred)* are
guesses based on markup, not things I saw move.

## Recommendations (ranked by impact vs effort)

| # | Effect | Build | Impact | Effort |
|---|---|---|---|---|
| 1 | Cross-page transitions (`@view-transition`), including a thumbnail that morphs into the project hero | Pure CSS, plus about 20 lines of JS for the morph | Very high | Low |
| 2 | Work list with a hover image or video preview that follows the cursor | Small vanilla JS (about 2 KB) | Very high | Low–med |
| 3 | Line-by-line headline reveals (masked lines slide up) | GSAP SplitText `mask:"lines"`, or CSS with hand-wrapped lines | High | Low |
| 4 | Images revealed with clip-path or inset as they scroll in, with a slight scale-down | CSS `animation-timeline: view()`, with IntersectionObserver as fallback | High | Low |
| 5 | Muted showreel hero video with a visible pause button | HTML `<video>` plus 10 lines of JS | High | Low |
| 6 | Parallax image inside a fixed frame (image moves about 10%, frame stays still) | CSS scroll-driven animation | Med–high | Low |
| 7 | Custom cursor label ("View", "Play", "Drag") on pointer devices only | Vanilla JS (about 1.5 KB) | Medium | Low |
| 8 | Short first-visit intro with a counter (under 1.2 s, skippable, once per session) | Vanilla JS and CSS | Medium | Low |
| 9 | Client-logo marquee that pauses on hover or focus and has a pause control | Pure CSS | Medium | Low |
| 10 | Optional Lenis smooth scroll (desktop only, off under reduced motion) | Lenis, 18 KB minified | Low–med | Low |

**Stack:** the native platform first: CSS scroll-driven animations, cross-document View Transitions, and
IntersectionObserver. Then add **GSAP core + ScrollTrigger + SplitText** only if we need timelines or pinning. GSAP is now free
for commercial use (see below). Self-host it. Estimated budget: 0–50 KB gz.

**Avoid**
- Scroll-jacking and long pinned horizontal sections, especially on phones. Never put key text or CTAs inside them ([NN/g](https://www.nngroup.com/articles/scrolljacking-101/)).
- Full-screen WebGL or three.js scenes on phones. They are heavy and drain the battery. Keep them, if at all, as a desktop-only extra.
- Preloaders that hide content on every page view. A "Enter with sound" gate like RemyShoots is fine for a showreel micro-site, but wrong for a booking site.
- Layout shift. Every image and video needs `width` and `height` or `aspect-ratio`. Animate only `transform`, `opacity`, `clip-path` and `filter`.
- Motion without an off switch. Under WCAG 2.2.2, anything moving for more than 5 s needs pause/stop/hide ([W3C](https://www.w3.org/WAI/WCAG22/Understanding/pause-stop-hide.html)). For 2.3.3, use `prefers-reduced-motion` (technique C39) ([W3C](https://www.w3.org/WAI/WCAG22/Understanding/animation-from-interactions.html)).
- Count-up statistics. Palomino shows them, but kuvadoo.fi should only show numbers the owner has written down.

## 1. Reference sites (2026 Awwwards winners, photo, film and creative studios)

| Site | Award (Awwwards) | What stands out | Tech listed / seen |
|---|---|---|---|
| [Milledollars](https://milledollars.fr/), a video production bureau | SOTD + Dev, 2 Oct 2026; Animations 7.40 ([page](https://www.awwwards.com/sites/milledollars)) | Very sparse page: a numbered work list (01–07, title, client, year) with highlighted Loader, Gallery and Work motion | GSAP, Vue, Contentful |
| [LxL Creative](https://www.lxlcreative.co.uk/), an entertainment campaign agency | SOTD + Dev, 17 Sep 2026; Animations 8.0, A11y 7.6 ([page](https://www.awwwards.com/sites/lxl-creative)) | Draggable photo carousel hero with a "Drag" label, nav dropdowns with project thumbnails, page transitions | GSAP, Barba.js, Webflow |
| [Michael Gatt](https://michaelgatt.com/), a composer (film, TV, games) | SOTD + Dev, 18 Aug 2026; Animations 8.60 ([page](https://www.awwwards.com/sites/michael-gatt)) | Interactive loader, scroll-based storytelling on the About page, player equaliser | WebGL, Nuxt |
| [Warm & Fuzzy](https://www.warmnfuzzy.tv/), a production and VFX company | SOTD + Dev, 12 Sep 2026 ([page](https://www.awwwards.com/sites/warm-fuzzy)) | Video hero; highlighted Work hover, page transition, idle animation and intro animation; witty footer | Next.js, Mux video |
| [Butter](https://www.butter.video/), a video editor | SOTD + Dev, 28 Sep 2026 ([page](https://www.awwwards.com/sites/butter)) | Timeline-themed motion; filter effects | WebGL, React, p5.js |
| [Gil Huybrecht](https://gilhuybrecht.com/), a portfolio | SOTD + Dev, 21 Sep 2026; Animations 8.40 ([page](https://www.awwwards.com/sites/gil-huybrecht)) | Infinite-scroll gallery view, clean typography | WebGL, Next.js |
| [Léo Parpeix](https://leoparpeix.com/), an art director | SOTD + Dev, 14 Sep 2026; Animations 8.40 ([page](https://www.awwwards.com/sites/leo-parpeix-portfolio-2026)) | Experimental 3D transitions, typography-led | WebGL, Blender |
| [RemyShoots](https://www.remyshoots.co.za/), a photo and film studio | HM, 12 Sep 2026 ([page](https://www.awwwards.com/sites/remyshoots)) | "Enter with/without sound" gate with a 00% counter; slider/grid/list view switch; "fisheye" toggle; project transitions; gestures | three.js, Next.js, Sanity |
| [Palomino](https://palominoprod.com/), a sports photo and video studio | HM, 19 Aug 2026 ([page](https://www.awwwards.com/sites/palomino)) | Screen loader, work list and grid, "Work to Archive" transition, logo strip *(inferred marquee)*, stats starting at "0" *(inferred count-up)* | Next.js, GSAP, Matter.js |
| [Remedy](https://remedyeditorial.com/), a production studio | HM, 4 Sep 2026 ([page](https://www.awwwards.com/sites/remedy)) | "Video-first showcase", floating nav, button hover, footer animation | **WordPress** |
| [Sadu Media](https://www.sadumedia.com/), a production house | HM, 6 Sep 2026 ([page](https://www.awwwards.com/sites/sadu-media)) | Loader, scrollytelling home, list-view works, works transition, scroll slider | **WordPress**, GSAP |
| [Filmbot](https://filmbot.com/), cinema ticketing | HM, 9 Sep 2026 ([page](https://www.awwwards.com/sites/filmbot)) | Highlighted "Scrolling Image Reveal", one WebGL element, page transitions | WebGL, Webflow |
| [SEE ME](https://seemecreative.com/), an artist management agency | HM, 31 Aug 2026 ([page](https://www.awwwards.com/sites/see-me)) | Big background images and video, gallery | WordPress, Nuxt |

**Patterns across them:** two-colour palettes (most use near-black and off-white, according to the Awwwards pages). Work is shown as a
**numbered list or a switchable list/grid** rather than a plain card grid. **Page transitions** and a **loader** are almost
always highlighted. Remedy, Sadu Media and SEE ME prove that server-rendered WordPress sites (like our PHP setup) can win. The heavy
WebGL sites score high on Animations (8.4–8.6) but lower on Accessibility (6.0–6.8, for example
Milledollars 6.00 and Michael Gatt 6.80). LxL, with GSAP + Barba and no WebGL, scored 7.6 on accessibility.

## 2. Effect catalogue

"RM" means what to do under `prefers-reduced-motion: reduce`. Unless stated otherwise, all content must be visible with JS off.

1. **Masked line reveal on headings.** Each line sits in an `overflow:clip` wrapper and slides up from `translateY(100%)`. GSAP SplitText does this with
   `mask:"lines"` and `autoSplit` (it re-splits after fonts load or the element resizes). Its default `aria:"auto"` puts an aria-label on the parent and hides the pieces
   ([GSAP docs](https://gsap.com/docs/v3/Plugins/SplitText/)). Cost: SplitText is 7.6 KB minified. Risk: split text breaks screen readers if `aria` is not handled. RM: show instantly. *Lib or hand-wrapped CSS.*
2. **Clip-path image reveal on scroll.** `clip-path: inset(100% 0 0 0)` animates to `inset(0)`, combined with `scale(1.15)` to `1`. Use `animation-timeline: view()`
   in CSS. For browsers without it, the image simply shows (progressive enhancement). Cost: about 0 KB. RM: no animation. *Pure CSS.*
3. **Parallax inside a frame.** The frame has `overflow:hidden`, and the image is 115% tall and moves with `translateY` on a `view()` timeline. Cost: tiny, runs on the GPU. Risk: vestibular triggers. RM: off. *Pure CSS.*
4. **Cross-document page transitions.** `@view-transition { navigation: auto; }` on both pages, plus custom `::view-transition-old/new` keyframes
   ([MDN](https://developer.mozilla.org/en-US/docs/Web/CSS/Reference/At-rules/@view-transition)). Works on normal PHP page loads, with no SPA router. RM: wrap it in `@media (prefers-reduced-motion: no-preference)`. *Pure CSS.*
5. **Thumbnail-to-hero morph.** Set `view-transition-name` on the clicked thumbnail in `pageswap` and on the hero in `pagereveal`. Register the listener in a blocking `<head>` script
   ([Chrome docs](https://developer.chrome.com/docs/web-platform/view-transitions/cross-document)). About 1 KB of vanilla JS.
6. **Hover preview on list items.** On a work list (title, client, year), a floating image or muted looping clip follows the cursor
   (LxL nav thumbnails, Warm & Fuzzy "Work Hover"). Use `pointermove` + `transform` in rAF and load the clip only on hover. Touch: no preview, so show a static
   thumbnail in the row. Keyboard: show the preview on `:focus-visible`. *Vanilla JS, about 2 KB.*
7. **Video hover previews in the grid.** A poster image plus `<video muted playsinline preload="none">` that plays on hover or focus. Cost: bandwidth, so use short 3–5 s clips under 1 MB. RM and `Save-Data`: show the poster only.
8. **Custom cursor with labels** ("View", "Play", "Drag", as on LxL). Show it only under `@media (hover:hover) and (pointer:fine)`. Keep the native cursor
   (do not use `cursor:none` on links). Use `aria-hidden` on the cursor element. *Vanilla JS, about 1.5 KB.*
9. **Magnetic buttons.** The button moves a few px toward the pointer. Pointer devices only, and off under RM. *Vanilla JS, under 1 KB.*
10. **Preloader with counter** (RemyShoots 00%, Palomino and Sadu Media loaders). The honest version is a short brand intro on the first visit only (`sessionStorage`). It must never
    block the content's HTML. RM: skip it. *Vanilla JS.*
11. **Marquee** (client logos or "Book a shoot" ticker). Use a CSS `@keyframes` translate on a duplicated list. Pause it on `:hover`/`:focus-within`, add a pause button (WCAG 2.2.2), and stop it under RM. *Pure CSS.*
12. **Showreel hero video.** Use `autoplay muted loop playsinline` with a poster. A visible pause button is required (more than 5 s of motion). Serve a small mobile file. RM: do not autoplay.
13. **Sticky stacking cards** (services or packages). `position: sticky` with growing `top` offsets, plus a slight scale-down on a scroll timeline. Pure CSS. Low risk.
14. **Horizontal pinned gallery.** Needs GSAP ScrollTrigger `pin`. This is scroll-jacking, so NN/g warns against it on mobile ([NN/g](https://www.nngroup.com/articles/scrolljacking-101/)). Better: a native `overflow-x: auto` + `scroll-snap` strip with a "Drag" cursor (as on LxL).
15. **Smooth scroll (Lenis).** MIT, 18.3 KB minified (v1.3.26, [jsDelivr](https://cdn.jsdelivr.net/npm/lenis@1/dist/)). It turns smoothing off under reduced motion by default, and touch smoothing is off by default
    ([GitHub](https://github.com/darkroomengineering/lenis)). Nice but optional.
16. **List/grid view switch** (RemyShoots "slider · grid · list"). Use `<button aria-pressed>` and a CSS class swap. With View Transitions, the items animate between layouts for free.
17. **Grain/noise overlay.** A tiny tiled SVG `feTurbulence` or PNG on a `pointer-events:none` pseudo-element. Keep it static, because animated grain costs CPU and causes motion. Check text contrast with it on.
18. **Footer reveal.** The footer sits behind the page and is uncovered as you scroll (Remedy's "footer animation", Palomino's footer). Use `position: sticky; bottom: 0` with `z-index`. Pure CSS.
19. **Idle micro-animation** (Warm & Fuzzy "Idle Animation"), for example a slowly rotating logo mark. CSS, stopped under RM.

## 3. Libraries and native features: licence and support

- **GSAP 3.15** is **free for commercial use, including all former Club plugins (SplitText, MorphSVG and others)** under the Webflow "standard
  license". The only "Prohibited Uses" are no-code visual animation *tools* that compete with Webflow. You may not remove branding or notices. Webflow can change
  the terms for future versions ([gsap.com/standard-license](https://gsap.com/standard-license/)). A normal studio website is fine. Minified sizes
  ([jsDelivr](https://cdn.jsdelivr.net/npm/gsap@3/dist/)): gsap 71.2 KB, ScrollTrigger 43.5 KB, SplitText 7.6 KB, Flip 24.9 KB.
  *Estimate (not measured):* core + ScrollTrigger + SplitText is about 45–50 KB gzipped, which fits the 60 KB budget only with nothing else.
- **Lenis:** MIT, 18.3 KB minified ([GitHub](https://github.com/darkroomengineering/lenis)).
- **Motion (formerly Framer Motion / Motion One):** MIT, and has a vanilla `animate()` API. A paid "Motion+" sells examples and premium APIs, but the core is free
  ([GitHub](https://github.com/motiondivision/motion)). I did not check its size.
- **Barba.js:** MIT, about 7 KB, marked stable ([GitHub](https://github.com/barbajs/barba)). LxL uses it. For us, native `@view-transition` makes it unnecessary,
  because it does the same job without JS on real page loads.
- **Cross-document View Transitions (`@view-transition`):** Chrome/Edge 126+, Safari/iOS 18.2+, Samsung 28+. **Not in Firefox.** About 88% global support
  ([caniuse](https://caniuse.com/mdn-css_at-rules_view-transition)). In browsers without it, the page loads normally, which is a safe fallback.
- **Scroll-driven animations (`animation-timeline`):** Chrome/Edge 115+, **Safari 26+**, Firefox **160** only. About 88% global support
  ([caniuse](https://caniuse.com/mdn-css_properties_animation-timeline_scroll)). Wrap them in `@supports (animation-timeline: view())`, and make sure content is visible by default.

**Suggested stack for kuvadoo.fi:** first CSS (items 2–4, 11, 13, 17–19), then about 6 KB of our own vanilla JS (items 5–10, 16). Add GSAP only for one
signature moment such as SplitText headline reveals, and load it only on pages that use it. This keeps most pages under 10 KB of JS.

## 4. Rules for the build

- Content first: HTML is fully readable with JS and CSS animations off. Animations only *enhance* it.
- One `@media (prefers-reduced-motion: reduce)` block turns off transforms, autoplay, marquee, parallax, smooth scroll and transitions. JS checks
  `matchMedia` too (WCAG technique SCR40).
- Pointer-only effects (cursor, magnetic, hover preview) go behind `(hover:hover) and (pointer:fine)`. Touch users get static thumbnails and plain taps.
- Every hover effect also works on `:focus-visible`.
- Measure on a mid-range Android phone: LCP image not lazy-loaded, CLS 0, no long tasks from animation.
