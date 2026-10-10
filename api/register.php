
<?php
declare(strict_types=1);

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

    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        respond(400, [
            'success' => false,
            'message' => 'Data registrasi tidak valid.'
        ]);
    }

    $fullname = trim((string)($input['fullname'] ?? ''));
    $username = trim((string)($input['username'] ?? ''));
    $email = strtolower(trim((string)($input['email'] ?? '')));
    $password = (string)($input['password'] ?? '');

    if ($fullname === '' || strlen($fullname) > 450) {
        respond(400, [
            'success' => false,
            'message' => 'Nama lengkap wajib diisi dan maksimal 150 karakter.'
        ]);
    }

    if (!preg_match('/^[A-Za-z0-9_.-]{3,80}$/', $username)) {
        respond(400, [
            'success' => false,
            'message' => 'Username harus 3–80 karakter, menggunakan huruf, angka, titik, garis bawah, atau tanda hubung.'
        ]);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
        respond(400, [
            'success' => false,
            'message' => 'Format email tidak valid.'
        ]);
    }

    if (strlen($password) < 8 || strlen($password) > 72) {
        respond(400, [
            'success' => false,
            'message' => 'Password harus 8–72 byte.'
        ]);
    }

    $check = $pdo->prepare(
        'SELECT id FROM users
         WHERE username = :username OR email = :email
         LIMIT 1'
    );

    $check->execute([
        'username' => $username,
        'email' => $email
    ]);

    if ($check->fetch()) {
        respond(409, [
            'success' => false,
            'message' => 'Username atau email sudah terdaftar.'
        ]);
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $insert = $pdo->prepare(
        "INSERT INTO users
            (fullname, username, email, password_hash, role, is_active)
         VALUES
            (:fullname, :username, :email, :password_hash, 'user', 1)"
    );

    $insert->execute([
        'fullname' => $fullname,
        'username' => $username,
        'email' => $email,
        'password_hash' => $passwordHash
    ]);

    respond(201, [
        'success' => true,
        'message' => 'Registrasi berhasil.',
        'user' => [
            'id' => (int)$pdo->lastInsertId(),
            'fullname' => $fullname,
            'username' => $username,
            'email' => $email,
            'role' => 'user'
        ]
    ]);

} catch (PDOException $e) {
    error_log('Izanami registration database error: ' . $e->getMessage());

    if ($e->getCode() === '23000') {
        respond(409, [
            'success' => false,
            'message' => 'Username atau email sudah digunakan.'
        ]);
    }

    respond(500, [
        'success' => false,
        'message' => 'Registrasi gagal karena masalah database.'
    ]);

} catch (Throwable $e) {
    error_log('Izanami registration error: ' . $e->getMessage());

    respond(500, [
        'success' => false,
        'message' => 'Terjadi kesalahan server. Periksa konfigurasi.'
    ]);
}
