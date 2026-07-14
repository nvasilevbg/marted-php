<?php
// Router for PHP built-in server — maps pretty URLs to .php files
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

// Static files: let the server handle them
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|ico|webp|woff2?|ttf|map)$/', $uri)) {
    return false; // serve as-is
}

// Pretty URL → .php file mapping
$map = [
    "/" => "/index.php",
    "/uslugi" => "/uslugi.php",
    "/proekti" => "/proekti.php",
    "/kontakti" => "/kontakti.php",
    "/zapazi" => "/zapazi.php",
    "/za-nas" => "/za-nas.php",
];

if (isset($map[$uri]) || isset($map[rtrim($uri, "/")])) {
    $file = isset($map[$uri]) ? $map[$uri] : $map[rtrim($uri, "/")];
    $_SERVER["SCRIPT_NAME"] = $file;
    require __DIR__ . $file;
    return true;
}

// Project detail: /proekti/{slug}
if (preg_match("#^/proekti/([a-zA-Z0-9_-]+)$#", $uri, $m)) {
    $_GET["slug"] = $m[1];
    $_SERVER["SCRIPT_NAME"] = "/proekt.php";
    require __DIR__ . "/proekt.php";
    return true;
}

// Admin
if (preg_match("#^/admin/?$#", $uri)) {
    $_SERVER["SCRIPT_NAME"] = "/admin/index.php";
    require __DIR__ . "/admin/index.php";
    return true;
}

// Default: try .php extension
$phpFile = rtrim($uri, "/") . ".php";
if (file_exists(__DIR__ . $phpFile)) {
    $_SERVER["SCRIPT_NAME"] = $phpFile;
    require __DIR__ . $phpFile;
    return true;
}

// 404
http_response_code(404);
echo "404 Not Found";
return true;