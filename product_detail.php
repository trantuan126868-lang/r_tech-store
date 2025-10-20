<?php
session_start();
require 'config/config.php';
include 'includes/header.php';

// --- Kiểm tra ID sản phẩm ---
if(!isset($_GET['id'])) { 
  header('Location: products.php'); 
  exit(); 
}
$id = intval($_GET['id']);

// --- Lấy sản phẩm theo ID ---
$query = 'SELECT * FROM products WHERE id=?';
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("❌ Lỗi prepare SQL (lấy sản phẩm): " . $conn->error . "<br>❗ Câu lệnh: " . $query);
}

$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows == 0){ 
  echo 'Sản phẩm không tồn tại'; 
  exit(); 
}
$p = $res->fetch_assoc();


// --- Lấy sản phẩm liên quan (cùng category, khác id) ---
$rel_sql = 'SELECT id, name, image, price FROM products WHERE category=? AND id<>? LIMIT 4';
$rel = $conn->prepare($rel_sql);
if (!$rel) {
    die("❌ Lỗi SQL sản phẩm liên quan: " . $conn->error . "<br>❗ Câu lệnh: " . $rel_sql);
}
$rel->bind_param('si', $p['category'], $id);
$rel->execute();
$related = $rel->get_result();

// --- Lấy bình luận ---
$cmt = $conn->prepare('SELECT username, content, created_at FROM comments WHERE product_id=? ORDER BY created_at DESC');
$cmt->bind_param('i', $id);
$cmt->execute();
$comments = $cmt->get_result();

// --- Gửi bình luận ---
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['comment'])){
    $username = $_SESSION['user']['username'] ?? 'Khách';
    $content = trim($_POST['comment']);
    if($content!==''){
        $add = $conn->prepare('INSERT INTO comments(product_id, username, content, created_at) VALUES(?,?,?,NOW())');
        $add->bind_param('iss',$id,$username,$content);
        $add->execute();
        header("Location: product-detail.php?id=".$id);
        exit;
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset='utf-8'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($p['name']) ?></title>
  <link rel="stylesheet" href="assets/css/product-detail.css">
  <script src="https://kit.fontawesome.com/a2d04b0c4b.js" crossorigin="anonymous"></script>
</head>
<body>

<main class="product-detail">
  <div class="product-image">
    <!-- ✅ Ảnh sản phẩm chính -->
    <img src="assets/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
  </div>

  <div class="product-info">
    <h1><?= htmlspecialchars($p['name']) ?></h1>

    <div class="product-meta">
      <div class="rating">
        ⭐⭐⭐⭐☆ <span class="rating-text">(4.0/5 - 128 đánh giá)</span>
      </div>
      <button class="like-btn"><i class="fa-solid fa-heart"></i> <span>Thích</span></button>
    </div>

    <p class="product-price"><?= number_format($p['price'],0,',','.') ?>đ</p>
    <p class="product-description"><?= nl2br(htmlspecialchars($p['description'])) ?></p>

    <div class="action-buttons">
      <form method="post" action="cart.php">
        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
        <input type="number" name="quantity" value="1" min="1">
        <button type="submit" name="add" class="btn-cart">🛒 Thêm vào giỏ</button>
      </form>
      <button class="btn-buy">Mua ngay</button>
    </div>
  </div>
</main>

<!-- ===== BÌNH LUẬN ===== -->
<section class="comments-section">
  <h2>Bình luận</h2>
  <form method="post" class="comment-form">
    <textarea name="comment" placeholder="Nhập bình luận của bạn..." required></textarea>
    <button type="submit">Gửi</button>
  </form>

  <div class="comment-list">
    <?php if($comments->num_rows>0): ?>
      <?php while($c=$comments->fetch_assoc()): ?>
        <div class="comment-item">
          <strong><?= htmlspecialchars($c['username']) ?></strong>
          <span class="time"><?= htmlspecialchars($c['created_at']) ?></span>
          <p><?= nl2br(htmlspecialchars($c['content'])) ?></p>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Chưa có bình luận nào.</p>
    <?php endif; ?>
  </div>
</section>

<!-- ===== SẢN PHẨM LIÊN QUAN ===== -->
<section class="related-section">
  <h2>Sản phẩm liên quan</h2>
  <div class="related-list">
    <?php if($related->num_rows > 0): ?>
      <?php while($r = $related->fetch_assoc()): ?>
        <a href="product-detail.php?id=<?= $r['id'] ?>" class="related-item">
          <!-- ✅ Ảnh sản phẩm liên quan -->
          <img src="assets/<?= htmlspecialchars($r['image']) ?>" alt="<?= htmlspecialchars($r['name']) ?>">
          <h3><?= htmlspecialchars($r['name']) ?></h3>
          <p><?= number_format($r['price'],0,',','.') ?>đ</p>
        </a>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Không có sản phẩm liên quan.</p>
    <?php endif; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<script>
document.querySelector('.like-btn')?.addEventListener('click', e=>{
  e.currentTarget.classList.toggle('liked');
});
</script>
</body>
</html>
