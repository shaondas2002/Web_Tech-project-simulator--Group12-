<?php
require_once('../Model/Auth.php');
require_once('../Model/OrderModel.php');

start_session_safe();
require_role(['admin']);

$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';

if ($order_id <= 0 || $status === '') {
    header('Location: ../View/admin_orders.php?msg=invalid');
    exit;
}

$ok = set_order_status($order_id, $status);
header('Location: ../View/admin_orders.php?msg=' . ($ok ? 'updated' : 'invalid'));
exit;
