<?php
// File: mahasiswa/proses_edit.php
// Menangani proses pembaruan data mahasiswa dan penggantian file foto

require_once '../koneksi.php';

// Pastikan skrip dipanggil melalui tombol update form
if (isset($_POST['btn_update'])) {
    $id            = (int)($_POST['id'] ?? 0);
    $nim           = trim($_POST['nim'] ?? '');
    $nama          = trim($_POST['nama'] ?? '');
    $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
    $nama_wali     = trim($_POST['nama_wali'] ?? '');
    $alamat        = trim($_POST['alamat'] ?? '');

    // Validasi: tidak boleh ada kolom yang kosong
    if (empty($id) || empty($nim) || empty($nama) || empty($tanggal_lahir) || empty($nama_wali) || empty($alamat)) {
        header("Location: index.php?pesan=gagal_validasi");
        exit();
    }

    // Hindari celah keamanan SQL Injection
    $nim_safe    = mysqli_real_escape_string($koneksi, $nim);
    $nama_safe   = mysqli_real_escape_string($koneksi, $nama);
    $tgl_safe    = mysqli_real_escape_string($koneksi, $tanggal_lahir);
    $wali_safe   = mysqli_real_escape_string($koneksi, $nama_wali);
    $alamat_safe = mysqli_real_escape_string($koneksi, $alamat);

    // Cek apakah NIM baru bentrok dengan mahasiswa lain
    $cek_nim = mysqli_query($koneksi, "SELECT id FROM mahasiswa WHERE nim = '$nim_safe' AND id != $id");
    if (mysqli_num_rows($cek_nim) > 0) {
        header("Location: index.php?pesan=duplikat_nim");
        exit();
    }

    // Ambil data foto lama dari database
    $query_lama = mysqli_query($koneksi, "SELECT foto FROM mahasiswa WHERE id = $id");
    $data_lama  = mysqli_fetch_array($query_lama);
    $foto_lama  = $data_lama['foto'] ?? null;

    $nama_foto_baru = $foto_lama;

    // Periksa apakah ada foto baru yang diunggah
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $nama_file = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        $size_file = $_FILES['foto']['size'];

        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $ekstensi_boleh = ['jpg', 'jpeg', 'png', 'webp'];

        // Validasi format file foto baru
        if (!in_array($ekstensi, $ekstensi_boleh)) {
            header("Location: index.php?pesan=gagal_foto");
            exit();
        }

        // Validasi ukuran file (maksimal 2MB)
        if ($size_file > 2 * 1024 * 1024) {
            header("Location: index.php?pesan=gagal_foto");
            exit();
        }

        $folder_upload = '../uploads/mahasiswa/';
        if (!file_exists($folder_upload)) {
            mkdir($folder_upload, 0777, true);
        }

        // Hapus file fisik foto lama jika ada
        if (!empty($foto_lama) && file_exists($folder_upload . $foto_lama)) {
            unlink($folder_upload . $foto_lama);
        }

        // Simpan foto baru dengan nama unik
        $nama_foto_baru = time() . '_' . uniqid() . '.' . $ekstensi;
        move_uploaded_file($tmp_file, $folder_upload . $nama_foto_baru);
    }

    // Query untuk memperbarui data mahasiswa
    $query = "UPDATE mahasiswa SET 
                nim = '$nim_safe',
                nama = '$nama_safe',
                tanggal_lahir = '$tgl_safe',
                nama_wali = '$wali_safe',
                alamat = '$alamat_safe',
                foto = " . ($nama_foto_baru ? "'$nama_foto_baru'" : "NULL") . "
              WHERE id = $id";

    $eksekusi = mysqli_query($koneksi, $query);

    if ($eksekusi) {
        header("Location: index.php?pesan=sukses_edit");
    } else {
        header("Location: index.php?pesan=gagal_validasi");
    }
    exit();
} else {
    // Alihkan ke index jika diakses langsung tanpa submit
    header("Location: index.php");
    exit();
}
?>
