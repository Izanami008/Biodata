<?php

// =============================
// KONEKSI DATABASE
// =============================

$host = "localhost";
$user = "root";
$pass = "";
$db   = "global";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("koneksi gagal");
}


// =============================
// AMBIL DATA JSON
// =============================

$data = json_decode(file_get_contents("php://input"), true);


// =============================
// DATA PERANGKAT
// =============================

$ip        = $_SERVER['REMOTE_ADDR'];
$device    = $data['device'] ?? '-';
$platform  = $data['platform'] ?? '-';
$bahasa    = $data['bahasa'] ?? '-';
$waktu     = $data['waktu'] ?? '-';
$latitude  = $data['latitude'] ?? '-';
$longitude = $data['longitude'] ?? '-';
$foto      = $data['foto'] ?? '';

$tanggal = date("Y-m-d H:i:s");


// =============================
// SIMPAN FOTO
// =============================

$namaFoto = "";

if ($foto != "") {

    $foto = str_replace('data:image/png;base64,', '', $foto);
    $foto = str_replace(' ', '+', $foto);

    $namaFoto = "foto_" . time() . ".png";

    file_put_contents("foto/" . $namaFoto, base64_decode($foto));
}


// =============================
// SIMPAN KE DATABASE
// =============================

$sql = "INSERT INTO perangkat 
        (ip, device, platform, bahasa, waktu_user, latitude, longitude, foto, waktu_server)
        VALUES 
        ('$ip','$device','$platform','$bahasa','$waktu','$latitude','$longitude','$namaFoto','$tanggal')";

$conn->query($sql);


// =============================
// RESPONSE
// =============================

echo json_encode([
    "status" => "sukses"
]);

?>
