<?php
require_once __DIR__ . '/koneksi.php';

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
    <title>Data Mahasiswa</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            max-width: 900px;
            margin: 25px auto;
            padding: 0 15px;
        }
        h2 {
            color: #222;
            margin-bottom: 15px;
        }
        .nav-mode {
            background-color: #f0f4f8;
            padding: 8px 12px;
            margin-bottom: 20px;
        }
        .nav-mode a {
            color: #0056b3;
            text-decoration: none;
        }
        .nav-mode a:hover {
            text-decoration: underline;
        }
        .alert {
            padding: 10px 14px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #bbb;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        a {
            color: #0056b3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .btn {
            display: inline-block;
            padding: 6px 12px;
            background-color: #0056b3;
            color: #fff !important;
            text-decoration: none;
            border-radius: 3px;
            font-size: 13px;
        }
        .btn:hover {
            background-color: #004085;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="nav-mode">
        <strong>Multi-Page</strong>
        &nbsp;|&nbsp;
        <a href="single-page.php">Single-Page</a>
    </div>

    <h2>Data Mahasiswa</h2>

    <?php if (isset($_GET['pesan'])): ?>
        <?php if ($_GET['pesan'] == 'sukses_tambah'): ?>
            <div class="alert alert-success">Data mahasiswa berhasil ditambahkan!</div>
        <?php elseif ($_GET['pesan'] == 'sukses_edit'): ?>
            <div class="alert alert-success">Data mahasiswa berhasil diperbarui!</div>
        <?php elseif ($_GET['pesan'] == 'sukses_hapus'): ?>
            <div class="alert alert-success">Data mahasiswa berhasil dihapus!</div>
        <?php elseif ($_GET['pesan'] == 'gagal_validasi'): ?>
            <div class="alert alert-danger">Semua form input wajib diisi!</div>
        <?php elseif ($_GET['pesan'] == 'duplikat_nim'): ?>
            <div class="alert alert-danger">Gagal menyimpan: <strong>NIM sudah terdaftar</strong> di database! Silakan gunakan NIM lain.</div>
        <?php elseif ($_GET['pesan'] == 'gagal_db'): ?>
            <div class="alert alert-danger">Terjadi kesalahan pada database<?php echo !empty($_GET['err']) ? ': ' . htmlspecialchars($_GET['err']) : '.'; ?></div>
        <?php endif; ?>
    <?php endif; ?>

    <p>
        <a href="tambah.php" class="btn">[+] Tambah Mahasiswa Baru</a>
    </p>

    <table>
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No</th>
                <th style="width: 120px;">NIM</th>
                <th>Nama Lengkap</th>
                <th style="width: 200px;">Jurusan</th>
                <th>Alamat</th>
                <th style="width: 130px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (mysqli_num_rows($hasil) > 0):
                while ($row = mysqli_fetch_assoc($hasil)): 
            ?>
                <tr>
                    <td style="text-align: center;"><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['nim']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                    <td style="text-align: center;">
                        <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                        &nbsp;|&nbsp;
                        <a href="hapus.php?id=<?php echo $row['id']; ?>" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                           Hapus
                        </a>
                    </td>
                </tr>
            <?php 
                endwhile;
            else:
            ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #888; padding: 20px;">
                        Belum ada data mahasiswa.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>