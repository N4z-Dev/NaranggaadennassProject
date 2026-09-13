<?php
// ===================================================================
// FILE: tambah.php (Multi-Page CRUD - Form Tambah)
// REFERENSI: CodePolitan - Tambah Data PHP & MySQL
// Mahasiswa : Narangga Adennas Shaputra (2507421029) - TMJ 3A
// Dosen     : Pak Chandra
// ===================================================================

require_once __DIR__ . '/koneksi.php';

$error = '';

// Proses form saat tombol Submit diklik
if (isset($_POST['Submit'])) {
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $alamat  = trim($_POST['alamat'] ?? '');

    // Validasi input tidak boleh kosong
    if (empty($nim) || empty($nama) || empty($jurusan) || empty($alamat)) {
        $error = "Semua kolom formulir wajib diisi, tidak boleh kosong!";
    } else {
        $nim_safe     = mysqli_real_escape_string($koneksi, $nim);
        $nama_safe    = mysqli_real_escape_string($koneksi, $nama);
        $jurusan_safe = mysqli_real_escape_string($koneksi, $jurusan);
        $alamat_safe  = mysqli_real_escape_string($koneksi, $alamat);

        $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, alamat) 
                VALUES ('$nim_safe', '$nama_safe', '$jurusan_safe', '$alamat_safe')";
        
        if (mysqli_query($koneksi, $sql)) {
            header("Location: index.php?pesan=sukses_tambah");
            exit();
        } else {
            $error = "Gagal menyimpan ke database. Kemungkinan NIM sudah terdaftar!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa - CRUD Multi-Page</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 25px auto;
            padding: 0 15px;
        }
        h2 { margin-bottom: 5px; color: #222; }
        .student-info {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px 15px;
            margin-bottom: 15px;
            font-size: 13px;
        }
        .pesan-error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 10px 12px;
            margin-bottom: 15px;
        }
        table {
            margin-top: 15px;
        }
        table td {
            padding: 6px 8px;
        }
        input[type="text"], select, textarea {
            padding: 5px 8px;
            font-size: 14px;
            border: 1px solid #aaa;
            box-sizing: border-box;
        }
        input[type="submit"], input[type="reset"] {
            padding: 6px 14px;
            font-size: 13px;
            cursor: pointer;
            background-color: #f0f0f0;
            border: 1px solid #aaa;
        }
        input[type="submit"]:hover, input[type="reset"]:hover {
            background-color: #e0e0e0;
        }
        a {
            color: #0056b3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h2>Tugas Praktikum Pemrograman Web</h2>
    <div class="student-info">
        Nama: <strong>Narangga Adennas Shaputra</strong> &bull; NIM: <strong>2507421029</strong> &bull; Kelas: <strong>TMJ 3A</strong> &bull; Dosen: <strong>Pak Chandra</strong>
    </div>

    <p><a href="index.php">&laquo; Kembali ke Daftar Mahasiswa</a></p>

    <h3>Form Tambah Mahasiswa Baru (Multi-Page)</h3>

    <?php if (!empty($error)): ?>
        <div class="pesan-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="tambah.php" method="POST">
        <table border="0">
            <tr>
                <td width="150">NIM</td>
                <td>: <input type="text" name="nim" size="30" placeholder="Contoh: 2507421029" required></td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>: <input type="text" name="nama" size="40" placeholder="Nama mahasiswa" required></td>
            </tr>
            <tr>
                <td>Program Studi / Jurusan</td>
                <td>: 
                    <select name="jurusan" required>
                        <option value="">-- Pilih Jurusan --</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Teknik Komputer">Teknik Komputer</option>
                        <option value="Teknologi Multimedia">Teknologi Multimedia</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td valign="top">Alamat</td>
                <td>: <textarea name="alamat" rows="3" cols="40" placeholder="Alamat lengkap" required></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="Submit" value="Simpan Mahasiswa">
                    &nbsp;
                    <input type="reset" value="Reset Form">
                </td>
            </tr>
        </table>
    </form>

</body>
</html>
