-- =======================================================
-- SKRIP DATABASE UNTUK TUGAS PRAKTIKUM CRUD PHP & MYSQL
-- Database: db_kampus
-- Tabel   : mahasiswa
-- =======================================================

-- 1. Buat Database jika belum ada
CREATE DATABASE IF NOT EXISTS db_kampus;
USE db_kampus;

-- 2. Buat Struktur Tabel Mahasiswa
DROP TABLE IF EXISTS mahasiswa;
CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    jurusan VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Masukkan Data Sampel Awal (Dummy Records)
INSERT INTO mahasiswa (nim, nama, jurusan, alamat) VALUES
('2507421029', 'Narangga Aden', 'Teknik Informatika', 'Depok, Jawa Barat'),
('2507421001', 'Ahmad Pratama', 'Sistem Informasi', 'Jakarta Selatan, DKI Jakarta'),
('2507421015', 'Siti Rahmawati', 'Teknik Komputer', 'Bandung, Jawa Barat'),
('2507421033', 'Budi Santoso', 'Teknik Informatika', 'Bogor, Jawa Barat');
