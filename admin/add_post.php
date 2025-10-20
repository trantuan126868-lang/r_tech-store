<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../config/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author = $_SESSION['user']['username'];
    $image = '';

    // ✅ Xử lý upload ảnh
    if (!empty($_FILES['image']['name'])) {
        $targetDir = '../uploads/posts/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $image = $fileName;
            } else {
                $message = "Không thể tải ảnh lên.";
            }
        } else {
            $message = "Định dạng ảnh không hợp lệ.";
        }
    }

    // ✅ Lưu vào DB
    if (empty($message)) {
        $stmt = $conn->prepare("INSERT INTO posts (title, image, content, author, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $title, $image, $content, $author);
        if ($stmt->execute()) {
            header("Location: posts.php");
            exit;
        } else {
            $message = "Lỗi khi lưu bài viết: " . htmlspecialchars($stmt->error);
        }
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Thêm Bài Viết</title>
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="admin-wrapper">
 <aside class="admin-sidebar">
    <h2>
      <a href="../admin/dashboard.php">
        <i class="fa-solid fa-microchip"></i>
        <span class="admin-logo">R-TECH</span>
      </a>
    </h2>
    <ul>
      <li><a href="dashboard.php" class="active"><i class="fa-solid fa-gauge"></i> Bảng điều khiển</a></li>
      <li><a href="categories.php"><i class="fa-solid fa-tags"></i> Danh mục</a></li>
      <li><a href="products.php"><i class="fa-solid fa-box"></i> Sản phẩm</a></li>
      <li><a href="posts.php"><i class="fa-solid fa-newspaper"></i> Bài viết</a></li>
      <li><a href="users.php"><i class="fa-solid fa-user"></i> Người dùng</a></li>
      <li><a href="orders.php"><i class="fa-solid fa-cart-shopping"></i> Đơn hàng</a></li>
      <li><a href="../logout.php" class="admin-btn danger"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
    </ul>
  </aside>

  <main class="admin-main">
    <h1>Thêm bài viết mới</h1>

    <?php if ($message): ?>
      <p style="color:red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="admin-form">
      <label>Tiêu đề</label>
      <input type="text" name="title" required>

      <label>Ảnh đại diện</label>
      <input type="file" name="image" accept="image/*">

      <label>Nội dung</label>
      <textarea name="content" rows="8" required></textarea>

      <button type="submit" class="admin-btn">Đăng bài</button>
    </form>
  </main>
</div>
</body>
</html>
