<?php
// Shared setup for the public site and the admin panel. No database: content lives in JSON files in /data.
declare(strict_types=1);

const ROOT = __DIR__ . '/..';
const DATA_DIR = ROOT . '/data';
const MEDIA_DIR = ROOT . '/media';
const SITE_URL = 'https://kuvadoo.fi';
const ASSET_VER = '8';

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

/** Read a data file directly from disk (no cache). Throws if the file exists but is broken. */
function data_read_fresh(string $name): array
{
    $file = DATA_DIR . "/$name.json";
    if (!is_file($file)) {
        return [];
    }
    $raw = file_get_contents($file);
    $json = $raw === false ? null : json_decode($raw, true);
    if (!is_array($json)) {
        throw new RuntimeException("Data file $name.json cannot be read. Restore it from a backup.");
    }
    return $json;
}

/**
 * Change a data file safely: lock → read fresh → $fn($current) → write → unlock.
 * $fn returns the new value, or null to leave the file unchanged. Use this for every read-change-write,
 * so two admin tabs (or a long upload) cannot overwrite each other's changes.
 */
function data_update(string $name, callable $fn)
{
    static $depth = 0;
    static $lock = null;
    if ($depth === 0) {
        $lock = fopen(DATA_DIR . '/.lock', 'c');
        if (!$lock || !flock($lock, LOCK_EX)) {
            throw new RuntimeException('Could not lock the data folder.');
        }
    }
    $depth++;
    try {
        $new = $fn(data_read_fresh($name));
        if ($new !== null) {
            data_write($name, $new);
        }
        return $new;
    } finally {
        $depth--;
        if ($depth === 0) {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }
}

/** Replace a whole data file (locked). */
function data_put(string $name, array $value): void
{
    data_update($name, fn() => $value);
}

/** Write temp file + rename. Never replaces the real file with a broken or partial one. Call only under the lock. */
function data_write(string $name, array $value): void
{
    $file = DATA_DIR . "/$name.json";
    $json = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
    $tmp = $file . '.' . bin2hex(random_bytes(4)) . '.tmp';
    if (file_put_contents($tmp, $json) !== strlen($json) || !rename($tmp, $file)) {
        @unlink($tmp);
        throw new RuntimeException("Could not save $name.json (is the disk full?). Nothing was changed.");
    }
    $GLOBALS['__data_cache'][$name] = $value;
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

/** Random id. Starts with a letter so PHP never turns it into an integer array key. */
function new_id(): string
{
    return 'p' . bin2hex(random_bytes(6));
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
function img(?array $p, string $sizes, string $class = '', bool $lazy = true, int $w = 960, ?string $alt = null): string
{
    if (!$p) {
        return '<div class="img-missing ' . e($class) . '" aria-hidden="true"></div>';
    }
    $max = end($p['widths']);
    $h = (int) round($p['h'] * $max / $p['w']);
    return '<img src="' . e(photo_url($p, $w)) . '" srcset="' . e(photo_srcset($p)) . '" sizes="' . e($sizes) . '"'
        . ' width="' . $max . '" height="' . $h . '" alt="' . e($alt ?? tr($p['alt'] ?? '')) . '"'
        . ($class ? ' class="' . e($class) . '"' : '')
        . ($lazy ? ' loading="lazy" decoding="async"' : ' fetchpriority="high"') . '>';
}

/** Escape text and turn *word* into a highlighted span (owner can mark the highlight in the admin). */
function hl_text(string $text): string
{
    return preg_replace('/\*(.+?)\*/u', '<span class="hl">$1</span>', e($text));
}

/** Plain text without *highlight* markers (for meta tags). */
function plain(string $text): string
{
    return str_replace('*', '', $text);
}

/** Mark the Finnish brand name with lang="fi" on English pages. Input must already be escaped. */
function hf(string $escaped): string
{
    if (($GLOBALS['lang'] ?? 'fi') !== 'en') {
        return $escaped;
    }
    return str_replace('Hämeen Films', '<span lang="fi">Hämeen Films</span>', $escaped);
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
