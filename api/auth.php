
<?php
declare(strict_types=1);

session_start();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function respond(int $status, array $data): never {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, [
        'success' => false,
        'message' => 'Metode request tidak diizinkan.'
    ]);
}

try {
    require_once __DIR__ . '/../db.php';

    if (!isset($pdo) || !($pdo instanceof PDO)) {
        throw new RuntimeException('PDO database connection is not configured.');
    }

    $input = json_decode(
        file_get_contents('php://input'),
        true
    );

    if (!is_array($input)) {
        respond(400, [
            'success' => false,
            'message' => 'Data request tidak valid.'
        ]);
    }

    $action = $_GET['action'] ?? '';

    if ($action === 'login') {
        $username = trim((string)($input['username'] ?? ''));
        $password = (string)($input['password'] ?? '');

        if ($username === '' || $password === '') {
            respond(400, [
                'success' => false,
                'message' => 'Username dan password wajib diisi.'
            ]);
        }

        $stmt = $pdo->prepare(
            'SELECT id, fullname, username, email,
                    password_hash, role, is_active
             FROM users
             WHERE username = :username
             LIMIT 1'
        );

        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (
            !$user ||
            !password_verify($password, $user['password_hash']) ||
            (int)$user['is_active'] !== 1
        ) {
            respond(401, [
                'success' => false,
                'message' => 'Username atau password salah.'
            ]);
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        respond(200, [
            'success' => true,
            'message' => 'Login berhasil.',
            'user' => [
                'id' => (int)$user['id'],
                'fullname' => $user['fullname'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ]);
    }

    if ($action === 'logout') {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => 'Lax'
            ]);
        }

        session_destroy();

        respond(200, [
            'success' => true,
            'message' => 'Logout berhasil.'
        ]);
    }

    respond(400, [
        'success' => false,
        'message' => 'Action tidak dikenal.'
    ]);

} catch (Throwable $e) {
    error_log('Izanami auth error: ' . $e->getMessage());

    respond(500, [
        'success' => false,
        'message' => 'Terjadi kesalahan server. Periksa konfigurasi database.'
    ]);
}
