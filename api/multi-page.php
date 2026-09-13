<?php
// ===================================================================
// FILE: api/multi-page.php (CRUD Multi-Page di Vercel Cloud)
// REFERENSI: CodePolitan - Multi-Page CRUD PHP & Database
// ===================================================================

require_once __DIR__ . '/koneksi.php';

$page = $_GET['hal'] ?? 'index';
$pesan = "";
$pesan_tipe = "";

// 1. PROSES TAMBAH (CREATE)
if (isset($_POST['btn_simpan'])) {
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $alamat  = trim($_POST['alamat'] ?? '');

    // Validasi tidak boleh kosong
    if (!empty($nim) && !empty($nama) && !empty($jurusan) && !empty($alamat)) {
        try {
            $stmt = $db->prepare("INSERT INTO mahasiswa (nim, nama, jurusan, alamat) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nim, $nama, $jurusan, $alamat]);
            header("Location: multi-page.php?pesan=sukses_tambah");
            exit();
        } catch (Exception $e) {
            header("Location: multi-page.php?pesan=gagal_db");
            exit();
        }
    } else {
        header("Location: multi-page.php?pesan=gagal_validasi");
        exit();
    }
}

// 2. PROSES EDIT (UPDATE)
if (isset($_POST['btn_update'])) {
    $id      = intval($_POST['id'] ?? 0);
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $alamat  = trim($_POST['alamat'] ?? '');

    if (!empty($id) && !empty($nim) && !empty($nama) && !empty($jurusan) && !empty($alamat)) {
        try {
            $stmt = $db->prepare("UPDATE mahasiswa SET nim = ?, nama = ?, jurusan = ?, alamat = ? WHERE id = ?");
            $stmt->execute([$nim, $nama, $jurusan, $alamat, $id]);
            header("Location: multi-page.php?pesan=sukses_edit");
            exit();
        } catch (Exception $e) {
            header("Location: multi-page.php?pesan=gagal_db");
            exit();
        }
    } else {
        header("Location: multi-page.php?pesan=gagal_validasi");
        exit();
    }
}

// 3. PROSES HAPUS (DELETE)
if ($page == 'hapus' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $db->prepare("DELETE FROM mahasiswa WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: multi-page.php?pesan=sukses_hapus");
        exit();
    } catch (Exception $e) {
        header("Location: multi-page.php?pesan=gagal_db");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Page CRUD (PHP Cloud) | Narangga 2507421029</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background: #f8fafc; color: #0f172a; padding: 24px 16px; }
        .container { max-width: 960px; margin: 0 auto; background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 2px solid #e2e8f0; }
        h1 { font-size: 20px; font-weight: 700; }
        p.sub { font-size: 13px; color: #64748b; margin-top: 2px; }
        .alert { padding: 12px 16px; border-radius: 6px; font-size: 14px; margin-bottom: 16px; }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .form-group { margin-bottom: 14px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px; color: #334155; }
        input[type="text"], select, textarea { width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; outline: none; background: #fff; }
        input[type="text"]:focus, select:focus, textarea:focus { border-color: #2563eb; }
        .btn { display: inline-block; padding: 8px 14px; font-size: 13px; font-weight: 600; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-secondary { background: #64748b; color: #fff; }
        .btn-sm { padding: 4px 10px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; color: #475569; font-weight: 600; }
        tr:hover td { background: #f8fafc; }
        .badge { font-size: 11px; padding: 3px 8px; border-radius: 999px; font-weight: 600; background: #eff6ff; color: #2563eb; }
    </style>
</head>
<body>

<div class="container">
    <header>
        <div>
            <h1>CRUD Multi-Page (PHP di Vercel Cloud)</h1>
            <p class="sub">Pola CodePolitan &bull; Narangga Adennas (2507421029) &bull; Mode Database: <span class="badge"><?php echo strtoupper($driver); ?> CLOUD</span></p>
        </div>
        <div>
            <a href="single-page.php" class="btn btn-secondary btn-sm">&larr; Versi Single-Page</a>
            <a href="../index.html" class="btn btn-primary btn-sm">&larr; Portal Utama</a>
        </div>
    </header>

    <!-- Notifikasi Status -->
    <?php if (isset($_GET['pesan'])): ?>
        <?php if ($_GET['pesan'] == 'sukses_tambah'): ?>
            <div class="alert alert-success">Data mahasiswa baru berhasil ditambahkan!</div>
        <?php elseif ($_GET['pesan'] == 'sukses_edit'): ?>
            <div class="alert alert-success">Perubahan data mahasiswa berhasil disimpan!</div>
        <?php elseif ($_GET['pesan'] == 'sukses_hapus'): ?>
            <div class="alert alert-success">Data mahasiswa berhasil dihapus!</div>
        <?php elseif ($_GET['pesan'] == 'gagal_validasi'): ?>
            <div class="alert alert-danger">Validasi Gagal: Semua kolom form wajib diisi!</div>
        <?php elseif ($_GET['pesan'] == 'gagal_db'): ?>
            <div class="alert alert-danger">Terjadi kesalahan pada database (NIM mungkin sudah ada)!</div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($page == 'tambah'): ?>
        <!-- HALAMAN FORM TAMBAH -->
        <div style="max-width: 600px; margin: 0 auto;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h2 style="font-size:18px;">Form Tambah Mahasiswa Baru</h2>
                <a href="multi-page.php" class="btn btn-secondary btn-sm">&laquo; Kembali ke Daftar</a>
            </div>
            <form action="multi-page.php" method="POST">
                <div class="form-group">
                    <label>Nomor Induk Mahasiswa (NIM):</label>
                    <input type="text" name="nim" placeholder="Contoh: 2507421029" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Nama Lengkap:</label>
                    <input type="text" name="nama" placeholder="Masukkan nama mahasiswa" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Jurusan / Program Studi:</label>
                    <select name="jurusan" required>
                        <option value="">-- Pilih Jurusan --</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Teknik Komputer">Teknik Komputer</option>
                        <option value="Teknologi Multimedia">Teknologi Multimedia</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap:</label>
                    <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
                </div>
                <div style="display:flex; gap:10px;">
                    <button type="submit" name="btn_simpan" class="btn btn-primary">Simpan Mahasiswa</button>
                    <a href="multi-page.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>

    <?php elseif ($page == 'edit' && isset($_GET['id'])): ?>
        <?php 
        $id_edit = intval($_GET['id']);
        $stmt = $db->prepare("SELECT * FROM mahasiswa WHERE id = ? LIMIT 1");
        $stmt->execute([$id_edit]);
        $data_edit = $stmt->fetch();
        if (!$data_edit) {
            die("Data mahasiswa tidak ditemukan!");
        }
        ?>
        <!-- HALAMAN FORM EDIT -->
        <div style="max-width: 600px; margin: 0 auto;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h2 style="font-size:18px;">Form Edit Data Mahasiswa</h2>
                <a href="multi-page.php" class="btn btn-secondary btn-sm">&laquo; Kembali ke Daftar</a>
            </div>
            <form action="multi-page.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $data_edit['id']; ?>">
                <div class="form-group">
                    <label>NIM:</label>
                    <input type="text" name="nim" value="<?php echo htmlspecialchars($data_edit['nim']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap:</label>
                    <input type="text" name="nama" value="<?php echo htmlspecialchars($data_edit['nama']); ?>" required>
                </div>
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
                    <label>Alamat Lengkap:</label>
                    <textarea name="alamat" rows="3" required><?php echo htmlspecialchars($data_edit['alamat']); ?></textarea>
                </div>
                <div style="display:flex; gap:10px;">
                    <button type="submit" name="btn_update" class="btn btn-success">Simpan Perubahan</button>
                    <a href="multi-page.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>

    <?php else: ?>
        <!-- HALAMAN UTAMA (INDEX / READ DATA) -->
        <?php $list = $db->query("SELECT * FROM mahasiswa ORDER BY id DESC")->fetchAll(); ?>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <a href="multi-page.php?hal=tambah" class="btn btn-primary">+ Tambah Mahasiswa Baru</a>
            <span style="font-size:13px; color:#64748b;">Total: <?php echo count($list); ?> Mahasiswa</span>
        </div>

        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Jurusan</th>
                        <th>Alamat</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($list)): ?>
                        <tr><td colspan="6" style="text-align:center; padding:20px; color:#94a3b8;">Belum ada data mahasiswa.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($list as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nim']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                            <td style="text-align:center;">
                                <a href="multi-page.php?hal=edit&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                <a href="multi-page.php?hal=hapus&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data <?php echo htmlspecialchars($row['nama']); ?>?');">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
