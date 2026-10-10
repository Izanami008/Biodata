
<?php
declare(strict_types=1);

$host = 'sql301.infinityfree.com';
$dbname = 'if0_43133752_izanami';
$username = 'if0_43133752';

// Isi menggunakan password MySQL terbaru.
// Jangan unggah password ke repositori publik.
$password = getenv('IZANAMI_DB_PASSWORD') ?: '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log('Izanami database connection failed.');
    http_response_code(500);
    exit('Koneksi database gagal.');
}
