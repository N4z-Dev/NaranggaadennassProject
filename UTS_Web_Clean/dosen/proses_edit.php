<?php
// File: dosen/proses_edit.php
// Menangani proses pembaruan data dosen dan pengelolaan file foto lama/baru

require_once '../koneksi.php';

// Pastikan proses dijalankan setelah form disubmit
if (isset($_POST['btn_update'])) {
    $id                  = (int)($_POST['id'] ?? 0);
    $nip_nidn            = trim($_POST['nip_nidn'] ?? '');
    $nama                = trim($_POST['nama'] ?? '');
    $tanggal_lahir       = trim($_POST['tanggal_lahir'] ?? '');
    $pendidikan_terakhir = trim($_POST['pendidikan_terakhir'] ?? '');
    $alamat              = trim($_POST['alamat'] ?? '');

    // Validasi kelengkapan form
    if (empty($id) || empty($nip_nidn) || empty($nama) || empty($tanggal_lahir) || empty($pendidikan_terakhir) || empty($alamat)) {
        header("Location: index.php?pesan=gagal_validasi");
        exit();
    }

    // Hindari celah keamanan SQL Injection
    $nip_safe        = mysqli_real_escape_string($koneksi, $nip_nidn);
    $nama_safe       = mysqli_real_escape_string($koneksi, $nama);
    $tgl_safe        = mysqli_real_escape_string($koneksi, $tanggal_lahir);
    $pendidikan_safe = mysqli_real_escape_string($koneksi, $pendidikan_terakhir);
    $alamat_safe     = mysqli_real_escape_string($koneksi, $alamat);

    // Cek apakah NIP / NIDN baru bentrok dengan dosen lainnya
    $cek_nip = mysqli_query($koneksi, "SELECT id FROM dosen WHERE nip_nidn = '$nip_safe' AND id != $id");
    if (mysqli_num_rows($cek_nip) > 0) {
        header("Location: index.php?pesan=duplikat_nip");
        exit();
    }

    // Ambil data foto lama dari database
    $query_lama = mysqli_query($koneksi, "SELECT foto FROM dosen WHERE id = $id");
    $data_lama  = mysqli_fetch_array($query_lama);
    $foto_lama  = $data_lama['foto'] ?? null;

    $nama_foto_baru = $foto_lama;

    // Periksa apakah ada file foto baru yang diunggah
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $nama_file = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        $size_file = $_FILES['foto']['size'];

        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $ekstensi_boleh = ['jpg', 'jpeg', 'png', 'webp'];

        // Validasi format file foto
        if (!in_array($ekstensi, $ekstensi_boleh)) {
            header("Location: index.php?pesan=gagal_foto");
            exit();
        }

        // Validasi batas ukuran file (maksimal 2MB)
        if ($size_file > 2 * 1024 * 1024) {
            header("Location: index.php?pesan=gagal_foto");
            exit();
        }

        $folder_upload = '../uploads/dosen/';
        if (!file_exists($folder_upload)) {
            mkdir($folder_upload, 0777, true);
        }

        // Hapus fisik file foto lama jika ada
        if (!empty($foto_lama) && file_exists($folder_upload . $foto_lama)) {
            unlink($folder_upload . $foto_lama);
        }

        // Simpan foto baru dengan nama acak
        $nama_foto_baru = time() . '_' . uniqid() . '.' . $ekstensi;
        move_uploaded_file($tmp_file, $folder_upload . $nama_foto_baru);
    }

    // Query untuk mengupdate data dosen di database
    $query = "UPDATE dosen SET 
                nip_nidn = '$nip_safe',
                nama = '$nama_safe',
                tanggal_lahir = '$tgl_safe',
                pendidikan_terakhir = '$pendidikan_safe',
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
