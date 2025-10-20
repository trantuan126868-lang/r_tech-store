<?php
require '../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Kiểm tra quyền admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// ✅ Kiểm tra ID danh mục hợp lệ
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: categories.php');
    exit;
}

$id = (int) $_GET['id'];

// ✅ Kiểm tra danh mục có tồn tại không
$check = $conn->prepare("SELECT id FROM categories WHERE id = ?");
$check->bind_param("i", $id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    // Không tồn tại
    $_SESSION['message'] = "Danh mục không tồn tại!";
    $_SESSION['message_type'] = "error";
    header('Location: categories.php');
    exit;
}
$check->close();

// ✅ Xoá danh mục
$stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['message'] = "Xóa danh mục thành công!";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Lỗi khi xóa danh mục: " . $conn->error;
    $_SESSION['message_type'] = "error";
}

$stmt->close();
$conn->close();

// ✅ Quay lại trang danh mục
header('Location: categories.php');
exit;
?>
