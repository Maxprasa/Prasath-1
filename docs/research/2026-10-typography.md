# Typography for kuvadoo.fi (photo + video studio, "Hämeen Films")

Research date: 2026-10-07. Scope: fonts that look modern and premium, can be self-hosted legally on a commercial site, and support Finnish (ä ö å).

## Recommendations (short)

1. **Test Pairing A (dark cinematic) first: Zodiak headings + Switzer body.** Both are free from Fontshare under the ITF Free Font License (FFL) and both list Finnish. This follows the most common pattern on current award sites (display serif + neutral grotesk), but Zodiak is sharper and more dramatic than Instrument Serif, which the owner rejected.
2. **Make three more mockups of the same page:** B (Gambetta + General Sans, clean editorial), C (Clash Display + Satoshi, bold vibrant) and D (Mona Sans alone, using its width axis). Show all four with the owner's real photos, since type that sits on images reads differently.
3. **Self-host WOFF2 only, with no CDN or Fontshare API**, and keep the licence file (`FFL.txt` / `OFL.txt`) next to the fonts. Use at most two families and variable files.
4. **Use the variable axes for micro-animations** (for example a weight shift on link hover, or a width change on Mona Sans headings), and switch them off under `prefers-reduced-motion`.
5. **Paid upgrade if the owner wants "the real thing":** PP Editorial New + PP Neue Montreal (Pangram Pangram). Licences start at $40, and the web licence is priced by pageviews. The free Pangram downloads are for personal use only, so they must not go live.

## 1. What award-level sites use (observations)

| Site (type, year) | Fonts | Source |
|---|---|---|
| Fermented Films (film production, 2024) | Mona Sans (narrow + normal widths), Instrument Serif/Sans, logo from Anton | [Fonts In Use](https://fontsinuse.com/uses/64040/fermented-films-1) |
| All This Productions (production company representing photographers/directors, 2025) | Chroma, Large, Plain | [Fonts In Use](https://fontsinuse.com/uses/75808/all-this-productions-website) |
| Sean Hazen (photographer, LA, 2026) | ABC Marist only, "without competing with the photography" | [Fonts In Use](https://fontsinuse.com/uses/74326/sean-hazen-portfolio-website) |
| London Short Film Festival (2025) | Diatype, Diatype Mono | [Typewolf](https://www.typewolf.com/site-of-the-day/london-short-film-festival) |
| Elena Scott (2025) | Editorial Old, Neue Montreal | [Typewolf](https://www.typewolf.com/site-of-the-day/elena-scott) |
| Nik Bentel Studio (2025) | Optima, Rom | [Typewolf p.3](https://www.typewolf.com/site-of-the-day?page=3) |
| Speakeasy (2025) | Tobias, Diatype | [Typewolf](https://www.typewolf.com/site-of-the-day) |
| Early Works / Athletics (2025) | Feature, Söhne | [Typewolf p.2](https://www.typewolf.com/site-of-the-day?page=2), [p.5](https://www.typewolf.com/site-of-the-day?page=5) |
| Dream Recorder (2025) | Times Now, Neue Montreal | [Typewolf p.2](https://www.typewolf.com/site-of-the-day?page=2) |
| Daylight (2024) | Arizona Flare, Rom | [Typewolf p.8](https://www.typewolf.com/site-of-the-day?page=8) |
| Tom Baxter (art director, 2024) | Ready Active (full-width titles), HAL Four Grotesk | [Fonts In Use](https://fontsinuse.com/uses/62108/tom-baxter-portfolio-website) |
| Alessandro Romagnoli (photographer, older: 2020) | Saol Display + Neue Montreal | [Fonts In Use](https://fontsinuse.com/uses/35481/alessandro-romagnoli-portfolio-website) |

The 2026 Awwwards photography and film honourable mentions (RemyShoots, Kavieng Creative, SENAWA, SEE ME, "Untitled.") list only colours, not fonts. They use near-black and off-white palettes such as #111111/#FFFFFF and #050505/#f0efe9 ([listing](https://www.awwwards.com/websites/photography/), [RemyShoots](https://www.awwwards.com/sites/remyshoots), [Kavieng](https://www.awwwards.com/sites/kavieng-creative)). I could not read their fonts from the fetched HTML, so they are not counted above.

**Patterns**
- **Most common:** an editorial or display serif for headlines, paired with a neutral neo-grotesk for text (Editorial Old/Saol/Tobias/Feature/Times Now/Arizona with Neue Montreal/Söhne/Diatype/Rom).
- **Second:** one grotesk system that varies its width or adds a mono (Mona Sans narrow + normal, Diatype + Diatype Mono).
- **Third:** one loud display face for titles only (Ready Active, Chroma), with a quiet text face.
- Photographers keep the text type quiet so the images lead (Sean Hazen entry).
- Most of these fonts are paid (Pangram, Klim, Dinamo, Commercial Type). The free fonts below are chosen to play the same roles.

## 2. Shortlist (self-hostable, Finnish OK)

**Fontshare licence (ITF FFL).** I could not render the official page [fontshare.com/licenses/itf-ffl](https://www.fontshare.com/licenses/itf-ffl) because it is JavaScript-only. Its indexed text (Version 2.0, 17 Aug 2026) says: "You may self-host the Font Software on your own servers or infrastructure for use on your own websites and applications, including through standard webfont technologies such as CSS @font-face. Self-hosting by end users is permitted and recommended." Redistributing the font files themselves is not allowed. Older third-party pages say the opposite, for example [fontalternatives](https://fontalternatives.com/fonts/satoshi/) and an [adom wiki](https://wiki.adom.inc/api/pages/adom/adom-theme/files/fonts/satoshi/README.md), which quotes a ban on "uploading them in a public server". **Action:** read `FFL.txt` inside each downloaded zip before going live, and keep it in `assets/fonts/`.

The Fontshare data below (licence type `itf_ffl`, axes, "Finnish" in the languages list) comes from the official API, [api.fontshare.com/v2/fonts](https://api.fontshare.com/v2/fonts?offset=0&limit=100). Download pattern: `https://api.fontshare.com/v2/fonts/download/<slug>`. I confirmed it returns a zip for `general-sans` (the zip contained `Fonts/TTF/GeneralSans-Variable.ttf`). Check each zip for a WEB folder with WOFF2 files. If there is none, convert the TTF to WOFF2.

| Font | Role / mood | Licence | Weights / axes | Finnish |
|---|---|---|---|---|
| **Zodiak** (Fontshare) | High-contrast display serif; dramatic, cinematic | ITF FFL | wght 100–900 + italics | Yes |
| **Gambetta** (Fontshare) | Warmer editorial serif | ITF FFL | variable wght + italics | Yes |
| **Boska** (Fontshare) | Sharp, fashion-style serif | ITF FFL | wght 200–900 | Yes |
| **Sentient** (Fontshare) | Calm text serif | ITF FFL | variable wght + italics | Yes |
| **Switzer** (Fontshare) | Neutral Swiss neo-grotesk; body text | ITF FFL | wght 100–900 + italics | Yes |
| **General Sans** (Fontshare) | Friendly modern grotesk; body/UI | ITF FFL | wght 200–700 + italics | Yes |
| **Satoshi** (Fontshare) | Clean geometric grotesk; body/UI | ITF FFL | wght 300–900 + italics | Yes |
| **Clash Display** (Fontshare) | Tight, punchy display grotesk | ITF FFL | wght 200–700 | Yes |
| **Panchang** (Fontshare) | Wide/extended grotesk for big titles | ITF FFL | wght 200–800 | Yes |
| **Mona Sans** (GitHub) | Grotesk superfamily; condensed to wide | SIL OFL 1.1 ([repo](https://github.com/github/mona-sans)) | wdth 75–125, wght 200–900, opsz, ital | Yes: the latin subset covers U+0000–00FF, which includes ä ö å ([Fontsource CSS](https://cdn.jsdelivr.net/npm/@fontsource-variable/mona-sans/index.css)) |
| **Fraunces** (spare OFL serif) | Soft "old-style" display serif | OFL-1.1 ([Fontsource API](https://api.fontsource.org/v1/fonts/fraunces)) | variable wght 100–900 + italics, latin + latin-ext | Yes (latin subset) |

Note: Fontsource's variable Mona Sans file only has the weight axis. For the width axis, use GitHub's `mona-sans-webfonts` zip.

**Paid options**
- **PP Editorial New** (16 styles, variable, Finnish) and **PP Neue Montreal** (36 styles, Finnish): licences "start at $40" (USD). The web licence is sized to "anticipated total monthly pageviews". The free versions are "free to try for personal use as long as it is not used in a commercial project". Purchases include WOFF/WOFF2 ([Editorial New](https://pangrampangram.com/products/editorial-new), [Neue Montreal](https://pangrampangram.com/products/neue-montreal), [FAQ](https://pangrampangram.com/pages/faq)).
- **ABC Marist** (Dinamo, six weights + italics, Finnish), the font the Sean Hazen site uses. No price is shown on the [page](https://abcdinamo.com/typefaces/marist); you have to ask for a quote.

## 3. Four pairings

**A. Dark cinematic: Zodiak (headings) + Switzer (body).**
This is the award-site pattern of display serif + Swiss grotesk (like Editorial Old + Neue Montreal) using free fonts. Zodiak in a light weight or italic at very large sizes looks like film titles on a near-black background, while Switzer stays invisible in body text. This suits Hämeen Films. Micro-animation: a weight change on hover (wght 300→400).

**B. Clean editorial: Gambetta (headings) + General Sans (body).**
A softer, magazine feel for weddings and portraits, and warmer than Zodiak. It works on off-white backgrounds with lots of space, and General Sans is friendly for prices and forms.

**C. Bold vibrant: Clash Display (headings) + Satoshi (body).**
Punchy uppercase or tight headlines for campaign and event work. It follows the "one loud display face" pattern (Tom Baxter, All This Productions). Optional: use Panchang only for one-word hero titles if a wide look is wanted. Risk: Clash Display and Satoshi are widely used in templates, so they may feel less unique.

**D. Single superfamily: Mona Sans (wide for headings, normal for body).**
This is the same approach as the film company Fermented Films. One file covers everything, and the width axis allows a subtle "stretch" reveal on headings. Risk: it is a grotesk close to Archivo or Inter Tight, which the owner disliked, so show it at its wide setting (wdth 110–125) so it looks clearly different.

**Constraint check:** all four are static WOFF2 files with no trackers, CDN or external requests, and all support Finnish. Load at most two files on the first page view and preload the heading font.
