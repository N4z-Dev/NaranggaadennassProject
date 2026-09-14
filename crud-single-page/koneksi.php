<?php
mysqli_report(MYSQLI_REPORT_OFF);

$if_host = "sql304.infinityfree.com";
$if_user = "if0_42682927";
$if_pass = "rangga1504";
$if_db   = "if0_42682927_db_Narangga";

$koneksi = @mysqli_connect($if_host, $if_user, $if_pass, $if_db);

if (!$koneksi) {
    $cloud_host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
    $cloud_port = 4000;
    $cloud_user = "2L76wuLfHFgniLG.root";
    $cloud_pass = "LlsMsy8xpeQSlRyh";
    $cloud_db   = "db_kampus";

    $koneksi = mysqli_init();
    $koneksi->ssl_set(NULL, NULL, NULL, NULL, NULL);

    if (!@$koneksi->real_connect($cloud_host, $cloud_user, $cloud_pass, $cloud_db, $cloud_port, NULL, MYSQLI_CLIENT_SSL)) {
        $koneksi = @mysqli_connect("localhost", "root", "", "db_kampus");
        if (!$koneksi) {
            die("Koneksi ke database gagal: " . mysqli_connect_error());
        }
    }
}
?>
