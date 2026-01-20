<?php
require_once(__DIR__ . '/DatabaseConnection.php');

function get_user_by_id(int $id): ?array {
    $conn = getConnection();
    $stmt = $conn->prepare('SELECT id, name, email, role, seller_status, is_active, created_at FROM users WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    return ($res && $res->num_rows === 1) ? $res->fetch_assoc() : null;
}

function list_users(): array {
    $conn = getConnection();
    $res = $conn->query('SELECT id, name, email, role, seller_status, is_active, created_at FROM users ORDER BY created_at DESC');
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function set_user_active(int $id, int $is_active): bool {
    $conn = getConnection();
    $stmt = $conn->prepare('UPDATE users SET is_active = ? WHERE id = ?');
    $stmt->bind_param('ii', $is_active, $id);
    return $stmt->execute();
}
?>
