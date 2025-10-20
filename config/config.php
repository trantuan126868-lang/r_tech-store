<?php
// config/config.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB config
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','r_tech_store');

// File upload config
define('UPLOAD_DIR', __DIR__ . '/../uploads/'); // thư mục lưu ảnh
define('UPLOAD_WEB', '/uploads/'); // đường dẫn web nếu cần

// Start session if not already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connect
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Kết nối DB thất bại: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
