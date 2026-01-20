<?php
require_once('../Model/Auth.php');
start_session_safe();
require_role(['customer']);

$pid = (int)($_GET['product_id'] ?? 0);
if (isset($_SESSION['cart'][$pid])) {
    unset($_SESSION['cart'][$pid]);
}
header('Location: ../View/cart.php');
exit;
?>
