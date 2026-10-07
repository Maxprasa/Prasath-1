<?php
// Shared setup for the public site and the admin panel. No database: content lives in JSON files in /data.
declare(strict_types=1);

const ROOT = __DIR__ . '/..';
const DATA_DIR = ROOT . '/data';
const MEDIA_DIR = ROOT . '/media';
const SITE_URL = 'https://kuvadoo.fi';
const ASSET_VER = '1';

mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Helsinki');

/** Escape for HTML. */
function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Read a JSON data file (returns $default if missing). Cached per request. */
function data_get(string $name, $default = [])
{
    $cache = &$GLOBALS['__data_cache'];
    if (isset($cache[$name])) {
        return $cache[$name];
    }
    $file = DATA_DIR . "/$name.json";
    if (!is_file($file)) {
        return $default;
    }
    $json = json_decode((string) file_get_contents($file), true);
    return $cache[$name] = is_array($json) ? $json : $default;
}

/** Write a JSON data file atomically (temp file + rename, with a lock). */
function data_put(string $name, array $value): void
{
    $file = DATA_DIR . "/$name.json";
    $lock = fopen(DATA_DIR . '/.lock', 'c');
    flock($lock, LOCK_EX);
    $tmp = $file . '.' . bin2hex(random_bytes(4)) . '.tmp';
    file_put_contents($tmp, json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    rename($tmp, $file);
    $GLOBALS['__data_cache'][$name] = $value;
    flock($lock, LOCK_UN);
    fclose($lock);
}

/** Pick the current language from a {fi, en} value; falls back to Finnish. */
function tr($value, ?string $lang = null): string
{
    $lang ??= $GLOBALS['lang'] ?? 'fi';
    if (!is_array($value)) {
        return (string) $value;
    }
    $v = $value[$lang] ?? '';
    return $v !== '' ? (string) $v : (string) ($value['fi'] ?? '');
}

/** Interface strings (labels, menu) — see app/i18n.php. Site texts the owner edits are in data/content.json. */
function t(string $key): string
{
    static $strings = null;
    $strings ??= require __DIR__ . '/i18n.php';
    $lang = $GLOBALS['lang'] ?? 'fi';
    return $strings[$key][$lang] ?? $strings[$key]['fi'] ?? $key;
}

/** Editable site text from data/content.json, current language. */
function txt(string $key): string
{
    $content = data_get('content');
    return tr($content['text'][$key] ?? '');
}

function setting(string $key): string
{
    return (string) (data_get('content')['site'][$key] ?? '');
}

/** Turn plain text with blank lines into <p> paragraphs (escaped). */
function paras(string $text, string $class = ''): string
{
    $out = '';
    foreach (preg_split('/\R{2,}/', trim($text)) as $p) {
        if ($p === '') {
            continue;
        }
        $out .= '<p' . ($class ? ' class="' . e($class) . '"' : '') . '>' . nl2br(e($p), false) . "</p>\n";
    }
    return $out;
}

function slugify(string $s): string
{
    $s = mb_strtolower(trim($s));
    $s = strtr($s, ['ä' => 'a', 'ö' => 'o', 'å' => 'a', 'é' => 'e', 'ü' => 'u']);
    $s = preg_replace('/[^a-z0-9]+/', '-', $s);
    return trim((string) $s, '-') ?: 'albumi';
}

function new_id(): string
{
    return bin2hex(random_bytes(6));
}

/** Format a price like "1 190 €" (Finnish style) or "1,190 €" (English). */
function money(int $amount): string
{
    $sep = ($GLOBALS['lang'] ?? 'fi') === 'en' ? ',' : "\u{202F}";
    return number_format($amount, 0, '', $sep) . "\u{00A0}€";
}

/** Photo categories. Slugs are part of the URL, so keep them stable. */
function categories(): array
{
    return [
        'events' => ['fi' => 'tapahtumat', 'en' => 'events', 'name' => ['fi' => 'Tapahtumat', 'en' => 'Events']],
        'portraits' => ['fi' => 'muotokuvat', 'en' => 'portraits', 'name' => ['fi' => 'Muotokuvat ja parikuvat', 'en' => 'Portraits and couples']],
        'confirmation' => ['fi' => 'rippikuvat', 'en' => 'confirmation', 'name' => ['fi' => 'Rippikuvat', 'en' => 'Confirmation photos']],
        'weddings' => ['fi' => 'haat', 'en' => 'weddings', 'name' => ['fi' => 'Häät', 'en' => 'Weddings']],
        'business' => ['fi' => 'yrityksille', 'en' => 'business', 'name' => ['fi' => 'Yrityksille', 'en' => 'Business']],
        'aerial' => ['fi' => 'ilmakuvat', 'en' => 'aerial', 'name' => ['fi' => 'Ilmakuvat', 'en' => 'Aerial']],
    ];
}

/** Video kinds for Hämeen Films. */
function video_kinds(): array
{
    return [
        'wedding' => ['fi' => 'Häät', 'en' => 'Weddings'],
        'event' => ['fi' => 'Tapahtumat', 'en' => 'Events'],
        'business' => ['fi' => 'Yritykset', 'en' => 'Business'],
        'music' => ['fi' => 'Musiikkivideot', 'en' => 'Music videos'],
        'aerial' => ['fi' => 'Ilmakuvaus', 'en' => 'Aerial'],
        'film' => ['fi' => 'Lyhytelokuvat ja matkat', 'en' => 'Short films and travel'],
    ];
}

/** Visible albums, newest first (by the order in the file). */
function albums(?string $category = null): array
{
    $list = array_values(array_filter(data_get('albums'), fn($a) => !empty($a['visible']) && !empty($a['photos'])));
    if ($category !== null) {
        $list = array_values(array_filter($list, fn($a) => $a['category'] === $category));
    }
    return $list;
}

function photo(string $id): ?array
{
    $p = data_get('photos')[$id] ?? null;
    return $p ? $p + ['id' => $id] : null;
}

/** Widths we keep of every photo. The largest may be smaller if the original is smaller. */
const PHOTO_WIDTHS = [480, 960, 1600, 2200];

function photo_url(array $p, int $w): string
{
    $best = null;
    foreach ($p['widths'] as $have) {
        if ($have >= $w) {
            $best = $have;
            break;
        }
    }
    $best ??= end($p['widths']);
    return '/media/photos/' . $p['file'] . '-' . $best . '.webp';
}

function photo_srcset(array $p): string
{
    return implode(', ', array_map(fn($w) => '/media/photos/' . $p['file'] . "-$w.webp {$w}w", $p['widths']));
}

/** <img> tag for a photo. $sizes is the CSS sizes attribute. */
function img(?array $p, string $sizes, string $class = '', bool $lazy = true, int $w = 960): string
{
    if (!$p) {
        return '<div class="img-missing ' . e($class) . '" aria-hidden="true"></div>';
    }
    $max = end($p['widths']);
    $h = (int) round($p['h'] * $max / $p['w']);
    return '<img src="' . e(photo_url($p, $w)) . '" srcset="' . e(photo_srcset($p)) . '" sizes="' . e($sizes) . '"'
        . ' width="' . $max . '" height="' . $h . '" alt="' . e(tr($p['alt'] ?? '')) . '"'
        . ($class ? ' class="' . e($class) . '"' : '')
        . ($lazy ? ' loading="lazy" decoding="async"' : ' fetchpriority="high"') . '>';
}

function whatsapp_link(string $textKey = 'wa_hello'): string
{
    $num = preg_replace('/\D/', '', setting('whatsapp'));
    return 'https://wa.me/' . $num . '?text=' . rawurlencode(t($textKey));
}

function phone_display(): string
{
    return setting('whatsapp_display') ?: setting('whatsapp');
}
