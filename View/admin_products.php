<?php
require_once('../Model/Auth.php');
require_once('../Model/ProductModel.php');
start_session_safe();
require_role(['admin']);

$products = list_all_products();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Products</title>
  <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
<div class="container">
  <h2>Manage Products</h2>
  <a class="btn" href="dashboard.php">Dashboard</a>

  <?php if (empty($products)): ?>
    <p>No products.</p>
  <?php else: ?>
    <table class="table">
      <tr><th>Name</th><th>Seller</th><th>Price</th><th>Stock</th><th>Active</th><th>Action</th></tr>
      <?php foreach ($products as $p): ?>
        <tr>
          <td><?php echo htmlspecialchars($p['name']); ?></td>
          <td><?php echo htmlspecialchars($p['seller_name']); ?></td>
          <td><?php echo number_format((float)$p['price'],2); ?></td>
          <td><?php echo (int)$p['stock']; ?></td>
          <td><?php echo ((int)$p['is_active']===1)?'Yes':'No'; ?></td>
          <td>
            <a href="../Controller/admin_toggle_product.php?id=<?php echo (int)$p['id']; ?>&to=<?php echo ((int)$p['is_active']===1)?0:1; ?>">
              Set <?php echo ((int)$p['is_active']===1)?'Inactive':'Active'; ?>
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>
</body>
</html>
