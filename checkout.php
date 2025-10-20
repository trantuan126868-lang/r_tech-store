<?php
session_start();
require 'config/config.php';

if(!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
if(empty($_SESSION['cart'])) { header('Location: cart.php'); exit; }

if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['name']);
  $phone = trim($_POST['phone']);
  $address = trim($_POST['address']);
  $user_id = $_SESSION['user']['id'] ?? null;
  $total = 0;
  foreach($_SESSION['cart'] as $it) $total += $it['price'] * $it['quantity'];

  // Kiểm tra tồn kho
  foreach ($_SESSION['cart'] as $it) {
      $check = $conn->prepare("SELECT quantity FROM products WHERE id=?");
      $check->bind_param('i', $it['id']);
      $check->execute();
      $res = $check->get_result()->fetch_assoc();
      if ($res['quantity'] < $it['quantity']) {
          echo "<script>alert('Sản phẩm {$it['name']} không đủ hàng!'); window.location='cart.php';</script>";
          exit;
      }
  }

  // Thêm đơn hàng
  $stmt = $conn->prepare('INSERT INTO orders (user_id,name,phone,address,total) VALUES (?,?,?,?,?)');
  $stmt->bind_param('isssd',$user_id,$name,$phone,$address,$total);
  $stmt->execute();
  $order_id = $stmt->insert_id;

  // Thêm chi tiết đơn hàng
  $stmt_item = $conn->prepare('INSERT INTO order_items (order_id,product_id,quantity,price) VALUES (?,?,?,?)');
  foreach($_SESSION['cart'] as $it){
    $stmt_item->bind_param('iiid',$order_id,$it['id'],$it['quantity'],$it['price']);
    $stmt_item->execute();
  }

  // Cập nhật số lượng tồn kho
  $update_stock = $conn->prepare('UPDATE products SET quantity = quantity - ? WHERE id = ?');
  foreach($_SESSION['cart'] as $it){
    $update_stock->bind_param('ii', $it['quantity'], $it['id']);
    $update_stock->execute();
  }

  unset($_SESSION['cart']);
  header('Location: success.php?order_id='.$order_id);
  exit;
}

include 'includes/header.php';
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Thanh toán</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<main style="padding:20px">
<h2>Thanh toán</h2>
<form method="post">
  <input name="name" required placeholder="Họ tên"><br><br>
  <input name="phone" required placeholder="Số điện thoại"><br><br>
  <textarea name="address" required placeholder="Địa chỉ nhận hàng"></textarea><br><br>
  <button type="submit">Xác nhận đặt hàng</button>
</form>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>
