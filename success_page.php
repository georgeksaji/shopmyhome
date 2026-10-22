<?php
// Only allow redirects to a local PHP page. This prevents open redirects and
// header-injection attempts through the return_url parameter.
$return_url = (string) ($_GET['return_url'] ?? '');

if (preg_match('~^/?[A-Za-z0-9_.-]+\.php$~', $return_url) === 1 && strpos($return_url, '..') === false) {
    header('Location: ' . $return_url);
    exit();
}

header('Location: index.php');
exit();
