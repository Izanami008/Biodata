<?php

$conn = new mysqli("localhost","root","","global");

$nama = $_POST['nama'];
$umur = $_POST['umur'];
$alamat = $_POST['alamat'];
$pekerjaan = $_POST['pekerjaan'];

$conn->query("INSERT INTO biodata(nama,umur,alamat,pekerjaan)
VALUES('$nama','$umur','$alamat','$pekerjaan')");

header("Location: data_biodata.php");
