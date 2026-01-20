<?php
require_once('../Model/Auth.php');
require_once('../Model/OrderModel.php');

start_session_safe();
require_role(['admin']);

$orders = list_all_orders();
$allowed_status = ['placed','processing','shipped','delivered','cancelled'];
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Orders</title>
  <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
<div class="container">
  <h2>Manage Orders</h2>
  <a class="btn" href="dashboard.php">Dashboard</a>

  <?php if ($msg === 'updated'): ?>
    <p class="success">Order status updated.</p>
  <?php elseif ($msg === 'invalid'): ?>
    <p class="error">Invalid request.</p>
  <?php endif; ?>

  <?php if (empty($orders)): ?>
    <p>No orders yet.</p>
  <?php else: ?>
    <table class="table">
      <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Total</th>
        <th>Status</th>
        <th>Created</th>
        <th>Update</th>
      </tr>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td><?php echo (int)$o['id']; ?></td>
          <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
          <td><?php echo number_format((float)$o['total_amount'], 2); ?></td>
          <td><?php echo htmlspecialchars($o['status']); ?></td>
          <td><?php echo htmlspecialchars($o['created_at']); ?></td>
          <td>
            <form method="post" action="../Controller/admin_update_order_status.php" style="display:flex; gap:8px; align-items:center;">
              <input type="hidden" name="order_id" value="<?php echo (int)$o['id']; ?>">
              <select name="status">
                <?php foreach ($allowed_status as $st): ?>
                  <option value="<?php echo $st; ?>" <?php echo ($st === $o['status']) ? 'selected' : ''; ?>>
                    <?php echo $st; ?>
                  </option>
                <?php endforeach; ?>
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
