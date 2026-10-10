<?php
// File: api/mahasiswa/proses_tambah.php
// Menangani proses penambahan data mahasiswa ke database (Vercel Serverless Ready)

require_once __DIR__ . '/../koneksi.php';

if (isset($_POST['btn_simpan'])) {
    $nim           = trim($_POST['nim'] ?? '');
    $nama          = trim($_POST['nama'] ?? '');
    $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
    $nama_wali     = trim($_POST['nama_wali'] ?? '');
    $alamat        = trim($_POST['alamat'] ?? '');

    // Validasi form
    if (empty($nim) || empty($nama) || empty($tanggal_lahir) || empty($nama_wali) || empty($alamat)) {
        header("Location: /mahasiswa?pesan=gagal_validasi");
        exit();
    }

    $nim_safe    = mysqli_real_escape_string($koneksi, $nim);
    $nama_safe   = mysqli_real_escape_string($koneksi, $nama);
    $tgl_safe    = mysqli_real_escape_string($koneksi, $tanggal_lahir);
    $wali_safe   = mysqli_real_escape_string($koneksi, $nama_wali);
    $alamat_safe = mysqli_real_escape_string($koneksi, $alamat);

    // Cek duplikasi NIM
    $cek_nim = mysqli_query($koneksi, "SELECT id FROM mahasiswa WHERE nim = '$nim_safe'");
    if ($cek_nim && mysqli_num_rows($cek_nim) > 0) {
        header("Location: /mahasiswa?pesan=duplikat_nim");
        exit();
    }

    // Proses konversi foto ke format Base64 (Data URI) untuk lingkungan serverless
    $foto_data_uri = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $nama_file = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        $size_file = $_FILES['foto']['size'];

        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $ekstensi_boleh = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!in_array($ekstensi, $ekstensi_boleh) || $size_file > 2 * 1024 * 1024) {
            header("Location: /mahasiswa?pesan=gagal_foto");
            exit();
        }

        // Tentukan mime type gambar
        $tipe_mime = 'image/' . ($ekstensi == 'jpg' ? 'jpeg' : $ekstensi);
        $isi_file = file_get_contents($tmp_file);
        $foto_data_uri = 'data:' . $tipe_mime . ';base64,' . base64_encode($isi_file);
    }

    // Simpan ke database
    $foto_safe = $foto_data_uri ? "'" . mysqli_real_escape_string($koneksi, $foto_data_uri) . "'" : "NULL";

    $query = "INSERT INTO mahasiswa (nim, nama, tanggal_lahir, nama_wali, alamat, foto) 
              VALUES ('$nim_safe', '$nama_safe', '$tgl_safe', '$wali_safe', '$alamat_safe', $foto_safe)";

    $eksekusi = mysqli_query($koneksi, $query);

    if ($eksekusi) {
        header("Location: /mahasiswa?pesan=sukses_tambah");
    } else {
        header("Location: /mahasiswa?pesan=gagal_validasi");
    }
    exit();
} else {
    header("Location: /mahasiswa");
    exit();
}
?>
