<?php
// File: dosen/hapus.php
// Menangani proses penghapusan data dosen beserta file fotonya

require_once '../koneksi.php';

// Ambil ID dosen yang ingin dihapus dari parameter URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Ambil nama file foto dari database terlebih dahulu
    $query = mysqli_query($koneksi, "SELECT foto FROM dosen WHERE id = $id");
    $data  = mysqli_fetch_array($query);

    // Hapus file fisik foto dari folder uploads jika ada
    if (!empty($data['foto'])) {
        $file_foto = '../uploads/dosen/' . $data['foto'];
        if (file_exists($file_foto)) {
            unlink($file_foto);
        }
    }

    // Hapus data dosen dari database
    $hapus = mysqli_query($koneksi, "DELETE FROM dosen WHERE id = $id");

    if ($hapus) {
        header("Location: index.php?pesan=sukses_hapus");
        exit();
    }
}

// Kembalikan ke halaman daftar jika gagal atau ID tidak valid
header("Location: index.php");
exit();
?>
