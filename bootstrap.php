<?php
/**
 * Shared bootstrap for shopmyhome / OHAS.
 * Included by connection.php on every page.
 */

// ---------------------------------------------------------------------------
// Configuration
// ---------------------------------------------------------------------------
define('APP_DEBUG', getenv('OHAS_DEBUG') === '1');

define('DB_HOST', getenv('OHAS_DB_HOST') ?: 'localhost');
define('DB_USER', getenv('OHAS_DB_USER') ?: 'root');
define('DB_PASS', getenv('OHAS_DB_PASS') ?: '');
define('DB_NAME', getenv('OHAS_DB_NAME') ?: 'ohas');

// ---------------------------------------------------------------------------
// Error handling: log everything, only show details in debug mode.
// ---------------------------------------------------------------------------
error_reporting(E_ALL);
ini_set('display_errors', APP_DEBUG ? '1' : '0');
ini_set('log_errors', '1');

set_exception_handler(function (Throwable $e): void {
    error_log('[OHAS] ' . get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    if (!headers_sent()) {
        http_response_code(500);
    }
    if (APP_DEBUG) {
        echo '<pre style="white-space:pre-wrap">' . htmlspecialchars((string) $e, ENT_QUOTES, 'UTF-8') . '</pre>';
    } else {
        echo 'Something went wrong. Please try again later.';
    }
});

// ---------------------------------------------------------------------------
// Session hardening (must run before session_start()).
// ---------------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

// ---------------------------------------------------------------------------
// Database
// ---------------------------------------------------------------------------
function db(): mysqli
{
    static $conn = null;

    if ($conn instanceof mysqli) {
        return $conn;
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');

    return $conn;
}

/**
 * Run a prepared statement.
 *
 * @param array<int, mixed> $params Values for the ? placeholders.
 */
function db_exec(string $sql, array $params = []): mysqli_stmt
{
    $stmt = db()->prepare($sql);
    if ($params) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();

    return $stmt;
}

/**
 * @param array<int, mixed> $params
 * @return array<int, array<string, mixed>>
 */
function db_all(string $sql, array $params = []): array
{
    $result = db_exec($sql, $params)->get_result();

    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

/**
 * @param array<int, mixed> $params
 * @return array<string, mixed>|null
 */
function db_one(string $sql, array $params = []): ?array
{
    $rows = db_all($sql, $params);

    return $rows[0] ?? null;
}

/**
 * @param array<int, mixed> $params
 * @return mixed
 */
function db_val(string $sql, array $params = [])
{
    $row = db_one($sql, $params);
    if ($row === null) {
        return null;
    }

    return reset($row);
}

// ---------------------------------------------------------------------------
// Output / request helpers
// ---------------------------------------------------------------------------
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/**
 * OHAS identifiers are two uppercase letters followed by five digits
 * (for example AP00001, CM00012).
 */
function is_valid_id($value): bool
{
    return is_string($value) && preg_match('/^[A-Z]{2}\d{5}$/', $value) === 1;
}

function post_id(string $key): ?string
{
    $value = $_POST[$key] ?? null;

    return is_valid_id($value) ? $value : null;
}

/**
 * Validate and store an uploaded image. Returns the relative path to store in
 * the database, or null when the field was left empty.
 *
 * @throws RuntimeException when the upload is not a safe, supported image.
 */
function save_uploaded_image(string $field, string $subdir): ?string
{
    if (empty($_FILES[$field]['name']) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('The image upload failed. Please try again.');
    }

    if (($_FILES[$field]['size'] ?? 0) > 5 * 1024 * 1024) {
        throw new RuntimeException('Images must be 5 MB or smaller.');
    }

    $info = @getimagesize($_FILES[$field]['tmp_name']);
    if ($info === false) {
        throw new RuntimeException('Only JPG, PNG, GIF and WebP images are allowed.');
    }

    $extensions = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG  => 'png',
        IMAGETYPE_GIF  => 'gif',
        IMAGETYPE_WEBP => 'webp',
    ];
    $ext = $extensions[$info[2]] ?? null;
    if ($ext === null) {
        throw new RuntimeException('Only JPG, PNG, GIF and WebP images are allowed.');
    }

    $subdir = trim($subdir, '/');
    $dir = __DIR__ . '/' . $subdir;
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new RuntimeException('Could not create the upload directory.');
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $dir . '/' . $filename)) {
        throw new RuntimeException('Could not save the uploaded image.');
    }

    return $subdir . '/' . $filename;
}

// ---------------------------------------------------------------------------
// Auth helpers
// ---------------------------------------------------------------------------
function current_user_id(): ?string
{
    return isset($_SESSION['User_ID']) && $_SESSION['User_ID'] !== null
        ? (string) $_SESSION['User_ID']
        : null;
}

function current_user_type(): ?string
{
    return isset($_SESSION['User_Type']) && $_SESSION['User_Type'] !== null
        ? (string) $_SESSION['User_Type']
        : null;
}

/**
 * @param array<int, string> $roles
 */
function require_role(array $roles, string $loginUrl = 'Login.php'): void
{
    if (current_user_id() === null) {
        redirect($loginUrl);
    }
    if (!in_array(current_user_type(), $roles, true)) {
        http_response_code(403);
        exit('Access denied.');
    }
}

function require_login(string $loginUrl = 'Login.php'): void
{
    if (current_user_id() === null) {
        redirect($loginUrl);
    }
}

// ---------------------------------------------------------------------------
// CSRF helpers (enforced in Phase 2; helpers are shared by every page)
// ---------------------------------------------------------------------------
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): void
{
    // Ensure a token exists for this session (also used when rendering forms).
    $expected = csrf_token();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || $token === '' || !hash_equals($expected, $token)) {
        http_response_code(403);
        exit('Invalid or expired form token. Please reload the page and try again.');
    }
}

// Inject a CSRF token into every POST form as it is sent to the browser.
// Pages call csrf_verify() right after session_start(), so the token is
// persisted before this output callback runs.
ob_start(function (string $buffer): string {
    if (session_status() !== PHP_SESSION_ACTIVE || stripos($buffer, '<form') === false) {
        return $buffer;
    }

    $token = csrf_token();

    return preg_replace_callback(
        '/<form\b[^>]*method\s*=\s*["\']post["\'][^>]*>/i',
        function (array $match) use ($token): string {
            if (stripos($match[0], 'csrf_token') !== false) {
                return $match[0];
            }
            return $match[0] . '<input type="hidden" name="csrf_token" value="' . e($token) . '">';
        },
        $buffer
    );
});
