<?php
// includes/functions.php
require_once __DIR__ . '/../config/config.php';

// Escape output
function esc($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

// Flash messages
function flash($key, $msg = null) {
    if ($msg === null) {
        if(isset($_SESSION['flash'][$key])) {
            $m = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $m;
        }
        return null;
    } else {
        $_SESSION['flash'][$key] = $msg;
    }
}

// Auth helpers
function is_logged_in() {
    return !empty($_SESSION['user']);
}
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
function require_admin() {
    if (!is_logged_in() || ($_SESSION['user']['role'] ?? '') !== 'admin') {
        header('HTTP/1.1 403 Forbidden');
        echo 'Bạn không có quyền truy cập.';
        exit;
    }
}

// Secure file upload for images
function handle_image_upload($fileInputName, $maxBytes = 2*1024*1024) {
    if (empty($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success'=>false, 'error'=>'No file'];
    }
    $f = $_FILES[$fileInputName];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        return ['success'=>false, 'error'=>'Upload lỗi: '.$f['error']];
    }
    if ($f['size'] > $maxBytes) {
        return ['success'=>false, 'error'=>'Kích thước file quá lớn'];
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($f['tmp_name']);
    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif','image/webp'=>'webp'];
    if (!isset($allowed[$mime])) {
        return ['success'=>false, 'error'=>'Định dạng file không hợp lệ'];
    }
    // generate filename
    $ext = $allowed[$mime];
    $name = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    $dest = UPLOAD_DIR . $name;
    if (!move_uploaded_file($f['tmp_name'], $dest)) {
        return ['success'=>false, 'error'=>'Không thể lưu file'];
    }
    return ['success'=>true, 'path'=>$name];
}
