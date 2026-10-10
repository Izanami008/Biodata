
<?php
declare(strict_types=1);

session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function output(int $status, array $data): never {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if (
    empty($_SESSION['user_id']) ||
    ($_SESSION['role'] ?? '') !== 'admin'
) {
    output(403, [
        'success' => false,
        'message' => 'Silakan login sebagai admin.'
    ]);
}

try {
    require_once __DIR__ . '/../db.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header('Allow: GET');
        output(405, [
            'success' => false,
            'message' => 'Metode tidak diizinkan.'
        ]);
    }

    $action = $_GET['action'] ?? 'stats';

    if ($action === 'stats') {
        $users = (int) $pdo->query(
            'SELECT COUNT(*) FROM users'
        )->fetchColumn();

        $products = (int) $pdo->query(
            'SELECT COUNT(*) FROM products WHERE is_active = 1'
        )->fetchColumn();

        $orders = (int) $pdo->query(
            'SELECT COUNT(*) FROM orders'
        )->fetchColumn();

        $revenue = $pdo->query(
            "SELECT COALESCE(SUM(total), 0)
             FROM orders
             WHERE status IN ('paid','processing','shipped','completed')"
        )->fetchColumn();

        output(200, [
            'success' => true,
            'stats' => [
                'users' => $users,
                'products' => $products,
                'orders' => $orders,
                'revenue' => (float) $revenue
            ]
        ]);
    }

    if ($action === 'users') {
        $stmt = $pdo->query(
            'SELECT id, fullname, username, email, role, is_active, created_at
             FROM users ORDER BY id DESC LIMIT 500'
        );

        output(200, [
            'success' => true,
            'data' => $stmt->fetchAll()
        ]);
    }

    if ($action === 'products') {
        $stmt = $pdo->query(
            'SELECT id, name, description, price, image, stock, is_active
             FROM products ORDER BY id DESC LIMIT 500'
        );

        output(200, [
            'success' => true,
            'data' => $stmt->fetchAll()
        ]);
    }

    if ($action === 'orders') {
        $stmt = $pdo->query(
            "SELECT o.id, o.user_id, o.total, o.status,
                    o.customer_name, o.created_at, u.username, u.email
             FROM orders o
             LEFT JOIN users u ON u.id = o.user_id
             ORDER BY o.id DESC LIMIT 500"
        );

        $orders = $stmt->fetchAll();

        $itemsStmt = $pdo->prepare(
            'SELECT product_name, quantity
             FROM order_items WHERE order_id = ?'
        );

        foreach ($orders as &$order) {
            $itemsStmt->execute([$order['id']]);
            $order['items'] = $itemsStmt->fetchAll();
        }
        unset($order);

        output(200, [
            'success' => true,
            'data' => $orders
        ]);
    }

    output(400, [
        'success' => false,
        'message' => 'Aksi tidak dikenal.'
    ]);

} catch (Throwable $e) {
    error_log('Izanami admin API: ' . $e->getMessage());

    output(500, [
        'success' => false,
        'message' => 'Terjadi kesalahan saat membaca database.'
    ]);
}
