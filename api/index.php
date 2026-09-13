<?php
// ===================================================================
// TUGAS PRAKTIKUM PEMROGRAMAN WEB: CRUD PHP & MYSQL
// Implementasi 1: Multi-Page CRUD (Referensi: CodePolitan)
// Mahasiswa : Narangga Adennas Shaputra
// NIM       : 2507421029
// Kelas     : TMJ 3A
// Dosen     : Pak Chandra
// ===================================================================

require_once __DIR__ . '/koneksi.php';

// Ambil notifikasi dari URL jika ada
$pesan = $_GET['pesan'] ?? '';

// Query untuk mengambil seluruh data mahasiswa
$query = mysqli_query($koneksi, "SELECT * FROM mahasiswa ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD PHP &amp; MySQL - Multi-Page (CodePolitan)</title>
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
            margin-bottom: 5px;
            color: #222;
        }
        .student-info {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px 15px;
            margin-bottom: 15px;
            font-size: 13px;
        }
        .nav-mode {
            background-color: #f0f4f8;
            border-left: 4px solid #0056b3;
            padding: 8px 12px;
            margin-bottom: 20px;
        }
        .nav-mode a {
            color: #0056b3;
            font-weight: bold;
            text-decoration: none;
        }
        .nav-mode a:hover {
            text-decoration: underline;
        }
        .pesan-sukses {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 10px 12px;
            margin-bottom: 15px;
        }
        .pesan-error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 10px 12px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 25px;
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
            color: #222;
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
        .btn-tambah {
            display: inline-block;
            margin-bottom: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2>Tugas Praktikum Pemrograman Web</h2>
    
    <div class="student-info">
        <strong>Identitas Mahasiswa:</strong><br>
        Nama : <strong>Narangga Adennas Shaputra</strong><br>
        NIM : <strong>2507421029</strong><br>
        Kelas : <strong>TMJ 3A</strong> (Teknik Multimedia dan Jaringan)<br>
        Mata Kuliah : <strong>Pemrograman Web</strong><br>
        Dosen Pengampu : <strong>Pak Chandra</strong>
    </div>

    <!-- Navigasi Pilihan Versi Tugas Sesuai Arahan Dosen -->
    <div class="nav-mode">
        <strong>Pilihan Versi Tugas:</strong>
        &nbsp;&nbsp;
        <strong>[1] Versi Multi-Page (Referensi: CodePolitan)</strong>
        &nbsp;|&nbsp;
        <a href="single-page.php">[2] Versi Single-Page (Referensi: PetaniKode)</a>
    </div>

    <!-- Notifikasi Operasi CRUD -->
    <?php if ($pesan == 'sukses_tambah'): ?>
        <div class="pesan-sukses">Data mahasiswa berhasil ditambahkan!</div>
    <?php elseif ($pesan == 'sukses_edit'): ?>
        <div class="pesan-sukses">Data mahasiswa berhasil diperbarui!</div>
    <?php elseif ($pesan == 'sukses_hapus'): ?>
        <div class="pesan-sukses">Data mahasiswa berhasil dihapus!</div>
    <?php elseif ($pesan == 'gagal'): ?>
        <div class="pesan-error">Terjadi kesalahan pada query database.</div>
    <?php endif; ?>

    <h3>Daftar Mahasiswa (Versi Multi-Page - CodePolitan)</h3>

    <p>
        <a href="tambah.php" class="btn-tambah">[+] Tambah Mahasiswa Baru</a>
    </p>

    <table>
        <thead>
            <tr>
                <th width="40" style="text-align: center;">No</th>
                <th width="120">NIM</th>
                <th>Nama Lengkap</th>
                <th width="180">Jurusan</th>
                <th>Alamat</th>
                <th width="130" style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if ($query && mysqli_num_rows($query) > 0):
                while ($row = mysqli_fetch_assoc($query)): 
            ?>
                <tr>
                    <td style="text-align: center;"><?php echo $no++; ?></td>
                    <td><strong><?php echo htmlspecialchars($row['nim']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                    <td style="text-align: center;">
                        <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                        &nbsp;|&nbsp;
                        <a href="hapus.php?id=<?php echo $row['id']; ?>" 
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
                    <td colspan="6" style="text-align: center; color: #777; padding: 20px;">
                        Belum ada data mahasiswa dalam database.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
