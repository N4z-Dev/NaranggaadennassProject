<?php
// File: api/dosen/edit.php
// Form untuk mengubah data dosen (Vercel Serverless Ready)

require_once __DIR__ . '/../koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = mysqli_query($koneksi, "SELECT * FROM dosen WHERE id = $id");
$data  = $query ? mysqli_fetch_array($query) : null;

if (!$data) {
    header("Location: /dosen");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Dosen</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

    <!-- Navigasi Menu Antar Halaman -->
    <div class="navbar">
        <a href="/mahasiswa">Data Mahasiswa</a>
        <a href="/dosen" class="active">Data Dosen</a>
    </div>

    <h2>Edit Data Dosen</h2>
    <p><a href="/dosen">&laquo; Kembali ke Daftar Dosen</a></p>

    <!-- Form Edit Data Dosen -->
    <form action="/dosen/proses_edit.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

        <div class="form-group">
            <label>NIP / NIDN:</label>
            <input type="text" name="nip_nidn" value="<?php echo htmlspecialchars($data['nip_nidn']); ?>" required>
        </div>

        <div class="form-group">
            <label>Nama Lengkap & Gelar:</label>
            <input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
        </div>

        <div class="form-group">
            <label>Tanggal Lahir:</label>
            <input type="date" name="tanggal_lahir" value="<?php echo $data['tanggal_lahir']; ?>" required>
        </div>

        <div class="form-group">
            <label>Pendidikan Terakhir:</label>
            <select name="pendidikan_terakhir" required>
                <option value="S1 - Sarjana" <?php if($data['pendidikan_terakhir'] == 'S1 - Sarjana') echo 'selected'; ?>>S1 - Sarjana</option>
                <option value="S2 - Magister" <?php if($data['pendidikan_terakhir'] == 'S2 - Magister') echo 'selected'; ?>>S2 - Magister</option>
                <option value="S3 - Doktor" <?php if($data['pendidikan_terakhir'] == 'S3 - Doktor') echo 'selected'; ?>>S3 - Doktor</option>
                <option value="Profesor" <?php if($data['pendidikan_terakhir'] == 'Profesor') echo 'selected'; ?>>Profesor</option>
            </select>
        </div>

        <div class="form-group">
            <label>Alamat Lengkap:</label>
            <textarea name="alamat" rows="3" required><?php echo htmlspecialchars($data['alamat']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Foto Dosen Saat Ini:</label>
            <?php if (!empty($data['foto'])): ?>
                <img src="<?php echo $data['foto']; ?>" alt="Foto" class="foto-preview">
            <?php else: ?>
                <span style="color: #999; font-size: 12px;">(Belum ada foto)</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Ganti Foto (Opsional):</label>
            <input type="file" name="foto" accept="image/*">
            <small style="display:block; color:#777; margin-top:3px;">Biarkan kosong jika tidak ingin mengubah foto saat ini.</small>
        </div>

        <div style="margin-top: 20px;">
            <input type="submit" name="btn_update" value="Perbarui Data">
            &nbsp;
            <a href="/dosen" class="btn btn-batal">Batal</a>
        </div>
    </form>

</body>
</html>
