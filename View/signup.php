<?php
session_start();
$msg = "";
if (isset($_SESSION["signup_msg"])) {
    $msg = $_SESSION["signup_msg"];
    unset($_SESSION["signup_msg"]);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Signup - E-commerce Store</title>
    <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Signup</h2>

    <?php if ($msg !== ""): ?>
        <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <form method="POST" action="../Controller/SignUpValidation.php">
        <label>Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Role</label>
        <select name="role" required>
            <option value="customer">Customer</option>
            <option value="seller">Seller</option>
        </select>

        <button type="submit">Create Account</button>
    </form>

    <p class="small">
        Already have an account? <a href="login.php">Login</a>
    </p>
</div>
    <script src="../Assets/js/app.js"></script>
</body>
</html>
