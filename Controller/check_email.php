<?php
header('Content-Type: application/json; charset=utf-8');
require_once('../Model/DatabaseConnection.php');

$email = trim((string)($_GET['email'] ?? ''));
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok'=>false, 'available'=>false, 'message'=>'Invalid email']);
    exit;
}

$conn = getConnection();
$stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$res = $stmt->get_result();
$exists = ($res && $res->num_rows > 0);

echo json_encode(['ok'=>true, 'available'=>!$exists]);
?>
