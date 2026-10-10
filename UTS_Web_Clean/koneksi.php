<?php
// File: koneksi.php
// Konfigurasi Database Multi-Environment (InfinityFree, TiDB Cloud, & Localhost)
mysqli_report(MYSQLI_REPORT_OFF);

$cloud_host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
$cloud_port = 4000;
$cloud_user = "2L76wuLfHFgniLG.root";
$cloud_pass = "LlsMsy8xpeQSlRyh";
$cloud_db   = "db_kampus";

$is_vercel = (isset($_ENV['VERCEL']) && $_ENV['VERCEL'] == '1') || 
             (isset($_SERVER['VERCEL']) && $_SERVER['VERCEL'] == '1') ||
             (isset($_SERVER['HTTP_X_VERCEL_ID']));

if ($is_vercel) {
    // 1. Langsung hubungkan ke TiDB Cloud jika di Vercel
    $koneksi = mysqli_init();
    $koneksi->ssl_set(NULL, NULL, NULL, NULL, NULL);
    if (!@$koneksi->real_connect($cloud_host, $cloud_user, $cloud_pass, $cloud_db, $cloud_port, NULL, MYSQLI_CLIENT_SSL)) {
        die("Koneksi Database TiDB Gagal: " . mysqli_connect_error());
    }
} else {
    // 2. Jika di InfinityFree atau lokal
    $if_host = "sql304.infinityfree.com";
    $if_user = "if0_42682927";
    $if_pass = "rangga1504";
    $if_db   = "if0_42682927_db_Narangga";

    $koneksi = @mysqli_connect($if_host, $if_user, $if_pass, $if_db);

    if (!$koneksi) {
        // 3. Fallback ke TiDB Cloud
        $koneksi = mysqli_init();
        $koneksi->ssl_set(NULL, NULL, NULL, NULL, NULL);
        if (!@$koneksi->real_connect($cloud_host, $cloud_user, $cloud_pass, $cloud_db, $cloud_port, NULL, MYSQLI_CLIENT_SSL)) {
            // 4. Fallback ke Localhost XAMPP
            $koneksi = @mysqli_connect("localhost", "root", "", "db_uts_siakad");
            if (!$koneksi) {
                die("Koneksi ke database gagal: " . mysqli_connect_error());
            }
        }
    }
}
?>
