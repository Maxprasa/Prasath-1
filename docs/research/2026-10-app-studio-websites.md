# Research: multi-app developer websites (Oct 2026)

Sites studied (via WebFetch): panic.com, culturedcode.com/things, flexibits.com, readdle.com, bear.app,
dayoneapp.com, overcast.fm, lux.camera, iconfactory.com, linear.app, supercell.com, rovio.com,
sindresorhus.com/apps, lowtechguys.com, fossify.org, christianselig.com.

## What makes them look professional
- **One sentence per app**: icon + name + single tagline on every card (Flexibits, Lux, Sindre Sorhus).
- **The same skeleton on every app page**: hero with store badge → screenshots → features → proof → FAQ/support.
- **A real business identity**: Overcast tells the founder's story; Supercell/Panic show their address.
- **Privacy as a plain-language selling point**: Overcast's "A Normal Business", Day One's privacy pledge,
  Fossify's "Permissions & Data" section on each app page.
- **A footer that routes**: grouped Products / Support / Company / Legal (Flexibits, Bear, Fossify).
- **Support as self-service**: categories/FAQ first, contact last (Things support).
- **Restraint**: whitespace, one accent colour, real screenshots, no stock photos.

## Per-app page pattern (Sindre Sorhus, Fossify, Low-Tech Guys)
Hero (icon, name, tagline, badge, platform/price) → screenshots → features with icons → privacy at a glance
→ FAQ → support → "More from <developer>" → per-app links row (Privacy · Delete data · Support).

## Avoid
Carousels; invented testimonials/user counts/award logos; buried privacy/deletion links; cookie banners,
analytics, third-party embeds; oversized footers; changing URLs that Play Console links to.

## Google Play account deletion page (support.google.com/googleplay/android-developer/answer/13327111)
Must load without errors, name the app or developer, show the deletion path prominently, and allow a
request without the app (link, email or form). FinnSana has optional sign-in, so this page IS required.
