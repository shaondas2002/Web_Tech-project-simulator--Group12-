<?php
require_once('../Model/Auth.php');
require_once('../Model/ProductModel.php');
start_session_safe();
require_role(['customer']);

$product_id = (int)($_POST['product_id'] ?? 0);
$qty = (int)($_POST['qty'] ?? 1);
if ($qty < 1) $qty = 1;

$p = get_product($product_id);
if (!$p || (int)$p['is_active'] !== 1 || (int)$p['is_approved'] !== 1) {
    header('Location: ../View/products.php');
    exit;
}

$max = (int)$p['stock'];
if ($qty > $max) $qty = $max;
if ($max <= 0) {
    header('Location: ../View/products.php');
    exit;
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$current = (int)($_SESSION['cart'][$product_id] ?? 0);
$new = $current + $qty;
if ($new > $max) $new = $max;
$_SESSION['cart'][$product_id] = $new;

header('Location: ../View/cart.php');
exit;
?>
