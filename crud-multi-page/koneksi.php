<?php
// =======================================================
// FILE: koneksi.php
// FUNGSI: Menghubungkan script PHP dengan database MySQL
//         Mendukung Cloud TiDB Serverless & MySQL Lokal
// =======================================================

$host = getenv('DB_HOST') ?: "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
$port = intval(getenv('DB_PORT') ?: 4000);
$user = getenv('DB_USER') ?: "2L76wuLfHFgniLG.root";
$pass = getenv('DB_PASS') ?: "LlsMsy8xpeQSlRyh";
$db   = getenv('DB_NAME') ?: "db_kampus";

// 1. Coba koneksi ke Cloud MySQL (TiDB Serverless via SSL)
$koneksi = mysqli_init();
$koneksi->ssl_set(NULL, NULL, NULL, NULL, NULL);

$connect_cloud = @$koneksi->real_connect($host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL);

if (!$connect_cloud) {
    // 2. Fallback jika dijalankan di localhost Laragon
    $koneksi = @mysqli_connect("localhost", "root", "", "db_kampus");
    if (!$koneksi) {
        die("Gagal terhubung ke MySQL: " . mysqli_connect_error());
    }
}
?>
