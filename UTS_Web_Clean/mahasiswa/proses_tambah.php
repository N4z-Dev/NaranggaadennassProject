<?php
// File: mahasiswa/proses_tambah.php
// Menangani proses penambahan data mahasiswa ke database dan penyimpanan file foto

require_once '../koneksi.php';

// Pastikan skrip dipicu melalui tombol simpan dari form
if (isset($_POST['btn_simpan'])) {
    // Ambil dan bersihkan data dari inputan form
    $nim           = trim($_POST['nim'] ?? '');
    $nama          = trim($_POST['nama'] ?? '');
    $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
    $nama_wali     = trim($_POST['nama_wali'] ?? '');
    $alamat        = trim($_POST['alamat'] ?? '');

    // Validasi: seluruh kolom wajib diisi
    if (empty($nim) || empty($nama) || empty($tanggal_lahir) || empty($nama_wali) || empty($alamat)) {
        header("Location: index.php?pesan=gagal_validasi");
        exit();
    }

    // Hindari celah keamanan SQL Injection
    $nim_safe    = mysqli_real_escape_string($koneksi, $nim);
    $nama_safe   = mysqli_real_escape_string($koneksi, $nama);
    $tgl_safe    = mysqli_real_escape_string($koneksi, $tanggal_lahir);
    $wali_safe   = mysqli_real_escape_string($koneksi, $nama_wali);
    $alamat_safe = mysqli_real_escape_string($koneksi, $alamat);

    // Cek apakah NIM sudah pernah digunakan oleh mahasiswa lain
    $cek_nim = mysqli_query($koneksi, "SELECT id FROM mahasiswa WHERE nim = '$nim_safe'");
    if (mysqli_num_rows($cek_nim) > 0) {
        header("Location: index.php?pesan=duplikat_nim");
        exit();
    }

    // Proses unggah foto mahasiswa
    $nama_foto_baru = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $nama_file = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        $size_file = $_FILES['foto']['size'];

        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $ekstensi_boleh = ['jpg', 'jpeg', 'png', 'webp'];

        // Validasi ekstensi file
        if (!in_array($ekstensi, $ekstensi_boleh)) {
            header("Location: index.php?pesan=gagal_foto");
            exit();
        }

        // Validasi ukuran file (maksimal 2MB)
        if ($size_file > 2 * 1024 * 1024) {
            header("Location: index.php?pesan=gagal_foto");
            exit();
        }

        // Pastikan direktori folder upload tersedia
        $folder_upload = '../uploads/mahasiswa/';
        if (!file_exists($folder_upload)) {
            mkdir($folder_upload, 0777, true);
        }

        // Generate nama file unik agar tidak bentrok
        $nama_foto_baru = time() . '_' . uniqid() . '.' . $ekstensi;
        move_uploaded_file($tmp_file, $folder_upload . $nama_foto_baru);
    }

    // Simpan data mahasiswa baru ke tabel database
    $query = "INSERT INTO mahasiswa (nim, nama, tanggal_lahir, nama_wali, alamat, foto) 
              VALUES ('$nim_safe', '$nama_safe', '$tgl_safe', '$wali_safe', '$alamat_safe', " . 
              ($nama_foto_baru ? "'$nama_foto_baru'" : "NULL") . ")";

    $eksekusi = mysqli_query($koneksi, $query);

    if ($eksekusi) {
        header("Location: index.php?pesan=sukses_tambah");
    } else {
        header("Location: index.php?pesan=gagal_validasi");
    }
    exit();
} else {
    // Alihkan kembali ke index jika diakses langsung tanpa submit form
    header("Location: index.php");
    exit();
}
?>
