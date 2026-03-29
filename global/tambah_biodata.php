<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Tambah Biodata</title>
<link rel="stylesheet" href="style.css">

<style>

body{
    background:#0f172a;
    color:white;
    font-family:Arial;
}

.box{
    width:400px;
    margin:50px auto;
    background:#1e293b;
    padding:20px;
    border-radius:10px;
}

input, textarea{
    width:100%;
    padding:10px;
    margin:10px 0;
}

button{
    padding:10px;
    width:100%;
    background:#00ffcc;
    border:none;
}

</style>

</head>
<body>

<div class="box">

<h2>Tambah Biodata</h2>

<form action="simpan_biodata.php" method="POST">

<input type="text" name="nama" placeholder="Nama" required>

<input type="number" name="umur" placeholder="Umur" required>

<textarea name="alamat" placeholder="Alamat"></textarea>

<input type="text" name="pekerjaan" placeholder="Pekerjaan">

<button>Simpan</button>

</form>

</div>

</body>
</html>
