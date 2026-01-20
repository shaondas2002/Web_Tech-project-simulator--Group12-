<?php
session_start();
require_once("../Model/DatabaseConnection.php");

function backWithMsg(string $msg): void {
    $_SESSION["signup_msg"] = $msg;
    header("Location: ../View/signup.php");
    exit();
}

if (!isset($_POST["name"], $_POST["email"], $_POST["password"], $_POST["role"])) {
    backWithMsg("Invalid request!");
}

$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$password = (string)$_POST["password"];
$role = (string)$_POST["role"];

if ($name === "" || $email === "" || $password === "") {
    backWithMsg("All fields are required!");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    backWithMsg("Invalid email format!");
}

if (!in_array($role, ['customer','seller'], true)) {
    backWithMsg("Invalid role selected!");
}

if (strlen($password) < 6) {
    backWithMsg("Password must be at least 6 characters!");
}

$conn = getConnection();

// Check email exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows > 0) {
    backWithMsg("Email already exists. Try another!");
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$seller_status = ($role === 'seller') ? 'approved' : null; // keep seller but simple

$stmt2 = $conn->prepare("INSERT INTO users (name, email, password_hash, role, seller_status, is_active) VALUES (?, ?, ?, ?, ?, 1)");
$stmt2->bind_param("sssss", $name, $email, $hash, $role, $seller_status);

if ($stmt2->execute()) {
    $_SESSION["signup_msg"] = "Signup successful! Please login.";
    header("Location: ../View/login.php");
    exit();
}

backWithMsg("Signup failed! Please try again.");
?>
