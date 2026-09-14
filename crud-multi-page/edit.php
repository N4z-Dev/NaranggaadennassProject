<?php
require_once 'koneksi.php';

$id = intval($_GET['id'] ?? 0);
$query = "SELECT * FROM mahasiswa WHERE id = $id LIMIT 1";
$hasil = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($hasil);

if (!$data) {
    die("Data mahasiswa tidak ditemukan! <a href='index.php'>Kembali</a>");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Edit Data Mahasiswa</h2>
    <p><a href="index.php">&laquo; Kembali ke Daftar</a></p>

    <form action="proses_edit.php" method="POST">
        <!-- Hidden input untuk ID data yang akan diupdate -->
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

        <table border="0">
            <tr>
                <td width="150">NIM</td>
                <td>: <input type="text" name="nim" value="<?php echo htmlspecialchars($data['nim']); ?>" size="30" required></td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>: <input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" size="40" required></td>
            </tr>
            <tr>
                <td>Jurusan</td>
                <td>: 
                    <select name="jurusan" required>
                        <option value="Teknik Informatika" <?php echo ($data['jurusan'] == 'Teknik Informatika') ? 'selected' : ''; ?>>Teknik Informatika</option>
                        <option value="Teknik Multimedia dan Jaringan" <?php echo ($data['jurusan'] == 'Teknik Multimedia dan Jaringan') ? 'selected' : ''; ?>>Teknik Multimedia dan Jaringan</option>
                        <option value="Teknik Komputer" <?php echo ($data['jurusan'] == 'Teknik Komputer') ? 'selected' : ''; ?>>Teknik Komputer</option>
                        <option value="Sistem Informasi" <?php echo ($data['jurusan'] == 'Sistem Informasi') ? 'selected' : ''; ?>>Sistem Informasi</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td valign="top">Alamat</td>
                <td>: <textarea name="alamat" rows="3" cols="40" required><?php echo htmlspecialchars($data['alamat']); ?></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="btn_update" value="Simpan Perubahan">
                    &nbsp;
                    <a href="index.php">Batal</a>
                </td>
            </tr>
        </table>
    </form>
</div>

</body>
</html>