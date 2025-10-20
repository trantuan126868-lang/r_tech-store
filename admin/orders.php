<?php
require '../config/config.php';

// ✅ Chỉ khởi tạo session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Chặn người không phải admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// ✅ Truy vấn danh sách đơn hàng
$sql = "SELECT id, user_id, name, phone, address, total, created_at FROM orders ORDER BY created_at DESC";
$result = $conn->query($sql);

// ✅ Kiểm tra lỗi SQL
if (!$result) {
    die("<h3 style='color:red;text-align:center'>Lỗi SQL: " . htmlspecialchars($conn->error) . "</h3>");
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
    <div class="admin-header">
      <h1>Quản lý đơn hàng</h1>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>ID Người dùng</th>
          <th>Tên khách</th>
          <th>Số điện thoại</th>
          <th>Địa chỉ</th>
          <th>Tổng tiền</th>
          <th>Ngày tạo</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['user_id']) ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['phone']) ?></td>
              <td><?= htmlspecialchars($row['address']) ?></td>
              <td><?= number_format($row['total'], 0, ',', '.') ?>đ</td>
              <td><?= htmlspecialchars($row['created_at']) ?></td>
              <td>
                <a href="order_detail.php?id=<?= $row['id'] ?>">Xem</a> |
                <a href="delete_order.php?id=<?= $row['id'] ?>" onclick="return confirm('Xóa đơn hàng này?')">Xóa</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8" style="text-align:center;">Không có đơn hàng nào.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>
</div>
</body>
</html>
