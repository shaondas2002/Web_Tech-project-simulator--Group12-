<?php
require_once('../Model/Auth.php');
require_once('../Model/ProductModel.php');
start_session_safe();
require_role(['customer']);

$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0.0;
if (is_array($cart)) {
    foreach ($cart as $pid => $qty) {
        $p = get_product((int)$pid);
        if (!$p || (int)$p['is_active'] !== 1 || (int)$p['is_approved'] !== 1) continue;
        $qty = (int)$qty;
        if ($qty <= 0) continue;
        $sub = (float)$p['price'] * $qty;
        $total += $sub;
        $items[] = ['p'=>$p, 'qty'=>$qty, 'sub'=>$sub];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Cart</title>
  <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
<div class="container">
  <h2>Your Cart</h2>
  <a class="btn" href="products.php">Continue Shopping</a>
  <a class="btn" href="dashboard.php">Dashboard</a>

  <?php if (empty($items)): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>
    <form method="POST" action="../Controller/cart_update.php">
      <table class="table">
        <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr>
        <?php foreach ($items as $it): $p=$it['p']; ?>
          <tr>
            <td><?php echo htmlspecialchars($p['name']); ?></td>
            <td><?php echo number_format((float)$p['price'],2); ?></td>
            <td><input data-qty-input="1" type="number" name="qty[<?php echo (int)$p['id']; ?>]" value="<?php echo (int)$it['qty']; ?>" min="1" max="<?php echo (int)$p['stock']; ?>" style="width:80px;"></td>
            <td><?php echo number_format((float)$it['sub'],2); ?></td>
            <td><a href="../Controller/cart_remove.php?product_id=<?php echo (int)$p['id']; ?>">Remove</a></td>
          </tr>
        <?php endforeach; ?>
      </table>

      <p><b>Total: <?php echo number_format($total,2); ?></b></p>

      <button type="submit">Update Cart</button>
      <a class="btn" href="../Controller/checkout.php">Checkout</a>
    </form>
  <?php endif; ?>
</div>
<script src="../Assets/js/app.js"></script>
</body>
</html>
