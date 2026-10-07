<?php
// Front controller: every page request comes here (see .htaccess).
declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/app/routes.php';

$path = trim(rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'), '/');

if ($path === 'hallinta' && !str_ends_with(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '', '/')) {
    header('Location: /hallinta/', true, 301); // the admin cookie lives on /hallinta/ only
    exit;
}
if ($path === 'hallinta' || str_starts_with($path, 'hallinta/')) {
    require __DIR__ . '/app/admin/admin.php';
    exit;
}
if ($path === 'sitemap.xml') {
    require __DIR__ . '/app/views/sitemap.php';
    exit;
}
if ($to = old_redirect($path)) {
    header('Location: ' . $to, true, 301);
    exit;
}

$r = route($path);
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if ($r && $path !== '' && !str_ends_with($requestPath, '/')) {
    header('Location: /' . $path . '/', true, 301);
    exit;
}

[$page, $lang, $params] = $r ?? ['404', str_starts_with($path, 'en/') ? 'en' : 'fi', []];
$GLOBALS['lang'] = $lang;

if ($page === '404') {
    http_response_code(404);
}
header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; frame-src https://www.youtube-nocookie.com; script-src 'self'; style-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'");

require __DIR__ . '/app/views/' . $page . '.php';
