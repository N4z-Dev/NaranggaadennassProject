<?php
require_once 'koneksi.php';

if (isset($_POST['btn_update'])) {
    $id      = intval($_POST['id'] ?? 0);
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $alamat  = trim($_POST['alamat'] ?? '');

    // Validasi form tidak boleh kosong
    if (empty($id) || empty($nim) || empty($nama) || empty($jurusan) || empty($alamat)) {
        header("Location: index.php?pesan=gagal_validasi");
        exit();
    }

    $nim_safe     = mysqli_real_escape_string($koneksi, $nim);
    $nama_safe    = mysqli_real_escape_string($koneksi, $nama);
    $jurusan_safe = mysqli_real_escape_string($koneksi, $jurusan);
    $alamat_safe  = mysqli_real_escape_string($koneksi, $alamat);

    $query = "UPDATE mahasiswa SET 
                nim = '$nim_safe', 
                nama = '$nama_safe', 
                jurusan = '$jurusan_safe', 
                alamat = '$alamat_safe' 
              WHERE id = $id";
    
    $eksekusi = mysqli_query($koneksi, $query);

    if ($eksekusi) {
        header("Location: index.php?pesan=sukses_edit");
        exit();
    } else {
        if (mysqli_errno($koneksi) == 1062) {
            header("Location: index.php?pesan=duplikat_nim");
        } else {
            $err = mysqli_error($koneksi);
            header("Location: index.php?pesan=gagal_db&err=" . urlencode($err));
        }
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>