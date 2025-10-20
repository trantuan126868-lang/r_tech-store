<?php
session_start();
require '../config/config.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){
    header('Location: ../login.php'); exit;
}

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Quản lý người dùng</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-wrapper">
  <aside class="admin-sidebar">
    <h2>
      <a href="../dashboard.php">
        <i class="fa-solid fa-microchip"></i>
        <span class="admin-logo">R-TECH</span>
      </a>
    </h2>
    <ul>
      <li><a href="dashboard.php"><i class="fa-solid fa-gauge"></i> Bảng điều khiển</a></li>
      <li><a href="categories.php"><i class="fa-solid fa-tags"></i> Danh mục</a></li>
      <li><a href="products.php"><i class="fa-solid fa-box"></i> Sản phẩm</a></li>
      <li><a href="posts.php"><i class="fa-solid fa-newspaper"></i> Bài viết</a></li>
      <li><a href="users.php" class="active"><i class="fa-solid fa-user"></i> Người dùng</a></li>
      <li><a href="orders.php"><i class="fa-solid fa-cart-shopping"></i> Đơn hàng</a></li>
      <li><a href="../logout.php" class="admin-btn danger"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
    </ul>
  </aside>

  <main class="admin-main">
    <div class="admin-header">
      <h1><i class="fa-solid fa-user"></i> Quản lý người dùng</h1>
      <a href="add_user.php" class="admin-btn primary">
        <i class="fa-solid fa-plus"></i> Thêm người dùng
      </a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tên người dùng</th>
          <th>Email</th>
          <th>Role</th>
          <th>Ngày tạo</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php if($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= $row['role'] ?></td>
              <td><?= $row['created_at'] ?></td>
              <td>
                <a href="edit_user.php?id=<?= $row['id'] ?>"><i class="fa-solid fa-pen"></i></a> |
                <a href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Xóa người dùng này?')"><i class="fa-solid fa-trash"></i></a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6">Không có người dùng nào</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>
</div>
</body>
</html>
