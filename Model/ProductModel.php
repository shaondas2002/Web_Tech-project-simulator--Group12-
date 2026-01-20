<?php
require_once(__DIR__ . '/DatabaseConnection.php');

function list_active_products(): array {
    $conn = getConnection();
    $stmt = $conn->prepare('SELECT id, seller_id, name, description, price, stock, image_path FROM products WHERE is_active = 1 AND is_approved = 1 ORDER BY created_at DESC');
    $stmt->execute();
    $res = $stmt->get_result();
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function get_product(int $id): ?array {
    $conn = getConnection();
    $stmt = $conn->prepare('SELECT id, seller_id, name, description, price, stock, image_path, is_active, is_approved FROM products WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    return ($res && $res->num_rows === 1) ? $res->fetch_assoc() : null;
}

function list_seller_products(int $seller_id): array {
    $conn = getConnection();
    $stmt = $conn->prepare('SELECT id, name, price, stock, is_active, is_approved, created_at FROM products WHERE seller_id = ? ORDER BY created_at DESC');
    $stmt->bind_param('i', $seller_id);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function save_product(int $seller_id, ?int $id, string $name, string $desc, float $price, int $stock): bool {
    $conn = getConnection();
    if ($id === null) {
        
        $stmt = $conn->prepare('INSERT INTO products (seller_id, name, description, price, stock, is_active, is_approved) VALUES (?, ?, ?, ?, ?, 1, 1)');
        $stmt->bind_param('issdi', $seller_id, $name, $desc, $price, $stock);
        return $stmt->execute();
    }
    $stmt = $conn->prepare('UPDATE products SET name=?, description=?, price=?, stock=?, is_approved=1 WHERE id=? AND seller_id=?');
    $stmt->bind_param('ssdiii', $name, $desc, $price, $stock, $id, $seller_id);
    return $stmt->execute();
}

function delete_product(int $seller_id, int $id): bool {
    $conn = getConnection();
    $stmt = $conn->prepare('DELETE FROM products WHERE id=? AND seller_id=?');
    $stmt->bind_param('ii', $id, $seller_id);
    return $stmt->execute();
}

function admin_set_product_active(int $id, int $is_active): bool {
    $conn = getConnection();
    $stmt = $conn->prepare('UPDATE products SET is_active=? WHERE id=?');
    $stmt->bind_param('ii', $is_active, $id);
    return $stmt->execute();
}
function list_all_products(): array {
    $conn = getConnection();
    $res = $conn->query('SELECT p.id, p.name, u.name AS seller_name, p.price, p.stock, p.is_active, p.is_approved, p.created_at
                         FROM products p
                         JOIN users u ON u.id = p.seller_id
                         ORDER BY p.created_at DESC');
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

?>
