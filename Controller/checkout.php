
<?php
require_once('../Model/Auth.php');
require_once('../Model/OrderModel.php');
start_session_safe();
require_role(['customer']);

$cart = $_SESSION['cart'] ?? [];
$order_id = create_order((int)$_SESSION['user_id'], is_array($cart) ? $cart : []);

if ($order_id !== null) {
    $_SESSION['cart'] = [];
    $_SESSION['checkout_msg'] = "Order placed! Your order ID is #{$order_id}.";
} else {
    $_SESSION['checkout_msg'] = "Checkout failed. Please check stock and try again.";
}

header('Location: ../View/my_orders.php');
exit;
?>
