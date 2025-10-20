<?php
require '../config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$id = (int)$_GET['id'];
$category = $conn->query("SELECT * FROM categories WHERE id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if ($name !== '') {
        $stmt = $conn->prepare("UPDATE categories SET name=? WHERE id=?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        header("Location: categories.php");
        exit;
    } else {
        $error = "Tên danh mục không được để trống!";
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Quản lý đơn hàng</title>
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
    <div class="admin-header"><h1>Sửa danh mục</h1></div>

    <?php if (!empty($error)): ?>
      <p style="color:red;text-align:center;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" class="admin-form">
      <label>Tên danh mục</label>
      <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
      <button type="submit" class="admin-btn">Lưu thay đổi</button>
    </form>
  </main>
</div>
</body>
</html>
