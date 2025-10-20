<?php
require '../config/config.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// ✅ Thống kê sản phẩm đã bán
$soldQuery = $conn->query("SELECT SUM(quantity) AS sold FROM order_items");
$sold = $soldQuery->fetch_assoc()['sold'] ?? 0;

// ✅ Thống kê doanh thu
$revenueQuery = $conn->query("SELECT SUM(total) AS revenue FROM orders");
$revenue = $revenueQuery->fetch_assoc()['revenue'] ?? 0;

// ✅ Thống kê hàng tồn kho
$stockQuery = $conn->query("SELECT SUM(quantity) AS stock FROM products");
$stock = $stockQuery->fetch_assoc()['stock'] ?? 0;
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Bảng điều khiển - R-TECH Admin</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="admin-sidebar">
  <h2>R-TECH Admin</h2>
  <ul>
    <li><a href="index.php" class="active">Bảng điều khiển</a></li>
    <li><a href="categories.php">Danh mục</a></li>
    <li><a href="products.php">Sản phẩm</a></li>
    <li><a href="users.php">Người dùng</a></li>
    <li><a href="orders.php">Đơn hàng</a></li>
    <li><a href="stats.php">Thống kê</a></li>
    <li><a href="../logout.php" class="admin-btn danger">Đăng xuất</a></li>
  </ul>
</div>

<main class="admin-main">
  <div class="admin-header">
    <h1>Thống kê tổng quan</h1>
  </div>

  <div class="stats-grid">
    <div class="stats-card">
      <h3>🛒 Sản phẩm đã bán</h3>
      <p><?= (int)$sold ?></p>
    </div>

    <div class="stats-card">
      <h3>💰 Doanh thu</h3>
      <p><?= number_format($revenue, 0, ',', '.') ?>đ</p>
    </div>

    <div class="stats-card">
      <h3>📦 Hàng tồn kho</h3>
      <p><?= (int)$stock ?></p>
    </div>
  </div>
</main>

</body>
</html>
