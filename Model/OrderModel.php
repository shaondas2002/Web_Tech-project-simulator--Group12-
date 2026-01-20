<?php
require_once(__DIR__ . '/DatabaseConnection.php');

function create_order(int $customer_id, array $cart): ?int {
    if (empty($cart)) return null;

    $conn = getConnection();
    $conn->begin_transaction();

    try {
        $total = 0.0;
        $items = []; 
        $product_stmt = $conn->prepare('SELECT id, seller_id, price, stock FROM products WHERE id=? AND is_active=1 AND is_approved=1 LIMIT 1');
        foreach ($cart as $pid => $qty) {
            $pid = (int)$pid;
            $qty = (int)$qty;
            if ($qty <= 0) continue;

            $product_stmt->bind_param('i', $pid);
            $product_stmt->execute();
            $res = $product_stmt->get_result();
            if (!$res || $res->num_rows !== 1) {
                throw new Exception('Product not found');
            }
            $p = $res->fetch_assoc();
            if ((int)$p['stock'] < $qty) {
                throw new Exception('Not enough stock');
            }
            $price = (float)$p['price'];
            $total += $price * $qty;
            $items[] = ['product_id'=>$pid, 'seller_id'=>(int)$p['seller_id'], 'qty'=>$qty, 'price'=>$price];
        }

        if (empty($items)) {
            throw new Exception('Empty order');
        }

        $order_stmt = $conn->prepare('INSERT INTO orders (customer_id, total_amount, status) VALUES (?, ?, "placed")');
        $order_stmt->bind_param('id', $customer_id, $total);
        if (!$order_stmt->execute()) {
            throw new Exception('Order insert failed');
        }
        $order_id = (int)$conn->insert_id;

        $item_stmt = $conn->prepare('INSERT INTO order_items (order_id, product_id, seller_id, qty, price_each, seller_status) VALUES (?, ?, ?, ?, ?, "new")');
        $stock_stmt = $conn->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');

        foreach ($items as $it) {
            $item_stmt->bind_param('iiiid', $order_id, $it['product_id'], $it['seller_id'], $it['qty'], $it['price']);
            if (!$item_stmt->execute()) {
                throw new Exception('Order item insert failed');
            }
            $stock_stmt->bind_param('ii', $it['qty'], $it['product_id']);
            if (!$stock_stmt->execute()) {
                throw new Exception('Stock update failed');
            }
        }

        $conn->commit();
        return $order_id;
    } catch (Exception $e) {
        $conn->rollback();
        return null;
    }
}

function list_customer_orders(int $customer_id): array {
    $conn = getConnection();
    $stmt = $conn->prepare('SELECT id, total_amount, status, created_at FROM orders WHERE customer_id=? ORDER BY created_at DESC');
    $stmt->bind_param('i', $customer_id);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function list_all_orders(): array {
    $conn = getConnection();
    $res = $conn->query('SELECT o.id, u.name as customer_name, o.total_amount, o.status, o.created_at FROM orders o JOIN users u ON u.id=o.customer_id ORDER BY o.created_at DESC');
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function set_order_status(int $order_id, string $status): bool {
    $allowed = ['placed','processing','shipped','delivered','cancelled'];
    if (!in_array($status, $allowed, true)) return false;
    $conn = getConnection();
    $stmt = $conn->prepare('UPDATE orders SET status=? WHERE id=?');
    $stmt->bind_param('si', $status, $order_id);
    return $stmt->execute();
}

function list_seller_order_items(int $seller_id): array {
    $conn = getConnection();
    $stmt = $conn->prepare('SELECT oi.id, oi.order_id, p.name as product_name, oi.qty, oi.price_each, oi.seller_status, o.status as order_status, o.created_at
                            FROM order_items oi
                            JOIN products p ON p.id = oi.product_id
                            JOIN orders o ON o.id = oi.order_id
                            WHERE oi.seller_id = ?
                            ORDER BY o.created_at DESC');
    $stmt->bind_param('i', $seller_id);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function set_seller_item_status(int $seller_id, int $item_id, string $status): bool {
    $allowed = ['new','accepted','shipped'];
    if (!in_array($status, $allowed, true)) return false;
    $conn = getConnection();
    $stmt = $conn->prepare('UPDATE order_items SET seller_status=? WHERE id=? AND seller_id=?');
    $stmt->bind_param('sii', $status, $item_id, $seller_id);
    return $stmt->execute();
}
?>
