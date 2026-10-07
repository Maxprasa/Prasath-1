<?php
// Admin authentication: one owner account, password hash in data/auth.json (never committed).
// First run: a one-time setup code is written to data/setup-code.txt, which only the owner can read
// (Hostinger File Manager). Whoever knows the code may set the password.
declare(strict_types=1);

const SESSION_IDLE = 7200;          // log out after 2 h without activity
const LOGIN_MAX_FAILS = 5;          // per IP within the window
const LOGIN_WINDOW = 900;           // 15 min

function is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

function admin_session_start(): void
{
    session_name('kuvadoo_admin');
    session_set_cookie_params([
        'lifetime' => 0, 'path' => '/hallinta/', 'secure' => is_https(), 'httponly' => true, 'samesite' => 'Strict',
    ]);
    ini_set('session.use_strict_mode', '1');
    session_start();
    if (isset($_SESSION['last']) && time() - $_SESSION['last'] > SESSION_IDLE) {
        $_SESSION = [];
        session_regenerate_id(true);
    }
    $_SESSION['last'] = time();
    $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

function auth_data(): ?array
{
    $f = DATA_DIR . '/auth.json';
    if (!is_file($f)) {
        return null;
    }
    $d = json_decode((string) file_get_contents($f), true);
    return is_array($d) && !empty($d['hash']) ? $d : null;
}

function auth_set_password(string $password): void
{
    data_put('auth', ['hash' => password_hash($password, PASSWORD_DEFAULT), 'changed' => date('c')]);
    @chmod(DATA_DIR . '/auth.json', 0600);
}

function setup_code(): string
{
    $f = DATA_DIR . '/setup-code.txt';
    if (!is_file($f)) {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < 12; $i++) {
            $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        file_put_contents($f, $code . "\n");
        @chmod($f, 0600);
    }
    return trim((string) file_get_contents($f));
}

function is_logged_in(): bool
{
    return !empty($_SESSION['admin']);
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e($_SESSION['csrf']) . '">';
}

function csrf_check(): void
{
    $t = $_POST['csrf'] ?? '';
    if (!is_string($t) || !hash_equals($_SESSION['csrf'] ?? '', $t)) {
        http_response_code(400);
        exit('Security check failed. Go back, reload the page and try again.');
    }
}

function client_key(): string
{
    return hash('sha256', ($_SERVER['REMOTE_ADDR'] ?? '') . '|kuvadoo');
}

/** True if this IP is temporarily blocked after too many wrong passwords. */
function login_blocked(): bool
{
    $a = data_get('login-attempts')[client_key()] ?? null;
    return $a && $a['n'] >= LOGIN_MAX_FAILS && time() - $a['t'] < LOGIN_WINDOW;
}

function login_record(bool $ok): void
{
    $all = json_decode((string) @file_get_contents(DATA_DIR . '/login-attempts.json'), true) ?: [];
    $now = time();
    $all = array_filter($all, fn($a) => $now - $a['t'] < LOGIN_WINDOW); // forget old entries
    $k = client_key();
    if ($ok) {
        unset($all[$k]);
    } else {
        $all[$k] = ['n' => ($all[$k]['n'] ?? 0) + 1, 't' => $all[$k]['t'] ?? $now];
    }
    data_put('login-attempts', $all);
}

function password_problem(string $p, string $p2): ?string
{
    if ($p !== $p2) {
        return 'The two passwords are not the same.';
    }
    if (mb_strlen($p) < 10) {
        return 'Use at least 10 characters.';
    }
    return null;
}
