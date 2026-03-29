<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost","root","","global");

$data = $conn->query("SELECT * FROM biodata");
?>

<!DOCTYPE html>
<html>
<head>

<title>Data Biodata</title>

<style>

body{
    background:#0f172a;
    color:white;
    font-family:Arial;
}

table{
    width:90%;
    margin:40px auto;
    border-collapse:collapse;
}

th, td{
    padding:10px;
    border:1px solid #334155;
}

th{
    background:#1e293b;
}

button{
    padding:5px 10px;
    background:red;
    border:none;
    color:white;
}

</style>

</head>
<body>

<h2 style="text-align:center">Data Biodata</h2>

<table>

<tr>
<th>ID</th>
<th>Nama</th>
<th>Umur</th>
<th>Alamat</th>
<th>Pekerjaan</th>
<th>Aksi</th>
</tr>

<?php while($row=$data->fetch_assoc()) { ?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['nama']; ?></td>
<td><?php echo $row['umur']; ?></td>
<td><?php echo $row['alamat']; ?></td>
<td><?php echo $row['pekerjaan']; ?></td>

<td>

<a href="hapus_biodata.php?id=<?php echo $row['id']; ?>">
<button>Hapus</button>
</a>

</td>

</tr>

<?php } ?>

</table>

</body>
</html>
