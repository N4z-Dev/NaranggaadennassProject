<?php
// File: api/dosen/index.php
// Menampilkan daftar data dosen (Vercel Serverless & TiDB Cloud Ready)

require_once __DIR__ . '/../koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dosen</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

    <!-- Navigasi Menu Antar Halaman -->
    <div class="navbar">
        <a href="/mahasiswa">Data Mahasiswa</a>
        <a href="/dosen" class="active">Data Dosen</a>
    </div>

    <h2>Data Dosen</h2>

    <!-- Notifikasi Status Operasi CRUD -->
    <?php if (isset($_GET['pesan'])): ?>
        <?php if ($_GET['pesan'] == 'sukses_tambah'): ?>
            <div class="alert alert-success">Data dosen berhasil ditambahkan!</div>
        <?php elseif ($_GET['pesan'] == 'sukses_edit'): ?>
            <div class="alert alert-success">Data dosen berhasil diperbarui!</div>
        <?php elseif ($_GET['pesan'] == 'sukses_hapus'): ?>
            <div class="alert alert-success">Data dosen berhasil dihapus!</div>
        <?php elseif ($_GET['pesan'] == 'gagal_validasi'): ?>
            <div class="alert alert-danger">Gagal: Semua kolom form wajib diisi!</div>
        <?php elseif ($_GET['pesan'] == 'gagal_foto'): ?>
            <div class="alert alert-danger">Gagal upload foto! Pastikan format file JPG/PNG/WEBP dan ukuran maksimal 2MB.</div>
        <?php elseif ($_GET['pesan'] == 'duplikat_nip'): ?>
            <div class="alert alert-danger">Gagal: NIP / NIDN sudah terdaftar di database!</div>
        <?php endif; ?>
    <?php endif; ?>

    <p>
        <a href="/dosen/tambah.php" class="btn">[+] Tambah Dosen Baru</a>
    </p>

    <!-- Tabel Menampilkan Data Dosen -->
    <table>
        <thead>
            <tr>
                <th style="width: 35px; text-align: center;">No</th>
                <th style="width: 65px; text-align: center;">Foto</th>
                <th style="width: 140px;">NIP / NIDN</th>
                <th>Nama Dosen</th>
                <th style="width: 120px;">Pendidikan</th>
                <th style="width: 100px;">Tgl Lahir</th>
                <th>Alamat</th>
                <th style="width: 110px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = mysqli_query($koneksi, "SELECT * FROM dosen ORDER BY id DESC");
            if ($query && mysqli_num_rows($query) > 0):
                while ($data = mysqli_fetch_array($query)):
            ?>
                <tr>
                    <td style="text-align: center;"><?php echo $no++; ?></td>
                    <td style="text-align: center;">
                        <?php if (!empty($data['foto'])): ?>
                            <img src="<?php echo $data['foto']; ?>" alt="Foto" class="foto-tabel">
                        <?php else: ?>
                            <span style="color: #999; font-size: 11px;">Tidak ada</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($data['nip_nidn']); ?></td>
                    <td><strong><?php echo htmlspecialchars($data['nama']); ?></strong></td>
                    <td><?php echo htmlspecialchars($data['pendidikan_terakhir']); ?></td>
                    <td><?php echo date('d-m-Y', strtotime($data['tanggal_lahir'])); ?></td>
                    <td><?php echo htmlspecialchars($data['alamat']); ?></td>
                    <td style="text-align: center;">
                        <a href="/dosen/edit.php?id=<?php echo $data['id']; ?>">Edit</a>
                        &nbsp;|&nbsp;
                        <a href="/dosen/hapus.php?id=<?php echo $data['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data dosen ini?');">Hapus</a>
                    </td>
                </tr>
            <?php
                endwhile;
            else:
            ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: #888; padding: 15px;">Belum ada data dosen.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
