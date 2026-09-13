<?php
// =======================================================
// FILE: tambah.php (Multi-Page CRUD)
// FUNGSI: Form HTML untuk menginputkan data mahasiswa baru
// REFERENSI: CodePolitan - Multi-page PHP & MySQL CRUD
// =======================================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa - CRUD Multi-Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container" style="max-width: 600px;">
    <header>
        <h1>Form Tambah Mahasiswa Baru</h1>
        <p>Silakan isi seluruh input form di bawah ini. Semua field wajib diisi.</p>
    </header>

    <!-- 
      ALUR FORM KE POST:
      - method="POST": Mengirimkan data secara tersembunyi via body HTTP Request (tidak di URL).
      - action="proses_tambah.php": File target backend pemroses request.
      - Atribut name: Kunci asosiatif yang akan diterima di $_POST['nama_input'].
    -->
    <form action="proses_tambah.php" method="POST">
        <div class="form-group">
            <label for="nim">Nomor Induk Mahasiswa (NIM):</label>
            <input type="text" id="nim" name="nim" placeholder="Contoh: 2507421029" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="nama">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" placeholder="Masukkan nama mahasiswa" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="jurusan">Program Studi / Jurusan:</label>
            <select id="jurusan" name="jurusan" required>
                <option value="">-- Pilih Jurusan --</option>
                <option value="Teknik Informatika">Teknik Informatika</option>
                <option value="Sistem Informasi">Sistem Informasi</option>
                <option value="Teknik Komputer">Teknik Komputer</option>
                <option value="Teknologi Multimedia">Teknologi Multimedia</option>
            </select>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat Lengkap:</label>
            <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat domisili" required></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" name="btn_simpan" class="btn btn-primary">Simpan Data</button>
            <a href="index.php" class="btn btn-secondary">Batal & Kembali</a>
        </div>
    </form>
</div>

</body>
</html>
