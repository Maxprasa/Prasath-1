<?php
// URL map. Finnish pages live at the root, English pages under /en/. Every page URL ends with a slash.
declare(strict_types=1);

const PAGE_PATHS = [
    'home' => ['fi' => '', 'en' => ''],
    'photo' => ['fi' => 'valokuvaus', 'en' => 'photography'],
    'films' => ['fi' => 'hameen-films', 'en' => 'hameen-films'],
    'prices' => ['fi' => 'hinnasto', 'en' => 'prices'],
    'about' => ['fi' => 'tietoa', 'en' => 'about'],
    'contact' => ['fi' => 'yhteystiedot', 'en' => 'contact'],
    'privacy' => ['fi' => 'tietosuoja', 'en' => 'privacy'],
    'terms' => ['fi' => 'varausehdot', 'en' => 'booking-terms'],
];

/** URL of a page in a language. $params: ['cat' => key, 'album' => slug] for gallery pages. */
function url(string $page, ?string $lang = null, array $params = []): string
{
    $lang ??= $GLOBALS['lang'] ?? 'fi';
    $prefix = $lang === 'en' ? '/en/' : '/';
    if ($page === 'cat' || $page === 'album') {
        $path = PAGE_PATHS['photo'][$lang] . '/' . categories()[$params['cat']][$lang] . '/';
        if ($page === 'album') {
            $path .= $params['album'] . '/';
        }
        return $prefix . $path;
    }
    $p = PAGE_PATHS[$page][$lang];
    return $prefix . ($p === '' ? '' : $p . '/');
}

/** Old Website Builder addresses → new pages (301). */
function old_redirect(string $path): ?string
{
    $map = [
        'videos' => '/hameen-films/',
        'en/videos' => '/en/hameen-films/',
        'en/pricing' => '/en/prices/',
        'wanaja-2026' => '/valokuvaus/tapahtumat/wanaja-2026/',
        'en/wanaja-2026' => '/en/photography/events/wanaja-2026/',
        'drift-masters-2026' => '/valokuvaus/tapahtumat/drift-masters-2026/',
        'en/drift-masters-2026' => '/en/photography/events/drift-masters-2026/',
        'mellakka-festival-2026' => '/valokuvaus/tapahtumat/mellakka-2026/',
        'en/mellakka-festival-2026' => '/en/photography/events/mellakka-2026/',
        'samantha-and-teemu' => '/valokuvaus/muotokuvat/samantha-ja-teemu/',
        'en/samantha-and-teemu' => '/en/photography/portraits/samantha-ja-teemu/',
        'rippikuvaus-hameenlinna' => '/valokuvaus/rippikuvat/',
        'en/rippikuvaus-hameenlinna' => '/en/photography/confirmation/',
        'signature-event-experience' => '/hinnasto/',
        'popular-outdoor-portrait-session' => '/hinnasto/',
        'starter-outdoor-portrait-session' => '/hinnasto/',
    ];
    return $map[$path] ?? null;
}

/**
 * Resolve a path (without leading slash) to [page, lang, params] or null.
 */
function route(string $path): ?array
{
    $lang = 'fi';
    if ($path === 'en' || str_starts_with($path, 'en/')) {
        $lang = 'en';
        $path = substr($path, 3);
    }
    $path = trim($path, '/');
    $parts = $path === '' ? [] : explode('/', $path);

    foreach (PAGE_PATHS as $page => $paths) {
        if ($path === $paths[$lang]) {
            return [$page, $lang, []];
        }
    }
    // Gallery pages: photo/<cat>/ and photo/<cat>/<album>/
    if (count($parts) >= 2 && count($parts) <= 3 && $parts[0] === PAGE_PATHS['photo'][$lang]) {
        foreach (categories() as $key => $c) {
            if ($c[$lang] !== $parts[1]) {
                continue;
            }
            if (count($parts) === 2) {
                return ['cat', $lang, ['cat' => $key]];
            }
            foreach (albums($key) as $a) {
                if ($a['slug'] === $parts[2]) {
                    return ['album', $lang, ['cat' => $key, 'album' => $a['slug'], 'data' => $a]];
                }
            }
        }
    }
    return null;
}
