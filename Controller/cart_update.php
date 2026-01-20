
<?php
require_once('../Model/Auth.php');
require_once('../Model/ProductModel.php');
start_session_safe();
require_role(['customer']);

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

foreach (($_POST['qty'] ?? []) as $pid => $qty) {
    $pid = (int)$pid;
    $qty = (int)$qty;
    if ($qty <= 0) {
        unset($_SESSION['cart'][$pid]);
        continue;
    }
    $p = get_product($pid);
    if (!$p || (int)$p['is_active'] !== 1 || (int)$p['is_approved'] !== 1) {
        unset($_SESSION['cart'][$pid]);
        continue;
    }
    $max = (int)$p['stock'];
    if ($qty > $max) $qty = $max;
    if ($max <= 0) {
        unset($_SESSION['cart'][$pid]);
        continue;
    }
    $_SESSION['cart'][$pid] = $qty;
}

header('Location: ../View/cart.php');
exit;
?>
