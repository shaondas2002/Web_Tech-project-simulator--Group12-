<?php
require_once('../Model/Auth.php');
require_once('../Model/OrderModel.php');
start_session_safe();
require_role(['seller']);

$seller_id = (int)$_SESSION['user_id'];
$items = list_seller_order_items($seller_id);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Orders for My Products</title>
  <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
<div class="container">
  <h2>Orders for My Products</h2>
  <a class="btn" href="dashboard.php">Dashboard</a>

  <?php if (empty($items)): ?>
    <p>No orders yet.</p>
  <?php else: ?>
    <table class="table">
      <tr><th>Order ID</th><th>Product</th><th>Qty</th><th>Item Status</th><th>Order Status</th><th>Date</th><th>Update</th></tr>
    <?php foreach ($items as $it): ?>
      <tr>
        <td>#<?php echo (int)$it['order_id']; ?></td>
        <td><?php echo htmlspecialchars($it['product_name']); ?></td>
        <td><?php echo (int)$it['qty']; ?></td>
        <td><?php echo htmlspecialchars($it['seller_status']); ?></td>
        <td><?php echo htmlspecialchars($it['order_status']); ?></td>
        <td><?php echo htmlspecialchars($it['created_at']); ?></td>
        <td>
          <form method="POST" action="../Controller/seller_item_status.php">
            <input type="hidden" name="item_id" value="<?php echo (int)$it['id']; ?>">
            <select name="status">
              <option value="new" <?php echo $it['seller_status']==='new'?'selected':''; ?>>new</option>
              <option value="accepted" <?php echo $it['seller_status']==='accepted'?'selected':''; ?>>accepted</option>
              <option value="shipped" <?php echo $it['seller_status']==='shipped'?'selected':''; ?>>shipped</option>
            </select>
            <button type="submit">Save</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>
</body>
</html>
