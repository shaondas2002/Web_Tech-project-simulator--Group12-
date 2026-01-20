<?php
require_once('../Model/Auth.php');
start_session_safe();
require_login();

$name = $_SESSION['user_name'];
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard - E-commerce Store</title>
    <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Dashboard</h2>
    <p>Welcome, <b><?php echo htmlspecialchars($name); ?></b></p>
    <p>Role: <b><?php echo htmlspecialchars($role); ?></b></p>

    <div class="card">
        <?php if ($role === 'customer'): ?>
            <a class="btn" href="products.php">Browse Products</a>
            <a class="btn" href="cart.php">Cart</a>
            <a class="btn" href="my_orders.php">My Orders</a>
        <?php elseif ($role === 'seller'): ?>
            <a class="btn" href="seller_products.php">My Products</a>
            <a class="btn" href="seller_orders.php">Orders for My Products</a>
        <?php elseif ($role === 'admin'): ?>
            <a class="btn" href="admin_users.php">Manage Users</a>
            <a class="btn" href="admin_products.php">Manage Products</a>
            <a class="btn" href="admin_orders.php">Manage Orders</a>
        <?php else: ?>
            <p>Unknown role.</p>
        <?php endif; ?>
    </div>

    <a class="btn" href="../Controller/logout.php">Logout</a>
</div>
</body>
</html>
