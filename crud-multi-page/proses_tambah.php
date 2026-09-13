<?php
// =======================================================
// FILE: proses_tambah.php (Multi-Page CRUD)
// FUNGSI: Menerima data POST, memvalidasi, menyusun query INSERT,
//         mengeksekusi ke MySQL, dan mengalihkan halaman.
// =======================================================

require_once 'koneksi.php';

// Cek apakah tombol submit 'btn_simpan' telah ditekan
if (isset($_POST['btn_simpan'])) {

    // ----------------------------------------------------
    // TAHAP 1 & 2: PENANGKAPAN & KONVERSI DARI POST KE VARIABEL
    // ----------------------------------------------------
    $nim     = trim($_POST['nim']);
    $nama    = trim($_POST['nama']);
    $jurusan = trim($_POST['jurusan']);
    $alamat  = trim($_POST['alamat']);

    // ----------------------------------------------------
    // TAHAP 3: VALIDASI INPUT (TIDAK BOLEH KOSONG)
    // ----------------------------------------------------
    if (empty($nim) || empty($nama) || empty($jurusan) || empty($alamat)) {
        // Jika ada input yang kosong, kembalikan dengan pesan gagal
        header("Location: index.php?pesan=gagal_validasi");
        exit();
    }

    // Sanitasi input untuk mencegah SQL Injection dasar
    $nim_safe     = mysqli_real_escape_string($koneksi, $nim);
    $nama_safe    = mysqli_real_escape_string($koneksi, $nama);
    $jurusan_safe = mysqli_real_escape_string($koneksi, $jurusan);
    $alamat_safe  = mysqli_real_escape_string($koneksi, $alamat);

    // ----------------------------------------------------
    // TAHAP 4: MENYUSUN QUERY SQL INSERT
    // ----------------------------------------------------
    $query = "INSERT INTO mahasiswa (nim, nama, jurusan, alamat) 
              VALUES ('$nim_safe', '$nama_safe', '$jurusan_safe', '$alamat_safe')";

    // ----------------------------------------------------
    // TAHAP 5: MENGEKSEKUSI QUERY KE MYSQL MELALUI $koneksi
    // ----------------------------------------------------
    $eksekusi = mysqli_query($koneksi, $query);

    // ----------------------------------------------------
    // TAHAP 6: REDIRECT / TAMPIL KEMBALI KE INDEX
    // ----------------------------------------------------
    if ($eksekusi) {
        header("Location: index.php?pesan=sukses_tambah");
        exit();
    } else {
        // Jika terjadi error (misal duplicate NIM)
        header("Location: index.php?pesan=gagal_db");
        exit();
    }

} else {
    // Jika file diakses langsung tanpa method POST, arahkan kembali ke index.php
    header("Location: index.php");
    exit();
}
?>
