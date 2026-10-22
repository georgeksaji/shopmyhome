<?php
/**
 * Router for PHP's built-in development server.
 *
 * Usage:
 *   php -S 127.0.0.1:8000 router.php
 *
 * It keeps the SQL, Composer packages, IDE metadata and dotfiles from being
 * downloaded as static files. Everything else is served normally.
 */

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = rawurldecode($path);

$isPrivateDirectory = preg_match('~^/(?:database|vendor|\.idea|\.git)(?:/|$)~i', $path) === 1
    || preg_match('~^/assets/uploads/.*\.(?:php|phtml|phar)$~i', $path) === 1;
$isDotFile = preg_match('~(?:^|/)\.~', $path) === 1;
$isPrivateExtension = preg_match('~\.(?:sql|md|zip|log|env|ini|bak|old|lock)$~i', $path) === 1;

if ($isPrivateDirectory || $isDotFile || $isPrivateExtension) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Not found.\n";
    return true;
}

// Let the built-in server handle the request normally.
return false;
