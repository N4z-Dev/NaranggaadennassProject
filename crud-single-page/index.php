<?php
// ===================================================================
// FILE: index.php (Single-Page CRUD)
// FUNGSI: Menggabungkan operasi Create, Read, Update, Delete ke dalam 1 file
// REFERENSI: PetaniKode - Kode CRUD dalam Satu File PHP
// ===================================================================

// --- 1. KONEKSI KE DATABASE ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_kampus";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Global feedback message
$pesan = "";
$pesan_tipe = ""; // success atau danger

// ===================================================================
// --- 2. FUNGSI CREATE (TAMBAH DATA) ---
// ===================================================================
function tambah($koneksi) {
    global $pesan, $pesan_tipe;

    // Alur: Form -> POST -> Validasi -> Konversi Variabel -> Query INSERT -> Koneksi -> Tampil Kembali
    if (isset($_POST['btn_simpan'])) {
        $nim     = trim($_POST['nim']);
        $nama    = trim($_POST['nama']);
        $jurusan = trim($_POST['jurusan']);
        $alamat  = trim($_POST['alamat']);

        // Validasi input tidak boleh kosong
        if (!empty($nim) && !empty($nama) && !empty($jurusan) && !empty($alamat)) {
            $nim_safe     = mysqli_real_escape_string($koneksi, $nim);
            $nama_safe    = mysqli_real_escape_string($koneksi, $nama);
            $jurusan_safe = mysqli_real_escape_string($koneksi, $jurusan);
            $alamat_safe  = mysqli_real_escape_string($koneksi, $alamat);

            $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, alamat) 
                    VALUES ('$nim_safe', '$nama_safe', '$jurusan_safe', '$alamat_safe')";
            $simpan = mysqli_query($koneksi, $sql);

            if ($simpan) {
                $pesan = "Data mahasiswa berhasil disimpan!";
                $pesan_tipe = "success";
                // Jika berada pada aksi create tersendiri, redirect ke halaman utama
                if (isset($_GET['aksi']) && $_GET['aksi'] == 'create') {
                    header("Location: index.php?status=sukses_tambah");
                    exit();
                }
            } else {
                $pesan = "Gagal menyimpan data ke database (NIM mungkin sudah ada)!";
                $pesan_tipe = "danger";
            }
        } else {
            $pesan = "Peringatan: Semua field formulir wajib diisi, tidak boleh kosong!";
            $pesan_tipe = "danger";
        }
    }
    ?>
    <div class="card-form">
        <h2>Tambah Data Mahasiswa</h2>
        <?php if (!empty($pesan)): ?>
            <div class="alert alert-<?php echo $pesan_tipe; ?>"><?php echo $pesan; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="nim">NIM (Nomor Induk Mahasiswa):</label>
                <input type="text" id="nim" name="nim" placeholder="Contoh: 2507421029" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="nama">Nama Lengkap:</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="jurusan">Jurusan / Program Studi:</label>
                <select id="jurusan" name="jurusan" required>
                    <option value="">-- Pilih Jurusan --</option>
                    <option value="Teknik Informatika">Teknik Informatika</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Teknik Komputer">Teknik Komputer</option>
                    <option value="Teknologi Multimedia">Teknologi Multimedia</option>
                </select>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
            </div>

            <div class="form-actions">
                <input type="submit" name="btn_simpan" value="Simpan Mahasiswa" class="btn btn-primary">
                <input type="reset" value="Reset Form" class="btn btn-secondary">
            </div>
        </form>
    </div>
    <?php
}

// ===================================================================
// --- 3. FUNGSI READ (TAMPILKAN DATA) ---
// ===================================================================
function tampil_data($koneksi) {
    $sql = "SELECT * FROM mahasiswa ORDER BY id DESC";
    $query = mysqli_query($koneksi, $sql);
    ?>
    <div class="table-container">
        <h2>Daftar Mahasiswa Terdaftar</h2>
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
            if (mysqli_num_rows($query) > 0):
                while ($data = mysqli_fetch_assoc($query)): 
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><strong><?php echo htmlspecialchars($data['nim']); ?></strong></td>
                    <td><?php echo htmlspecialchars($data['nama']); ?></td>
                    <td><?php echo htmlspecialchars($data['jurusan']); ?></td>
                    <td><?php echo htmlspecialchars($data['alamat']); ?></td>
                    <td style="text-align: center;">
                        <a href="index.php?aksi=update&id=<?php echo $data['id']; ?>" class="btn btn-success btn-sm">Ubah</a>
                        <a href="index.php?aksi=delete&id=<?php echo $data['id']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus data <?php echo htmlspecialchars($data['nama']); ?>?');">
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
                        Belum ada data mahasiswa. Silakan isi form tambah di atas.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// ===================================================================
// --- 4. FUNGSI UPDATE (UBAH DATA) ---
// ===================================================================
function ubah($koneksi) {
    global $pesan, $pesan_tipe;

    // Menangani aksi submit form ubah
    if (isset($_POST['btn_ubah'])) {
        $id      = intval($_POST['id']);
        $nim     = trim($_POST['nim']);
        $nama    = trim($_POST['nama']);
        $jurusan = trim($_POST['jurusan']);
        $alamat  = trim($_POST['alamat']);

        // Validasi input tidak boleh kosong
        if (!empty($id) && !empty($nim) && !empty($nama) && !empty($jurusan) && !empty($alamat)) {
            $nim_safe     = mysqli_real_escape_string($koneksi, $nim);
            $nama_safe    = mysqli_real_escape_string($koneksi, $nama);
            $jurusan_safe = mysqli_real_escape_string($koneksi, $jurusan);
            $alamat_safe  = mysqli_real_escape_string($koneksi, $alamat);

            $sql_update = "UPDATE mahasiswa SET 
                            nim = '$nim_safe', 
                            nama = '$nama_safe', 
                            jurusan = '$jurusan_safe', 
                            alamat = '$alamat_safe' 
                          WHERE id = $id";
            $update = mysqli_query($koneksi, $sql_update);

            if ($update) {
                header("Location: index.php?status=sukses_edit");
                exit();
            } else {
                $pesan = "Gagal memperbarui data di database!";
                $pesan_tipe = "danger";
            }
        } else {
            $pesan = "Peringatan: Semua data wajib diisi saat mengubah data!";
            $pesan_tipe = "danger";
        }
    }

    // Mengambil data terkini berdasarkan ID dari parameter URL
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $result = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE id = $id LIMIT 1");
        
        if ($result && mysqli_num_rows($result) > 0) {
            $data_edit = mysqli_fetch_assoc($result);
            ?>
            <div class="card-form edit-mode">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h2>Form Ubah Data Mahasiswa</h2>
                    <a href="index.php" class="btn btn-secondary btn-sm">&laquo; Kembali ke Home</a>
                </div>

                <?php if (!empty($pesan)): ?>
                    <div class="alert alert-<?php echo $pesan_tipe; ?>"><?php echo $pesan; ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <!-- Input Hidden ID untuk target query UPDATE -->
                    <input type="hidden" name="id" value="<?php echo $data_edit['id']; ?>">

                    <div class="form-group">
                        <label for="edit_nim">NIM:</label>
                        <input type="text" id="edit_nim" name="nim" value="<?php echo htmlspecialchars($data_edit['nim']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_nama">Nama Lengkap:</label>
                        <input type="text" id="edit_nama" name="nama" value="<?php echo htmlspecialchars($data_edit['nama']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_jurusan">Jurusan:</label>
                        <select id="edit_jurusan" name="jurusan" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="Teknik Informatika" <?php echo ($data_edit['jurusan'] == 'Teknik Informatika') ? 'selected' : ''; ?>>Teknik Informatika</option>
                            <option value="Sistem Informasi" <?php echo ($data_edit['jurusan'] == 'Sistem Informasi') ? 'selected' : ''; ?>>Sistem Informasi</option>
                            <option value="Teknik Komputer" <?php echo ($data_edit['jurusan'] == 'Teknik Komputer') ? 'selected' : ''; ?>>Teknik Komputer</option>
                            <option value="Teknologi Multimedia" <?php echo ($data_edit['jurusan'] == 'Teknologi Multimedia') ? 'selected' : ''; ?>>Teknologi Multimedia</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_alamat">Alamat:</label>
                        <textarea id="edit_alamat" name="alamat" rows="3" required><?php echo htmlspecialchars($data_edit['alamat']); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <input type="submit" name="btn_ubah" value="Simpan Perubahan" class="btn btn-success">
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
            <?php
        } else {
            echo "<div class='alert alert-danger'>Data dengan ID tersebut tidak ditemukan.</div>";
        }
    }
}

// ===================================================================
// --- 5. FUNGSI DELETE (HAPUS DATA) ---
// ===================================================================
function hapus($koneksi) {
    if (isset($_GET['id']) && isset($_GET['aksi']) && $_GET['aksi'] == 'delete') {
        $id = intval($_GET['id']);
        $sql_hapus = "DELETE FROM mahasiswa WHERE id = $id";
        $hapus = mysqli_query($koneksi, $sql_hapus);

        if ($hapus) {
            header("Location: index.php?status=sukses_hapus");
            exit();
        } else {
            header("Location: index.php?status=gagal_db");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Mahasiswa dalam Satu File PHP</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #1e293b;
            padding: 30px 20px;
        }
        .container {
            max-width: 960px;
            margin: 0 auto;
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }
        header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        header h1 {
            font-size: 24px;
            color: #0f172a;
        }
        header p {
            color: #64748b;
            font-size: 14px;
            margin-top: 4px;
        }
        .card-form {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }
        .card-form.edit-mode {
            background: #fefce8;
            border-color: #fef08a;
        }
        .card-form h2 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #1e293b;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #334155;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            background-color: #ffffff;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 6px;
            cursor: pointer;
            border: none;
        }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-success:hover { background: #15803d; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-secondary { background: #64748b; color: #fff; }
        .btn-secondary:hover { background: #475569; }
        .btn-sm { padding: 4px 10px; font-size: 12px; }
        .table-container { margin-top: 20px; }
        .table-container h2 { font-size: 18px; margin-bottom: 12px; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 600;
        }
        table tr:hover {
            background-color: #f8fafc;
        }
        .alert {
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 15px;
        }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    </style>
</head>
<body>

<div class="container">
    <header>
        <div>
            <h1>CRUD Single-Page (Satu File PHP)</h1>
            <p>Implementasi Pola PetaniKode &bull; Router Berbasis Fungsi &bull; Data Mahasiswa</p>
        </div>
        <div>
            <a href="../crud-multi-page/index.php" class="btn btn-secondary btn-sm">&larr; Buka Versi Multi-Page</a>
        </div>
    </header>

    <!-- Notifikasi dari Redirect URL -->
    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] == 'sukses_tambah'): ?>
            <div class="alert alert-success">Data mahasiswa berhasil ditambahkan!</div>
        <?php elseif ($_GET['status'] == 'sukses_edit'): ?>
            <div class="alert alert-success">Perubahan data mahasiswa berhasil disimpan!</div>
        <?php elseif ($_GET['status'] == 'sukses_hapus'): ?>
            <div class="alert alert-success">Data mahasiswa berhasil dihapus dari database!</div>
        <?php elseif ($_GET['status'] == 'gagal_db'): ?>
            <div class="alert alert-danger">Operasi database gagal!</div>
        <?php endif; ?>
    <?php endif; ?>

    <?php
    // ===================================================================
    // --- 6. PROGRAM UTAMA / ROUTER (SWITCH CASE AKSI) ---
    // ===================================================================
    if (isset($_GET['aksi'])) {
        switch ($_GET['aksi']) {
            case "create":
                echo '<div style="margin-bottom: 15px;"><a href="index.php" class="btn btn-secondary btn-sm">&laquo; Kembali ke Halaman Utama</a></div>';
                tambah($koneksi);
                break;

            case "read":
                tampil_data($koneksi);
                break;

            case "update":
                ubah($koneksi);
                tampil_data($koneksi);
                break;

            case "delete":
                hapus($koneksi);
                break;

            default:
                echo "<div class='alert alert-danger'>Aksi <i>" . htmlspecialchars($_GET['aksi']) . "</i> tidak valid!</div>";
                tambah($koneksi);
                tampil_data($koneksi);
                break;
        }
    } else {
        // Halaman default (Home): tampilkan form tambah dan tabel data sekaligus
        tambah($koneksi);
        tampil_data($koneksi);
    }
    ?>
</div>

</body>
</html>
