<?php
require_once('../Model/Auth.php');
require_once('../Model/ProductModel.php');
start_session_safe();
require_role(['seller']);

$seller_id = (int)$_SESSION['user_id'];
$products = list_seller_products($seller_id);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>My Products</title>
  <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
<div class="container">
  <h2>My Products</h2>
  <a class="btn" href="dashboard.php">Dashboard</a>
  <a class="btn" href="seller_product_form.php">Add Product</a>

  <?php if (empty($products)): ?>
    <p>No products yet.</p>
  <?php else: ?>
    <table class="table">
      <tr><th>Name</th><th>Price</th><th>Stock</th><th>Active</th><th></th></tr>
      <?php foreach ($products as $p): ?>
        <tr>
          <td><?php echo htmlspecialchars($p['name']); ?></td>
          <td><?php echo number_format((float)$p['price'],2); ?></td>
          <td><?php echo (int)$p['stock']; ?></td>
          <td><?php echo ((int)$p['is_active']===1)?'Yes':'No'; ?></td>
          <td>
            <a href="seller_product_form.php?id=<?php echo (int)$p['id']; ?>">Edit</a>
            |
            <a href="../Controller/seller_product_delete.php?id=<?php echo (int)$p['id']; ?>" onclick="return confirm('Delete this product?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>
</body>
</html>
