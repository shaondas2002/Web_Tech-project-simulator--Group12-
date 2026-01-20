<?php
require_once("../Model/Auth.php");
require_once("../Model/ProductModel.php");
start_session_safe();
require_role(['seller']);

$seller_id = (int)$_SESSION['user_id'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$product = $id ? get_product($id) : null;
if ($id && (!$product || (int)$product['seller_id'] !== $seller_id)) {
    http_response_code(403);
    echo 'Access forbidden';
    exit;
}
$msg = $_SESSION['seller_product_msg'] ?? '';
unset($_SESSION['seller_product_msg']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo $id ? 'Edit Product' : 'Add Product'; ?></title>
  <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
<div class="container">
  <h2><?php echo $id ? 'Edit Product' : 'Add Product'; ?></h2>
  <a class="btn" href="seller_products.php">Back</a>

  <?php if ($msg !== ''): ?>
    <div class="msg error"><?php echo htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <form method="POST" action="../Controller/seller_product_save.php">
    <?php if ($id): ?>
      <input type="hidden" name="id" value="<?php echo (int)$id; ?>">
    <?php endif; ?>

    <label>Name</label>
    <input type="text" name="name" required value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>">

    <label>Description</label>
    <textarea name="description" rows="4"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>

    <label>Price</label>
    <input type="number" step="0.01" name="price" required value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>">

    <label>Stock</label>
    <input type="number" name="stock" required value="<?php echo htmlspecialchars($product['stock'] ?? '0'); ?>">

    <button type="submit">Save</button>
  </form>
</div>
</body>
</html>
