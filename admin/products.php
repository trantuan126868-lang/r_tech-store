<?php
session_start();
require '../config/config.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){
  header('Location: ../login.php');
  exit;
}

// Lấy danh sách sản phẩm, có cột quantity
$result = $conn->query("SELECT id, name, price, quantity, image, created_at FROM products ORDER BY id DESC");
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Quản lý sản phẩm</title>
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
      <h1>Quản lý sản phẩm</h1>
      <a href="add_product.php" class="admin-btn">+ Thêm sản phẩm</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Hình ảnh</th>
          <th>Tên sản phẩm</th>
          <th>Giá</th>
          <th>Số lượng</th>
          <th>Ngày thêm</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><img src="../assets/<?= htmlspecialchars($row['image']) ?>" width="60" alt="<?= htmlspecialchars($row['name']) ?>"></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= number_format($row['price'],0,',','.') ?>đ</td>
          <td><?= (int)$row['quantity'] ?></td>
          <td><?= htmlspecialchars($row['created_at']) ?></td>
          <td>
            <a href="product_edit.php?id=<?= $row['id'] ?>">Sửa</a> |
            <a href="product_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>
</body>
</html>
