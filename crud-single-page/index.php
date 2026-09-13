<?php
// ===================================================================
// TUGAS PRAKTIKUM PEMROGRAMAN WEB: CRUD PHP & MYSQL
// Implementasi 2: Single-Page CRUD (Referensi: PetaniKode)
// Menggabungkan Create, Read, Update, Delete dalam satu file PHP
// Mahasiswa : Narangga Adennas Shaputra
// NIM       : 2507421029
// Kelas     : TMJ 3A
// Dosen     : Pak Chandra
// ===================================================================

require_once __DIR__ . '/koneksi.php';

$pesan = $_GET['pesan'] ?? '';
$aksi  = $_GET['aksi'] ?? '';

// 1. PROSES CREATE (TAMBAH DATA)
if (isset($_POST['btn_simpan'])) {
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $alamat  = trim($_POST['alamat'] ?? '');

    if (empty($nim) || empty($nama) || empty($jurusan) || empty($alamat)) {
        header("Location: single-page.php?pesan=kosong");
        exit();
    }

    $nim_safe     = mysqli_real_escape_string($koneksi, $nim);
    $nama_safe    = mysqli_real_escape_string($koneksi, $nama);
    $jurusan_safe = mysqli_real_escape_string($koneksi, $jurusan);
    $alamat_safe  = mysqli_real_escape_string($koneksi, $alamat);

    $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, alamat) 
            VALUES ('$nim_safe', '$nama_safe', '$jurusan_safe', '$alamat_safe')";
    
    if (mysqli_query($koneksi, $sql)) {
        header("Location: single-page.php?pesan=sukses_tambah");
        exit();
    } else {
        header("Location: single-page.php?pesan=gagal");
        exit();
    }
}

// 2. PROSES UPDATE (UBAH DATA)
if (isset($_POST['btn_ubah'])) {
    $id      = intval($_POST['id'] ?? 0);
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $alamat  = trim($_POST['alamat'] ?? '');

    if (empty($id) || empty($nim) || empty($nama) || empty($jurusan) || empty($alamat)) {
        header("Location: single-page.php?pesan=kosong");
        exit();
    }

    $nim_safe     = mysqli_real_escape_string($koneksi, $nim);
    $nama_safe    = mysqli_real_escape_string($koneksi, $nama);
    $jurusan_safe = mysqli_real_escape_string($koneksi, $jurusan);
    $alamat_safe  = mysqli_real_escape_string($koneksi, $alamat);

    $sql = "UPDATE mahasiswa SET 
                nim = '$nim_safe', 
                nama = '$nama_safe', 
                jurusan = '$jurusan_safe', 
                alamat = '$alamat_safe' 
            WHERE id = $id";
    
    if (mysqli_query($koneksi, $sql)) {
        header("Location: single-page.php?pesan=sukses_edit");
        exit();
    } else {
        header("Location: single-page.php?pesan=gagal");
        exit();
    }
}

// 3. PROSES DELETE (HAPUS DATA)
if ($aksi == 'hapus' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM mahasiswa WHERE id = $id";
    if (mysqli_query($koneksi, $sql)) {
        header("Location: single-page.php?pesan=sukses_hapus");
        exit();
    } else {
        header("Location: single-page.php?pesan=gagal");
        exit();
    }
}

// Query untuk menampilkan seluruh data (READ)
$query = mysqli_query($koneksi, "SELECT * FROM mahasiswa ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD PHP &amp; MySQL - Single-Page (PetaniKode)</title>
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
        h2 { margin-bottom: 5px; color: #222; }
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
        .nav-mode a:hover { text-decoration: underline; }
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
        fieldset {
            border: 1px solid #bbb;
            padding: 15px;
            margin-bottom: 20px;
        }
        legend {
            font-weight: bold;
            padding: 0 8px;
            color: #222;
        }
        table.form-table td {
            padding: 5px 8px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table, table.data-table th, table.data-table td {
            border: 1px solid #bbb;
        }
        table.data-table th, table.data-table td {
            padding: 8px 12px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f2f2f2;
        }
        table.data-table tr:nth-child(even) {
            background-color: #fafafa;
        }
        input[type="text"], select, textarea {
            padding: 5px 8px;
            font-size: 14px;
            border: 1px solid #aaa;
            box-sizing: border-box;
        }
        input[type="submit"], input[type="reset"] {
            padding: 6px 14px;
            font-size: 13px;
            cursor: pointer;
            background-color: #f0f0f0;
            border: 1px solid #aaa;
        }
        input[type="submit"]:hover, input[type="reset"]:hover {
            background-color: #e0e0e0;
        }
        a {
            color: #0056b3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
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

    <!-- Navigasi Pilihan Versi Tugas -->
    <div class="nav-mode">
        <strong>Pilihan Versi Tugas:</strong>
        &nbsp;&nbsp;
        <a href="index.php">[1] Versi Multi-Page (Referensi: CodePolitan)</a>
        &nbsp;|&nbsp;
        <strong>[2] Versi Single-Page (Referensi: PetaniKode)</strong>
    </div>

    <!-- Notifikasi -->
    <?php if ($pesan == 'sukses_tambah'): ?>
        <div class="pesan-sukses">Data mahasiswa berhasil disimpan!</div>
    <?php elseif ($pesan == 'sukses_edit'): ?>
        <div class="pesan-sukses">Data mahasiswa berhasil diperbarui!</div>
    <?php elseif ($pesan == 'sukses_hapus'): ?>
        <div class="pesan-sukses">Data mahasiswa berhasil dihapus!</div>
    <?php elseif ($pesan == 'kosong'): ?>
        <div class="pesan-error">Peringatan: Semua form input wajib diisi, tidak boleh kosong!</div>
    <?php elseif ($pesan == 'gagal'): ?>
        <div class="pesan-error">Terjadi kesalahan pada query database.</div>
    <?php endif; ?>

    <?php if ($aksi == 'edit' && isset($_GET['id'])): ?>
        <!-- ============================================================== -->
        <!-- FORM UBAH DATA (MUNCUL JIKA KLIK EDIT)                         -->
        <!-- ============================================================== -->
        <?php 
        $id = intval($_GET['id']);
        $res = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE id = $id LIMIT 1");
        $data = mysqli_fetch_assoc($res);
        ?>
        <fieldset>
            <legend>Ubah Data Mahasiswa</legend>
            <form action="single-page.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                <table class="form-table" border="0">
                    <tr>
                        <td width="150">NIM</td>
                        <td>: <input type="text" name="nim" value="<?php echo htmlspecialchars($data['nim']); ?>" size="30" required></td>
                    </tr>
                    <tr>
                        <td>Nama Lengkap</td>
                        <td>: <input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" size="40" required></td>
                    </tr>
                    <tr>
                        <td>Jurusan</td>
                        <td>: 
                            <select name="jurusan" required>
                                <option value="Teknik Informatika" <?php echo ($data['jurusan'] == 'Teknik Informatika') ? 'selected' : ''; ?>>Teknik Informatika</option>
                                <option value="Sistem Informasi" <?php echo ($data['jurusan'] == 'Sistem Informasi') ? 'selected' : ''; ?>>Sistem Informasi</option>
                                <option value="Teknik Komputer" <?php echo ($data['jurusan'] == 'Teknik Komputer') ? 'selected' : ''; ?>>Teknik Komputer</option>
                                <option value="Teknologi Multimedia" <?php echo ($data['jurusan'] == 'Teknologi Multimedia') ? 'selected' : ''; ?>>Teknologi Multimedia</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Alamat</td>
                        <td>: <textarea name="alamat" rows="3" cols="40" required><?php echo htmlspecialchars($data['alamat']); ?></textarea></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" name="btn_ubah" value="Simpan Perubahan">
                            &nbsp;
                            <a href="single-page.php">Batal</a>
                        </td>
                    </tr>
                </table>
            </form>
        </fieldset>

    <?php else: ?>
        <!-- ============================================================== -->
        <!-- FORM TAMBAH DATA (POLA PETANIKODE)                             -->
        <!-- ============================================================== -->
        <fieldset>
            <legend>Tambah Data Mahasiswa</legend>
            <form action="single-page.php" method="POST">
                <table class="form-table" border="0">
                    <tr>
                        <td width="150">NIM</td>
                        <td>: <input type="text" name="nim" placeholder="Contoh: 2507421029" size="30" required></td>
                    </tr>
                    <tr>
                        <td>Nama Lengkap</td>
                        <td>: <input type="text" name="nama" placeholder="Nama mahasiswa" size="40" required></td>
                    </tr>
                    <tr>
                        <td>Jurusan</td>
                        <td>: 
                            <select name="jurusan" required>
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="Teknik Informatika">Teknik Informatika</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Teknik Komputer">Teknik Komputer</option>
                                <option value="Teknologi Multimedia">Teknologi Multimedia</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Alamat</td>
                        <td>: <textarea name="alamat" rows="3" cols="40" placeholder="Alamat lengkap" required></textarea></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" name="btn_simpan" value="Simpan Mahasiswa">
                            &nbsp;
                            <input type="reset" value="Reset Form">
                        </td>
                    </tr>
                </table>
            </form>
        </fieldset>
    <?php endif; ?>

    <!-- ============================================================== -->
    <!-- TABEL DATA MAHASISWA (POLA PETANIKODE)                         -->
    <!-- ============================================================== -->
    <fieldset>
        <legend>Daftar Mahasiswa (Pola PetaniKode)</legend>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="40" style="text-align: center;">No</th>
                    <th width="120">NIM</th>
                    <th>Nama Lengkap</th>
                    <th width="180">Jurusan</th>
                    <th>Alamat</th>
                    <th width="130" style="text-align: center;">Tindakan</th>
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
                            <a href="single-page.php?aksi=edit&id=<?php echo $row['id']; ?>">Ubah</a>
                            &nbsp;|&nbsp;
                            <a href="single-page.php?aksi=hapus&id=<?php echo $row['id']; ?>" 
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
                            Belum ada data mahasiswa.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </fieldset>

</body>
</html>
