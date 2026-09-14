-- Struktur Tabel Mahasiswa
CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    jurusan VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Awal Mahasiswa
INSERT INTO mahasiswa (nim, nama, jurusan, alamat) VALUES
('2507421029', 'Narangga Aden', 'Teknik Multimedia dan Jaringan', 'Depok, Jawa Barat'),
('2507421001', 'Ahmad Pratama', 'Teknik Informatika', 'Jakarta Selatan, DKI Jakarta'),
('2507421015', 'Siti Rahmawati', 'Teknik Komputer', 'Bandung, Jawa Barat'),
('2507421033', 'Budi Santoso', 'Sistem Informasi', 'Bogor, Jawa Barat');
