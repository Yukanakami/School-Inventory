-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 02:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_sekolah`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `kondisi` enum('baik','rusak','hilang') NOT NULL DEFAULT 'baik'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `kategori`, `nama_barang`, `jumlah`, `kondisi`) VALUES
(14, 'Elektronik', 'Microphone Pelunas Hutang', 12, 'baik'),
(17, 'ATK', 'rokok', 100, 'baik'),
(18, 'Lainnya', 'Marimas', 100, 'baik'),
(19, 'Lainnya', 'Uang Kaget', 20, 'baik'),
(20, 'Lainnya', 'Uang Kaget', 20, 'baik'),
(21, 'Lainnya', 'Uang Kaget', 20, 'baik'),
(22, 'Lainnya', 'Uang Kaget', 20, 'baik'),
(23, 'Lainnya', 'Uang Kaget', 20, 'baik'),
(24, 'Elektronik', 'TIKUSSS', 1, 'rusak'),
(27, 'Elektronik', 'Mouse', 12, 'baik'),
(28, 'Elektronik', 'Mouse', 12, 'baik'),
(29, 'Elektronik', 'Mouse', 12, 'baik'),
(30, 'Elektronik', 'Mouse', 12, 'baik'),
(31, 'Lainnya', 'Ari', 1, 'baik'),
(32, 'Elektronik', 'Smarthphone', 1, 'baik');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `aksi` enum('Tambah','Edit','Hapus') NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `kategori` varchar(255) NOT NULL,
  `kondisi` varchar(255) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_user` int(11) DEFAULT NULL,
  `nama_barang` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `aksi`, `id_barang`, `jumlah`, `kategori`, `kondisi`, `waktu`, `id_user`, `nama_barang`, `user`) VALUES
(3, 'Tambah', 23, 20, 'Lainnya', 'baik', '2025-04-08 12:11:44', 3, NULL, NULL),
(4, 'Tambah', 24, 1, 'Elektronik', 'rusak', '2025-04-08 12:46:58', 3, NULL, NULL),
(5, 'Tambah', 30, 12, 'Elektronik', 'baik', '2025-04-08 12:50:35', 3, 'Mouse', NULL),
(6, 'Tambah', 31, 1, 'Lainnya', 'baik', '2025-04-08 12:51:49', 3, 'Ari', NULL),
(7, 'Tambah', 32, 1, 'Elektronik', 'baik', '2025-04-08 12:52:31', NULL, 'Smarthphone', 'Maheka'),
(8, 'Hapus', NULL, 12, 'Elektronik', 'baik', '2025-04-08 12:53:56', NULL, 'Mouse', 'Maheka'),
(9, 'Edit', NULL, 1, 'Elektronik', 'rusak', '2025-04-08 12:54:10', NULL, 'TIKUSSS', 'Maheka'),
(10, 'Hapus', NULL, 12, 'Elektronik', 'baik', '2025-04-08 12:56:08', NULL, 'Mouse', 'Maheka');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas','siswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`) VALUES
(3, 'Maheka', '$2y$12$xXUeiXcIuLNDSaVXN/pwau.LI1BoscGEcggOhULyJvRULpz0c8g4S', 'admin'),
(11, 'Yukana', '$2y$12$HQP5Hr3hHYOXM3OHWHeD2uHdQus2rmXM1LwlUxVuXNixIAoGv4SY6', 'siswa'),
(12, 'komek', '$2y$12$MLEE/iCT2coln.9Eu6p3wuAQdCuy3OxQJxv1BDOfRWXQcyblrliWO', 'siswa'),
(13, 'Makane', '$2y$12$hB074iBrH.FmzfpRqx5PBOGzO2Wm73D.GQXcCjAxCRNog6d25R2kO', 'siswa'),
(14, 'Teguh', '$2y$12$8DztWOORxaffpetMwOww.OUAAdgKgcSoZe3BDpyyT2.dOhkV57uZ6', 'admin'),
(15, 'Mahardika', '$2y$12$BD5WiWsYiMERbfWBaWGE3.ogMSwzEKjYYbaz4cmI0QyGEuAfCCkPi', 'admin'),
(16, 'Deandra', '$2y$12$XtIAuaQifv3xpm7TZFB11.jhHun8//.j0R8kPwHdabZSViBUe1pAC', 'admin'),
(18, 'jeje', '$2y$12$Qwb.iIn44g./I9ept/ZBg.jxnU7eM10LaJD3HKxHZO56.pxerjqiy', 'siswa'),
(19, 'Guru1', '$2y$12$bbKTEzvGqFIbM6h.Bbdlf.PbAajrqFdyvMF4Q.Rn2nARDicTzU/qW', 'petugas');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `history_ibfk_1` (`id_barang`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE,
  ADD CONSTRAINT `history_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
