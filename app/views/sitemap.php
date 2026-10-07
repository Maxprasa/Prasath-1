<?php
// Dynamic sitemap: every page in both languages, plus every category with photos and every album.
header('Content-Type: application/xml; charset=utf-8');
$entries = [];
foreach (array_keys(PAGE_PATHS) as $page) {
    $entries[] = [url($page, 'fi'), url($page, 'en')];
}
foreach (categories() as $key => $c) {
    if (albums($key)) {
        $entries[] = [url('cat', 'fi', ['cat' => $key]), url('cat', 'en', ['cat' => $key])];
    }
}
foreach (albums() as $a) {
    $p = ['cat' => $a['category'], 'album' => $a['slug']];
    $entries[] = [url('album', 'fi', $p), url('album', 'en', $p)];
}
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
foreach ($entries as [$fi, $en]) {
    foreach ([$fi, $en] as $loc) {
        echo '<url><loc>' . e(SITE_URL . $loc) . '</loc>'
            . '<xhtml:link rel="alternate" hreflang="fi" href="' . e(SITE_URL . $fi) . '"/>'
            . '<xhtml:link rel="alternate" hreflang="en" href="' . e(SITE_URL . $en) . '"/></url>' . "\n";
    }
}
echo "</urlset>\n";
