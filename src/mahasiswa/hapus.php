<?php
// File: api/mahasiswa/hapus.php
// Menangani proses penghapusan data mahasiswa (Vercel Serverless Ready)

require_once __DIR__ . '/../koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $hapus = mysqli_query($koneksi, "DELETE FROM mahasiswa WHERE id = $id");
    if ($hapus) {
        header("Location: /mahasiswa?pesan=sukses_hapus");
        exit();
    }
}

header("Location: /mahasiswa");
exit();
?>
