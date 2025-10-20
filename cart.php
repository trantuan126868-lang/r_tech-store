<?php
session_start();
require 'config/config.php';

// 🔒 Bắt buộc đăng nhập
if(!isset($_SESSION['user'])){
  header('Location: login.php');
  exit;
}

// --- Thêm vào giỏ hàng ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity'] ?? 1);

    // Lấy thông tin sản phẩm từ DB
    $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id=?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        // Nếu chưa có giỏ hàng thì khởi tạo
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

        // Nếu sản phẩm đã có -> tăng số lượng
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity
            ];
        }
    }

    header('Location: cart.php');
    exit;
}

// --- Xóa sản phẩm ---
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$remove_id]);
    header('Location: cart.php');
    exit;
}

include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Giỏ hàng</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<main class="cart-container">
  <h2>Giỏ hàng của bạn</h2><br>

  <?php if (empty($_SESSION['cart'])): ?>
    <br><p>Giỏ hàng trống. <a href="products.php">Tiếp tục mua sắm</a></p>
  <?php else: ?>
    <form method="post" action="checkout.php">
      <table class="cart-table">
        <thead>
          <tr>
            <th>Hình ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
            <th>Xóa</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $total = 0;
          foreach ($_SESSION['cart'] as $item): 
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
          ?>
          <tr>
            <td><img src="assets/<?= htmlspecialchars($item['image']) ?>" width="80"></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= number_format($item['price'],0,',','.') ?>đ</td>
            <td><?= $item['quantity'] ?></td>
            <td><?= number_format($subtotal,0,',','.') ?>đ</td>
            <td><a href="cart.php?remove=<?= $item['id'] ?>" class="remove">X</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="cart-total">
        <strong>Tổng cộng:</strong> <?= number_format($total,0,',','.') ?>đ
      </div>

      <button type="submit" class="btn-checkout">Thanh toán</button>
    </form>
  <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
