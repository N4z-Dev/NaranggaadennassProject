<?php
// ===================================================================
// FILE: api/single-page.php (CRUD Single-Page di Vercel Cloud)
// REFERENSI: PetaniKode - Pola Satu File PHP
// ===================================================================

require_once __DIR__ . '/koneksi.php';

$pesan = "";
$pesan_tipe = "";

// Aksi CREATE / TAMBAH
if (isset($_POST['btn_simpan'])) {
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $alamat  = trim($_POST['alamat'] ?? '');

    // Validasi server-side
    if (!empty($nim) && !empty($nama) && !empty($jurusan) && !empty($alamat)) {
        try {
            $stmt = $db->prepare("INSERT INTO mahasiswa (nim, nama, jurusan, alamat) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nim, $nama, $jurusan, $alamat]);
            header("Location: single-page.php?status=sukses_tambah");
            exit();
        } catch (Exception $e) {
            $pesan = "Gagal menyimpan data! NIM mungkin sudah terdaftar.";
            $pesan_tipe = "danger";
        }
    } else {
        $pesan = "Semua field formulir wajib diisi, tidak boleh kosong!";
        $pesan_tipe = "danger";
    }
}

// Aksi UPDATE / UBAH
if (isset($_POST['btn_ubah'])) {
    $id      = intval($_POST['id'] ?? 0);
    $nim     = trim($_POST['nim'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $alamat  = trim($_POST['alamat'] ?? '');

    if (!empty($id) && !empty($nim) && !empty($nama) && !empty($jurusan) && !empty($alamat)) {
        try {
            $stmt = $db->prepare("UPDATE mahasiswa SET nim = ?, nama = ?, jurusan = ?, alamat = ? WHERE id = ?");
            $stmt->execute([$nim, $nama, $jurusan, $alamat, $id]);
            header("Location: single-page.php?status=sukses_edit");
            exit();
        } catch (Exception $e) {
            $pesan = "Gagal memperbarui data!";
            $pesan_tipe = "danger";
        }
    } else {
        $pesan = "Semua data wajib diisi saat mengubah data!";
        $pesan_tipe = "danger";
    }
}

// Aksi DELETE / HAPUS
if (isset($_GET['aksi']) && $_GET['aksi'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $db->prepare("DELETE FROM mahasiswa WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: single-page.php?status=sukses_hapus");
        exit();
    } catch (Exception $e) {
        $pesan = "Gagal menghapus data!";
        $pesan_tipe = "danger";
    }
}

// Query Ambil Seluruh Data
$mahasiswa_list = $db->query("SELECT * FROM mahasiswa ORDER BY id DESC")->fetchAll();

// Jika sedang edit, ambil data lama
$data_edit = null;
if (isset($_GET['aksi']) && $_GET['aksi'] == 'update' && isset($_GET['id'])) {
    $id_edit = intval($_GET['id']);
    $stmt = $db->prepare("SELECT * FROM mahasiswa WHERE id = ? LIMIT 1");
    $stmt->execute([$id_edit]);
    $data_edit = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Single-Page CRUD (PHP Cloud) | Narangga 2507421029</title>
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
        .card-form { background: #f1f5f9; padding: 18px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #e2e8f0; }
        .card-form.edit-mode { background: #fefce8; border-color: #fef08a; }
        .form-group { margin-bottom: 12px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px; color: #334155; }
        input[type="text"], select, textarea { width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; outline: none; background: #fff; }
        input[type="text"]:focus, select:focus, textarea:focus { border-color: #2563eb; }
        .btn { display: inline-block; padding: 8px 14px; font-size: 13px; font-weight: 600; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-secondary { background: #64748b; color: #fff; }
        .btn-sm { padding: 4px 10px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
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
            <h1>CRUD Single-Page (PHP di Vercel Cloud)</h1>
            <p class="sub">Pola PetaniKode &bull; Narangga Adennas (2507421029) &bull; Mode Database: <span class="badge"><?php echo strtoupper($driver); ?> CLOUD</span></p>
        </div>
        <div>
            <a href="multi-page.php" class="btn btn-secondary btn-sm">Buka Versi Multi-Page &rarr;</a>
            <a href="../index.html" class="btn btn-primary btn-sm">&larr; Portal Utama</a>
        </div>
    </header>

    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] == 'sukses_tambah'): ?>
            <div class="alert alert-success">Data mahasiswa berhasil disimpan ke database!</div>
        <?php elseif ($_GET['status'] == 'sukses_edit'): ?>
            <div class="alert alert-success">Perubahan data mahasiswa berhasil diperbarui!</div>
        <?php elseif ($_GET['status'] == 'sukses_hapus'): ?>
            <div class="alert alert-success">Data mahasiswa berhasil dihapus dari database!</div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!empty($pesan)): ?>
        <div class="alert alert-<?php echo $pesan_tipe; ?>"><?php echo $pesan; ?></div>
    <?php endif; ?>

    <?php if ($data_edit): ?>
        <!-- FORM EDIT DATA (UPDATE) -->
        <div class="card-form edit-mode">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <h2 style="font-size:16px;">Form Edit Data Mahasiswa</h2>
                <a href="single-page.php" class="btn btn-secondary btn-sm">Batal Edit</a>
            </div>
            <form action="single-page.php" method="POST">
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
                    <label>Alamat:</label>
                    <textarea name="alamat" rows="3" required><?php echo htmlspecialchars($data_edit['alamat']); ?></textarea>
                </div>
                <button type="submit" name="btn_ubah" class="btn btn-success">Simpan Perubahan</button>
            </form>
        </div>
    <?php else: ?>
        <!-- FORM TAMBAH DATA (CREATE) -->
        <div class="card-form">
            <h2 style="font-size:16px; margin-bottom:12px;">Form Tambah Mahasiswa Baru</h2>
            <form action="single-page.php" method="POST">
                <div class="form-group">
                    <label>NIM:</label>
                    <input type="text" name="nim" placeholder="Contoh: 2507421029" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Nama Lengkap:</label>
                    <input type="text" name="nama" placeholder="Masukkan nama mahasiswa" required autocomplete="off">
                </div>
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
                    <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
                </div>
                <button type="submit" name="btn_simpan" class="btn btn-primary">Simpan Mahasiswa</button>
            </form>
        </div>
    <?php endif; ?>

    <!-- TABEL DATA (READ) -->
    <h2 style="font-size:16px; margin-top:20px; margin-bottom:10px;">Daftar Mahasiswa Terdaftar (<?php echo count($mahasiswa_list); ?> Data)</h2>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Jurusan</th>
                    <th>Alamat</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($mahasiswa_list as $row): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><strong><?php echo htmlspecialchars($row['nim']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                    <td style="text-align:center;">
                        <a href="single-page.php?aksi=update&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                        <a href="single-page.php?aksi=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data <?php echo htmlspecialchars($row['nama']); ?>?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
