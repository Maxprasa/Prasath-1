# kuvadoo.fi photography site, local search and brand names (research, 2026-10-07)

Scope: the owner's photography site www.kuvadoo.fi (Hostinger Website Builder; assets on `assets.zyrosite.com`),
the older "Hämee Films" / "Hämeen Films" name, and local competitors. This is research only. It lives in `docs/`,
which is not uploaded, so the apps-site rule "say only Finland" does not stop us from naming the town here.
None of this should be copied to apps.kuvadoo.fi.

**Method limits.** All page data comes from WebFetch, which returns the page as converted text, not raw HTML.
So `<meta name="description">`, the `lang` attribute and `<script>` tags (analytics, cookies) **could not be
checked**. A meta description that looks "missing" may just have been dropped in the conversion. Products or
prices that load with JavaScript may also be missing. I had no shell tool in this session, so **no Playwright
screenshots were taken** and mobile quality and page speed were **not assessed**.

## Recommendations (for the owner; these are about kuvadoo.fi, not the apps site)

1. **Fix the price list.** Either show the three packages on `/hinnasto` or remove the empty "Price List" heading.
   Re-check the crossed-out "was" prices against the Finnish consumer rules on advertised price cuts before you
   promote the site (I did not research those rules here).
2. **Add business identity and legal pages.** Show your legal name and Y-tunnus. Add a privacy notice, because the
   contact form collects name, email and phone. Add booking or cancellation terms, because the package pages have
   an "Add to bag" button. Then check in hPanel whether the builder adds analytics or cookies; if it does, a cookie
   notice is needed.
3. **Pick one language per version.** Keep the Finnish pages in Finnish and the English pages in English. Today the
   Finnish pages carry English headings and body text.
4. **Remove template filler.** The Videos page has no videos. The Photography page describes a download platform and
   shows a stock-style image. Replace both with real work, or hide the pages until real work is ready.
5. **Cover SEO basics.** Give every page a clear Finnish H1 with the service and the town. Add a short text to the
   gallery pages, add alt text and set meta descriptions in the builder. Also add one sentence explaining the name
   "Kuvadoo".
6. **Brand.** Keep "Kuvadoo" as the main brand. Put the place name in page titles and H1s, not in the brand name.
   That is what local competitors do (see section 2).

## 1. What kuvadoo.fi shows (observations)

**Domains.** https://kuvadoo.fi/ and https://www.kuvadoo.fi/ returned the same content. I could not see from
WebFetch whether one redirects to the other.

**Title and headings.**
- The home page title is "Professional Photography & Video Production | KUVADOO Hämeenlinna | KUVADOO Photography
  and video productions". It is in English on both the Finnish and English versions, and "KUVADOO" appears twice.
- The Finnish home page H1 is "KUVAUS JA VIDEO", followed by the English headings "Price List" and "Get in touch".
  A stray "12" appears near the price heading.
  (https://www.kuvadoo.fi/)
- The English home page uses the H1 "PHOTO & VIDEO". (https://www.kuvadoo.fi/en)

**Services and offer.**
- The tagline is "Valokuvausta ja videotuotantoa, jotka herättävät tarinasi eloon".
- Listed services: weddings, events, portraits, families, and brand and marketing content for businesses.
- The stated area is "across Finland". (https://www.kuvadoo.fi/en)
- The offer is broad: photo and video, private and business clients. There is no single lead service.

**Pricing.**
- `/hinnasto` showed no prices or packages. (https://www.kuvadoo.fi/hinnasto)
- Three package pages exist in the sitemap (https://www.kuvadoo.fi/sitemap.xml), each with an "Add to bag" button,
  a "Chat on WhatsApp" link, and the note "Travel outside Hämeenlinna is charged separately":

| Package | Price (crossed-out price) | What is included | Source |
|---|---|---|---|
| Starter Outdoor Portrait Session | €100 (€150) | 30–60 min, 25 edited photos | https://www.kuvadoo.fi/starter-outdoor-portrait-session |
| Popular Outdoor Portrait Session | €160 (€200) | 60–90 min, 50 photos | https://www.kuvadoo.fi/popular-outdoor-portrait-session |
| Signature Event Experience | €250 (€350) | 2 h, 100 photos | https://www.kuvadoo.fi/signature-event-experience |

- The package text is in English. No terms, cancellation or refund links were found on these pages.

**Portfolio.**
- The home page shows 7 photos with camera file names (dsc…) and no captions.
- Gallery pages listed in the sitemap: Wanaja 2026, Drift Masters 2026, Mellakka Festival 2026, Samantha & Teemu,
  and Rippikuvaus Hämeenlinna.
- "Samantha & Teemu" has 21 photos, all with empty alt text, and the only text is "Loving moments".
  (https://www.kuvadoo.fi/samantha-and-teemu)
- "Rippikuvaus – Hämeenlinna" has 16 photos with no alt text and no body text.
  (https://www.kuvadoo.fi/rippikuvaus-hameenlinna)

**Template filler.**
- `/videos` reads "Explore a selection of our recent videography projects…" but has **0 video embeds**, only six
  images. (https://www.kuvadoo.fi/videos)
- `/valokuvaus`, the Finnish Photography page, is written in English ("Visual Mastery", "Frictionless Digital
  Delivery", "High-Resolution Downloads"). It shows a hero landscape and an image whose alt text starts "A close-up
  of a hand clicking a 'Download' button". (https://www.kuvadoo.fi/valokuvaus)

**Language.**
- There is a Finnish/English switcher, but the Finnish pages mix in a lot of English: headings, the Photography,
  Videos and gallery pages, and the package text.
- The English version is almost fully English. (https://www.kuvadoo.fi/en)

**Contact.**
- The only contact details are Kuvadoo@gmail.com plus Facebook and Instagram links (facebook.com/kuvadoo,
  instagram.com/kuvadoo).
- `/yhteystiedot` has a form with name, email, phone (optional) and project details, and promises "We respond to all
  inquiries within one business day". There is no phone number, address or privacy notice near the form.
  (https://www.kuvadoo.fi/yhteystiedot)

**Trust signals.**
- I saw no testimonials, reviews, "about me" text, photographer name or face.
  (https://www.kuvadoo.fi/, https://www.kuvadoo.fi/en)

**Legal basics.**
- The site has no Y-tunnus, no legal name beyond "KUVADOO", no privacy policy, and no terms. The footer reads only
  "© 2026 KUVADOO.fi" on every page fetched.
- Analytics and cookie scripts could not be checked (see Method limits).
- External assets come from `assets.zyrosite.com`, and flag icons load from jsDelivr.

**Brand explanation.** The name "Kuvadoo" is not explained anywhere I fetched.

**Facebook.** https://www.facebook.com/kuvadoo showed only a Facebook login wall, so no content was visible.

## 2. Local search reality

**Hämee Films / Hämeen Films.**
- Web searches for "Hämee Films", "Hämeen Films", "Hamee Films" and "HämeenFilms" returned **no matching page or
  business**, only unrelated film and Hämeenlinna results.
- https://www.facebook.com/hameefilms (a guessed URL) showed only a login wall, so nothing about the page is
  publicly visible to me.
- In short, the old name has no visible web footprint.

**Search engine limits.** My search tool is not Google, and it returned weak results for the plain queries
"valokuvaaja Hämeenlinna", "hääkuvaaja Hämeenlinna" and "videokuvaus Hämeenlinna": mostly Fonecta listings and
Yle articles. Broader queries found these businesses.

| Business | Naming style | Source |
|---|---|---|
| Mette Nivala | Personal name. Title: "Hääkuvaus Hämeenlinna ja koko Suomi - Mette Nivala - Valokuvaaja Kanta-Häme"; H1: "Hääkuvaus Hämeenlinna, Lahti & koko Suomi" | https://mettenivala.fi/haakuvaus-hameenlinna/ |
| Siiri Järvinen Photography | Personal name. Search title: "Valokuvaaja Hämeenlinna & Hattula" (the site itself returned 403) | https://www.siirijarvinenphotography.fi/ |
| Timo Ahola | Personal name | https://fonecta.fi/profiili/valokuvaaja-timo-ahola/3086488 |
| Silvia's Visuals | Personal name | https://fonecta.fi/profiili/silvia's-visuals/3077427 |
| Kuvahovi | Brand: "kuva" plus a word | https://www.kuvahovi.fi/ |
| Studio S | Brand | https://bystudios.fi/ |
| 4K-media | Brand (video) | https://www.4kmedia.fi/ |
| Kuvalähde | Regional "kuva-" brand | https://kuvalahde.fi/palvelumme/haakuvaus/ |
| Kuvajälki | Regional "kuva-" brand | https://kuvajalki.fi/ |

**Venuu.fi wedding-photographer list for Hämeenlinna** (https://venuu.fi/s/kuvaajat/haakuvaus/hameenlinna):
- I looked at the first 30 names. Most follow the pattern "Valokuvaaja + first and last name" or "First and last
  name + Photography/Media/Productions", for example "Jani Lappalainen Photography" and "Elias Nordstrom Productions".
- A few are brands ("Täydenkuun Kuva", "Ainoa Media", "Soihtu Photography", "Yhdessä Photography").
- **None of the 30 is a place-name brand.**

**Would a place-based name such as "Hämeen Films" help local SEO? (analysis)** Probably not much. The competitors
seen above get the place into search results through **page titles and H1s**, for example "Hääkuvaus Hämeenlinna",
not through the business name. kuvadoo.fi already does this on one page ("Rippikuvaus – Hämeenlinna"), but that page
has no text. The old name also has no visible footprint to build on.

## 3. Brand meaning (analysis, not sourced unless cited)

**"Kuvadoo".**
- Finnish *kuva* means "picture", "photograph", "image", and also "video" in compounds
  (https://en.wiktionary.org/wiki/kuva).
- To a Finnish speaker the name likely reads as "kuva" plus a playful ending. It fits a photo and video business,
  and its style matches local "kuva-" brands (Kuvahovi, Kuvalähde, Kuvajälki).
- To international ears "-doo" probably sounds informal or cartoonish. That is my judgement, with no source.
- The site never explains the name, so non-Finnish visitors get no hint that "kuva" means picture.

**"Hämeen Films".**
- "Hämeen" is the genitive of Häme, so the name reads as "Häme's / of Häme" (my reading of Finnish grammar,
  no source).
- "Films" suggests video or wedding films more than photography.
- The name ties the business to one region, while kuvadoo.fi says it serves "across Finland".
- It also has no visible web presence (section 2). On that evidence it is a weaker fit than "Kuvadoo" for a combined
  photo and video business.
