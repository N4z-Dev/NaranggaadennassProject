<?php
// ===================================================================
// FILE: koneksi.php
// Koneksi Database PHP & MySQL (TiDB Cloud Serverless)
// Mahasiswa : Narangga Adennas Shaputra
// NIM       : 2507421029
// Kelas     : TMJ 3A
// Dosen     : Pak Chandra
// ===================================================================

$host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
$port = 4000;
$user = "2L76wuLfHFgniLG.root";
$pass = "LlsMsy8xpeQSlRyh";
$db   = "db_kampus";

$koneksi = mysqli_init();
$koneksi->ssl_set(NULL, NULL, NULL, NULL, NULL);

if (!@$koneksi->real_connect($host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL)) {
    // Fallback koneksi localhost jika dijalankan via XAMPP lokal
    $koneksi = @mysqli_connect("localhost", "root", "", "db_kampus");
    if (!$koneksi) {
        die("<p style='color:red;'>Koneksi database gagal: " . mysqli_connect_error() . "</p>");
    }
}
?>
