<?php
function is_admin() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return !empty($_SESSION['is_admin']);
}
function admin_login($pass) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (hash_equals(config()['admin_pass'], $pass)) { $_SESSION['is_admin'] = true; return true; }
    return false;
}
function admin_logout() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION = []; session_destroy();
}
function require_admin() {
    if (!is_admin()) { header('Location: index.php'); exit; }
}
function upload_image($field, $dest_dir) {
    if (empty($_FILES[$field]['name']) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return null;
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp'];
    if (!in_array($ext, $allowed)) return null;
    $name = 'p_' . time() . '_' . rand(100,999) . '.' . $ext;
    $path = $dest_dir . '/' . $name;
    if (move_uploaded_file($_FILES[$field]['tmp_name'], $path)) return '/assets/media/projects/' . $name;
    return null;
}
function slugify($s) {
    $s = mb_strtolower(trim($s), 'UTF-8');
    $s = preg_replace('/[^a-z0-9а-я]+/u', '-', $s);
    $s = preg_replace('/^-+|-+$/u', '', $s);
    return $s ?: 'proekt-' . time();
}