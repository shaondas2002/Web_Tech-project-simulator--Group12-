<?php
session_start();
$msg = "";
if (isset($_SESSION["login_msg"])) {
    $msg = $_SESSION["login_msg"];
    unset($_SESSION["login_msg"]);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login - E-commerce Store</title>
    <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>

    <?php if ($msg !== ""): ?>
        <div class="msg error"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <form method="POST" action="../Controller/LoginValidation.php">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p class="small">
        Don't have an account? <a href="signup.php">Signup</a>
    </p>

    <hr>
    <p class="small">
      
    </p>
</div>
    <script src="../Assets/js/app.js"></script>
</body>
</html>
