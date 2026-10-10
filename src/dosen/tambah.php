<?php
// File: api/dosen/tambah.php
// Form input untuk menambahkan data dosen baru (Vercel Serverless Ready)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Dosen</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

    <!-- Navigasi Menu Antar Halaman -->
    <div class="navbar">
        <a href="/mahasiswa">Data Mahasiswa</a>
        <a href="/dosen" class="active">Data Dosen</a>
    </div>

    <h2>Tambah Data Dosen</h2>
    <p><a href="/dosen">&laquo; Kembali ke Daftar Dosen</a></p>

    <!-- Form Tambah Dosen dengan Upload File Foto -->
    <form action="/dosen/proses_tambah.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>NIP / NIDN:</label>
            <input type="text" name="nip_nidn" placeholder="Contoh: 198507122010121001" required>
        </div>

        <div class="form-group">
            <label>Nama Lengkap & Gelar:</label>
            <input type="text" name="nama" placeholder="Contoh: Dr. Budiarto, M.Kom." required>
        </div>

        <div class="form-group">
            <label>Tanggal Lahir:</label>
            <input type="date" name="tanggal_lahir" required>
        </div>

        <div class="form-group">
            <label>Pendidikan Terakhir:</label>
            <select name="pendidikan_terakhir" required>
                <option value="" disabled selected>-- Pilih Pendidikan Terakhir --</option>
                <option value="S1 - Sarjana">S1 - Sarjana</option>
                <option value="S2 - Magister">S2 - Magister</option>
                <option value="S3 - Doktor">S3 - Doktor</option>
                <option value="Profesor">Profesor</option>
            </select>
        </div>

        <div class="form-group">
            <label>Alamat Lengkap:</label>
            <textarea name="alamat" rows="3" placeholder="Alamat domisili dosen..." required></textarea>
        </div>

        <div class="form-group">
            <label>Foto Dosen:</label>
            <input type="file" name="foto" accept="image/*" required>
            <small style="display:block; color:#777; margin-top:3px;">Format: JPG, JPEG, PNG, WEBP (Maksimal 2MB)</small>
        </div>

        <div style="margin-top: 20px;">
            <input type="submit" name="btn_simpan" value="Simpan Dosen">
            &nbsp;
            <a href="/dosen" class="btn btn-batal">Batal</a>
        </div>
    </form>

</body>
</html>
