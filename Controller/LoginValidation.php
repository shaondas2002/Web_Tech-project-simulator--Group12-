<?php
session_start();
require_once("../Model/DatabaseConnection.php");

function backWithMsg(string $msg): void {
    $_SESSION["login_msg"] = $msg;
    header("Location: ../View/login.php");
    exit();
}

if (!isset($_POST["email"], $_POST["password"])) {
    backWithMsg("Invalid request!");
}

$email = trim((string)$_POST["email"]);
$password = (string)$_POST["password"];

if ($email === "" || $password === "") {
    backWithMsg("Email and password are required!");
}

$conn = getConnection();
$stmt = $conn->prepare("SELECT id, name, email, password_hash, role, seller_status, is_active FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows !== 1) {
    backWithMsg("Invalid email or password!");
}

$user = $res->fetch_assoc();

if ((int)$user['is_active'] !== 1) {
    backWithMsg("Your account is blocked!");
}

if (!password_verify($password, $user['password_hash'])) {
    backWithMsg("Invalid email or password!");
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['role'] = $user['role'];
$_SESSION['seller_status'] = $user['seller_status'];

header('Location: ../View/dashboard.php');
exit();
?>
