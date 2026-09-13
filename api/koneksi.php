<?php
// ===================================================================
// FILE: api/koneksi.php (Cloud MySQL TiDB Connection for Vercel)
// ===================================================================

$db_host = getenv('DB_HOST') ?: "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
$db_port = intval(getenv('DB_PORT') ?: 4000);
$db_user = getenv('DB_USER') ?: "2L76wuLfHFgniLG.root";
$db_pass = getenv('DB_PASS') ?: "LlsMsy8xpeQSlRyh";
$db_name = getenv('DB_NAME') ?: "db_kampus";

$db = null;
$driver = 'TiDB Cloud MySQL';

try {
    // Koneksi PDO MySQL dengan SSL ke TiDB Cloud
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_SSL_CA => true
    ];
    $db = new PDO($dsn, $db_user, $db_pass, $options);
} catch (Exception $e) {
    // Fallback SQLite jika terjadi kendala jaringan
    $sqlite_file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'db_kampus.sqlite';
    $db = new PDO("sqlite:" . $sqlite_file);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $driver = 'SQLite (Serverless Fallback)';
}
?>
