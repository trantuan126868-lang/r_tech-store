<?php
require 'config/config.php';
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head><meta charset="utf-8"><title>Sản phẩm</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<main>
  <div style="padding:20px"><h1>Sản phẩm</h1>
  <div class="grid">
<?php
$res = $conn->query("SELECT * FROM products ORDER BY id DESC");
if($res){
  while($row=$res->fetch_assoc()){
    echo '<div class="product">';
    echo '<img src="'.esc($row['image']).'">';
    echo '<h3>'.esc($row['name']).'</h3>';
    echo '<p class="desc">'.esc($row['description']).'</p>';
    echo '<div class="product-footer"><p class="price">'.number_format($row['price'],0,',','.').'đ</p><a href="product_detail.php?id='.$row['id'].'">Xem</a></div>';
    echo '</div>';
  }
}
?>
  </div></div>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>
