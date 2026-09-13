<?php
// =======================================================
// FILE: index.php (Multi-Page CRUD)
// FUNGSI: Menampilkan seluruh data mahasiswa (READ)
// REFERENSI: CodePolitan - Multi-page PHP & MySQL CRUD
// =======================================================

require_once 'koneksi.php';

// Menjalankan query untuk mengambil seluruh data mahasiswa
$query = "SELECT * FROM mahasiswa ORDER BY id DESC";
$hasil = mysqli_query($koneksi, $query);

if (!$hasil) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa - CRUD Multi-Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Daftar Mahasiswa (CRUD Multi-Page)</h1>
        <p>Demo Praktikum Pemrograman Web - Dosen Pengampu: Pak Chandra</p>
    </header>

    <!-- Notifikasi Status Operasi -->
    <?php if (isset($_GET['pesan'])): ?>
        <?php if ($_GET['pesan'] == 'sukses_tambah'): ?>
            <div class="alert alert-success">Data mahasiswa berhasil ditambahkan!</div>
        <?php elseif ($_GET['pesan'] == 'sukses_edit'): ?>
            <div class="alert alert-success">Data mahasiswa berhasil diperbarui!</div>
        <?php elseif ($_GET['pesan'] == 'sukses_hapus'): ?>
            <div class="alert alert-success">Data mahasiswa berhasil dihapus!</div>
        <?php elseif ($_GET['pesan'] == 'gagal_validasi'): ?>
            <div class="alert alert-danger">Peringatan: Semua form input wajib diisi, tidak boleh kosong!</div>
        <?php elseif ($_GET['pesan'] == 'gagal_db'): ?>
            <div class="alert alert-danger">Terjadi kesalahan pada database atau NIM sudah terdaftar!</div>
        <?php endif; ?>
    <?php endif; ?>

    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <a href="tambah.php" class="btn btn-primary">+ Tambah Mahasiswa Baru</a>
        <a href="../crud-single-page/index.php" class="btn btn-secondary btn-sm">Buka Versi Single-Page &rarr;</a>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>NIM</th>
                <th>Nama Lengkap</th>
                <th>Jurusan</th>
                <th>Alamat</th>
                <th style="width: 150px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (mysqli_num_rows($hasil) > 0):
                while ($row = mysqli_fetch_assoc($hasil)): 
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><strong><?php echo htmlspecialchars($row['nim']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                    <td style="text-align: center;">
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                        <a href="hapus.php?id=<?php echo $row['id']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus data <?php echo htmlspecialchars($row['nama']); ?>?');">
                           Hapus
                        </a>
                    </td>
                </tr>
            <?php 
                endwhile;
            else:
            ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #888; padding: 25px;">
                        Belum ada data mahasiswa. Silakan klik tombol <strong>+ Tambah Mahasiswa Baru</strong> di atas.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
