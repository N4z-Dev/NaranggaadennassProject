<?php
// ===================================================================
// FILE: hapus.php (Multi-Page CRUD - Hapus Data)
// REFERENSI: CodePolitan - Delete Data PHP & MySQL
// Mahasiswa : Narangga Adennas Shaputra (2507421029) - TMJ 3A
// Dosen     : Pak Chandra
// ===================================================================

require_once __DIR__ . '/koneksi.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $sql = "DELETE FROM mahasiswa WHERE id = $id";
    if (mysqli_query($koneksi, $sql)) {
        header("Location: index.php?pesan=sukses_hapus");
        exit();
    } else {
        header("Location: index.php?pesan=gagal");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
