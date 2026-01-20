<?php
require_once('../Model/Auth.php');
require_once('../Model/ProductModel.php');
start_session_safe();
require_role(['seller']);

function back(string $msg): void {
    $_SESSION['seller_product_msg'] = $msg;
    header('Location: ../View/seller_products.php');
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : null;
$name = trim((string)($_POST['name'] ?? ''));
$desc = trim((string)($_POST['description'] ?? ''));
$price = (float)($_POST['price'] ?? 0);
$stock = (int)($_POST['stock'] ?? 0);

if ($name === '' || $price <= 0 || $stock < 0) {
    back('Please provide valid product data.');
}

$seller_id = (int)$_SESSION['user_id'];

if (save_product($seller_id, $id ?: null, $name, $desc, $price, $stock)) {
    header('Location: ../View/seller_products.php');
    exit;
}

back('Save failed.');
?>
