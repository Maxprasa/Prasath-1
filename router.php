<?php
// Local development only (not uploaded): php -S 127.0.0.1:8765 router.php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (preg_match('#^/(app|data|docs|tools|\.git)(/|$)#', $path)) {
    http_response_code(404);
    return true;
}
if ($path !== '/' && is_file(__DIR__ . $path)) {
    return false;
}
require __DIR__ . '/index.php';
