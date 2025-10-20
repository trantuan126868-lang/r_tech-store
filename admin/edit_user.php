<?php
session_start();
require '../config/config.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){
    header('Location: ../login.php'); exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $username, $email, $role, $id);
    if ($stmt->execute()) {
        header("Location: users.php?success=updated");
        exit;
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Quản lý đơn hàng</title>
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
<?php include 'includes/sidebar.php'; ?>
<main class="admin-main">
  <h1>Sửa người dùng</h1>
  <form method="post" style="max-width:400px">
    <label>Tên người dùng</label>
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <label>Vai trò</label>
    <select name="role">
      <option value="user" <?= $user['role']=='user'?'selected':'' ?>>User</option>
      <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
    </select>

    <button type="submit">Lưu thay đổi</button>
  </form>
</main>
</body>
</html>
