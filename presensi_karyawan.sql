-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Des 2025 pada 07.35
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `presensi_karyawan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `izin`
--

CREATE TABLE `izin` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_izin` varchar(50) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `file_bukti` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `izin`
--

INSERT INTO `izin` (`id`, `user_id`, `tanggal`, `jenis_izin`, `keterangan`, `file_bukti`, `status`, `created_at`, `approved_by`, `approved_at`) VALUES
(1, 4, '2025-11-17', 'izin', 'acara keluarga', '1763298946_Screenshot 2025-09-29 162134.png', 'rejected', '2025-11-16 13:15:46', 1, '2025-12-11 00:41:38'),
(2, 10, '2025-12-11', 'cuti', '', NULL, 'pending', '2025-12-11 06:02:03', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `lokasi_presensi`
--

CREATE TABLE `lokasi_presensi` (
  `id` int(11) NOT NULL,
  `nama_lokasi` varchar(100) NOT NULL,
  `lat` decimal(10,7) NOT NULL,
  `lng` decimal(10,7) NOT NULL,
  `radius` int(11) NOT NULL DEFAULT 50,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lokasi_presensi`
--

INSERT INTO `lokasi_presensi` (`id`, `nama_lokasi`, `lat`, `lng`, `radius`, `created_at`) VALUES
(1, 'ridhwan', -7.7474720, 110.2509270, 1000, '2025-11-16 11:28:03'),
(3, 'UPN KAMPUS 2', -7.7821095, 110.4146224, 1000, '2025-11-16 11:35:54'),
(4, 'rumah adam', -7.7058000, 110.6524100, 1000, '2025-12-10 17:37:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `presensi`
--

CREATE TABLE `presensi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lokasi_id` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` datetime DEFAULT NULL,
  `jam_keluar` datetime DEFAULT NULL,
  `lat` decimal(10,7) DEFAULT NULL,
  `lng` decimal(10,7) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `presensi`
--

INSERT INTO `presensi` (`id`, `user_id`, `lokasi_id`, `tanggal`, `jam_masuk`, `jam_keluar`, `lat`, `lng`, `created_at`) VALUES
(1, 2, NULL, '2025-11-13', '2025-11-13 09:56:27', '2025-11-13 09:57:03', -7.7813666, 110.4163277, '2025-11-13 02:56:27'),
(2, 3, 1, '2025-11-16', '2025-11-16 18:37:52', '2025-11-16 18:37:59', -7.7474719, 110.2509359, '2025-11-16 11:37:52'),
(3, 4, 1, '2025-11-16', '2025-11-16 19:10:07', '2025-11-16 19:10:10', -7.7474727, 110.2509382, '2025-11-16 12:10:07'),
(4, 5, 1, '2025-11-16', '2025-11-16 19:12:07', '2025-11-16 19:12:11', -7.7474727, 110.2509382, '2025-11-16 12:12:07'),
(5, 6, 1, '2025-11-16', '2025-11-16 19:19:07', '2025-11-16 19:19:08', -7.7474727, 110.2509382, '2025-11-16 12:19:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','karyawan') NOT NULL DEFAULT 'karyawan',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `created_at`) VALUES
(1, NULL, 'admin', '$2y$10$QTKs7mpTwCuwTbu6l.J41en8i35LTMnI9A8z6N00nntv3yHNWDoEO', 'admin', '2025-11-13 02:40:30'),
(2, 'ridhwan', 'ridhwan', '$2y$10$9f3SkbCIj4Z2rWIlhJOGL.jxYZHfd3bPnfh7JfDXIIpusGtm0QLnW', 'admin', '2025-11-13 02:49:10'),
(3, 'RAMADHAN', 'RAMADHAN', '$2y$10$v5oD7hCIjsJNXOf6uh5FD.fCO0KMH1I0r/Q5Sc/Mann9vHVxlaMD6', 'karyawan', '2025-11-16 11:36:27'),
(4, 'muh', 'muh', '$2y$10$UvqdiEj1n8AFJh46AXvG1.cB5HKAX6ypDKd/K/rQfFnWsaOMQsckC', 'karyawan', '2025-11-16 12:05:20'),
(5, 'coba', 'coba', '$2y$10$Ke.iW79vrbZUmMrfxqAeQO6cPSn2LbnYZDNpT/SgvcSScFXoDPKnu', 'karyawan', '2025-11-16 12:11:55'),
(6, 'aku', 'aku', '$2y$10$lsFGRX0IZFdga.n2rrmJWOAb.0UkpLepMuMAuisoKztO1RwYneTNi', 'karyawan', '2025-11-16 12:18:56'),
(10, 'adam', 'adam', '$2y$10$GrXza7nczuCDeGA8Vt847OZZG618c4Jk6OVMeRK/nn/7tGiwdI8U2', 'karyawan', '2025-12-10 16:17:08');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `izin`
--
ALTER TABLE `izin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_lokasi_presensi` (`lokasi_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `izin`
--
ALTER TABLE `izin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `fk_lokasi_presensi` FOREIGN KEY (`lokasi_id`) REFERENCES `lokasi_presensi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
