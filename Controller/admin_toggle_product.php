<?php
require_once('../Model/Auth.php');
require_once('../Model/ProductModel.php');

start_session_safe();
require_role(['admin']);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$to = isset($_GET['to']) ? (int)$_GET['to'] : -1;
if ($id <= 0 || ($to !== 0 && $to !== 1)) {
    header('Location: ../View/admin_products.php');
    exit;
}

admin_set_product_active($id, $to);
header('Location: ../View/admin_products.php');
exit;
