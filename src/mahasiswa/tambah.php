<?php
// File: api/mahasiswa/tambah.php
// Form input untuk menambahkan data mahasiswa baru (Vercel Ready)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Mahasiswa</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

    <!-- Navigasi Menu Antar Halaman -->
    <div class="navbar">
        <a href="/mahasiswa" class="active">Data Mahasiswa</a>
        <a href="/dosen">Data Dosen</a>
    </div>

    <h2>Tambah Data Mahasiswa</h2>
    <p><a href="/mahasiswa">&laquo; Kembali ke Daftar Mahasiswa</a></p>

    <!-- Form Tambah Mahasiswa dengan Upload File Foto -->
    <form action="/mahasiswa/proses_tambah.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>NIM:</label>
            <input type="text" name="nim" placeholder="Contoh: 2507421029" required>
        </div>

        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" placeholder="Nama lengkap mahasiswa..." required>
        </div>

        <div class="form-group">
            <label>Tanggal Lahir:</label>
            <input type="date" name="tanggal_lahir" required>
        </div>

        <div class="form-group">
            <label>Nama Orang Tua / Wali:</label>
            <input type="text" name="nama_wali" placeholder="Nama orang tua atau wali..." required>
        </div>

        <div class="form-group">
            <label>Alamat Lengkap:</label>
            <textarea name="alamat" rows="3" placeholder="Alamat tempat tinggal..." required></textarea>
        </div>

        <div class="form-group">
            <label>Foto Mahasiswa:</label>
            <input type="file" name="foto" accept="image/*" required>
            <small style="display:block; color:#777; margin-top:3px;">Format: JPG, JPEG, PNG, WEBP (Maksimal 2MB)</small>
        </div>

        <div style="margin-top: 20px;">
            <input type="submit" name="btn_simpan" value="Simpan Mahasiswa">
            &nbsp;
            <a href="/mahasiswa" class="btn btn-batal">Batal</a>
        </div>
    </form>

</body>
</html>
