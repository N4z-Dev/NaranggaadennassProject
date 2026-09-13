<?php
// =======================================================
// FILE: hapus.php (Multi-Page CRUD)
// FUNGSI: Menghapus baris record mahasiswa berdasarkan ID (DELETE)
// =======================================================

require_once 'koneksi.php';

// Pastikan parameter ID ada di URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id = intval($_GET['id']);

    // Menyusun query DELETE dengan klausa WHERE id
    $query = "DELETE FROM mahasiswa WHERE id = $id";

    // Eksekusi query ke database
    $eksekusi = mysqli_query($koneksi, $query);

    if ($eksekusi) {
        header("Location: index.php?pesan=sukses_hapus");
        exit();
    } else {
        header("Location: index.php?pesan=gagal_db");
        exit();
    }

} else {
    header("Location: index.php");
    exit();
}
?>
