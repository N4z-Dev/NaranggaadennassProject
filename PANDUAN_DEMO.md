# PANDUAN LENGKAP DEMO & DISKUSI SCRIPT CRUD PHP MYSQL
**Mata Kuliah:** Pemrograman Web  
**Dosen Pengampu:** Pak Chandra  
**Mahasiswa:** Narangga Aden (NIM: 2507421029)  
**Referensi:** PetaniKode (Single-Page) & CodePolitan (Multi-Page)  

---

## 📋 1. Checklist Pemenuhan Kebutuhan Teknis Demo

| No | Kebutuhan Teknis | Status | Bukti Implementasi pada Script |
|:---|:---|:---:|:---|
| 1 | **Form input dengan `name` dan `value` sesuai** |  **TERPENUHI** | - Form input memiliki atribut `name="nim"`, `name="nama"`, `name="jurusan"`, `name="alamat"`.<br>- Pada form Edit, atribut `value="<?php echo htmlspecialchars($data['...']); ?>"` terisi otomatis dari hasil query database.<br>- Form edit menyertakan `<input type="hidden" name="id" value="...">` untuk target klausul `WHERE id = ...`. |
| 2 | **Validasi input (tidak boleh kosong)** |  **TERPENUHI** | - **Client-side:** Atribut `required` pada setiap tag input/select/textarea di HTML5.<br>- **Server-side:** `if (empty(trim($nim)) \|\| empty(trim($nama)) ...)` untuk mencegah input kosong atau spasi putih (whitespace). |
| 3 | **Koneksi ke MySQL berfungsi & sesuai konfigurasi** |  **TERPENUHI** | - `mysqli_connect("localhost", "root", "", "db_kampus")`.<br>- Penanganan error koneksi dengan `die(mysqli_connect_error())`. |
| 4 | **Operasi CREATE, READ, UPDATE, DELETE berjalan sesuai alur** |  **TERPENUHI** | - **Create:** Form Tambah $\rightarrow$ query `INSERT INTO`.<br>- **Read:** Query `SELECT * FROM mahasiswa` $\rightarrow$ perulangan `while ($row = mysqli_fetch_assoc(...))` pada tabel.<br>- **Update:** Ambil data lama $\rightarrow$ form edit $\rightarrow$ query `UPDATE mahasiswa SET ... WHERE id = ...`.<br>- **Delete:** Parameter URL `?id=...` $\rightarrow$ query `DELETE FROM mahasiswa WHERE id = ...` dengan konfirmasi dialog JavaScript. |
| 5 | **Mampu menjelaskan alur data secara runtut** |  **TERPENUHI** | Tersedia penjelasan 6 tahap alur data form $\rightarrow$ database $\rightarrow$ tampilan di Bab 3 panduan ini. |

---

## 🚀 2. Cara Menjalankan Project Saat Sesi Demo

Tersedia 2 opsi mudah untuk menjalankan aplikasi saat sesi demo:

### Opsi A: Menggunakan Server Bawaan PHP (Paling Cepat & Praktis)
1. Buka terminal (PowerShell / Command Prompt) di folder `TUGAS`:
   ```bash
   cd "c:\Users\Naz\OneDrive\WORKSHOP\TUGAS"
   ```
2. Pastikan MySQL aktif (buka Laragon lalu klik **Start All**, atau jalankan mysqld).
3. Jalankan server lokal:
   ```bash
   php -S localhost:8000
   ```
4. Buka browser:
   - **Multi-Page CRUD:** `http://localhost:8000/crud-multi-page/`
   - **Single-Page CRUD:** `http://localhost:8000/crud-single-page/`

### Opsi B: Menggunakan Laragon Virtual Host / Root
- Buka aplikasi **Laragon**, klik tombol **Start All**.
- Buka menu **Database** (phpMyAdmin / HeidiSQL), impor file `database.sql`.
- Akses melalui browser sesuai konfigurasi Laragon Anda.

---

## 🔄 3. Penjelasan Alur Data (Wajib Dipahami Saat Ditanya Dosen)

Berikut adalah urutan alur teknis yang harus Anda jelaskan saat Pak Chandra menanyakan *"Jelaskan bagaimana data dari form bisa masuk ke database lalu tampil kembali di layar!"*:

```
[1. Form HTML] ──(HTTP POST)──► [2. Web Server / PHP]
                                           │
                                  (Tangkap $_POST & Validasi)
                                           ▼
[6. Tampil Kembali] ◄──(Redirect)─── [3. Variabel PHP]
         ▲                                 │
         │                        (Susun Query SQL)
         │                                 ▼
[5. Hasil Eksekusi] ◄──(Query)────── [4. Koneksi MySQL]
```

### Rincian 6 Tahapan Alur:

1. **Tahap 1: Form HTML (Penginputan Data)**
   - Pengguna mengisi inputan di halaman web.
   - Tag `<form action="proses_tambah.php" method="POST">` menentukan bahwa data akan dikirim ke `proses_tambah.php` menggunakan metode HTTP **POST** (data dikirim melalui payload request body, bukan di URL).
   - Setiap tag input memiliki atribut `name` (misalnya `name="nama"`), yang akan menjadi kata kunci (key) di array asosiatif PHP.

2. **Tahap 2: Pengiriman HTTP POST**
   - Saat tombol `<button type="submit" name="btn_simpan">` ditekan, browser membungkus nilai dari seluruh input yang memiliki atribut `name` ke dalam payload HTTP POST dan mengirimkannya ke server.

3. **Tahap 3: Konversi Variabel & Validasi di PHP**
   - Skrip PHP menangkap array global `$_POST` dan memindahkannya ke variabel lokal:
     ```php
     $nim     = trim($_POST['nim']);
     $nama    = trim($_POST['nama']);
     $jurusan = trim($_POST['jurusan']);
     $alamat  = trim($_POST['alamat']);
     ```
   - **Validasi Server-Side:** Skrip memeriksa apakah ada input kosong menggunakan `empty()`:
     ```php
     if (empty($nim) || empty($nama) || empty($jurusan) || empty($alamat)) {
         header("Location: index.php?pesan=gagal_validasi");
         exit();
     }
     ```
   - **Sanitasi:** Nilai dibersihkan dengan `mysqli_real_escape_string($koneksi, $input)` guna menghindari karakter kutip yang dapat merusak struktur query atau SQL Injection dasar.

4. **Tahap 4: Penyusunan Query SQL**
   - PHP menyusun sintaks string query SQL dengan memasukkan variabel-variabel tersebut:
     ```php
     $query = "INSERT INTO mahasiswa (nim, nama, jurusan, alamat) 
               VALUES ('$nim_safe', '$nama_safe', '$jurusan_safe', '$alamat_safe')";
     ```

5. **Tahap 5: Eksekusi ke Database melalui Objek Koneksi**
   - PHP mengirimkan string query ke server database MySQL menggunakan fungsi:
     ```php
     $eksekusi = mysqli_query($koneksi, $query);
     ```
   - Di sini, variabel `$koneksi` (hasil dari `mysqli_connect(...)`) berperan sebagai jembatan/koneksi aktif yang menghubungkan interpreter PHP dengan daemon MySQL.

6. **Tahap 6: Tampil Kembali (Redirect & Rendering)**
   - Jika query berhasil dieksekusi, PHP menginstruksikan browser untuk kembali ke halaman utama:
     ```php
     header("Location: index.php?pesan=sukses_tambah");
     exit();
     ```
   - Di halaman `index.php`, skrip membaca ulang seluruh data dengan query:
     ```php
     $hasil = mysqli_query($koneksi, "SELECT * FROM mahasiswa ORDER BY id DESC");
     ```
   - Lalu fungsi `while ($row = mysqli_fetch_assoc($hasil))` merender baris tabel HTML sehingga data baru langsung terlihat oleh pengguna.

---

## ⚖️ 4. Perbandingan Dua Pola: Single-Page vs Multi-Page

| Parameter | Multi-Page (CodePolitan) | Single-Page (PetaniKode) |
|:---|:---|:---|
| **Struktur File** | Modular/terpisah (`koneksi.php`, `index.php`, `tambah.php`, `proses_tambah.php`, `edit.php`, `proses_edit.php`, `hapus.php`). | Seluruh logika dan tampilan bersatu dalam 1 file (`index.php`). |
| **Mekanisme Alur** | Halaman form terpisah dari file pemroses aksi backend (misal `tambah.php` $\rightarrow$ `proses_tambah.php`). | Menggunakan fungsi PHP (`tambah()`, `tampil_data()`, `ubah()`, `hapus()`) dan routing berbasis parameter `?aksi=...` serta kondisi `isset($_POST['btn_...'])`. |
| **Kelebihan** | Kode sangat terstruktur (*separation of concerns*), mudah dirawat saat skala aplikasi membesar, kode HTML dan backend terpisah rapi. | Praktis, portabel, tidak memerlukan banyak file, mudah didistribusikan untuk modul script mandiri. |
| **Kekurangan** | Jumlah file lebih banyak, perlu me-require file koneksi di tiap skrip. | File menjadi panjang jika fitur bertambah, logika tampilan dan backend tercampur dalam satu file. |

---

## 🗣️ 5. Naskah Simulasi Presentasi di Depan Pak Chandra

Gunakan urutan berbicara ini saat diminta mendemokan tugas:

> **Salam & Pembuka:**  
> *"Selamat pagi/siang Pak Chandra. Hari ini saya akan mendemokan tugas praktikum CRUD PHP dan MySQL. Sesuai ketentuan, saya telah menyiapkan dua implementasi, yaitu versi Multi-Page berbasis tutorial CodePolitan dan versi Single-Page berbasis tutorial PetaniKode menggunakan database `db_kampus` dengan tabel `mahasiswa`."*

> **Demo Multi-Page (Alur Utama):**  
> 1. *"Pertama, ini adalah tampilan `index.php` untuk menampilkan daftar data mahasiswa (operasi READ).*
> 2. *Ketika saya klik 'Tambah Mahasiswa', kita berpindah ke `tambah.php`. Jika saya mencoba menyimpan form kosong, validasi HTML5 `required` dan validasi server `empty()` akan memblokir proses.*
> 3. *Sekarang saya masukkan NIM, Nama, Jurusan, dan Alamat, lalu klik Simpan. Form ini mengirim request dengan method POST ke `proses_tambah.php`.*
> 4. *Di `proses_tambah.php`, data `$_POST` dikonversi ke variabel, divalidasi, disanitasi, lalu query `INSERT INTO` dijalankan lewat `$koneksi` dan kita di-redirect kembali ke `index.php` dengan pesan sukses.*
> 5. *Untuk Update, saya klik tombol 'Edit' pada salah satu data. Di halaman `edit.php`, nilai form terisi otomatis (`value`) sesuai ID yang dipilih berkat query `SELECT ... WHERE id = $id`, dan menyertakan `input type="hidden"` untuk ID. Saat saya ubah jurusannya dan klik Simpan, data berhasil diperbarui.*
> 6. *Untuk Delete, ketika saya klik 'Hapus', muncul konfirmasi dialog JavaScript. Saat saya klik OK, request diarahkan ke `hapus.php?id=...` yang mengeksekusi `DELETE FROM mahasiswa WHERE id = $id` dan data terhapus dari tabel."*

> **Demo Single-Page (Nilai Tambah):**  
> *"Selanjutnya, untuk versi Single-Page, seluruh operasi CRUD berada di dalam satu file `index.php`. File ini menggunakan struktur router `switch($_GET['aksi'])`. Form input tambah dan tabel data ditampilkan bersamaan di halaman utama, sementara aksi ubah dan hapus dipicu melalui URL parameter `?aksi=update` dan `?aksi=delete` dengan fungsi terpisah di file yang sama."*

---

## ❓ 6. Pertanyaan Populer Dosen dan Jawaban Tepatnya

### Q1: *"Mengapa untuk input form kita menggunakan metode POST, bukan GET?"*
> **Jawaban:**  
> *"Karena metode POST mengirimkan data di dalam request body HTTP, sehingga data tidak terlihat di URL browser, lebih aman dari manipulasi riwayat browser, dan tidak memiliki batasan panjang data seperti GET. Metode GET hanya digunakan untuk mengambil data atau operasi yang bersifat aman/idempotent seperti pencarian atau navigasi ID."*

### Q2: *"Apa fungsi `<input type="hidden" name="id">` pada form edit?"*
> **Jawaban:**  
> *"Elemen `type="hidden"` berfungsi untuk menyimpan nilai ID mahasiswa secara tersembunyi di form tanpa bisa diedit langsung oleh pengguna, sehingga ketika form di-submit via POST, script `proses_edit.php` mengetahui baris data mana yang harus diperbarui pada klausa `WHERE id = $id` dalam query SQL."*

### Q3: *"Mengapa validasi tidak cukup hanya dengan atribut `required` di HTML?"*
> **Jawaban:**  
> *"Atribut `required` adalah validasi sisi klien (client-side) yang sangat mudah dibypass oleh pengguna menggunakan Inspect Element browser atau alat seperti Postman. Oleh karena itu, wajib ada validasi sisi server (server-side) menggunakan fungsi `empty()` atau `trim()` di PHP untuk menjamin tidak ada data kosong yang masuk ke database."*

### Q4: *"Apa fungsi fungsi `htmlspecialchars()` yang kamu gunakan di tabel dan form?"*
> **Jawaban:**  
> *"Fungsi `htmlspecialchars()` digunakan untuk mencegah celah keamanan Cross-Site Scripting (XSS). Karakter khusus seperti tanda kurung siku `< >` dan kutip `' "` akan diubah menjadi entitas HTML seperti `&lt;` dan `&gt;`, sehingga teks berbahaya tidak akan dieksekusi sebagai script oleh browser."*

### Q5: *"Apa perbedaan `mysqli_fetch_assoc()` dengan `mysqli_fetch_array()`?"*
> **Jawaban:**  
> *"`mysqli_fetch_assoc()` mengembalikan data baris database dalam bentuk array asosiatif (nama kolom sebagai kunci, misal `$row['nama']`). Sedangkan `mysqli_fetch_array()` secara default mengembalikan array ganda (baik indeks angka `0, 1, 2` maupun nama kolom), sehingga `mysqli_fetch_assoc()` lebih hemat memori dan kodenya lebih eksplisit dibaca."*
