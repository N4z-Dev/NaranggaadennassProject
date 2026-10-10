<?php
// File: mahasiswa/hapus.php
// Menangani proses penghapusan data mahasiswa beserta file fotonnya

require_once '../koneksi.php';

// Ambil ID mahasiswa yang ingin dihapus dari parameter URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Ambil nama file foto dari database terlebih dahulu
    $query = mysqli_query($koneksi, "SELECT foto FROM mahasiswa WHERE id = $id");
    $data  = mysqli_fetch_array($query);

    // Hapus file fisik foto dari folder uploads jika ada
    if (!empty($data['foto'])) {
        $file_foto = '../uploads/mahasiswa/' . $data['foto'];
        if (file_exists($file_foto)) {
            unlink($file_foto);
        }
    }

    // Hapus rekaman data mahasiswa dari database
    $hapus = mysqli_query($koneksi, "DELETE FROM mahasiswa WHERE id = $id");

    if ($hapus) {
        header("Location: index.php?pesan=sukses_hapus");
        exit();
    }
}

// Kembalikan ke halaman daftar jika gagal atau ID tidak valid
header("Location: index.php");
exit();
?>
