-- Database UTS Pemrograman Web: CRUD Mahasiswa & Dosen
CREATE DATABASE IF NOT EXISTS `db_uts_siakad`;
USE `db_uts_siakad`;

-- 1. Tabel Mahasiswa
DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE `mahasiswa` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nim` VARCHAR(20) NOT NULL UNIQUE,
  `nama` VARCHAR(100) NOT NULL,
  `alamat` TEXT NOT NULL,
  `tanggal_lahir` DATE NOT NULL,
  `nama_wali` VARCHAR(100) NOT NULL,
  `foto` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Awal Mahasiswa
INSERT INTO `mahasiswa` (`nim`, `nama`, `alamat`, `tanggal_lahir`, `nama_wali`, `foto`) VALUES
('2507421029', 'Narangga Adennas Shaputra', 'Depok, Jawa Barat', '2004-07-15', 'Bambang Shaputra', NULL),
('2507421001', 'Ahmad Pratama', 'Jakarta Selatan, DKI Jakarta', '2004-03-22', 'H. Hendra Pratama', NULL),
('2507421015', 'Siti Rahmawati', 'Bogor Tengah, Kota Bogor', '2004-11-08', 'Drs. Supardi', NULL);

-- 2. Tabel Dosen
DROP TABLE IF EXISTS `dosen`;
CREATE TABLE `dosen` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nip_nidn` VARCHAR(30) NOT NULL UNIQUE,
  `nama` VARCHAR(100) NOT NULL,
  `alamat` TEXT NOT NULL,
  `tanggal_lahir` DATE NOT NULL,
  `pendidikan_terakhir` VARCHAR(50) NOT NULL,
  `foto` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Awal Dosen
INSERT INTO `dosen` (`nip_nidn`, `nama`, `alamat`, `tanggal_lahir`, `pendidikan_terakhir`, `foto`) VALUES
('198507122010121001', 'Dr. Budiarto, S.Kom., M.Kom.', 'Sukmajaya, Depok', '1985-07-12', 'S3 - Doktor', NULL),
('197904152005012003', 'Prof. Dr. Ir. Wahyu Wibowo, M.T.', 'Cinere, Depok', '1979-04-15', 'Profesor / Guru Besar', NULL),
('199011032018031002', 'Farhan Fauzi, S.T., M.T.', 'Tebet, Jakarta Selatan', '1990-11-03', 'S2 - Magister', NULL);
