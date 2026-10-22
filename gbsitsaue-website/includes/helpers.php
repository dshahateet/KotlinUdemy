<?php
declare(strict_types=1);

const LANGS = ['en', 'ar'];

/* ---------- Database ---------- */

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', DB_HOST, DB_PORT, DB_NAME);
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

function q(string $sql, array $params = []): PDOStatement
{
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st;
}

/* ---------- Output ---------- */

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = '', array $params = []): string
{
    $u = rtrim(BASE_PATH, '/') . '/' . ltrim($path, '/');
    return $params ? $u . '?' . http_build_query($params) : $u;
}

function asset(string $path): string
{
    return url('assets/' . $path) . '?v=' . ASSET_VERSION;
}

function upload_url(?string $file): string
{
    return $file ? url('uploads/' . rawurlencode($file)) : '';
}

function redirect(string $to): void
{
    header('Location: ' . $to);
    exit;
}

function not_found(): void
{
    http_response_code(404);
    require ROOT . '/404.php';
    exit;
}

/* ---------- Language ---------- */

function init_lang(): void
{
    $lang = $_GET['lang'] ?? $_COOKIE['lang'] ?? DEFAULT_LANG;
    if (!in_array($lang, LANGS, true)) {
        $lang = DEFAULT_LANG;
    }
    if (isset($_GET['lang'])) {
        setcookie('lang', $lang, ['expires' => time() + 31536000, 'path' => '/', 'samesite' => 'Lax']);
    }
    $GLOBALS['LANG'] = $lang;
    $GLOBALS['STRINGS'] = require ROOT . "/lang/$lang.php";
}

function lang(): string
{
    return $GLOBALS['LANG'];
}

function is_rtl(): bool
{
    return lang() === 'ar';
}

/** Translate an interface string. */
function t(string $key): string
{
    return $GLOBALS['STRINGS'][$key] ?? $key;
}

/** Read the localized column of a DB row (e.g. title_en / title_ar), falling back to English. */
function f(array $row, string $field): string
{
    $v = $row[$field . '_' . lang()] ?? '';
    if ($v === '' || $v === null) {
        $v = $row[$field . '_en'] ?? '';
    }
    return (string)$v;
}

function lang_switch_url(string $to): string
{
    $params = $_GET;
    $params['lang'] = $to;
    $path = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
    return $path . '?' . http_build_query($params);
}

/* ---------- Settings & cached content ---------- */

function setting(string $key, string $default = ''): string
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        try {
            foreach (q('SELECT skey, value_en, value_ar FROM settings') as $r) {
                $cache[$r['skey']] = $r;
            }
        } catch (Throwable $ex) {
            // Database not ready yet: fall back to defaults.
        }
    }
    if (!isset($cache[$key])) {
        return $default;
    }
    $v = f($cache[$key], 'value');
    return $v !== '' ? $v : $default;
}

function site_name(): string
{
    return setting('site_name', 'GBSITS');
}

function active_services(): array
{
    static $services = null;
    if ($services === null) {
        try {
            $services = q('SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order, id')->fetchAll();
        } catch (Throwable $ex) {
            $services = [];
        }
    }
    return $services;
}

function service_url(array $s): string
{
    return url('service.php', ['slug' => $s['slug']]);
}

function post_url(array $p): string
{
    return url('post.php', ['slug' => $p['slug']]);
}

function format_date(?string $date): string
{
    if (!$date) {
        return '';
    }
    $ts = strtotime($date);
    if (lang() === 'ar') {
        $months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
        return date('j', $ts) . ' ' . $months[(int)date('n', $ts) - 1] . ' ' . date('Y', $ts);
    }
    return date('F j, Y', $ts);
}

function phone_href(string $phone): string
{
    return 'tel:' . preg_replace('/[^0-9+]/', '', $phone);
}

function whatsapp_href(): string
{
    $n = preg_replace('/\D/', '', setting('whatsapp'));
    return $n ? 'https://wa.me/' . $n : '';
}

/* ---------- Security ---------- */

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_valid(): bool
{
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string)$_POST['csrf']);
}

function flash(string $key, ?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
        return null;
    }
    $v = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $v;
}

/** Only allow redirects back to a local path. */
function safe_return(?string $path): string
{
    $path = (string)$path;
    if ($path === '' || $path[0] !== '/' || str_starts_with($path, '//') || preg_match('/[\r\n]/', $path)) {
        return url('contact.php');
    }
    return $path;
}
