<?php
function _start_session() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_set_cookie_params(['httponly'=>true, 'samesite'=>'Strict', 'path'=>'/']);
        session_start();
    }
}
function is_admin() {
    _start_session();
    return !empty($_SESSION['is_admin']);
}
function admin_login($pass) {
    _start_session();
    $configPass = config()['admin_pass'];
    $dbPass = setting('admin_pass', '');
    $validPass = ($dbPass !== '' && $dbPass !== 'CHANGE_ME_IN_CONFIG') ? $dbPass : $configPass;
    if (hash_equals($validPass, $pass)) { $_SESSION['is_admin'] = true; return true; }
    return false;
}
function admin_logout() {
    _start_session();
    $_SESSION = []; session_destroy();
}
function require_admin() {
    if (!is_admin()) { header('Location: index.php'); exit; }
}
function csrf_token() {
    _start_session();
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_check() {
    _start_session();
    $t = $_POST['csrf'] ?? '';
    return !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $t);
}
function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

function convert_to_webp($src, $dest, $quality = 85) {
    // Try cwebp command first (supports effort parameter)
    if (function_exists('exec')) {
        $cmd = 'cwebp -q ' . $quality . ' -m 6 ' . escapeshellarg($src) . ' -o ' . escapeshellarg($dest) . ' 2>&1';
        @exec($cmd, $out, $ret);
        if ($ret === 0 && file_exists($dest)) return true;
    }
    // Fallback to GD library
    if (function_exists('imagewebp') && function_exists('getimagesize')) {
        $info = @getimagesize($src);
        if (!$info) return false;
        switch ($info[2]) {
            case IMAGETYPE_JPEG: $img = @imagecreatefromjpeg($src); break;
            case IMAGETYPE_PNG:
                $img = @imagecreatefrompng($src);
                if ($img) { imagealphablending($img, true); imagesavealpha($img, true); }
                break;
            case IMAGETYPE_WEBP: return @copy($src, $dest);
            default: return false;
        }
        if (!$img) return false;
        $ok = @imagewebp($img, $dest, $quality);
        imagedestroy($img);
        return $ok;
    }
    return false;
}

function upload_image($field, $dest_dir) {
    if (empty($_FILES[$field]['name']) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return null;
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp','mp4','webm','mov'];
    if (!in_array($ext, $allowed)) return null;
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES[$field]['tmp_name']);
    finfo_close($finfo);
    $allowedMimes = ['image/jpeg','image/png','image/webp','video/mp4','video/webm','video/quicktime'];
    if (!in_array($mime, $allowedMimes)) return null;
    
    // Upload original to temp
    $tmpName = 'p_' . time() . '_' . rand(100,999) . '.' . $ext;
    $tmpPath = $dest_dir . '/' . $tmpName;
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $tmpPath)) return null;
    
    // Videos: keep as-is (no WebP conversion)
    $videoExts = ['mp4','webm','mov'];
    if (in_array($ext, $videoExts)) {
        return '/assets/media/projects/' . $tmpName;
    }
    // Images: convert to WebP
    $webpName = 'p_' . time() . '_' . rand(100,999) . '.webp';
    $webpPath = $dest_dir . '/' . $webpName;
    if (convert_to_webp($tmpPath, $webpPath, 85)) {
        @unlink($tmpPath);
        return '/assets/media/projects/' . $webpName;
    }
    return '/assets/media/projects/' . $tmpName;
}

function slugify($s) {
    $s = mb_strtolower(trim($s), 'UTF-8');
    $s = preg_replace('/[^a-z0-9--]+/u', '-', $s);
    $s = preg_replace('/^-+|-+$/u', '', $s);
    return $s ?: 'proekt-' . time();
}