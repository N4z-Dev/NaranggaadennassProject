<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Tambah Data Mahasiswa</h2>
    <p><a href="index.php">&laquo; Kembali ke Daftar</a></p>

    <form action="proses_tambah.php" method="POST">
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
                    <input type="submit" name="btn_simpan" value="Simpan">
                    &nbsp;
                    <input type="reset" value="Reset">
                </td>
            </tr>
        </table>
    </form>
</div>

</body>
</html>