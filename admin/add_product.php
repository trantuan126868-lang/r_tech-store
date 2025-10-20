<?php
require '../config/config.php';
require '../includes/functions.php';

require_admin(); // chỉ admin được thêm

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');

    if ($name === '') $errors[] = 'Tên sản phẩm bắt buộc.';
    if ($price <= 0) $errors[] = 'Giá phải lớn hơn 0.';
    if ($quantity < 0) $errors[] = 'Số lượng không hợp lệ.';

    $imgResult = ['success' => false];
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $imgResult = handle_image_upload('image', 3 * 1024 * 1024);
        if (!$imgResult['success']) $errors[] = 'Ảnh: ' . $imgResult['error'];
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('INSERT INTO products (name, description, price, quantity, image, category, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
        $imgName = $imgResult['success'] ? $imgResult['path'] : null;
        $stmt->bind_param('ssdi ss', $name, $description, $price, $quantity, $imgName, $category);
        if ($stmt->execute()) {
            flash('success', 'Thêm sản phẩm thành công.');
            header('Location: products.php');
            exit;
        } else {
            $errors[] = 'Lỗi DB: ' . $conn->error;
        }
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Thêm Sản Phẩm</title>
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
</head>
<body>
  <div class="container">
    <div class="add-product-form">
      <h1>Thêm sản phẩm mới</h1>

      <?php foreach ($errors as $e): ?>
        <p style="color:red; text-align:center;"><?= esc($e) ?></p>
      <?php endforeach; ?>

      <form method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label>Tên sản phẩm</label>
          <input name="name" value="<?= esc($_POST['name'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label>Giá</label>
          <input name="price" type="number" min="0" step="0.01" value="<?= esc($_POST['price'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label>Số lượng</label>
          <input name="quantity" type="number" min="0" value="<?= esc($_POST['quantity'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label>Danh mục</label>
          <input name="category" value="<?= esc($_POST['category'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label>Mô tả</label>
          <textarea name="description"><?= esc($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label>Ảnh</label>
          <input type="file" name="image" accept="image/*" id="imageInput">
          <div id="previewContainer" style="margin-top:10px;"></div>
        </div>

        <div class="form-actions">
          <button type="submit">Thêm</button>
          <a href="products.php" class="cancel-btn">Hủy</a>
        </div>
      </form>
    </div>
  </div>

  <script>
  // Xem trước ảnh trước khi upload
  const input = document.getElementById('imageInput');
  const preview = document.getElementById('previewContainer');
  input?.addEventListener('change', e => {
    const file = e.target.files[0];
    if (file) {
      const img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      img.style.maxWidth = '100%';
      img.style.borderRadius = '8px';
      img.style.marginTop = '8px';
      preview.innerHTML = '';
      preview.appendChild(img);
    }
  });
  </script>
</body>
</html>
