# Photographer and videographer websites: what works (October 2026)

For the kuvadoo.fi rebuild and the "Hämeen Films – a video service by Kuvadoo" sub-brand.
I read 8 sites with WebFetch on 2026-10-07: 5 Finnish and 3 international. WebFetch returns page text,
not a rendered page. So I could **not** measure page weight, see animations, or check for trackers.
Notes about "heavy" pages only describe what the text showed (number of images, widgets). Some
famous sites (josandtree.com, storyandgoldweddings.com) returned an empty page to the tool and are not
included.

## Recommendations for kuvadoo.fi (short list)

1. **Show prices.** Show fixed or "alkaen" (from) prices for each service, with what is included (hours,
   number of photos, video length), whether VAT is included, and travel costs. The best Finnish sites
   do this (Freimi, Kuvalähde, Ruska, Lindell). Use only prices the owner gives us.
2. **Make one price table for photo, video and photo + video.** Three columns or three blocks, the same
   hour steps, and the photo + video package shown last. Freimi and Kuvalähde both do this.
3. **Present Hämeen Films as a clearly named section of Kuvadoo, not a separate site.** Use one menu, one
   footer, and one contact form. Add a "Video" / "Hämeen Films" page with its own hero line and its own
   price block. Ruska Media and Taylor & Porter keep video inside the main brand.
4. **Use click-to-load video only.** Show a poster image (WebP), a play button and a short note like
   "Plays from YouTube/Vimeo. Clicking loads their player and they may set cookies." Load the iframe
   only after the click (`youtube-nocookie.com` / Vimeo `dnt=1`). Ruska Media already uses a
   click-to-unblock YouTube placeholder. Without JS, the poster is a normal link to the video.
5. **Do not autoplay a background video in the hero.** None of the 8 sites I read had one. Use one
   strong photo (responsive WebP, `srcset`, width/height set) and a short H1 that says what and where.
6. **Organise the portfolio by service, the way clients search:** Häät / Perhe / Rippikuvat /
   Tapahtumat / Yritykset / Video. Each service page has 9–15 photos, prices and a contact button.
   Wallineva, Lindell and Freimi all organise by service.
7. **Make trust signals personal and true.** Use the owner's name and photo, a short story, how long
   the business has been running (only if the owner gives a number), and the business ID if the owner
   wants it. Use only real testimonials, with written permission. Avoid unnamed "award-winning" claims
   (seen on Taylor & Porter and Stories by Joseph Radhik); we must not copy them.
8. **Make contact simple and work without JS.** Show phone (`tel:`), email (`mailto:`) and one short
   form, with a static form handler (Hostinger PHP mail or mailto fallback). Lindell also explains what
   happens next ("about 30 min planning call"). We should add a short "How booking works" section.
9. **Start in Finnish, and maybe add a small English page later.** 4 of 5 Finnish sites are Finnish
   only. Ruska has an EN/FI switch. If English is added, use `/en/` and `hreflang`, and give the
   switch link a text label ("Suomeksi" / "In English").
10. **Leave out Instagram feeds, sliders and shop carts.** These widgets appeared on several sites
    (Jonas Peterson, Ed Peers, Ali K, Davey & Krista). They add third-party requests and weight. Use a
    plain text link to Instagram instead.
11. **Add a rippikuvat page.** Finnish competitors list it (Freimi, Wallineva, Lindell), but on Lindell it
    has no details or prices. A clear page with season, place, prices and booking is an easy win.

---

## Site notes

### 1. Freimi Visuals (Oulu, photo + video, sole photographer) – https://freimivisuals.fi/
- **Nav:** Etusivu, Yrityksille, Häät, Ihmiset, Hinnasto, "Valokuvaaja Jori Turpeinen", Yhteystiedot.
  Has a "Hyppää sisältöön" skip link.
- **Hero:** Still wedding image, probably a slideshow, with the H "Freimi Visuals – Valokuvaus ja videokuvaus Oulussa".
- **Portfolio:** By audience (business, weddings, people), with "Lue lisää" links to service pages.
- **Video:** No embeds on the home page. Video is only described in the text ("elokuvalliset häävideot").
- **Prices** (https://freimivisuals.fi/hinnasto/): Full and open. Wedding photo €450–1,700, wedding
  video 6 h €1,350 / 9 h €1,700 / 12 h €2,000 (3–7 min highlight), **hybrid photo + video packages**
  €1,000–3,200. Each package lists the number of photos and the video length. VAT is stated only for
  business prices ("+alv").
- **Trust:** The owner is named, with his own page. 4 named testimonials (3 companies, 1 couple).
- **Contact:** Phone (tel link), email, "Ota yhteyttä" buttons. No WhatsApp. Finnish only.
- **Photo vs video:** One brand. Video appears inside each service. Hybrid packages tie them together.

### 2. Kuvalähde (Tampere/Espoo, two-person team) – https://kuvalahde.fi/palvelumme/haakuvaus/
- **Nav:** Etusivu, Hinnasto, Portfolio, Meistä, plus an "Ota yhteyttä" button.
- **Hero:** Text H1 "AJATONTA HÄÄKUVAUSTA aidosti TÄRKEISSÄ HETKISSÄ" over four photos, then the
  buttons "PORTFOLIOON" and "OTA YHTEYTTÄ".
- **Portfolio** (https://kuvalahde.fi/portfolio/): 12 featured photos, then **8 couple stories** (name,
  short text, "Lue lisää"), then testimonials. The work is split into "Häävalokuvaus" and "Häävideot".
- **Prices** (https://kuvalahde.fi/hinnasto/): Fixed prices by hours, VAT included. Photo 6 h €1,100 to
  12 h €2,200. "Hääelokuva" 6 h €1,300 to 12 h €2,400. **"Premium Duo"** photo + video 8–12 h
  €2,600–3,600, with the saving explained (~€400). Clear travel rule (free under 100 km, then €0.59/km).
  Says drone use is "never guaranteed". This is an honest limit, stated openly.
- **Trust:** Company name and business ID in the footer. Named couple testimonials. Lists its values.
- **Contact:** Email and a contact page. Finnish only.
- **Photo vs video:** Same brand. Video is called "Hääelokuva" and has its own column in the prices.

### 3. Ruska Media (Finland-wide, EN default) – https://ruskamedia.com/en/wedding-photography/
- **Nav:** Ruska Event, Ruska Media, Services (dropdown with "Ruska Wedding Frames" and "Ruska
  Estate"), Team & Contact, "Suomeksi".
- **Hero:** Image slider (Slider Revolution), title "Ruska Wedding Frames by Joel Nykänen", and a
  "Reserve Your Date" button.
- **Portfolio:** One grid of about 20 photos (Visual Portfolio plugin), no captions.
- **Video:** **Two YouTube embeds behind an "Unblock content" placeholder.** The note says: "Please note that
  doing so will share data with third-party providers." This is a click-to-load model close to the one we need.
- **Prices (VAT incl.):** Portraits €500 (~30 images) up to all-day 12 h €2,000. Wedding video €2,500
  (~5 min). Add-ons: fast delivery and €200/h.
- **Trust:** Photographer named. "Years of experience" (no number given). No reviews on the page.
- **Contact:** Form (#lomake), plus a travel-cost calculator is mentioned. EN/FI switch.
- **Photo vs video / sub-brands:** Sub-brands are named in the menu ("Ruska Wedding Frames", "Ruska
  Estate") under one parent. This is a useful model for "Hämeen Films by Kuvadoo".
- **Heavy:** Slider plugin and gallery plugin.

### 4. Studio Lindell (Porvoo/Tuusula studio, family business) – https://www.studiolindell.fi/portfolio/perhekuvaus/
- **Nav:** Ajanvaraus, Ajankohtaista, Palvelumme ja hinnasto, Kuvagalleria, Tilaa kuvia, Valokuvaajat,
  Kehys- ja lahjakorttikauppa, Yhteystiedot. Has a search and a cart.
- **Portfolio:** By category (Perhe, Lapsi, Vauva, Yritys, Tapahtuma, Päiväkoti, Rippi). The family
  page shows about 30 photos with Prev/Next links to the next category.
- **Prices on the service page:** Family shoot €170 (studio, 60 min) / €220 (location, 90 min), digital
  packages €80–230, extra file €25, travel free within 10 km, then €1/km. Full price list as a PDF.
- **Trust:** Personal note signed by Oscar Lindell. "Over 50 years" in business.
- **Contact:** Phone with service hours, email, form, booking page. Explains the ~30 min planning call.
- **Video:** None. Rippikuvaus is only a tile, with no details.

### 5. Wallineva Photography (Oulu, sole photographer with studio) – https://www.wallineva.fi/
- **Nav:** PALVELUT, HINNASTO, YRITYSKUVAUKSET, Yhteydenotto. The submenus list every service, and
  "Videokuvaus" appears in all three.
- **Hero:** Wedding photo with the line "Valokuvausta ja videokuvausta Oulusta käsin".
- **Portfolio:** Service tiles. No gallery on the home page.
- **Video:** One service tile. No examples visible.
- **Trust:** Owner bio (20+ years in photography, business since 2013), business ID in the footer.
  The social links point to "#", a broken-link mistake to avoid.
- **Contact:** Phone, email, contact page. Finnish only. Mentions rippikuvaus.

### 6. Taylor & Porter (London studio, photo + Super 8 film) – https://www.taylorandporter.com/
- **Nav:** Photographs, About, Services, Super 8mm, Contact (plus a cart).
- **Hero:** Full-width photo with the headline "AWARD-WINNING LONDON BASED photography STUDIO…" (no
  award is named).
- **Portfolio:** Thumbnail grid of galleries by couple and venue, with "View all galleries".
- **Video:** **Super 8mm film is its own menu item and its own service**, "booked alone or with photography".
- **Prices:** None on the home page.
- **Trust:** Founder Louise introduced (photojournalism degree). 4 named couple testimonials.
- **Contact:** "ENQUIRE NOW" goes to an enquiry page. Email in the footer.

### 7. Josiah & Steph (Davey & Krista) – https://js.daveyandkrista.com/wedding-videography
- **Nav:** Meet Josiah & Steph, Wedding Photography, Wedding Videography, The Blog, Contact.
- **Video:** A "Signature Work" grid of 4 couple films, each on its own page.
- **Prices:** "Our highlight film add-ons begin at $989" (2–4 min films).
- **Photo vs video:** **Video is only an add-on for photo clients**, under the photo brand.
- **Trust:** The couple behind it, plus "Rave Reviews" from named couples.
- **Heavy / dated:** Carousels, Instagram widget, email-signup scripts, a typo in the footer
  ("VIDEOGRAPY"), © 2018. Many video menu links go to `#/`.

### 8. Photography by Ali K – https://www.photographyalik.com/videography-2
- **Nav:** Meet Ali, Information, Contact, Film, Portfolio, Boudoir, Journal, Client Love Notes.
- **Photo vs video:** Film is "The Storytellers Wedding Film Addition", an add-on to photo packages.
  Only "2 Photo + Film Combination Weddings a year" are taken. The limit is stated honestly and
  helps create demand.
- **Prices:** None. **Contact:** "Let's do it" goes to the contact page, plus a newsletter signup.
- **Heavy:** Instagram post embeds, cart icon. The text showed no video embeds.

---

## What makes them work (patterns)

1. **Prices in the open.** All 4 Finnish wedding/family sites with a price page list real euro amounts
   with hours and deliverables (Freimi, Kuvalähde, Ruska, Lindell). International sites mostly hide
   prices or show only "begin at".
2. **Packages are built on hours.** Clients compare 6 h / 8 h / full day easily.
3. **Deliverables are counted.** "~300 images", "3–5 min video", "25 finished portraits".
4. **Photo + video combo is the top package**, sometimes with the saving shown (Kuvalähde Premium
   Duo, Freimi hybrid).
5. **Video lives under the main brand.** It is either a named service (Ruska Wedding Frames, Super
   8mm, Hääelokuva) or an add-on (Josiah & Steph, Ali K). None of the 8 sites runs video as a fully
   separate website.
6. **A named person.** Every strong site names the photographer. The personal pages ("Valokuvaaja
   Jori Turpeinen", "Meet Ali") are in the main menu.
7. **Portfolio by service or by couple story.** Story pages (Kuvalähde, Taylor & Porter) give context
   and help SEO.
8. **One clear call to action, repeated.** "Ota yhteyttä", "Reserve Your Date", "ENQUIRE NOW".
9. **Practical rules are stated.** Travel km rules, VAT, drone "not guaranteed", planning call. These
   reduce questions and build trust.
10. **Short menus.** 4–7 items. Long submenus (Wallineva) are harder to scan.
11. **Business ID or company name in the footer** on Finnish sites (Kuvalähde, Wallineva), which signals
    a real business.
12. **Weak points to avoid:** unnamed "award-winning" claims, links to `#`, old copyright years,
    Instagram widgets, slider plugins, and video pages with no visible example.

## How photo vs video sub-brands are shown (summary)

| Site | Model |
|---|---|
| Ruska Media | Parent brand plus named sub-brands in a Services menu |
| Taylor & Porter | Video (Super 8mm) is its own menu item, bookable alone or with photos |
| Kuvalähde, Freimi | One brand, video is a column in the price table, plus combo package |
| Josiah & Steph, Ali K | Video only as an add-on for photo clients |

**For Kuvadoo:** Use the Ruska / Taylor & Porter model. Put "Hämeen Films" in the main menu as a
named page with the subtitle "a video service by Kuvadoo". Use the same header, footer, contact form
and design tokens, and a different hero line. Link the photo + video combo price from both the photo
and the video pages.
