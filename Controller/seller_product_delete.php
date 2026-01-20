<?php
require_once('../Model/Auth.php');
require_once('../Model/ProductModel.php');
start_session_safe();
require_role(['seller']);

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    delete_product((int)$_SESSION['user_id'], $id);
}
header('Location: ../View/seller_products.php');
exit;
?>
