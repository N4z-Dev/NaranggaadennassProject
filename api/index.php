<?php
// ===================================================================
// PRAKTIKUM PEMROGRAMAN WEB - CRUD PHP & MYSQL
// Dosen: Pak Chandra | Mahasiswa: Narangga Adennas Shaputra (2507421029)
// ===================================================================

require_once __DIR__ . '/koneksi.php';

// Cek Mode: 'multi' (CodePolitan) atau 'single' (PetaniKode)
$mode = $_GET['mode'] ?? 'multi';
$aksi = $_GET['aksi'] ?? 'index';
$pesan = $_GET['pesan'] ?? '';

// -------------------------------------------------------------------
// 1. PROSES CREATE (TAMBAH DATA)
// -------------------------------------------------------------------
if (isset($_POST['btn_simpan'])) {
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $alamat  = trim($_POST['alamat'] ?? '');

    // Validasi input tidak boleh kosong
    if (empty($nim) || empty($nama) || empty($jurusan) || empty($alamat)) {
        header("Location: index.php?mode=$mode&pesan=gagal_validasi");
        exit();
    }

    $nim_safe     = mysqli_real_escape_string($koneksi, $nim);
    $nama_safe    = mysqli_real_escape_string($koneksi, $nama);
    $jurusan_safe = mysqli_real_escape_string($koneksi, $jurusan);
    $alamat_safe  = mysqli_real_escape_string($koneksi, $alamat);

    $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, alamat) 
            VALUES ('$nim_safe', '$nama_safe', '$jurusan_safe', '$alamat_safe')";
    
    if (mysqli_query($koneksi, $sql)) {
        header("Location: index.php?mode=$mode&pesan=sukses_tambah");
        exit();
    } else {
        header("Location: index.php?mode=$mode&pesan=gagal_db");
        exit();
    }
}

// -------------------------------------------------------------------
// 2. PROSES UPDATE (SIMPAN PERUBAHAN)
// -------------------------------------------------------------------
if (isset($_POST['btn_update'])) {
    $id      = intval($_POST['id'] ?? 0);
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $alamat  = trim($_POST['alamat'] ?? '');

    if (empty($id) || empty($nim) || empty($nama) || empty($jurusan) || empty($alamat)) {
        header("Location: index.php?mode=$mode&pesan=gagal_validasi");
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
        header("Location: index.php?mode=$mode&pesan=sukses_edit");
        exit();
    } else {
        header("Location: index.php?mode=$mode&pesan=gagal_db");
        exit();
    }
}

// -------------------------------------------------------------------
// 3. PROSES DELETE (HAPUS DATA)
// -------------------------------------------------------------------
if ($aksi == 'hapus' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM mahasiswa WHERE id = $id";
    if (mysqli_query($koneksi, $sql)) {
        header("Location: index.php?mode=$mode&pesan=sukses_hapus");
        exit();
    } else {
        header("Location: index.php?mode=$mode&pesan=gagal_db");
        exit();
    }
}

// Query Ambil Semua Data Mahasiswa
$query_data = mysqli_query($koneksi, "SELECT * FROM mahasiswa ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa - CRUD PHP &amp; MySQL</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background-color: #f4f6f9; color: #333; padding-bottom: 40px; }
        
        /* Navbar */
        .navbar {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .navbar h2 { font-size: 18px; font-weight: 600; }
        .navbar p { font-size: 13px; color: #bdc3c7; }
        .mode-switch {
            display: flex;
            gap: 8px;
        }
        .mode-btn {
            padding: 7px 14px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 5px;
            text-decoration: none;
            color: #ecf0f1;
            background-color: #34495e;
            transition: all 0.2s;
        }
        .mode-btn:hover { background-color: #415b76; }
        .mode-btn.active {
            background-color: #3498db;
            color: #fff;
        }

        .container {
            max-width: 960px;
            margin: 25px auto;
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #ecf0f1;
        }
        .header-section h1 { font-size: 22px; color: #2c3e50; }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-primary { background-color: #3498db; color: #fff; }
        .btn-primary:hover { background-color: #2980b9; }
        .btn-success { background-color: #2ecc71; color: #fff; }
        .btn-success:hover { background-color: #27ae60; }
        .btn-danger { background-color: #e74c3c; color: #fff; }
        .btn-danger:hover { background-color: #c0392b; }
        .btn-secondary { background-color: #95a5a6; color: #fff; }
        .btn-secondary:hover { background-color: #7f8c8d; }
        .btn-sm { padding: 4px 10px; font-size: 12px; }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
            font-size: 14px;
        }
        table th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
        }
        table tr:hover { background-color: #fcfdfe; }

        /* Form */
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #34495e;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 14px;
            outline: none;
            background: #fff;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #3498db;
        }
        .card-form {
            background: #fdfdfd;
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<!-- Navbar Atas -->
<div class="navbar">
    <div>
        <h2>Sistem Data Mahasiswa (CRUD PHP &amp; MySQL)</h2>
        <p>Narangga Adennas Shaputra (NIM: 2507421029) &bull; Kelas TMJ 3A &bull; Dosen: Pak Chandra</p>
    </div>
    <div class="mode-switch">
        <a href="index.php?mode=multi" class="mode-btn <?php echo ($mode == 'multi') ? 'active' : ''; ?>">
            Mode Multi-Page (CodePolitan)
        </a>
        <a href="index.php?mode=single" class="mode-btn <?php echo ($mode == 'single') ? 'active' : ''; ?>">
            Mode Single-Page (PetaniKode)
        </a>
    </div>
</div>

<div class="container">

    <!-- Pesan Status Operasi -->
    <?php if ($pesan == 'sukses_tambah'): ?>
        <div class="alert alert-success">Data mahasiswa berhasil ditambahkan ke database!</div>
    <?php elseif ($pesan == 'sukses_edit'): ?>
        <div class="alert alert-success">Data mahasiswa berhasil diperbarui!</div>
    <?php elseif ($pesan == 'sukses_hapus'): ?>
        <div class="alert alert-success">Data mahasiswa berhasil dihapus dari database!</div>
    <?php elseif ($pesan == 'gagal_validasi'): ?>
        <div class="alert alert-danger">Peringatan: Semua kolom formulir wajib diisi, tidak boleh kosong!</div>
    <?php elseif ($pesan == 'gagal_db'): ?>
        <div class="alert alert-danger">Terjadi kesalahan pada database (NIM mungkin sudah terdaftar)!</div>
    <?php endif; ?>

    <?php if ($mode == 'multi'): ?>
        <!-- ============================================================== -->
        <!-- IMPLEMENTASI 1: MULTI-PAGE CRUD (REFERENSI CODEPOLITAN)        -->
        <!-- ============================================================== -->

        <?php if ($aksi == 'tambah'): ?>
            <!-- Halaman Form Tambah Mahasiswa Baru -->
            <div class="header-section">
                <h1>Form Tambah Mahasiswa Baru</h1>
                <a href="index.php?mode=multi" class="btn btn-secondary">&laquo; Kembali ke Daftar</a>
            </div>

            <form action="index.php?mode=multi" method="POST" style="max-width: 600px;">
                <div class="form-group">
                    <label for="nim">Nomor Induk Mahasiswa (NIM):</label>
                    <input type="text" id="nim" name="nim" placeholder="Contoh: 2507421029" required autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="nama">Nama Lengkap:</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required autocomplete="off">
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

                <div style="margin-top: 20px; display: flex; gap: 10px;">
                    <button type="submit" name="btn_simpan" class="btn btn-primary">Simpan Data</button>
                    <a href="index.php?mode=multi" class="btn btn-secondary">Batal</a>
                </div>
            </form>

        <?php elseif ($aksi == 'edit' && isset($_GET['id'])): ?>
            <!-- Halaman Form Edit Mahasiswa -->
            <?php 
            $id = intval($_GET['id']);
            $res_edit = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE id = $id LIMIT 1");
            $data_edit = mysqli_fetch_assoc($res_edit);
            if (!$data_edit) {
                die("Data tidak ditemukan!");
            }
            ?>
            <div class="header-section">
                <h1>Form Edit Data Mahasiswa</h1>
                <a href="index.php?mode=multi" class="btn btn-secondary">&laquo; Kembali ke Daftar</a>
            </div>

            <form action="index.php?mode=multi" method="POST" style="max-width: 600px;">
                <!-- Input hidden ID agar backend tahu ID yang akan di-update di WHERE clause -->
                <input type="hidden" name="id" value="<?php echo $data_edit['id']; ?>">

                <div class="form-group">
                    <label for="nim">Nomor Induk Mahasiswa (NIM):</label>
                    <input type="text" id="nim" name="nim" value="<?php echo htmlspecialchars($data_edit['nim']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="nama">Nama Lengkap:</label>
                    <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($data_edit['nama']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="jurusan">Program Studi / Jurusan:</label>
                    <select id="jurusan" name="jurusan" required>
                        <option value="Teknik Informatika" <?php echo ($data_edit['jurusan'] == 'Teknik Informatika') ? 'selected' : ''; ?>>Teknik Informatika</option>
                        <option value="Sistem Informasi" <?php echo ($data_edit['jurusan'] == 'Sistem Informasi') ? 'selected' : ''; ?>>Sistem Informasi</option>
                        <option value="Teknik Komputer" <?php echo ($data_edit['jurusan'] == 'Teknik Komputer') ? 'selected' : ''; ?>>Teknik Komputer</option>
                        <option value="Teknologi Multimedia" <?php echo ($data_edit['jurusan'] == 'Teknologi Multimedia') ? 'selected' : ''; ?>>Teknologi Multimedia</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat Lengkap:</label>
                    <textarea id="alamat" name="alamat" rows="3" required><?php echo htmlspecialchars($data_edit['alamat']); ?></textarea>
                </div>

                <div style="margin-top: 20px; display: flex; gap: 10px;">
                    <button type="submit" name="btn_update" class="btn btn-success">Simpan Perubahan</button>
                    <a href="index.php?mode=multi" class="btn btn-secondary">Batal</a>
                </div>
            </form>

        <?php else: ?>
            <!-- Halaman Utama Daftar Mahasiswa (READ) -->
            <div class="header-section">
                <h1>Daftar Mahasiswa (Versi Multi-Page)</h1>
                <a href="index.php?mode=multi&aksi=tambah" class="btn btn-primary">+ Tambah Mahasiswa Baru</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Jurusan</th>
                        <th>Alamat</th>
                        <th style="text-align: center; width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($query_data) > 0):
                        while ($row = mysqli_fetch_assoc($query_data)): 
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nim']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                            <td style="text-align: center;">
                                <a href="index.php?mode=multi&aksi=edit&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                <a href="index.php?mode=multi&aksi=hapus&id=<?php echo $row['id']; ?>" 
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
                                Belum ada data mahasiswa di database. Silakan klik tombol <strong>+ Tambah Mahasiswa Baru</strong> di atas.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>

    <?php else: ?>
        <!-- ============================================================== -->
        <!-- IMPLEMENTASI 2: SINGLE-PAGE CRUD (REFERENSI PETANIKODE)        -->
        <!-- Seluruh Form & Tabel Bersatu dalam 1 Halaman                   -->
        <!-- ============================================================== -->

        <div class="header-section">
            <h1>CRUD Single-Page (Pola PetaniKode)</h1>
            <span style="font-size: 13px; color: #7f8c8d;">Form &amp; Tabel dalam 1 File PHP</span>
        </div>

        <?php if ($aksi == 'edit' && isset($_GET['id'])): ?>
            <!-- Mode Edit Single-Page -->
            <?php 
            $id = intval($_GET['id']);
            $res_edit = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE id = $id LIMIT 1");
            $data_edit = mysqli_fetch_assoc($res_edit);
            ?>
            <div class="card-form" style="background-color: #fffde7; border-color: #fff59d;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 12px;">
                    <h3 style="font-size: 16px; color: #f57f17;">Form Ubah Data Mahasiswa</h3>
                    <a href="index.php?mode=single" class="btn btn-secondary btn-sm">Batal Ubah</a>
                </div>
                <form action="index.php?mode=single" method="POST">
                    <input type="hidden" name="id" value="<?php echo $data_edit['id']; ?>">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>NIM:</label>
                            <input type="text" name="nim" value="<?php echo htmlspecialchars($data_edit['nim']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Lengkap:</label>
                            <input type="text" name="nama" value="<?php echo htmlspecialchars($data_edit['nama']); ?>" required>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Jurusan:</label>
                            <select name="jurusan" required>
                                <option value="Teknik Informatika" <?php echo ($data_edit['jurusan'] == 'Teknik Informatika') ? 'selected' : ''; ?>>Teknik Informatika</option>
                                <option value="Sistem Informasi" <?php echo ($data_edit['jurusan'] == 'Sistem Informasi') ? 'selected' : ''; ?>>Sistem Informasi</option>
                                <option value="Teknik Komputer" <?php echo ($data_edit['jurusan'] == 'Teknik Komputer') ? 'selected' : ''; ?>>Teknik Komputer</option>
                                <option value="Teknologi Multimedia" <?php echo ($data_edit['jurusan'] == 'Teknologi Multimedia') ? 'selected' : ''; ?>>Teknologi Multimedia</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Alamat:</label>
                            <input type="text" name="alamat" value="<?php echo htmlspecialchars($data_edit['alamat']); ?>" required>
                        </div>
                    </div>
                    <button type="submit" name="btn_update" class="btn btn-success">Simpan Perubahan</button>
                </form>
            </div>
        <?php else: ?>
            <!-- Mode Tambah Single-Page -->
            <div class="card-form">
                <h3 style="font-size: 16px; margin-bottom: 12px; color: #2c3e50;">Tambah Data Mahasiswa</h3>
                <form action="index.php?mode=single" method="POST">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>NIM:</label>
                            <input type="text" name="nim" placeholder="Contoh: 2507421029" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Nama Lengkap:</label>
                            <input type="text" name="nama" placeholder="Masukkan nama mahasiswa" required autocomplete="off">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Jurusan:</label>
                            <select name="jurusan" required>
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="Teknik Informatika">Teknik Informatika</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Teknik Komputer">Teknik Komputer</option>
                                <option value="Teknologi Multimedia">Teknologi Multimedia</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Alamat:</label>
                            <input type="text" name="alamat" placeholder="Masukkan alamat" required>
                        </div>
                    </div>
                    <button type="submit" name="btn_simpan" class="btn btn-primary">Simpan Mahasiswa</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Tabel Data Langsung Ditampilkan di Bawah Form (Single-Page) -->
        <h3 style="font-size: 16px; margin-top: 25px; margin-bottom: 10px; color: #2c3e50;">Daftar Mahasiswa Terdaftar</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>NIM</th>
                    <th>Nama Lengkap</th>
                    <th>Jurusan</th>
                    <th>Alamat</th>
                    <th style="text-align: center; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if (mysqli_num_rows($query_data) > 0):
                    while ($row = mysqli_fetch_assoc($query_data)): 
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['nim']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                        <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                        <td style="text-align: center;">
                            <a href="index.php?mode=single&aksi=edit&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                            <a href="index.php?mode=single&aksi=hapus&id=<?php echo $row['id']; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Hapus data <?php echo htmlspecialchars($row['nama']); ?>?');">
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

    <?php endif; ?>

</div>

</body>
</html>
