<?php
$host = "localhost";
$username = "root";
$password = "root";
$database = "cpmk4";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>