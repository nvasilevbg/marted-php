<?php
// Router for PHP built-in server
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$uri = rtrim($uri, "/") ?: "/";

// Static files
if (preg_match("/\.(css|js|png|jpg|jpeg|gif|svg|ico|webp|woff2?|ttf|map)$/", $uri)) return false;

// Direct .php file (for /api/ endpoints)
if (preg_match("/\.php$/", $uri) && file_exists(__DIR__ . $uri)) {
    require __DIR__ . $uri;
    return true;
}

// Pretty URL map
$map = [
    "/" => "/index.php",
    "/uslugi" => "/uslugi.php",
    "/proekti" => "/proekti.php",
    "/kontakti" => "/kontakti.php",
    "/zapazi" => "/zapazi.php",
    "/za-nas" => "/za-nas.php",
];
if (isset($map[$uri])) { require __DIR__ . $map[$uri]; return true; }

// Project detail
if (preg_match("#^/proekti/([a-zA-Z0-9_-]+)$#", $uri, $m)) {
    $_GET["slug"] = $m[1];
    require __DIR__ . "/proekt.php";
    return true;
}

// Admin
if ($uri === "/admin" || $uri === "/admin/") {
    require __DIR__ . "/admin/index.php";
    return true;
}
if (strpos($uri, "/admin/") === 0 && file_exists(__DIR__ . $uri)) {
    require __DIR__ . $uri;
    return true;
}

// Fallback: try .php
$phpFile = $uri . ".php";
if (file_exists(__DIR__ . $phpFile)) { require __DIR__ . $phpFile; return true; }

http_response_code(404);
echo "404 Not Found";
return true;