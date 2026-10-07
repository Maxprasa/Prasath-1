"""Writes the first version of data/content.json and data/prices.json (run once; after that the admin edits them).
Every claim comes from docs/facts/kuvadoo.md."""
import json, pathlib, sys
root = pathlib.Path(__file__).resolve().parent.parent
force = '--force' in sys.argv

def T(fi, en): return {'fi': fi, 'en': en}

content = {
  'site': {
    'email': 'kuvadoo@gmail.com',
    'whatsapp': '+358417911831',
    'whatsapp_display': '041 791 1831',
    'ytunnus': '3635801-2',
    'town': 'Hämeenlinna',
    'instagram': 'https://www.instagram.com/kuvadoo/',
    'facebook': 'https://www.facebook.com/kuvadoo',
    'youtube': 'https://www.youtube.com/@SPrasath',
    'hero_photo': '',
    'about_photo': '',
  },
  'text': {
    'hero_kicker': T('Valokuvaus · Video · Ilmakuvaus', 'Photography · Video · Aerial'),
    'hero_title': T('Hetkiä, jotka saavat *tuntemaan*.', 'Moments that make people *feel* something.'),
    'hero_lead': T('Kuvadoo kuvaa ihmisiä, tapahtumia ja tarinoita – maasta ja ilmasta. Yli 12 vuoden kokemus valokuvauksesta, elokuvakuvauksesta ja editoinnista.',
                   'Kuvadoo photographs and films people, events and stories – on the ground and from the sky. Over 12 years of experience in photography, cinematography and editing.'),
    'services_title': T('Mitä teen', 'What I do'),
    'svc_photo_title': T('Valokuvaus', 'Photography'),
    'svc_photo_text': T('Tapahtumat ja festivaalit, muotokuvat, parikuvat, rippikuvat, häät ja yritykset.', 'Events and festivals, portraits, couples, confirmation photos, weddings and business.'),
    'svc_video_title': T('Video – Hämeen Films', 'Video – Hämeen Films'),
    'svc_video_text': T('Häävideot, tapahtumavideot, yritysvideot ja musiikkivideot. Kuvaus ja editointi samasta kädestä.', 'Wedding films, event films, business videos and music videos. Filming and editing from one hand.'),
    'svc_aerial_title': T('Ilmakuvaus', 'Aerial filming'),
    'svc_aerial_text': T('Sertifioitu drone-lentäjä, yli kymmenen vuoden kokemus ilmakuvauksesta.', 'Certified drone operator with more than a decade of aerial experience.'),
    'svc_edit_title': T('Editointi', 'Editing'),
    'svc_edit_text': T('Videoeditointi ja jälkikäsittely – ensimmäisestä otoksesta valmiiseen leikkaukseen.', 'Video editing and post-production – from the first frame to the final cut.'),
    'work_title': T('Viimeisimmät kuvaukset', 'Recent work'),
    'films_title': T('Hämeen Films', 'Hämeen Films'),
    'films_lead': T('Hämeen Films on Kuvadoon videopalvelu. Kuvaan ja editoin videot itse, joten tarina pysyy samassa kädessä alusta loppuun.',
                    'Hämeen Films is the video service of Kuvadoo. I film and edit every video myself, so the story stays in one hand from start to finish.'),
    'films_intro': T('Häät, tapahtumat, yritykset, musiikkivideot ja ilmakuvaus. Alla on töitä vuosien varrelta Sri Lankasta, Malesiasta ja Suomesta.',
                     'Weddings, events, business, music videos and aerial filming. Below is work from over the years in Sri Lanka, Malaysia and Finland.'),
    'about_kicker': T('Kuvaaja ja videokuvaaja', 'Cinematographer & photographer'),
    'about_title': T('Hei, olen Prasath.', "Hi, I'm Prasath."),
    'about_short': T('Olen kertonut tarinoita kameran kautta yli 12 vuotta – maassa ja ilmasta. Työskentelen valokuvaajana, elokuvakuvaajana ja editoijana, joten voin viedä projektin ensimmäisestä otoksesta valmiiseen leikkaukseen.',
                     "For over 12 years, I've told stories through the lens – on the ground and from the sky. I work as a photographer, cinematographer and editor, so I can carry a project from the first frame to the final cut."),
    'about_body': T(
      "Olen kertonut tarinoita kameran kautta yli 12 vuotta – maassa ja ilmasta.\n\n"
      "Työhöni kuuluvat elokuvakuvaus, valokuvaus ja videoeditointi, joten voin viedä projektin ensimmäisestä otoksesta valmiiseen leikkaukseen. Olen sertifioitu drone-lentäjä, ja minulla on yli kymmenen vuoden kokemus ilmakuvauksesta. Ilmasta näkee kuvakulmia, joihin katsoja pysähtyy.\n\n"
      "Olen tehnyt projekteja neljässä maassa: Sri Lankassa, Malesiassa, Intiassa ja Suomessa. Jokainen paikka on opettanut sopeutumaan uusiin kulttuureihin, maisemiin ja työryhmiin – ja löytämään tarinan, joka koskettaa ihmisiä missä tahansa.\n\n"
      "Minua ajaa yksinkertainen asia: haluan tallentaa hetkiä, jotka saavat ihmiset tuntemaan jotakin.\n\n"
      "Kuvadoo on yritykseni Suomessa. Hämeen Films on nimi, jolla teen videotyöt.",
      "For over 12 years, I've told stories through the lens, both on the ground and from the sky.\n\n"
      "My work covers cinematography, photography and video editing, so I can carry a project from the first frame to the final cut. As a certified drone operator with more than a decade of aerial experience, I bring perspectives that make audiences stop and look twice.\n\n"
      "I've worked on projects in four countries: Sri Lanka, Malaysia, India and Finland. Working in each place taught me to adapt to new cultures, landscapes and crews, and to find the story that connects with people wherever they are.\n\n"
      "What drives me is simple: capturing moments that make people feel something.\n\n"
      "Kuvadoo is my business in Finland. Hämeen Films is the name I use for my video work."),
    'about_name_note': T('Nimi Kuvadoo tulee suomen sanasta "kuva".', 'The name Kuvadoo comes from the Finnish word "kuva", which means picture.'),
    'steps_title': T('Näin varaat kuvauksen', 'How booking works'),
    'step1': T('Lähetä viesti WhatsAppissa tai sähköpostilla: päivä, paikka ja mitä haluat kuvata.', 'Send a message on WhatsApp or by email: the date, the place and what you would like.'),
    'step2': T('Saat kirjallisen tarjouksen, jossa on hinta ja mahdolliset matkakulut.', 'You get a written offer with the price and any travel costs.'),
    'step3': T('Kuvauspäivänä tallennetaan hetket – rennosti ja suunnitellusti.', 'On the day, we capture the moments – relaxed and well planned.'),
    'step4': T('Saat valmiit kuvat verkkogalleriassa tai valmiin videon.', 'You receive the finished photos in an online gallery, or the finished video.'),
    'photo_title': T('Valokuvaus', 'Photography'),
    'photo_lead': T('Tapahtumia, festivaaleja, ihmisiä ja tarinoita. Valitse kategoria tai selaa gallerioita.', 'Events, festivals, people and stories. Pick a category or browse the galleries.'),
    'cat_events': T('Festivaalit, moottoriurheilu ja yleisötapahtumat – tunnelma, esiintyjät ja ihmiset.', 'Festivals, motorsport and public events – the atmosphere, the performers and the people.'),
    'cat_portraits': T('Muotokuvat, parikuvat ja perhekuvat luonnonvalossa.', 'Portraits, couples and families in natural light.'),
    'cat_confirmation': T('Rippikuvat rennosti kotona, pihalla tai kauniissa paikassa – myös perheen ja suvun kanssa.', 'Confirmation photos at home, in the garden or in a beautiful place – with family too.'),
    'cat_weddings': T('Hääkuvaus vihkimisestä juhlaan.', 'Wedding photography from the ceremony to the party.'),
    'cat_business': T('Yrityskuvat, henkilökuvat ja sisältöä markkinointiin.', 'Business photos, headshots and content for marketing.'),
    'cat_aerial': T('Ilmakuvat dronella maisemista, tapahtumista ja kohteista.', 'Aerial photos by drone of landscapes, events and places.'),
    'prices_title': T('Hinnasto', 'Prices'),
    'prices_lead': T('Hinnat ovat lähtöhintoja. Lopullinen hinta sovitaan aina kirjallisesti ennen varausta. Kysy rohkeasti, jos tarpeesi ei sovi pakettiin.',
                     'Prices are starting prices. The final price is always agreed in writing before booking. Just ask if your idea does not fit a package.'),
    'prices_notes': T(
      "Kuvadoo ei ole arvonlisäverovelvollinen (vähäinen toiminta), joten hintoihin ei lisätä arvonlisäveroa. Hinnat ovat lopullisia hintoja, ja ne koskevat sekä yksityis- että yritysasiakkaita.\n\n"
      "Matkat 30 km:iin asti sisältyvät hintaan. Sen jälkeen 0,50 €/km (meno ja paluu). Tarkka summa kerrotaan tarjouksessa ennen varausta.\n\n"
      "\"Alk.\"-hinta on paketin pienin hinta. Hintaa nostavat esimerkiksi pidempi kuvausaika, useampi kuvauspaikka tai pidempi matka.",
      "Kuvadoo is not registered for VAT (small business), so no VAT is added. The prices are final prices for private and business customers.\n\n"
      "Travel up to 30 km is included. After that 0.50 €/km (both ways). The exact amount is in the offer before booking.\n\n"
      "A \"from\" price is the lowest price of the package. A longer shoot, more locations or a longer trip raise the price."),
    'contact_title': T('Yhteystiedot', 'Contact'),
    'contact_lead': T('Helpoimmin tavoitat minut WhatsAppissa tai sähköpostilla. Kerro päivä, paikka ja mitä haluat kuvata – lähetän tarjouksen.',
                      'The easiest way to reach me is WhatsApp or email. Tell me the date, the place and what you would like – I will send you an offer.'),
    'cta_title': T('Kerro *hetkestäsi*', 'Tell me about *your moment*'),
    'cta_text': T('Kysy vapaita päiviä ja tarjousta WhatsAppissa tai sähköpostilla.', 'Ask for free dates and an offer on WhatsApp or by email.'),
  },
}

prices = {
  'groups': [
    {'id': 'photo', 'title': T('Valokuvaus', 'Photography'), 'items': [
      {'id': 'mini', 'visible': True, 'from': True, 'price': 140, 'name': T('Minikuvaus / rippikuvat', 'Mini shoot / confirmation photos'),
       'includes': T('30–45 min\n1 kuvauspaikka\n20 käsiteltyä kuvaa\nVerkkogalleria', '30–45 min\n1 location\n20 edited photos\nOnline gallery')},
      {'id': 'portrait', 'visible': True, 'from': True, 'price': 220, 'name': T('Muotokuva- tai perhekuvaus', 'Portrait or family shoot'),
       'includes': T('60–90 min\nEnintään 2 kuvauspaikkaa\n40 käsiteltyä kuvaa\nVerkkogalleria', '60–90 min\nUp to 2 locations\n40 edited photos\nOnline gallery')},
      {'id': 'event', 'visible': True, 'from': True, 'price': 320, 'name': T('Tapahtumakuvaus', 'Event photography'),
       'includes': T('2 h\n100+ käsiteltyä kuvaa\nLisätunti 120 €', '2 h\n100+ edited photos\nExtra hour 120 €')},
      {'id': 'business', 'visible': True, 'from': True, 'price': 190, 'name': T('Yritys- ja henkilökuvat', 'Business portraits'),
       'includes': T('1 h paikan päällä\nEnintään 4 henkilöä\n2 kuvaa / henkilö', '1 h on site\nUp to 4 people\n2 photos per person')},
      {'id': 'wedding-s', 'visible': True, 'from': True, 'price': 690, 'name': T('Hääkuvaus: vihkiminen ja parikuvat', 'Wedding: ceremony and couple portraits'),
       'includes': T('Enintään 2,5 h\n120+ käsiteltyä kuvaa', 'Up to 2.5 h\n120+ edited photos')},
      {'id': 'wedding-m', 'visible': True, 'from': True, 'price': 1190, 'name': T('Hääkuvaus: puoli päivää', 'Wedding: half day'),
       'includes': T('Enintään 5 h\n300+ käsiteltyä kuvaa', 'Up to 5 h\n300+ edited photos')},
      {'id': 'wedding-l', 'visible': True, 'from': True, 'price': 1790, 'name': T('Hääkuvaus: koko päivä', 'Wedding: full day'),
       'includes': T('Enintään 9 h\n500+ käsiteltyä kuvaa\nLisätunti 140 €', 'Up to 9 h\n500+ edited photos\nExtra hour 140 €')},
    ]},
    {'id': 'video', 'title': T('Video – Hämeen Films', 'Video – Hämeen Films'), 'items': [
      {'id': 'v-event', 'visible': True, 'from': True, 'price': 890, 'name': T('Tapahtuma- tai yritysvideo', 'Event or business film'),
       'includes': T('Enintään 4 h kuvausta\n1–2 min video\n2 pystyvideota someen', 'Up to 4 h filming\n1–2 min film\n2 vertical clips for social media')},
      {'id': 'v-wedding-s', 'visible': True, 'from': True, 'price': 990, 'name': T('Häävideo: vihkiminen ja parikuvaus', 'Wedding film: ceremony and couple'),
       'includes': T('Enintään 3 h\n2–3 min video', 'Up to 3 h\n2–3 min film')},
      {'id': 'v-wedding-l', 'visible': True, 'from': True, 'price': 1790, 'name': T('Häävideo: koko päivä', 'Wedding film: full day'),
       'includes': T('Enintään 8 h\n4–6 min video\n1 min traileri', 'Up to 8 h\n4–6 min film\n1 min trailer')},
      {'id': 'v-music', 'visible': True, 'from': False, 'price': None, 'name': T('Musiikkivideo', 'Music video'),
       'includes': T('Suunnittelu, kuvaus ja editointi', 'Planning, filming and editing')},
      {'id': 'v-aerial', 'visible': True, 'from': False, 'price': None, 'name': T('Ilmakuvaus dronella', 'Aerial filming by drone'),
       'includes': T('Ilmakuvat ja -videot', 'Aerial photos and video')},
    ]},
  ],
}

for name, value in (('content', content), ('prices', prices)):
    f = root / 'data' / f'{name}.json'
    if f.exists() and not force:
        print('skip (exists):', f); continue
    f.write_text(json.dumps(value, ensure_ascii=False, indent=2))
    print('wrote', f)
