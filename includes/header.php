<?php
// require 'config/config.php'; // Đảm bảo đã được gọi ở index.php
// session_start();           // Đảm bảo đã được gọi ở index.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>R-TECH</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/auth.css"/>
    <link rel="stylesheet" href="assets/css/responsive.css" /> 
    <link rel="stylesheet" href="assets/css/product-detail.css"/>
<link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    </head>
<body>
    <div class="wrapper">
        <header>
            <nav>
                <div class="menu">
                    <ul class="menu-left">
                        <li>
                            <a href="index.php" style="display: flex; align-items: center; gap: 10px;">
                                <img src="assets/img/logort.png" alt="Logo">
                                <span style="font-size: 24px; font-weight: bold; color: #fff;"></span>
                            </a>
                        </li>

                        <li class="dropdown category-dropdown">
                            <a href="#" class="dropdown-toggle">
                                <i class="fas fa-list"></i> Danh mục
                            </a>
                            <ul class="dropdown-menu category-menu">
                                <li><a href="products.php">Tất cả sản phẩm</a></li>
                                <li><a href="#">Điện thoại</a></li>
                                <li><a href="#">Laptop</a></li>
                                <li><a href="#">Phụ kiện</a></li>
                                <li><a href="#">Đồng hồ</a></li>
                            </ul>
                        </li>
                    </ul>

                    <div class="search-container">
                        <form action="search.php" method="get">
                            <input type="text" name="query" placeholder="Bạn muốn mua gì hôm nay?" />
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>

                    <ul class="menu-right">
                        <li>
                            <a href="/WEB_R_TECH/cart.php" class="in-cart">
  Giỏ Hàng <i class="fa-solid fa-cart-shopping"></i>
</a>

                        </li>
                        <li class="dropdown">
  <?php if(isset($_SESSION['user'])): ?>
    <a href="#">
       Xin chào, <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong>
      <i class="fas fa-user-circle"></i>
    </a>
    <ul class="dropdown-menu">
      <li><a href="profile.php">Thông tin</a></li>
      <li><a href="checkout.php">Thanh toán</a></li>
      <li><a href="logout.php" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">Đăng xuất</a></li>
    </ul>
  <?php else: ?>
    <a href="#">Tài Khoản <i class="fas fa-user-circle"></i></a>
    <ul class="dropdown-menu">
      <li><a href="register.php">Đăng ký</a></li>
      <li><a href="login.php">Đăng nhập</a></li>
      <li><a href="#">Hỗ trợ</a></li>
    </ul>
  <?php endif; ?>
</li>

                    </ul>
                </div>
            </nav>
        </header>