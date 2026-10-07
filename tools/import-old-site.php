<?php
// One-time import of the old kuvadoo.fi galleries and the owner's YouTube videos into /data and /media.
// Usage: php tools/import-old-site.php <folder with original photos> <folder with page .list files>
// The .list files are the old site's image paths per page (sorted, as downloaded). Indices below refer to them.
declare(strict_types=1);

require __DIR__ . '/../app/bootstrap.php';
require __DIR__ . '/../app/images.php';

[$_, $src, $lists] = $argv + [null, null, null];
if (!$src || !$lists) {
    fwrite(STDERR, "usage: php tools/import-old-site.php <photos dir> <lists dir>\n");
    exit(1);
}

function L2(string $fi, string $en): array { return ['fi' => $fi, 'en' => $en]; }

$albums = [
    [
        'slug' => 'mellakka-2026', 'category' => 'events', 'list' => 'mellakka-festival-2026',
        'title' => L2('Mellakka-festivaali 2026', 'Mellakka Festival 2026'),
        'intro' => L2('Kesäinen festivaalipäivä Linnanpuistossa: ystäviä, aurinkoa, lavashow ja iltavalo.', 'A summer festival day at Linnanpuisto: friends, sunshine, the stage show and evening light.'),
        'alt' => L2('Festivaalivieraita Mellakka-festivaalilla', 'Festival guests at Mellakka Festival'),
        'altStage' => L2('Esiintymislava ja yleisö Mellakka-festivaalilla', 'The stage and the crowd at Mellakka Festival'),
        'stage' => [56, 114, 163, 255, 266, 267],
        'pick' => [255, 1, 3, 6, 14, 16, 27, 32, 35, 47, 56, 62, 71, 79, 93, 99, 101, 114, 118, 124, 163, 165, 169, 180, 189, 194, 206, 216, 226, 234, 242, 246, 265, 266, 267, 274],
        'featured' => true,
    ],
    [
        'slug' => 'wanaja-2026', 'category' => 'events', 'list' => 'wanaja-2026',
        'title' => L2('Wanaja Festival 2026', 'Wanaja Festival 2026'),
        'intro' => L2('Wanaja Festival 3.–4.7.2026 Linnanpuistossa: lavat, valot ja tuhansien ihmisten tunnelma – myös ilmasta.', 'Wanaja Festival, 3–4 July 2026 at Linnanpuisto: the stages, the lights and the mood of thousands of people – also from the air.'),
        'alt' => L2('Yleisö ja esiintymislava Wanaja Festivalilla', 'The crowd and the stage at Wanaja Festival'),
        'altStage' => L2('Valoshow lavalla Wanaja Festivalilla', 'Light show on stage at Wanaja Festival'),
        'stage' => [25, 26, 27, 28, 29, 30],
        'pick' => [29, 1, 3, 5, 7, 10, 11, 13, 16, 18, 19, 20, 22, 24, 25, 26, 27, 28, 30, 32, 33],
        'featured' => true,
    ],
    [
        'slug' => 'drift-masters-2026', 'category' => 'events', 'list' => 'drift-masters-2026',
        'title' => L2('Drift Masters 2026', 'Drift Masters 2026'),
        'intro' => L2('Drift Masters Ahvenistolla: renkaan savua, vauhtia ja täydet katsomot.', 'Drift Masters at Ahvenisto: tyre smoke, speed and full stands.'),
        'alt' => L2('Driftausauto savun keskellä Drift Masters -kilpailussa', 'A drift car in tyre smoke at Drift Masters'),
        'altStage' => L2('Katsojia ja rata Drift Masters -tapahtumassa', 'Spectators and the track at Drift Masters'),
        'stage' => [1, 2, 14, 15, 32, 35, 36, 45, 51],
        'pick' => [25, 1, 3, 4, 5, 8, 9, 10, 11, 13, 14, 16, 17, 18, 19, 20, 24, 26, 27, 30, 31, 32, 35, 36, 45, 51, 59],
        'featured' => true,
    ],
    [
        'slug' => 'samantha-ja-teemu', 'category' => 'portraits', 'list' => 'samantha-and-teemu',
        'title' => L2('Samantha & Teemu', 'Samantha & Teemu'),
        'intro' => L2('Parikuvaus kesäillan valossa järven rannalla – ja tietysti koiran kanssa.', 'A couple shoot in summer evening light by the lake – with their dog, of course.'),
        'alt' => L2('Samantha ja Teemu kesäisessä puistossa', 'Samantha and Teemu in a summer park'),
        'altStage' => L2('Samantha ja Teemu koiransa kanssa', 'Samantha and Teemu with their dog'),
        'stage' => [3, 14, 15, 16, 17, 18, 19, 20, 21],
        'pick' => [6, 1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
        'featured' => true,
    ],
    [
        'slug' => 'rippikuvaus', 'category' => 'confirmation', 'list' => 'rippikuvaus-hameenlinna',
        'title' => L2('Rippikuvaus', 'Confirmation photos'),
        'intro' => L2('Rippikuvat kotipihalla: muotokuvia, ruusu ja kuvat perheen ja suvun kanssa.', 'Confirmation photos in the home garden: portraits, a rose and photos with the family.'),
        'alt' => L2('Rippikuva nuoresta miehestä puvussa', 'Confirmation portrait of a young man in a suit'),
        'altStage' => L2('Rippikuva perheen kanssa', 'Confirmation photo with the family'),
        'stage' => [8, 9, 12, 13, 14, 15, 16, 17],
        'pick' => [3, 1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        'featured' => true,
    ],
];

$videos = [
    ['st2p772Tlcs', 'event', L2('Valoilmiö 2025', 'Valoilmiö 2025 light festival'), L2('Kuvattu ja editoitu MyIntegration Hämeenlinnalle (Hämeenlinnan kaupunki)', 'Filmed and edited for MyIntegration Hämeenlinna (City of Hämeenlinna)'), true],
    ['ws1bAm3ps7M', 'event', L2('Itsenäisyyspäivä 2025', 'Independence Day 2025'), L2('Kuvattu ja editoitu MyIntegration Hämeenlinnalle (Hämeenlinnan kaupunki)', 'Filmed and edited for MyIntegration Hämeenlinna (City of Hämeenlinna)'), true],
    ['MDHfeoaNSAQ', 'film', L2('Ella, Sri Lanka – Nine Arch Bridge', 'Ella, Sri Lanka – Nine Arch Bridge'), L2('Elokuvallinen matkavideo', 'Cinematic travel film'), true],
    ['e6JRj_wg22Q', 'music', L2('Echcharikkai – musiikkivideon kulisseista', 'Making of the Echcharikkai music video'), L2('', ''), false],
    ['tGftqTFx8lQ', 'film', L2('Order completed – sci-fi-lyhytelokuva', 'Order completed – sci-fi short film'), L2('', ''), false],
    ['V7EN72-U53k', 'business', L2('Parturi – elokuvallinen B-roll', 'Barber – cinematic B-roll'), L2('DOOFILMS', 'DOOFILMS'), false],
    ['LYm65Pe06V4', 'business', L2('Nissan GTR R35 – autokuvaus, Malesia', 'Nissan GTR R35 – car shoot, Malaysia'), L2('', ''), false],
    ['vmX9gCb32xQ', 'aerial', L2('Putrajaya, Malesia – drone', 'Putrajaya, Malaysia – drone'), L2('', ''), false],
    ['o3mM9yahCBE', 'aerial', L2('MATRADE-keskus, Malesia – ilmakuva 4K', 'MATRADE centre, Malaysia – aerial 4K'), L2('', ''), false],
    ['v2ohR4p7Ykw', 'film', L2('Gawarawilan tasangot, Sri Lanka', 'Gawarawila plains, Sri Lanka'), L2('Matkavideo', 'Travel film'), false],
];

@mkdir(MEDIA_DIR . '/photos', 0775, true);
@mkdir(MEDIA_DIR . '/videos', 0775, true);

$photos = data_get('photos');
$outAlbums = [];
$heroId = '';
foreach ($albums as $a) {
    $lines = file("$lists/{$a['list']}.list", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $ids = [];
    foreach ($a['pick'] as $i) {
        $name = basename($lines[$i]);
        $rec = photo_process("$src/$name", $a['slug']);
        $rec['alt'] = in_array($i, $a['stage'], true) ? $a['altStage'] : $a['alt'];
        $id = new_id();
        $photos[$id] = $rec;
        $ids[] = $id;
        echo '.';
    }
    if ($a['slug'] === 'mellakka-2026') {
        $heroId = $ids[0];
    }
    $outAlbums[] = [
        'id' => new_id(), 'slug' => $a['slug'], 'category' => $a['category'], 'title' => $a['title'], 'intro' => $a['intro'],
        'cover' => $ids[0], 'photos' => $ids, 'visible' => true, 'featured' => $a['featured'], 'created' => date('c'),
    ];
    echo " {$a['slug']} (" . count($ids) . ")\n";
}

$outVideos = [];
foreach ($videos as [$yt, $kind, $title, $credit, $featured]) {
    $ok = video_poster_fetch($yt);
    $outVideos[] = ['id' => new_id(), 'youtube' => $yt, 'kind' => $kind, 'title' => $title, 'credit' => $credit, 'featured' => $featured, 'visible' => true, 'poster' => $ok];
    echo "video $yt " . ($ok ? 'poster ok' : 'NO POSTER') . "\n";
}

data_put('photos', $photos);
data_put('albums', $outAlbums);
data_put('videos', $outVideos);
$content = data_get('content');
$content['site']['hero_photo'] = $heroId;
data_put('content', $content);
echo "done\n";
