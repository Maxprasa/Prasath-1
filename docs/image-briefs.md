# Image briefs (for Higgsfield or any image generator)

The owner creates the images; Claude optimises them (resize, compress, WebP/PNG) and places them.
Send finished images in the chat. File names below are where they will go.

## Rules for every image
- **No text or letters in the image** (generators garble text; we add text in HTML).
- **No real people, no flags, no coats of arms, no official-looking symbols** (the site must not
  look "official" or connected to any authority).
- Calm & warm style, matching the site: forest green `#1F6F4A`, light green `#E3EFE7`,
  warm off-white `#FAF7F0`, soft sand `#E2DBCC`, a little warm yellow `#E8B54A` as a highlight.
- Plain background in warm off-white `#FAF7F0` (so it blends into the page) unless the brief says otherwise.
- Generate at the size given or larger; Claude will scale down.
- AI-generated illustrations are fine as decoration; product screenshots must be **real** app screens.

**Style line to paste at the end of every prompt:**

> flat vector illustration, soft rounded shapes, minimal, calm and friendly, gentle grain texture,
> limited palette of forest green #1F6F4A, light green #E3EFE7, warm off-white #FAF7F0, sand #E2DBCC
> and a small accent of warm yellow #E8B54A, plain warm off-white background, no text, no letters,
> no people, high quality, editorial style

---

## Priority 1 — needed for a professional look

### 1. FinnSana app icon → `assets/finnsana-icon.png` (512×512)
**Do not generate a new icon.** Use the real icon from the FinnSana app (the one on Google Play), so
the website and the store match. Export it at 512×512 PNG from the app project. If you want a new icon
for the app too, design it for the app first, then send it here.

### 2. FinnSana screenshots → `assets/finnsana-screen-1.png` … `-5.png` (1080×1920, 9:16)
**Real screenshots from the app, not AI images.** Suggested screens:
1. Home / start screen
2. A word card with its picture (vocabulary)
3. A grammar lesson
4. A practice game (e.g. Word Match)
5. Review / spaced repetition screen

### 3. Home hero illustration → `assets/hero-lake.webp` (2400×1600, landscape)
Prompt:
> A peaceful Finnish lake landscape at early morning, gentle hills with pine and birch forest,
> calm water with soft reflections, a small wooden pier, a few soft clouds, lots of empty calm space
> on the left side for a headline, [style line]

## Priority 2 — nice to have

### 4. Four FinnSana feature illustrations → `assets/finnsana-art-words.webp` etc. (1200×1200, square)
Same style for all four, used next to the feature rows until screenshots exist.
- **Words** — "An open picture dictionary with small floating cards showing a birch tree, a coffee
  cup, a bicycle and a sauna bucket, small sound waves around the cards, [style line]"
- **Grammar** — "Neatly stacked building blocks of different sizes forming a small staircase, each
  block a different shade of green, a soft light bulb above, [style line]"
- **Games** — "Playful floating game pieces: matching cards, a stopwatch, a checkmark and a cross,
  a puzzle piece, arranged in a gentle circle, [style line]"
- **Review** — "A circular path with small stepping stones looping back on itself around a small
  calendar and a seedling growing into a little tree, [style line]"

### 5. Google Play feature graphic → `docs/store/finnsana-feature-graphic.png` (1024×500)
For the Play Store listing (not the website). Leave the left half calm for text added later.
> Wide banner, a calm Finnish lakeside with birch trees on the right side, small floating
> vocabulary cards with simple pictures (sun, coffee cup, boat), left half plain light green
> #E3EFE7 empty space, [style line]

### 6. Empty-state art for "More apps are on the way" → `assets/more-apps.webp` (1200×900)
> A small cosy workshop desk with a sketchbook, a phone showing a blank green screen, a cup of tea
> and a small potted plant, viewed from the front, [style line]

---

## How Claude processes the images
- Resize to 2× display size, convert to WebP (keep PNG for icons), compress (target < 150 KB each).
- Add `width`/`height`, `loading="lazy"` and alt text (decorative art gets `alt=""`).
- Note "illustrations are AI-generated" in the small print where relevant.
