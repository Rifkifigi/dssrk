<?php
$host = "sql303.infinityfree.com";
$user = "if0_40297930";
$pass = "Rifki123Rifki";
$db   = "if0_40297930_dss_makanan";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
