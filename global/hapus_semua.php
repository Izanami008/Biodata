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

// ===============================
// HAPUS SEMUA FOTO
// ===============================
$folder = "foto/";

$files = glob($folder . "*");

foreach($files as $file) {

    if(is_file($file)) {
        unlink($file);
    }
}

// ===============================
// HAPUS SEMUA DATA
// ===============================
$conn->query("DELETE FROM pengunjung");

// reset auto increment
$conn->query("ALTER TABLE pengunjung AUTO_INCREMENT = 1");

// ===============================
header("Location: admin.php");
exit();

?>
