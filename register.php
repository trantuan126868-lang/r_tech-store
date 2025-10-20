<?php
session_start();
require 'config/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // ✅ Kiểm tra nhập trống
    if (empty($username) || empty($email) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ thông tin.";
    } else {
        // ✅ Regex mật khẩu mạnh
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/';
        if (!preg_match($pattern, $password)) {
            $error = "Mật khẩu phải ≥8 ký tự, có chữ hoa, chữ thường, số và ký tự đặc biệt.";
        } else {
            // ✅ Kiểm tra username hoặc email đã tồn tại chưa
            $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $check->bind_param("ss", $username, $email);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $error = "Tên đăng nhập hoặc email đã tồn tại!";
            } else {
                // ✅ Nếu chưa tồn tại → thêm user
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, "user")');
                $stmt->bind_param('sss', $username, $email, $hashed);

                if ($stmt->execute()) {
                    echo "<script>
                            alert('Đăng ký thành công!');
                            setTimeout(() => { window.location = 'login.php'; }, 1000);
                          </script>";
                    $success = "Đăng ký thành công!";
                } else {
                    $error = "Lỗi khi đăng ký: " . $conn->error;
                }

                $stmt->close();
            }
            $check->close();
        }
    }
}

include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng ký</title>
<link rel="stylesheet" href="assets/css/auth.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<main class="auth-container">
  <div class="auth-box">
    <h2>Đăng ký tài khoản</h2>

    <?php if($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if($success): ?>
      <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post" onsubmit="return validatePassword();">
      <input name="username" required placeholder="Tên đăng nhập"><br>
      <input name="email" type="email" required placeholder="Email"><br>

      <div class="password-field">
        <input type="password" name="password" id="password" required placeholder="Mật khẩu" autocomplete="new-password">
        <i id="togglePwd" class="fa-solid fa-eye"></i>
      </div>
      <small id="msg" class="error"></small><br>

      <button type="submit" class="btn-primary">Đăng ký</button>

      <p class="auth-switch">Bạn đã có tài khoản?
        <a href="login.php">Đăng nhập ngay</a>
      </p>
    </form>
  </div>
</main>

<script>
const togglePwd = document.getElementById('togglePwd');
const pwdInput = document.getElementById('password');

togglePwd.addEventListener('click', () => {
  const type = pwdInput.type === 'password' ? 'text' : 'password';
  pwdInput.type = type;
  togglePwd.classList.toggle('fa-eye');
  togglePwd.classList.toggle('fa-eye-slash');
});

function validatePassword() {
  const pw = document.getElementById('password').value.trim();
  const msg = document.getElementById('msg');
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;

  if (!regex.test(pw)) {
    msg.textContent = "Mật khẩu phải ≥8 ký tự, có hoa, thường, số và ký tự đặc biệt.";
    return false;
  }
  msg.textContent = "";
  return true;
}
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
