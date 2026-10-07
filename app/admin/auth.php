<?php
// Admin authentication: one owner account, password hash in data/auth.json (never committed).
// First run: a one-time setup code is written to data/setup-code.txt, which only the owner can read
// (Hostinger File Manager). Whoever knows the code may set the password.
declare(strict_types=1);

const SESSION_IDLE = 7200;          // log out after 2 h without activity
const LOGIN_MAX_FAILS = 5;          // per IP within the window
const LOGIN_WINDOW = 900;           // 15 min
const LOGIN_GLOBAL_SLOW = 50;       // all IPs together: above this, every attempt is slowed down

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
    ini_set('session.gc_maxlifetime', (string) SESSION_IDLE);
    session_start();
    $expired = isset($_SESSION['last']) && time() - $_SESSION['last'] > SESSION_IDLE;
    // A password change logs out every other session
    $stale = !empty($_SESSION['admin']) && ($_SESSION['pw_changed'] ?? '') !== (auth_data()['changed'] ?? '');
    if ($expired || $stale) {
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
    $changed = date('c') . '-' . bin2hex(random_bytes(3));
    data_put('auth', ['hash' => password_hash($password, PASSWORD_DEFAULT), 'changed' => $changed]);
    @chmod(DATA_DIR . '/auth.json', 0600);
    $_SESSION['pw_changed'] = $changed;
}

/** Mark this session as logged in. */
function auth_login(): void
{
    session_regenerate_id(true);
    $_SESSION['admin'] = true;
    $_SESSION['pw_changed'] = auth_data()['changed'] ?? '';
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
        if (file_put_contents($f, $code . "\n") === false) {
            http_response_code(500);
            exit('The admin panel cannot write to the data folder. Check folder permissions in File Manager.');
        }
        @chmod($f, 0600);
    }
    $code = trim((string) file_get_contents($f));
    if (strlen($code) !== 12) { // never accept an empty or broken code (fail closed)
        http_response_code(500);
        exit('Setup code file is broken. Delete data/setup-code.txt in File Manager and reload.');
    }
    return $code;
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

/** Per-IP key. IPv6 addresses are grouped by /64 (one home connection gets a whole /64). */
function client_key(): string
{
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
    if (str_contains($ip, ':') && ($bin = @inet_pton($ip)) !== false) {
        $ip = bin2hex(substr($bin, 0, 8)) . '::/64';
    }
    return hash('sha256', $ip . '|kuvadoo');
}

/**
 * Count a login attempt BEFORE checking the password (under the data lock, so parallel requests
 * cannot get around the limit). Returns false if this IP is blocked.
 */
function login_attempt_allowed(): bool
{
    $allowed = true;
    $global = 0;
    data_update('login-attempts', function (array $all) use (&$allowed, &$global) {
        $now = time();
        $all = array_filter($all, fn($a) => is_array($a) && $now - $a['t'] < LOGIN_WINDOW);
        $k = client_key();
        $a = $all[$k] ?? ['n' => 0, 't' => $now];
        if ($a['n'] >= LOGIN_MAX_FAILS) {
            $allowed = false;
            return $all;
        }
        $a['n']++;
        $all[$k] = $a;
        $global = array_sum(array_column($all, 'n'));
        return $all;
    });
    if ($global > LOGIN_GLOBAL_SLOW) {
        sleep(3); // many failures from many addresses: slow everyone down (but never lock out the owner)
    }
    return $allowed;
}

/** After a correct password: forget this IP's failed attempts. */
function login_success(): void
{
    $k = client_key();
    data_update('login-attempts', function (array $all) use ($k) {
        unset($all[$k]);
        return $all;
    });
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
