
<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config.php';

try {
    $result = $pdo->query('SELECT DATABASE() AS db_name')->fetch();

    echo json_encode([
        'success' => true,
        'message' => 'Koneksi database berhasil.',
        'database' => $result['db_name']
    ], JSON_PRETTY_PRINT);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Pengujian database gagal.'
    ]);
}
