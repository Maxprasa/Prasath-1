# Research: app brand and domain for Kuvadoo apps (Oct 2026)

Question: keep the apps under "Kuvadoo" at apps.kuvadoo.fi, or move to a separate international app
brand on a .com? All findings checked on 2026-10-07 via WebFetch/WebSearch; URLs cited inline.

## Recommendations

1. **Don't rush a rename.** On the App Store (iPhone), a Finnish sole trader (toiminimi) is shown under
   his **personal legal name**, whatever brand he picks. A new brand only changes Google Play and the
   website. It does not change the iPhone seller name.
2. **Keep apps.kuvadoo.fi for now.** The Play Console privacy and deletion URLs already point there.
   A domain move can be done later with 301 redirects (see section 5). This fits the static, no-tracker
   site rules in `CLAUDE.md`.
3. **If he wants a separate brand later,** pick a name that he can (a) set as the Google Play developer
   name, (b) register as a PRH auxiliary business name (aputoiminimi) to protect it, and (c) get the
   .com for. Use .com as the main domain. Optionally add .app as a redirect.
4. **The cheapest test:** use the free Hostinger domain credit on **.com** (it is on Hostinger's
   free-domain list; .app and .fi are not). kuvadoo.com looked unregistered on 2026-10-07. Registering it
   protects the current brand at no cost and keeps every option open. Confirm availability at the registrar.
5. **Whatever he chooses, keep it honest.** Use the same name on the store, the website footer and the
   deletion page. Google requires the deletion page to name the app or the developer exactly as on the
   store listing.

## 1. Google Play

Observations:
- **Developer name:** "It's what appears on Google Play, can differ from your legal name, and can be
  changed at any time." (https://support.google.com/googleplay/android-developer/answer/13628312)
- **How to change it:** Play Console → Developer account → About you → "Developer name" → save.
  Google reviews the change before it shows. "Developer names can't be identical" to another account's
  name. (https://support.google.com/googleplay/android-developer/answer/13634081)
- **Personal account (individual):** requires a developer name, legal name, legal address, verified contact
  email and phone, and a developer email. **Shown publicly:** legal name, country only (not the full
  address), and developer email. If the app earns money (paid app or in-app purchases), the full address is
  shown too. (answer/13628312; answer/14177239 says the address "appears only as a country" for personal
  accounts: https://support.google.com/googleplay/android-developer/answer/14177239)
- **Organization account:** requires a **D-U-N-S number**, organization name and address (taken from the
  Google Payments profile and matched to Dun & Bradstreet), phone, and website. **Shown publicly:** legal
  name, full legal address, developer email and phone. D-U-N-S is free but "can take up to 30 days".
  (answer/13628312; https://support.google.com/googleplay/android-developer/answer/10840893)
- **EU DSA trader status on Play:** I could not open Google's own help page on trader status.
  Secondary sources (makaka.org tutorial, webtoapp.design) say Play Console also asks for a
  trader or non-trader declaration. **Not verified with Google. Check in Play Console.**

## 2. Apple App Store

Observations (https://developer.apple.com/support/enrollment/):
- "If you're an individual or sole proprietor/single-person business, your personal legal name will be
  listed as the seller on the App Store. Do not enter an alias, nickname, or company name…"
- Organizations must be a legal entity with a D-U-N-S Number. "We don't accept DBAs, fictitious
  businesses, trade names, or branches."
- "If you are a sole proprietor/single-person business, you must join as an individual and your legal
  name will appear as the seller."
- **So a toiminimi enrolls as an individual, and buyers see his personal legal name as the seller.**
  Neither "Kuvadoo" nor a new brand would appear as the seller. Only a separate legal entity (for
  example an osakeyhtiö) with a D-U-N-S number would change that.
- **EU DSA trader info on the App Store**
  (https://developer.apple.com/help/app-store-connect/manage-compliance-information/manage-european-union-digital-services-act-trader-requirements):
  traders must give an address (individuals may use a P.O. Box with proof), a phone number and an email.
  These are verified and "Apple will publish this information on your App Store product page when your app
  is distributed in any of the 27 territories of the EU." Individuals must upload "a current document that
  verifies your business name and address". Apple says that even developers who don't distribute in the
  EU must still declare a trader status
  (https://developer.apple.com/help/app-store-connect/manage-compliance-information/manage-european-union-digital-services-act-compliance-information).

## 3. Branding: same brand or separate app brand?

Observations:
- Official sources give no advice on this. Third-party and forum posts say the Play developer name
  can be any name that doesn't infringe someone else's rights. They advise matching it to your brand
  for recognition (https://anyteamnames.com/blog/can-i-change-my-developer-name-in-google-play-console/,
  https://discussions.unity.com/t/can-we-use-our-developer-name-at-personal-apple-developer-account/1715237).
  These are not authoritative.
- Our earlier study of multi-app developer sites (`docs/research/2026-10-app-studio-websites.md`) found
  solo developers using a **personal-name domain** (sindresorhus.com/apps, christianselig.com) and
  product or studio brands on **.app or .com** (bear.app, linear.app, flexibits.com). Both styles work.
  What they share is one consistent name across the site and the stores.
- **Protecting a second name in Finland:** a private trader can register an **auxiliary business name**
  (aputoiminimi) for one part of the business with a change notification to the Trade Register. The name
  must be distinguishable and must not be confusingly similar to registered names or trademarks. A fee
  applies (check the PRH price list).
  (https://prh.fi/en/companiesandorganisations/yritystennimet/aputoiminimi.html)
- **Search:** Google can show a site name for a subdomain (apps.kuvadoo.fi) separately. Without its own
  WebSite structured data, a subdomain may fall back to the domain-level name.
  (https://developers.google.com/search/docs/appearance/site-names)

Analysis (my reasoning, not from a source):
- **For "Kuvadoo":** it already exists, is registered as the business name, and needs no work.
  The kuvadoo.fi photography site lends real-business trust.
- **Against "Kuvadoo":** it signals photography. Search results and the Play developer page would mix two
  unrelated businesses. The .fi domain reads as local to buyers outside Finland.
- **For a separate brand:** the name fits apps for any language or market and gives a clean developer page.
- **Against a separate brand:** it costs money (domain, optional auxiliary name), adds a name to keep
  consistent, and the iPhone store still shows his personal name. A brand that appears nowhere official
  can look less trustworthy, not more.

## 4. Domains

Observations:
- **Hostinger free domain:** the eligible list for web, cloud and email plans is .xyz, .com, .online, .link,
  .shop, .live, .digital, .tech, .space, .website, .email, .fun, .click, .site, .uno, .in, .host, .store,
  .press, .me and .help. **.app and .fi are not listed.** The credit covers the first year only, comes with
  new 12-month-plus Premium or Business plans, and the list "may change"
  (https://www.hostinger.com/support/?p=631). Hostinger's search page showed .com at $19.99, with
  $0.01 for the first year (https://www.hostinger.com/domain-name-search).
- **.app needs HTTPS everywhere:** "The .app top-level domain is included on the HSTS preload list, making
  HTTPS required on all connections" (https://www.registry.google/domains/app/). Hostinger's free SSL
  would cover this, but I did not check that.

RDAP checks (2026-10-07). A 404 means no registry record, so the name is likely free. Confirm at the
registrar. As a control, google.com and cash.app returned records, so a 404 is meaningful.
.com was checked via `https://rdap.verisign.com/com/v1/domain/<name>`. .app via
`https://pubapi.registry.google/rdap/domain/<name>` (rdap.nic.google did not resolve from here).

| Domain | RDAP result |
|---|---|
| kuvadoo.com | 404, likely available |
| kuvadoo.app | 404, likely available |
| finnsana.com | 404, likely available |
| finnsana.app | 404, likely available |
| kuvadooapps.com | 404, likely available |
| **kielivo.com** (invented) | 404, likely available |
| **lintuvo.com** (invented) | 404, likely available |
| **sanakoo.com** (invented) | 404, likely available |
| **puhuvo.com** (invented) | 404, likely available |
| **kuulumo.com** (invented) | 404, likely available |
| sanavo.com, tavuu.com, lumivo.com, oppivo.com, tavulo.com, sanoru.com, kielora.com, vokaro.com | registered |

The invented names are loosely based on Finnish roots (kieli "language", lintu "bird", sana "word",
puhua "speak", kuulua "be heard"). They are easy to say in English. I did no trademark search.
Run one (EUIPO/TMview) before choosing.

## 5. Moving apps.kuvadoo.fi to a new domain later

Observations:
- Google's site-move guide: use server-side permanent redirects (301/308) from each old URL to the
  matching new URL, with no redirect chains. Submit a Change of Address in Search Console and a new
  sitemap. Keep the redirects "generally at least 1 year", or indefinitely. "Expect temporary fluctuation in
  site ranking during the move." Showing the new URLs can take "a few weeks or more"
  (https://developers.google.com/search/docs/crawling-indexing/site-move-with-url-changes).
- Play: the account-deletion link goes in the Data safety form (Policy → App content). It must load
  without errors and "reference the app or developer name (that is, as it appears on your store listing
  in Google Play)" (https://support.google.com/googleplay/android-developer/answer/13327111). If the
  developer name changes, the deletion page text must match it.

Implications for this project:
- The move can be done at any time. On Hostinger, the `.htaccess` file can 301 every
  `/finnsana/...` path to the same path on the new domain, which keeps the site static.
- Before switching, update the Play Console privacy policy URL and the Data safety deletion URL, plus the
  App Store privacy URL once the iPhone app exists. Also update `sitemap.xml` and canonical links.
  Keep the old subdomain redirecting indefinitely, because old store listings and caches may still link to it.
- The site is small and new, so the SEO cost of moving is small, and it grows the longer the site is live.
  That argues for deciding before the public Play launch, if a move is planned at all.
