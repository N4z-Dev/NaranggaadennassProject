<?php
// File: api/mahasiswa/proses_edit.php
// Menangani pembaruan data mahasiswa (Vercel Serverless Ready)

require_once __DIR__ . '/../koneksi.php';

if (isset($_POST['btn_update'])) {
    $id            = (int)($_POST['id'] ?? 0);
    $nim           = trim($_POST['nim'] ?? '');
    $nama          = trim($_POST['nama'] ?? '');
    $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
    $nama_wali     = trim($_POST['nama_wali'] ?? '');
    $alamat        = trim($_POST['alamat'] ?? '');

    if (empty($id) || empty($nim) || empty($nama) || empty($tanggal_lahir) || empty($nama_wali) || empty($alamat)) {
        header("Location: /mahasiswa?pesan=gagal_validasi");
        exit();
    }

    $nim_safe    = mysqli_real_escape_string($koneksi, $nim);
    $nama_safe   = mysqli_real_escape_string($koneksi, $nama);
    $tgl_safe    = mysqli_real_escape_string($koneksi, $tanggal_lahir);
    $wali_safe   = mysqli_real_escape_string($koneksi, $nama_wali);
    $alamat_safe = mysqli_real_escape_string($koneksi, $alamat);

    // Cek apakah NIM bentrok dengan mahasiswa lain
    $cek_nim = mysqli_query($koneksi, "SELECT id FROM mahasiswa WHERE nim = '$nim_safe' AND id != $id");
    if ($cek_nim && mysqli_num_rows($cek_nim) > 0) {
        header("Location: /mahasiswa?pesan=duplikat_nim");
        exit();
    }

    // Ambil foto lama
    $query_lama = mysqli_query($koneksi, "SELECT foto FROM mahasiswa WHERE id = $id");
    $data_lama  = $query_lama ? mysqli_fetch_array($query_lama) : null;
    $foto_lama  = $data_lama['foto'] ?? null;

    $foto_simpan = $foto_lama;

    // Periksa apakah pengguna mengunggah foto baru
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

        $tipe_mime = 'image/' . ($ekstensi == 'jpg' ? 'jpeg' : $ekstensi);
        $isi_file = file_get_contents($tmp_file);
        $foto_simpan = 'data:' . $tipe_mime . ';base64,' . base64_encode($isi_file);
    }

    $foto_safe = $foto_simpan ? "'" . mysqli_real_escape_string($koneksi, $foto_simpan) . "'" : "NULL";

    $query = "UPDATE mahasiswa SET 
                nim = '$nim_safe',
                nama = '$nama_safe',
                tanggal_lahir = '$tgl_safe',
                nama_wali = '$wali_safe',
                alamat = '$alamat_safe',
                foto = $foto_safe
              WHERE id = $id";

    $eksekusi = mysqli_query($koneksi, $query);

    if ($eksekusi) {
        header("Location: /mahasiswa?pesan=sukses_edit");
    } else {
        header("Location: /mahasiswa?pesan=gagal_validasi");
    }
    exit();
} else {
    header("Location: /mahasiswa");
    exit();
}
?>
