<?php

session_start();

if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// ===============================
// DATABASE
// ===============================
$host = "localhost";
$user = "root";
$pass = "";
$db   = "lacak_hp";

$conn = new mysqli($host, $user, $pass, $db);

$id = $_GET['id'];

// ===============================
// AMBIL FOTO
// ===============================
$result = $conn->query("SELECT foto FROM pengunjung WHERE id=$id");

$data = $result->fetch_assoc();

$foto = $data['foto'];

// ===============================
// HAPUS FILE FOTO
// ===============================
$path = "foto/" . $foto;

if(file_exists($path)) {
    unlink($path);
}

// ===============================
// HAPUS DATA
// ===============================
$conn->query("DELETE FROM pengunjung WHERE id=$id");

// ===============================
header("Location: admin.php");
exit();

?>
