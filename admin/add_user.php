<?php
session_start();
require '../config/config.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){
    header('Location: ../login.php'); exit;
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    if($username && $email && $_POST['password']){
        $stmt = $conn->prepare("INSERT INTO users (username,email,password,role,created_at) VALUES (?,?,?,?,NOW())");
        $stmt->bind_param("ssss",$username,$email,$password,$role);
        if($stmt->execute()){
            header("Location: users.php?msg=added");
            exit;
        } else {
            $error = "Lỗi khi thêm người dùng: " . htmlspecialchars($stmt->error);
        }
    } else {
        $error = "Vui lòng điền đầy đủ thông tin!";
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Thêm người dùng</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-wrapper">
  <aside class="admin-sidebar">
    <h2>
      <a href="../dashboard.php">
        <i class="fa-solid fa-microchip"></i>
        <span class="admin-logo">R-TECH</span>
      </a>
    </h2>
    <ul>
      <li><a href="dashboard.php"><i class="fa-solid fa-gauge"></i> Bảng điều khiển</a></li>
      <li><a href="categories.php"><i class="fa-solid fa-tags"></i> Danh mục</a></li>
      <li><a href="products.php"><i class="fa-solid fa-box"></i> Sản phẩm</a></li>
      <li><a href="posts.php"><i class="fa-solid fa-newspaper"></i> Bài viết</a></li>
      <li><a href="users.php" class="active"><i class="fa-solid fa-user"></i> Người dùng</a></li>
      <li><a href="orders.php"><i class="fa-solid fa-cart-shopping"></i> Đơn hàng</a></li>
      <li><a href="../logout.php" class="admin-btn danger"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
    </ul>
  </aside>

  <main class="admin-main">
    <h1><i class="fa-solid fa-user-plus"></i> Thêm người dùng</h1>

    <?php if(isset($error)): ?>
      <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="admin-form">
      <label>Tên người dùng:</label>
      <input type="text" name="username" required>

      <label>Email:</label>
      <input type="email" name="email" required>

      <label>Mật khẩu:</label>
      <input type="password" name="password" required>

      <label>Quyền:</label>
      <select name="role" required>
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select>

      <button type="submit" class="admin-btn primary">
        <i class="fa-solid fa-check"></i> Thêm người dùng
      </button>
      <a href="users.php" class="admin-btn danger"><i class="fa-solid fa-xmark"></i> Hủy</a>
    </form>
  </main>
</div>
</body>
</html>
