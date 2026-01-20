<?php
require_once('../Model/Auth.php');
require_once('../Model/OrderModel.php');
start_session_safe();
require_role(['seller']);

$item_id = (int)($_POST['item_id'] ?? 0);
$status = (string)($_POST['status'] ?? 'new');
if ($item_id > 0) {
    set_seller_item_status((int)$_SESSION['user_id'], $item_id, $status);
}
header('Location: ../View/seller_orders.php');
exit;
?>
