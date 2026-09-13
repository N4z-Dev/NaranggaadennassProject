<?php
// =======================================================
// FILE: koneksi.php
// FUNGSI: Menghubungkan script PHP dengan database MySQL
// =======================================================

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_kampus";

// Membuka koneksi menggunakan ekstensi mysqli
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek status keberhasilan koneksi
if (!$koneksi) {
    die("Gagal terhubung ke MySQL: " . mysqli_connect_error());
}
?>
