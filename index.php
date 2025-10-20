<?php
// ✅ Chỉ khởi tạo session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'config/config.php';
include 'includes/header.php'; // Header đã mở <body> và <div class="wrapper">
?>

<main>
  <section class="hero" style="padding:20px;text-align:center">
    <img src="assets/img/bannertv.jpg" alt="banner" style="max-width:100%;height:300px;object-fit:cover">
  </section>

  <section class="product-container">
    <h2 class="product-title">Sản phẩm nổi bật</h2>
    <div class="grid">
      <?php
      // --- Lấy dữ liệu từ database ---
      $sql = "SELECT id, name, price, image FROM products ORDER BY created_at DESC LIMIT 8";
      $res = $conn->query($sql);

      if ($res && $res->num_rows > 0) {
          while ($row = $res->fetch_assoc()) {
              $image_src = 'assets/' . htmlspecialchars($row['image']);
              ?>
              <div class="product">
                  <img src="<?= $image_src ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                  <h3><?= htmlspecialchars($row['name']) ?></h3>
                  <p class="price"><?= number_format($row['price'], 0, ',', '.') ?>đ</p>
                  <div class="product-footer">
                      <a href="product_detail.php?id=<?= $row['id'] ?>">Xem chi tiết</a>
                      <button class="buy-btn">Mua ngay</button>
                  </div>
              </div>
          <?php
          }
      } else {
          echo '<p style="padding:20px;text-align:center;">Chưa có sản phẩm nào được thêm vào.</p>';
      }
      ?>
    </div>
  </section>
</main>

<script src="assets/js/slide.js"></script>
<script src="assets/js/submenu.js"></script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
