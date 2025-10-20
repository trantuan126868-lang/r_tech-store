<?php
session_start();
require 'config/config.php';

// Tắt hiển thị lỗi ra trình duyệt (chỉ bật khi dev)
error_reporting(E_ALL);
ini_set('display_errors', 0);

$error = '';

// Nếu user đã đăng nhập thì chuyển hướng luôn
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

// Khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Kiểm tra input
    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
    } 
    // Admin đặc biệt
    elseif ($username === 'admin' && $password === 'admin2006') {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => 0, 'username' => 'admin', 'role' => 'admin'];
        header('Location: admin/dashboard.php');
        exit;
    } 
    // Kiểm tra độ mạnh mật khẩu với user thường
    elseif (strlen($password) < 8) {
        $error = 'Mật khẩu phải có ít nhất 8 ký tự.';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = 'Mật khẩu phải chứa ít nhất 1 chữ hoa (A-Z).';
    } elseif (!preg_match('/[a-z]/', $password)) {
        $error = 'Mật khẩu phải chứa ít nhất 1 chữ thường (a-z).';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = 'Mật khẩu phải chứa ít nhất 1 chữ số (0-9).';
    } elseif (!preg_match('/[\W_]/', $password)) {
        $error = 'Mật khẩu phải chứa ít nhất 1 ký tự đặc biệt (!@#$%^&*...).';
    } 
    else {
        // Chuẩn bị truy vấn an toàn
        $stmt = $conn->prepare('SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res && $row = $res->fetch_assoc()) {
                if (password_verify($password, $row['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'id' => $row['id'],
                        'username' => $row['username'],
                        'role' => $row['role']
                    ];
                    header('Location: index.php');
                    exit;
                }
            }
            $error = 'Sai tên đăng nhập hoặc mật khẩu.';
        } else {
            $error = 'Lỗi hệ thống, vui lòng thử lại sau.';
        }
    }
}
include 'includes/header.php';

?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng nhập</title>
<link rel="stylesheet" href="assets/css/auth.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
<main class="auth-container">
  <div class="auth-box">
    <h2>Đăng nhập</h2>

    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" autocomplete="off">
      <input name="username" required placeholder="Tên đăng nhập" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"><br>

      <div class="password-field">
        <input id="passwordInput" type="password" name="password" required placeholder="Mật khẩu" minlength="8"
               title="Tối thiểu 8 ký tự, có chữ hoa, chữ thường, số và ký tự đặc biệt">
        <i id="toggleIcon" class="fa-solid fa-eye"></i>
      </div><br>

      <button type="submit" class="btn-primary">Đăng nhập</button>

      <p class="auth-switch">
        Bạn chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
      </p>
    

    </form>
  </div>
</main>

<script>
const pwdInput = document.getElementById('passwordInput');
const toggleIcon = document.getElementById('toggleIcon');

toggleIcon.addEventListener('click', () => {
  const isHidden = pwdInput.type === 'password';
  pwdInput.type = isHidden ? 'text' : 'password';
  toggleIcon.classList.toggle('fa-eye');
  toggleIcon.classList.toggle('fa-eye-slash');
});
</script>
<?php include 'includes/footer.php'; ?>
</body>
</html>
