-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 02, 2026 at 04:57 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evently_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `npm` varchar(50) DEFAULT NULL,
  `tipe_akun` enum('mahasiswa','organisasi') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `program_studi` varchar(100) DEFAULT NULL,
  `semester` varchar(10) DEFAULT NULL,
  `no_whatsapp` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `email`, `npm`, `tipe_akun`, `program_studi`, `semester`, `no_whatsapp`, `password`, `role`, `created_at`) VALUES
(2, 'Kei', '2417051015@students.unila.ac.id', '2417051015', 'mahasiswa', 'Ilmu Komputer', '4', '08987654321', '$2y$10$yu7yhhyN660ZAcAQDQ8EDOp2wiPPVEY.4i0RQvnCays7NmjZo8XB2', 'user', '2026-05-02 03:08:04'),
(3, 'Daniel Pratama', 'daniel@unila.ac.id', '1234567890', 'mahasiswa', 'Ilmu Komputer', '4', '081234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '2026-05-02 07:19:47'),
(4, 'Admin Evently', 'admin@evently.com', 'ADMIN001', 'organisasi', 'IT Division', '-', '08111222333', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-05-02 07:19:47'),
(5, 'HIMAKOM Unila', 'himakom@unila.ac.id', 'ORG001', 'organisasi', 'Ilmu Komputer', '-', '08129998877', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '2026-05-02 07:19:47'),
(6, 'iqlima', '2417051048@unila.ac.id', '2417051048', 'mahasiswa', 'Ilmu Komputer', NULL, '087854562728', '$2y$10$rYn5Aja8uryEE.lopXdgb.KSeeQ1FMHCwsKonzmYzvhG9Ka0LWLHu', 'user', '2026-05-02 11:11:07'),
(7, 'Admin Evently', 'adminbaru@evently.com', 'ADM999', 'organisasi', 'IT Division', '-', '081234567890', '$2y$10$um477GT1P/eS5utEGVzg2.FXI0c9rLEOVMw7q6nfX70eqPd251O9.', 'admin', '2026-05-02 13:29:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
