<?php
require_once('../Model/Auth.php');
require_once('../Model/ProductModel.php');
start_session_safe();
require_role(['customer']);

$products = list_active_products();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Products</title>
  <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
<div class="container">
  <h2>Products</h2>
  <a class="btn" href="dashboard.php">Back</a>
  <a class="btn" href="cart.php">Cart</a>

  <?php if (empty($products)): ?>
    <p>No products available.</p>
  <?php else: ?>
    <table class="table">
      <tr>
        <th>Name</th><th>Price</th><th>Stock</th><th>Action</th>
      </tr>
      <?php foreach ($products as $p): ?>
        <tr>
          <td><?php echo htmlspecialchars($p['name']); ?></td>
          <td><?php echo number_format((float)$p['price'], 2); ?></td>
          <td><?php echo (int)$p['stock']; ?></td>
          <td>
            <form method="POST" action="../Controller/cart_add.php" style="display:flex;gap:8px;align-items:center;">
              <input type="hidden" name="product_id" value="<?php echo (int)$p['id']; ?>">
              <input data-qty-input="1" type="number" name="qty" value="1" min="1" max="<?php echo (int)$p['stock']; ?>" style="width:80px;">
              <button type="submit" <?php echo ((int)$p['stock']<=0)?'disabled':''; ?>>Add</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>
<script src="../Assets/js/app.js"></script>
</body>
</html>
