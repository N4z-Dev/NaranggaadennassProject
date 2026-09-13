<?php
// ===================================================================
// FILE: api/koneksi.php (Cloud & Serverless Database Handler)
// FUNGSI: Menghubungkan ke MySQL (jika tersedia host) atau otomatis
//         menggunakan SQLite di /tmp/ agar 100% berjalan online di Vercel.
// ===================================================================

$db_host = getenv('DB_HOST') ?: null;
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'db_kampus';
$db_port = getenv('DB_PORT') ?: 3306;

$db = null;
$driver = 'sqlite';

if (!empty($db_host)) {
    try {
        $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";
        $db = new PDO($dsn, $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        $driver = 'mysql';
    } catch (Exception $e) {
        $db = null;
    }
}

if (!$db) {
    // Jalur Cloud Serverless Vercel: Gunakan SQLite di folder writable /tmp
    $sqlite_dir = sys_get_temp_dir();
    $sqlite_file = $sqlite_dir . DIRECTORY_SEPARATOR . 'db_kampus.sqlite';
    
    $db = new PDO("sqlite:" . $sqlite_file);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $driver = 'sqlite';

    // Inisialisasi tabel jika belum ada
    $db->exec("CREATE TABLE IF NOT EXISTS mahasiswa (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nim TEXT NOT NULL UNIQUE,
        nama TEXT NOT NULL,
        jurusan TEXT NOT NULL,
        alamat TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Cek apakah data sampel sudah ada
    $count = $db->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
    if ($count == 0) {
        $stmt = $db->prepare("INSERT INTO mahasiswa (nim, nama, jurusan, alamat) VALUES (?, ?, ?, ?)");
        $stmt->execute(['2507421029', 'Narangga Aden', 'Teknik Informatika', 'Depok, Jawa Barat']);
        $stmt->execute(['2507421001', 'Ahmad Pratama', 'Sistem Informasi', 'Jakarta Selatan, DKI Jakarta']);
        $stmt->execute(['2507421015', 'Siti Rahmawati', 'Teknik Komputer', 'Bandung, Jawa Barat']);
        $stmt->execute(['2507421033', 'Budi Santoso', 'Teknik Informatika', 'Bogor, Jawa Barat']);
    }
}
?>
