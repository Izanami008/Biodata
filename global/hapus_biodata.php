<?php

$conn = new mysqli("localhost","root","","global");

$id = $_GET['id'];

$conn->query("DELETE FROM biodata WHERE id='$id'");

header("Location: data_biodata.php");
