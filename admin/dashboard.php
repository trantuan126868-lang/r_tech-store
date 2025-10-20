<?php
require '../config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Hàm tiện ích để lấy dữ liệu thống kê
function getSingleValue($conn, $sql, $field) {
    $result = $conn->query($sql);
    if (!$result) {
        echo "<p style='color:red;'>Lỗi SQL: " . htmlspecialchars($conn->error) . "</p>";
        return 0;
    }
    $row = $result->fetch_assoc();
    return $row[$field] ?? 0;
}

// Lấy số lượng bảng
$stats = [
    'products' => getSingleValue($conn, "SELECT COUNT(*) AS total FROM products", 'total'),
    'posts'    => getSingleValue($conn, "SELECT COUNT(*) AS total FROM posts", 'total'),
    'users'    => getSingleValue($conn, "SELECT COUNT(*) AS total FROM users", 'total'),
    'orders'   => getSingleValue($conn, "SELECT COUNT(*) AS total FROM orders", 'total')
];

// Thống kê nâng cao
$q_ordered = getSingleValue($conn, "SELECT SUM(quantity) AS total_ordered FROM order_items", "total_ordered");
$q_revenue = getSingleValue($conn, "SELECT SUM(total) AS total_revenue FROM orders", "total_revenue");
$q_stock   = getSingleValue($conn, "SELECT SUM(quantity) AS total_stock FROM products", "total_stock");
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>R-TECH Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
      <h1><i class="fa-solid fa-gauge"></i> Bảng điều khiển</h1>
      <span class="admin-user">Xin chào, <b><?= htmlspecialchars($_SESSION['user']['username']) ?></b></span>
    </div>

    <div class="admin-stats">
      <div class="admin-card"><h3>Sản phẩm</h3><p><?= $stats['products'] ?></p></div>
      <div class="admin-card"><h3>Bài viết</h3><p><?= $stats['posts'] ?></p></div>
      <div class="admin-card"><h3>Người dùng</h3><p><?= $stats['users'] ?></p></div>
      <div class="admin-card"><h3>Đơn hàng</h3><p><?= $stats['orders'] ?></p></div>
    </div>

    <h2 style="margin-top:40px;">📊 Thống kê nâng cao</h2>
    <div class="admin-extra">
      <div class="admin-card"><h3>Tổng sản phẩm đã đặt</h3><p><?= number_format($q_ordered) ?></p></div>
      <div class="admin-card"><h3>Tổng doanh thu</h3><p><?= number_format($q_revenue, 0, ',', '.') ?> ₫</p></div>
      <div class="admin-card"><h3>Tổng hàng tồn</h3><p><?= number_format($q_stock) ?></p></div>
    </div>
  </main>
</div>
</body>
</html>
