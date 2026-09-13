<?php
// =======================================================
// FILE: proses_edit.php (Multi-Page CRUD)
// FUNGSI: Menerima data update via POST, memvalidasi,
//         mengeksekusi query UPDATE, dan redirect ke index.
// =======================================================

require_once 'koneksi.php';

// Cek apakah tombol submit 'btn_update' ditekan
if (isset($_POST['btn_update'])) {

    // ----------------------------------------------------
    // TAHAP 1 & 2: PENANGKAPAN & KONVERSI DATA POST
    // ----------------------------------------------------
    $id      = intval($_POST['id']);
    $nim     = trim($_POST['nim']);
    $nama    = trim($_POST['nama']);
    $jurusan = trim($_POST['jurusan']);
    $alamat  = trim($_POST['alamat']);

    // ----------------------------------------------------
    // TAHAP 3: VALIDASI INPUT (TIDAK BOLEH KOSONG)
    // ----------------------------------------------------
    if (empty($id) || empty($nim) || empty($nama) || empty($jurusan) || empty($alamat)) {
        header("Location: index.php?pesan=gagal_validasi");
        exit();
    }

    // Sanitasi input string
    $nim_safe     = mysqli_real_escape_string($koneksi, $nim);
    $nama_safe    = mysqli_real_escape_string($koneksi, $nama);
    $jurusan_safe = mysqli_real_escape_string($koneksi, $jurusan);
    $alamat_safe  = mysqli_real_escape_string($koneksi, $alamat);

    // ----------------------------------------------------
    // TAHAP 4: MENYUSUN QUERY SQL UPDATE
    // ----------------------------------------------------
    $query = "UPDATE mahasiswa SET 
                nim = '$nim_safe', 
                nama = '$nama_safe', 
                jurusan = '$jurusan_safe', 
                alamat = '$alamat_safe' 
              WHERE id = $id";

    // ----------------------------------------------------
    // TAHAP 5: EKSEKUSI QUERY DENGAN KONEKSI MYSQL
    // ----------------------------------------------------
    $eksekusi = mysqli_query($koneksi, $query);

    // ----------------------------------------------------
    // TAHAP 6: REDIRECT KE TAMPILAN INDEX
    // ----------------------------------------------------
    if ($eksekusi) {
        header("Location: index.php?pesan=sukses_edit");
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
