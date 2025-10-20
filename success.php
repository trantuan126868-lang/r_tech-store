<?php
require '../config/config.php';
include '../includes/header.php';

// Lấy ID đơn hàng
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
?>
<style>
  .success-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 80px 20px;
    background: linear-gradient(135deg, #0d6efd, #00b4d8);
    min-height: 70vh;
  }
  .success-box {
    background: #fff;
    padding: 40px 60px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    text-align: center;
    max-width: 450px;
    animation: fadeInUp 0.7s ease-in-out;
  }
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .success-box i {
    font-size: 70px;
    color: #28a745;
    margin-bottom: 20px;
    animation: bounce 1.3s ease infinite alternate;
  }
  @keyframes bounce {
    from { transform: translateY(0); }
    to { transform: translateY(-10px); }
  }
  .success-box h1 {
    color: #222;
    font-size: 28px;
    margin-bottom: 10px;
  }
  .success-box p {
    font-size: 18px;
    color: #555;
    margin-bottom: 25px;
  }
  .success-box .order-id {
    display: inline-block;
    background: #0d6efd;
    color: #fff;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: bold;
    margin-bottom: 20px;
  }
  .success-box a {
    display: inline-block;
    background: #0d6efd;
    color: #fff;
    padding: 12px 24px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
  }
  .success-box a:hover {
    background: #084298;
  }
  .countdown {
    margin-top: 15px;
    font-size: 15px;
    color: #666;
  }
</style>

<div class="success-wrapper">
  <div class="success-box">
    <i class="fa-solid fa-circle-check"></i>
    <h1>Đặt hàng thành công!</h1>
    <p>Cảm ơn bạn đã mua hàng tại <strong>R-TECH</strong>.</p>
    <div class="order-id">Mã đơn hàng: #<?= $order_id ?></div>
    <a href="../index.php"><i class="fa-solid fa-house"></i> Về trang chủ</a>
    <p class="countdown">Tự động chuyển hướng sau <span id="timer">5</span> giây...</p>
  </div>
</div>

<script>
  let timeLeft = 5;
  const timerEl = document.getElementById('timer');
  const countdown = setInterval(() => {
    timeLeft--;
    timerEl.textContent = timeLeft;
    if (timeLeft <= 0) {
      clearInterval(countdown);
      window.location.href = "../index.php";
    }
  }, 1000);
</script>

<?php include '../includes/footer.php'; ?>
