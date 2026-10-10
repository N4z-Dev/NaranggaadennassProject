<?php
// File: api/mahasiswa/edit.php
// Form untuk mengubah data mahasiswa (Vercel Serverless Ready)

require_once __DIR__ . '/../koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE id = $id");
$data  = $query ? mysqli_fetch_array($query) : null;

if (!$data) {
    header("Location: /mahasiswa");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Mahasiswa</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

    <!-- Navigasi Menu Antar Halaman -->
    <div class="navbar">
        <a href="/mahasiswa" class="active">Data Mahasiswa</a>
        <a href="/dosen">Data Dosen</a>
    </div>

    <h2>Edit Data Mahasiswa</h2>
    <p><a href="/mahasiswa">&laquo; Kembali ke Daftar Mahasiswa</a></p>

    <!-- Form Edit Mahasiswa -->
    <form action="/mahasiswa/proses_edit.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

        <div class="form-group">
            <label>NIM:</label>
            <input type="text" name="nim" value="<?php echo htmlspecialchars($data['nim']); ?>" required>
        </div>

        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
        </div>

        <div class="form-group">
            <label>Tanggal Lahir:</label>
            <input type="date" name="tanggal_lahir" value="<?php echo $data['tanggal_lahir']; ?>" required>
        </div>

        <div class="form-group">
            <label>Nama Orang Tua / Wali:</label>
            <input type="text" name="nama_wali" value="<?php echo htmlspecialchars($data['nama_wali']); ?>" required>
        </div>

        <div class="form-group">
            <label>Alamat Lengkap:</label>
            <textarea name="alamat" rows="3" required><?php echo htmlspecialchars($data['alamat']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Foto Mahasiswa Saat Ini:</label>
            <?php if (!empty($data['foto'])): ?>
                <img src="<?php echo $data['foto']; ?>" alt="Foto" class="foto-preview">
            <?php else: ?>
                <span style="color: #999; font-size: 12px;">(Belum ada foto)</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Ganti Foto (Opsional):</label>
            <input type="file" name="foto" accept="image/*">
            <small style="display:block; color:#777; margin-top:3px;">Biarkan kosong jika tidak ingin mengubah foto yang sudah ada.</small>
        </div>

        <div style="margin-top: 20px;">
            <input type="submit" name="btn_update" value="Perbarui Data">
            &nbsp;
            <a href="/mahasiswa" class="btn btn-batal">Batal</a>
        </div>
    </form>

</body>
</html>
