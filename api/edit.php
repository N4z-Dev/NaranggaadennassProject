<?php
// ===================================================================
// FILE: edit.php (Multi-Page CRUD - Form Edit)
// REFERENSI: CodePolitan - Edit Data PHP & MySQL
// Mahasiswa : Narangga Adennas Shaputra (2507421029) - TMJ 3A
// Dosen     : Pak Chandra
// ===================================================================

require_once __DIR__ . '/koneksi.php';

$error = '';

// Proses update saat tombol Update diklik
if (isset($_POST['Update'])) {
    $id      = intval($_POST['id'] ?? 0);
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $alamat  = trim($_POST['alamat'] ?? '');

    if (empty($id) || empty($nim) || empty($nama) || empty($jurusan) || empty($alamat)) {
        $error = "Semua data wajib diisi, tidak boleh kosong!";
    } else {
        $nim_safe     = mysqli_real_escape_string($koneksi, $nim);
        $nama_safe    = mysqli_real_escape_string($koneksi, $nama);
        $jurusan_safe = mysqli_real_escape_string($koneksi, $jurusan);
        $alamat_safe  = mysqli_real_escape_string($koneksi, $alamat);

        $sql = "UPDATE mahasiswa SET 
                    nim = '$nim_safe', 
                    nama = '$nama_safe', 
                    jurusan = '$jurusan_safe', 
                    alamat = '$alamat_safe' 
                WHERE id = $id";

        if (mysqli_query($koneksi, $sql)) {
            header("Location: index.php?pesan=sukses_edit");
            exit();
        } else {
            $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
        }
    }
}

// Ambil data mahasiswa yang ingin diedit
$id = intval($_GET['id'] ?? 0);
$res = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE id = $id LIMIT 1");
$data = mysqli_fetch_assoc($res);

if (!$data) {
    die("Data mahasiswa tidak ditemukan! <a href='index.php'>Kembali</a>");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa - CRUD Multi-Page</title>
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
        input[type="submit"] {
            padding: 6px 14px;
            font-size: 13px;
            cursor: pointer;
            background-color: #f0f0f0;
            border: 1px solid #aaa;
        }
        input[type="submit"]:hover {
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

    <h3>Form Edit Data Mahasiswa (Multi-Page)</h3>

    <?php if (!empty($error)): ?>
        <div class="pesan-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="edit.php" method="POST">
        <!-- Input ID tersembunyi untuk referensi query UPDATE -->
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
                <td>Program Studi / Jurusan</td>
                <td>: 
                    <select name="jurusan" required>
                        <option value="Teknik Informatika" <?php echo ($data['jurusan'] == 'Teknik Informatika') ? 'selected' : ''; ?>>Teknik Informatika</option>
                        <option value="Sistem Informasi" <?php echo ($data['jurusan'] == 'Sistem Informasi') ? 'selected' : ''; ?>>Sistem Informasi</option>
                        <option value="Teknik Komputer" <?php echo ($data['jurusan'] == 'Teknik Komputer') ? 'selected' : ''; ?>>Teknik Komputer</option>
                        <option value="Teknologi Multimedia" <?php echo ($data['jurusan'] == 'Teknologi Multimedia') ? 'selected' : ''; ?>>Teknologi Multimedia</option>
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
                    <input type="submit" name="Update" value="Simpan Perubahan">
                    &nbsp;
                    <a href="index.php">Batal</a>
                </td>
            </tr>
        </table>
    </form>

</body>
</html>
