<?php
require_once 'koneksi.php';

if (isset($_POST['btn_simpan'])) {
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $alamat  = trim($_POST['alamat'] ?? '');

    // Validasi form tidak boleh kosong
    if (empty($nim) || empty($nama) || empty($jurusan) || empty($alamat)) {
        header("Location: index.php?pesan=gagal_validasi");
        exit();
    }

    $nim_safe     = mysqli_real_escape_string($koneksi, $nim);
    $nama_safe    = mysqli_real_escape_string($koneksi, $nama);
    $jurusan_safe = mysqli_real_escape_string($koneksi, $jurusan);
    $alamat_safe  = mysqli_real_escape_string($koneksi, $alamat);

    $query = "INSERT INTO mahasiswa (nim, nama, jurusan, alamat) 
              VALUES ('$nim_safe', '$nama_safe', '$jurusan_safe', '$alamat_safe')";
    
    $eksekusi = mysqli_query($koneksi, $query);

    if ($eksekusi) {
        header("Location: index.php?pesan=sukses_tambah");
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