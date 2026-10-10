<?php
// File: api/dosen/hapus.php
// Menangani proses penghapusan data dosen (Vercel Serverless Ready)

require_once __DIR__ . '/../koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $hapus = mysqli_query($koneksi, "DELETE FROM dosen WHERE id = $id");
    if ($hapus) {
        header("Location: /dosen?pesan=sukses_hapus");
        exit();
    }
}

header("Location: /dosen");
exit();
?>
