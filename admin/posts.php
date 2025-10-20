<?php
session_start();
require '../config/config.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){
  header('Location: ../login.php'); exit;
}
$res = $conn->query("SELECT id, title, author, created_at FROM posts ORDER BY id DESC");
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Quản lý bài viết</title>
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
    <div class="admin-header">
      <h1>Quản lý bài viết</h1>
      <a href="add_post.php" class="admin-btn">+ Thêm bài viết</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tiêu đề</th>
          <th>Tác giả</th>
          <th>Ngày đăng</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php while($r = $res->fetch_assoc()): ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td><?= htmlspecialchars($r['title']) ?></td>
          <td><?= htmlspecialchars($r['author']) ?></td>
          <td><?= htmlspecialchars($r['created_at']) ?></td>
          <td>
            <a href="post_edit.php?id=<?= $r['id'] ?>">Sửa</a> |
            <a href="post_delete.php?id=<?= $r['id'] ?>" onclick="return confirm('Xóa bài viết này?')">Xóa</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>
</body>
</html>
