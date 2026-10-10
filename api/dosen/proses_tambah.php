<?php
// File: api/dosen/proses_tambah.php
// Menangani proses penambahan data dosen ke database (Vercel Serverless Ready)

require_once __DIR__ . '/../koneksi.php';

if (isset($_POST['btn_simpan'])) {
    $nip_nidn            = trim($_POST['nip_nidn'] ?? '');
    $nama                = trim($_POST['nama'] ?? '');
    $tanggal_lahir       = trim($_POST['tanggal_lahir'] ?? '');
    $pendidikan_terakhir = trim($_POST['pendidikan_terakhir'] ?? '');
    $alamat              = trim($_POST['alamat'] ?? '');

    if (empty($nip_nidn) || empty($nama) || empty($tanggal_lahir) || empty($pendidikan_terakhir) || empty($alamat)) {
        header("Location: /dosen?pesan=gagal_validasi");
        exit();
    }

    $nip_safe        = mysqli_real_escape_string($koneksi, $nip_nidn);
    $nama_safe       = mysqli_real_escape_string($koneksi, $nama);
    $tgl_safe        = mysqli_real_escape_string($koneksi, $tanggal_lahir);
    $pendidikan_safe = mysqli_real_escape_string($koneksi, $pendidikan_terakhir);
    $alamat_safe     = mysqli_real_escape_string($koneksi, $alamat);

    // Cek duplikasi NIP / NIDN
    $cek_nip = mysqli_query($koneksi, "SELECT id FROM dosen WHERE nip_nidn = '$nip_safe'");
    if ($cek_nip && mysqli_num_rows($cek_nip) > 0) {
        header("Location: /dosen?pesan=duplikat_nip");
        exit();
    }

    // Proses konversi foto ke format Base64 (Data URI)
    $foto_data_uri = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $nama_file = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        $size_file = $_FILES['foto']['size'];

        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $ekstensi_boleh = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!in_array($ekstensi, $ekstensi_boleh) || $size_file > 2 * 1024 * 1024) {
            header("Location: /dosen?pesan=gagal_foto");
            exit();
        }

        $tipe_mime = 'image/' . ($ekstensi == 'jpg' ? 'jpeg' : $ekstensi);
        $isi_file = file_get_contents($tmp_file);
        $foto_data_uri = 'data:' . $tipe_mime . ';base64,' . base64_encode($isi_file);
    }

    $foto_safe = $foto_data_uri ? "'" . mysqli_real_escape_string($koneksi, $foto_data_uri) . "'" : "NULL";

    $query = "INSERT INTO dosen (nip_nidn, nama, tanggal_lahir, pendidikan_terakhir, alamat, foto) 
              VALUES ('$nip_safe', '$nama_safe', '$tgl_safe', '$pendidikan_safe', '$alamat_safe', $foto_safe)";

    $eksekusi = mysqli_query($koneksi, $query);

    if ($eksekusi) {
        header("Location: /dosen?pesan=sukses_tambah");
    } else {
        header("Location: /dosen?pesan=gagal_validasi");
    }
    exit();
} else {
    header("Location: /dosen");
    exit();
}
?>
