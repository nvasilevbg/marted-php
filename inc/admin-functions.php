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
    if (hash_equals(config()['admin_pass'], $pass)) { $_SESSION['is_admin'] = true; return true; }
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
function upload_image($field, $dest_dir) {
    if (empty($_FILES[$field]['name']) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return null;
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp'];
    if (!in_array($ext, $allowed)) return null;
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES[$field]['tmp_name']);
    finfo_close($finfo);
    $allowedMimes = ['image/jpeg','image/png','image/webp'];
    if (!in_array($mime, $allowedMimes)) return null;
    $name = 'p_' . time() . '_' . rand(100,999) . '.' . $ext;
    $path = $dest_dir . '/' . $name;
    if (move_uploaded_file($_FILES[$field]['tmp_name'], $path)) return '/assets/media/projects/' . $name;
    return null;
}
function slugify($s) {
    $s = mb_strtolower(trim($s), 'UTF-8');
    $s = preg_replace('/[^a-z0-9--]+/u', '-', $s);
    $s = preg_replace('/^-+|-+$/u', '', $s);
    return $s ?: 'proekt-' . time();
}