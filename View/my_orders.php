
<?php
require_once('../Model/Auth.php');
require_once('../Model/OrderModel.php');
start_session_safe();
require_role(['customer']);

$msg = $_SESSION['checkout_msg'] ?? '';
unset($_SESSION['checkout_msg']);

$orders = list_customer_orders((int)$_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>My Orders</title>
  <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
<div class="container">
  <h2>My Orders</h2>
  <a class="btn" href="dashboard.php">Dashboard</a>
  <a class="btn" href="products.php">Products</a>

  <?php if ($msg !== ''): ?>
    <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <?php if (empty($orders)): ?>
    <p>No orders yet.</p>
  <?php else: ?>
    <table class="table">
      <tr><th>Order ID</th><th>Total</th><th>Status</th><th>Date</th></tr>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td>#<?php echo (int)$o['id']; ?></td>
          <td><?php echo number_format((float)$o['total_amount'],2); ?></td>
          <td><?php echo htmlspecialchars($o['status']); ?></td>
          <td><?php echo htmlspecialchars($o['created_at']); ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>
</body>
</html>
