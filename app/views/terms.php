<?php
// Booking terms. Fixed legal text (not in the admin). Based on docs/research/2026-10-prices-and-rules-fi.md; not legal advice.
require __DIR__ . '/layout.php';
require __DIR__ . '/_legal.php';
$mail = e(setting('email'));
$prices = e(url('prices'));
if ($GLOBALS['lang'] === 'fi') {
    legal_page('terms', 'Varausehdot', 'Voimassa 7.10.2026 alkaen', [
        'Tarjous ja varaus' => ['Lähetän kirjallisen tarjouksen WhatsAppissa tai sähköpostilla. Varaus on vahvistettu, kun hyväksyt tarjouksen kirjallisesti. Tarjouksessa kerrotaan hinta, sisältö, matkakulut ja maksuehdot.'],
        'Hinnat' => ["Hinnat ovat <a href=\"$prices\">hinnaston</a> tai tarjouksen mukaiset. Kuvadoo ei ole arvonlisäverovelvollinen, joten hintoihin ei lisätä arvonlisäveroa."],
        'Peruutusoikeus (14 päivää)' => [
            'Kun varaat etänä (WhatsApp tai sähköposti), sinulla on kuluttajana oikeus perua sopimus 14 päivän kuluessa sopimuksen tekemisestä ilmoittamalla siitä sähköpostilla osoitteeseen <a href="mailto:' . $mail . '">' . $mail . '</a>. Voit käyttää vapaamuotoista ilmoitusta.',
            'Jos pyydät, että kuvaus tehdään peruutusajan aikana, ja perut sen jälkeen, maksat jo tehdystä työstä kohtuullisen osuuden.',
        ],
        'Muutokset ja myöhempi peruutus' => ['Päivän siirrosta ja peruutuksesta peruutusajan jälkeen sovitaan tarjouksessa. Jos kuvaaja sairastuu tai kuvaus estyy ylivoimaisen esteen vuoksi, sovitaan uusi aika tai palautetaan maksetut summat.'],
        'Kuvien ja videoiden toimitus' => ['Valmiit kuvat toimitetaan verkkogalleriassa ja videot latauslinkkinä. Toimitusaika sovitaan tarjouksessa.'],
        'Käyttöoikeus' => ['Tekijänoikeus kuviin ja videoihin säilyy kuvaajalla. Asiakas saa käyttää niitä omaan, yksityiseen käyttöönsä. Yrityskäytöstä sovitaan tarjouksessa. Kuvia näytetään Kuvadoon omissa kanavissa vain asiakkaan luvalla.'],
        'Erimielisyydet' => ['Yritämme ratkaista asiat ensin yhdessä. Kuluttaja voi pyytää apua kuluttajaneuvonnasta ja viedä asian kuluttajariitalautakuntaan (<a href="https://www.kuluttajariita.fi" rel="noopener">kuluttajariita.fi</a>).'],
    ], 'Nämä ehdot koskevat Kuvadoon valokuvaus- ja Hämeen Films -videopalveluita.');
} else {
    legal_page('terms', 'Booking terms', 'Valid from 7 October 2026', [
        'Offer and booking' => ['I send a written offer on WhatsApp or by email. The booking is confirmed when you accept the offer in writing. The offer states the price, what is included, travel costs and payment terms.'],
        'Prices' => ["Prices are as in the <a href=\"$prices\">price list</a> or the offer. Kuvadoo is not registered for VAT, so no VAT is added."],
        'Right of withdrawal (14 days)' => [
            'When you book at a distance (WhatsApp or email), you as a consumer have the right to cancel the contract within 14 days of making it by telling us by email at <a href="mailto:' . $mail . '">' . $mail . '</a>. A free-form message is fine.',
            'If you ask for the shoot to take place during the withdrawal period and then cancel, you pay a reasonable share for the work already done.',
        ],
        'Changes and later cancellation' => ['Moving the date and cancelling after the withdrawal period are agreed in the offer. If the photographer falls ill or the shoot is prevented by force majeure, we agree a new date or return the amounts paid.'],
        'Delivery of photos and videos' => ['Finished photos are delivered in an online gallery and videos as a download link. The delivery time is agreed in the offer.'],
        'Usage rights' => ['The copyright of photos and videos stays with the photographer. The client may use them for their own private use. Business use is agreed in the offer. Photos are shown in Kuvadoo\'s own channels only with the client\'s permission.'],
        'Disputes' => ['We first try to solve any issue together. A consumer can get help from consumer advice services and take the matter to the Finnish Consumer Disputes Board (<a href="https://www.kuluttajariita.fi/en/" rel="noopener">kuluttajariita.fi</a>).'],
    ], 'These terms apply to Kuvadoo photography and Hämeen Films video services.');
}
