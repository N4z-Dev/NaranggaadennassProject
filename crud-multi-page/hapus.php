<?php
require_once 'koneksi.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $query = "DELETE FROM mahasiswa WHERE id = $id";
    $eksekusi = mysqli_query($koneksi, $query);

    if ($eksekusi) {
        header("Location: index.php?pesan=sukses_hapus");
        exit();
    } else {
        $err = mysqli_error($koneksi);
        header("Location: index.php?pesan=gagal_db&err=" . urlencode($err));
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>