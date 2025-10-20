<?php
session_start();
require '../config/config.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){
    header('Location: ../login.php'); exit;
}

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header('Location: users.php?error=invalid'); exit;
}

$id = intval($_GET['id']);

// Không cho xóa chính mình
if ($_SESSION['user']['id'] == $id) {
    header('Location: users.php?error=self_delete'); exit;
}

// Không cho xóa admin khác
$check = $conn->query("SELECT role FROM users WHERE id=$id")->fetch_assoc();
if ($check && $check['role'] === 'admin') {
    header('Location: users.php?error=admin_protect'); exit;
}

$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header('Location: users.php?success=deleted');
exit;
