<?php
require 'config/config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}
header('Location: cart.php');
exit();
