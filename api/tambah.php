<?php
require_once __DIR__ . '/koneksi.php';

$error = '';

if (isset($_POST['Submit'])) {
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $alamat  = trim($_POST['alamat'] ?? '');

    if (empty($nim) || empty($nama) || empty($jurusan) || empty($alamat)) {
        header("Location: index.php?pesan=gagal_validasi");
        exit();
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
            if (mysqli_errno($koneksi) == 1062) {
                header("Location: index.php?pesan=duplikat_nim");
            } else {
                $err = mysqli_error($koneksi);
                header("Location: index.php?pesan=gagal_db&err=" . urlencode($err));
            }
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>
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
        h2 { margin-bottom: 15px; color: #222; }
        .alert {
            padding: 10px 14px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
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

    <h2>Tambah Data Mahasiswa</h2>
    <p><a href="index.php">&laquo; Kembali ke Daftar</a></p>

    <form action="tambah.php" method="POST">
        <table border="0">
            <tr>
                <td width="150">NIM</td>
                <td>: <input type="text" name="nim" size="30" required></td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>: <input type="text" name="nama" size="40" required></td>
            </tr>
            <tr>
                <td>Jurusan</td>
                <td>: 
                    <select name="jurusan" required>
                        <option value="">-- Pilih Jurusan --</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Teknik Multimedia dan Jaringan">Teknik Multimedia dan Jaringan</option>
                        <option value="Teknik Komputer">Teknik Komputer</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td valign="top">Alamat</td>
                <td>: <textarea name="alamat" rows="3" cols="40" required></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="Submit" value="Simpan">
                    &nbsp;
                    <input type="reset" value="Reset">
                </td>
            </tr>
        </table>
    </form>

</body>
</html>