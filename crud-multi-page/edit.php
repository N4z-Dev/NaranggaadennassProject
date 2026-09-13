<?php
// =======================================================
// FILE: edit.php (Multi-Page CRUD)
// FUNGSI: Form HTML untuk mengubah data mahasiswa yang ada.
//         Mengambil data lama dari database dan mengisikannya ke input value.
// =======================================================

require_once 'koneksi.php';

// Cek apakah parameter id tersedia di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// Ambil data mahasiswa berdasarkan ID
$query  = "SELECT * FROM mahasiswa WHERE id = $id LIMIT 1";
$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Data mahasiswa tidak ditemukan!");
}

$data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa - CRUD Multi-Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container" style="max-width: 600px;">
    <header>
        <h1>Form Edit Data Mahasiswa</h1>
        <p>Silakan ubah data mahasiswa di bawah ini dengan lengkap dan benar.</p>
    </header>

    <form action="proses_edit.php" method="POST">
        <!-- 
          Input hidden ID: Sangat penting agar backend tahu baris ID mana yang akan di-update di WHERE clause.
        -->
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

        <div class="form-group">
            <label for="nim">Nomor Induk Mahasiswa (NIM):</label>
            <input type="text" id="nim" name="nim" value="<?php echo htmlspecialchars($data['nim']); ?>" required>
        </div>

        <div class="form-group">
            <label for="nama">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
        </div>

        <div class="form-group">
            <label for="jurusan">Program Studi / Jurusan:</label>
            <select id="jurusan" name="jurusan" required>
                <option value="">-- Pilih Jurusan --</option>
                <option value="Teknik Informatika" <?php echo ($data['jurusan'] == 'Teknik Informatika') ? 'selected' : ''; ?>>Teknik Informatika</option>
                <option value="Sistem Informasi" <?php echo ($data['jurusan'] == 'Sistem Informasi') ? 'selected' : ''; ?>>Sistem Informasi</option>
                <option value="Teknik Komputer" <?php echo ($data['jurusan'] == 'Teknik Komputer') ? 'selected' : ''; ?>>Teknik Komputer</option>
                <option value="Teknologi Multimedia" <?php echo ($data['jurusan'] == 'Teknologi Multimedia') ? 'selected' : ''; ?>>Teknologi Multimedia</option>
            </select>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat Lengkap:</label>
            <textarea id="alamat" name="alamat" rows="3" required><?php echo htmlspecialchars($data['alamat']); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" name="btn_update" class="btn btn-success">Simpan Perubahan</button>
            <a href="index.php" class="btn btn-secondary">Batal & Kembali</a>
        </div>
    </form>
</div>

</body>
</html>
