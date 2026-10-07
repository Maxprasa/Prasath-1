<?php
// Privacy notice (GDPR art. 13). Text is fixed here (not in the admin) because it is a legal text.
// Strings are trusted HTML written by us; values from settings are escaped.
require __DIR__ . '/layout.php';
require __DIR__ . '/_legal.php';
$mail = e(setting('email'));
$id = e(setting('ytunnus'));
$town = e(setting('town'));
if ($GLOBALS['lang'] === 'fi') {
    legal_page('privacy', 'Tietosuojaseloste', 'Päivitetty 7.10.2026', [
        'Rekisterinpitäjä' => ["Kuvadoo (toiminimi), Y-tunnus $id, $town. Yhteyshenkilö: Prasath Sivakathiramalei, <a href=\"mailto:$mail\">$mail</a>."],
        'Mitä tietoja käsittelemme' => [[
            'Yhteydenoton tiedot: nimi, sähköpostiosoite, puhelinnumero ja viestien sisältö, kun otat yhteyttä WhatsAppissa tai sähköpostilla.',
            'Varauksen tiedot: kuvauksen päivä, paikka ja toiveet sekä laskutustiedot.',
            'Kuvat ja videot, joissa esiinnyt, kun olet tilannut kuvauksen.',
        ]],
        'Miksi ja millä perusteella' => [[
            'Vastaamme viesteihin, teemme tarjouksen ja hoidamme kuvauksen (sopimus ja sitä edeltävät toimet, GDPR 6.1 b).',
            'Kirjanpito ja laskutus (lakisääteinen velvoite, GDPR 6.1 c).',
            'Kuvia näytetään tällä sivustolla tai somessa vain luvallasi (suostumus, GDPR 6.1 a). Voit perua luvan milloin tahansa.',
        ]],
        'Kuinka kauan säilytämme' => ['Säilytämme viestit ja varauksen tiedot vain niin kauan kuin niitä tarvitaan kuvauksen hoitamiseen ja mahdollisiin kysymyksiin. Kirjanpitoaineisto säilytetään kirjanpitolain mukaan (tositteet 6 vuotta).'],
        'Kuka tietoja käsittelee' => [[
            'Sähköposti: Google (Gmail).',
            'WhatsApp: Meta, jos valitset WhatsAppin. WhatsApp-linkki on tavallinen linkki; sivusto ei lataa WhatsAppia.',
            'Verkkosivujen palvelin: Hostinger. Palvelin tallentaa tavanomaiset lokitiedot (esim. IP-osoite) tietoturvaa varten.',
            'YouTube (Google): vain jos painat videon toistopainiketta.',
            'Google ja Meta voivat siirtää tietoja EU:n ulkopuolelle (EU–USA Data Privacy Framework).',
        ]],
        'Evästeet' => ['Sivusto ei käytä evästeitä, analytiikkaa eikä seurantaa. YouTube voi asettaa evästeitä vasta, kun painat videon toistoa. Ylläpitäjän kirjautuminen käyttää välttämätöntä istuntoevästettä vain ylläpitosivuilla.'],
        'Sinun oikeutesi' => ['Sinulla on oikeus saada tietää, mitä tietoja sinusta on, korjata ja poistaa tietoja, rajoittaa käsittelyä, vastustaa käsittelyä ja siirtää tiedot. Ota yhteyttä: <a href="mailto:' . $mail . '">' . $mail . '</a>. Voit tehdä valituksen tietosuojavaltuutetulle (<a href="https://tietosuoja.fi" rel="noopener">tietosuoja.fi</a>).'],
    ], 'Näin Kuvadoo käsittelee henkilötietojasi.');
} else {
    legal_page('privacy', 'Privacy notice', 'Updated 7 October 2026', [
        'Controller' => ["Kuvadoo (sole trader), Business ID $id, $town, Finland. Contact: Prasath Sivakathiramalei, <a href=\"mailto:$mail\">$mail</a>."],
        'What data we handle' => [[
            'Contact details: name, email address, phone number and the content of messages when you contact us on WhatsApp or by email.',
            'Booking details: date, place and wishes for the shoot, and billing details.',
            'Photos and videos of you when you have ordered a shoot.',
        ]],
        'Why, and on what legal basis' => [[
            'To answer messages, make an offer and carry out the shoot (contract and steps before a contract, GDPR 6(1)(b)).',
            'Bookkeeping and invoicing (legal obligation, GDPR 6(1)(c)).',
            'Photos are shown on this website or social media only with your permission (consent, GDPR 6(1)(a)). You can withdraw it at any time.',
        ]],
        'How long we keep data' => ['We keep messages and booking details only as long as needed for the shoot and any questions after it. Accounting records are kept as the Finnish Accounting Act requires (vouchers 6 years).'],
        'Who processes the data' => [[
            'Email: Google (Gmail).',
            'WhatsApp: Meta, if you choose WhatsApp. The WhatsApp link is a normal link; this website does not load WhatsApp.',
            'Web hosting: Hostinger. The server keeps normal log data (such as IP address) for security.',
            'YouTube (Google): only if you press a video\'s play button.',
            'Google and Meta may transfer data outside the EU (EU–US Data Privacy Framework).',
        ]],
        'Cookies' => ['This website uses no cookies, analytics or tracking. YouTube may set cookies only after you press play on a video. The site owner\'s admin login uses a necessary session cookie on the admin pages only.'],
        'Your rights' => ['You have the right to know what data we have about you, to correct and delete it, to restrict or object to processing, and to data portability. Contact: <a href="mailto:' . $mail . '">' . $mail . '</a>. You can complain to the Finnish Data Protection Ombudsman (<a href="https://tietosuoja.fi/en" rel="noopener">tietosuoja.fi</a>).'],
    ], 'How Kuvadoo handles your personal data.');
}
