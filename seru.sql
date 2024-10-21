-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 21, 2024 at 04:57 PM
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
-- Database: `seru`
--

-- --------------------------------------------------------

--
-- Table structure for table `list_dokumen`
--

CREATE TABLE `list_dokumen` (
  `dokumen_id` int NOT NULL,
  `no_notulen` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `perihal` text NOT NULL,
  `draft_url` varchar(255) NOT NULL,
  `dokumen_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temuan`
--

CREATE TABLE `temuan` (
  `temuan_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `sumber_temuan` enum('MWT','MOD','PATUH','NOTULEN_RAPAT','LAINNYA') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fungsi` enum('RSD','PMS','HSSE','SSGA','QQ','FLEET','FT_TUBAN') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `temuan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rekomendasi_tindak_lanjut` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` enum('OPEN','CLOSE','ON_PROGRESS') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `deadline` date NOT NULL,
  `dokumentasi_tl` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `dokumentasi_gambar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `prioritas` enum('TINGGI','SEDANG','RENDAH') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'SEDANG',
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `temuan`
--

INSERT INTO `temuan` (`temuan_id`, `tanggal`, `sumber_temuan`, `fungsi`, `temuan`, `rekomendasi_tindak_lanjut`, `status`, `pic`, `deadline`, `dokumentasi_tl`, `dokumentasi_gambar`, `prioritas`, `keterangan`) VALUES
(8, '2024-10-20', 'MWT', 'RSD', 'Pembuatan shelter yang layak untuk petugas penggantian ban', 'Dilakukan perbaikan dan penggantian shelter di area fleet', 'CLOSE', 'FLEET', '2024-09-20', 'Telah dilakukan perbaikan', '../../img/uploads/mq-2.jpg', 'SEDANG', ''),
(9, '2024-04-25', 'NOTULEN_RAPAT', 'RSD', 'Pengecora atau pembuatan lantai kerja di gudang penggantian ban', 'Pengecoran di lantai kerja workshop', 'OPEN', 'FLEET', '2024-09-19', NULL, NULL, 'SEDANG', ''),
(14, '2024-10-21', 'MWT', 'RSD', 'tes', 'tes', 'OPEN', 'HSSE,FLEET', '2024-10-24', NULL, NULL, 'RENDAH', ''),
(16, '2024-10-21', 'NOTULEN_RAPAT', 'RSD', 'Panel triplek di anak tangga terkelupas pada control room', 'Dilakukan penggantian panel triplek anak tangga pada control room', 'CLOSE', 'PMS,QQ', '2024-12-13', 'Telah dilakukan perbaikan', '../../img/uploads/hello.jpg', 'SEDANG', ''),
(17, '2024-10-21', 'NOTULEN_RAPAT', 'RSD', 'tes pic a', 'tes pic 1', 'OPEN', 'HSSE,SSGA,FLEET', '2025-01-10', '', NULL, 'SEDANG', ''),
(18, '2024-10-21', 'MWT', 'FT_TUBAN', 'tes fungsi 1', 'tes fungsi 1', 'OPEN', 'QQ,FLEET', '2025-03-14', NULL, NULL, 'SEDANG', ''),
(19, '2024-10-21', 'NOTULEN_RAPAT', 'FLEET', 'tes fungsi from notulen rapat 1', 'tes fungsi from notulen rapat 1', 'OPEN', 'RSD,QQ,FLEET', '2025-04-11', NULL, NULL, 'TINGGI', ''),
(22, '2024-10-21', 'NOTULEN_RAPAT', 'FLEET', 'testing g tau yang ke berapa', 'testing g tau yang ke berapa', 'OPEN', 'PMS,SSGA', '2025-04-10', NULL, NULL, 'TINGGI', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','RSD','PMS','HSSE','SSGA','QQ','FLEET') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `nama`, `username`, `password`, `role`) VALUES
(1, 'Wijaya Kusumo Ningrat', 'wijaya', 'wijaya123', 'admin'),
(3, 'admin', 'admin', 'admin123', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `list_dokumen`
--
ALTER TABLE `list_dokumen`
  ADD PRIMARY KEY (`dokumen_id`);

--
-- Indexes for table `temuan`
--
ALTER TABLE `temuan`
  ADD PRIMARY KEY (`temuan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `list_dokumen`
--
ALTER TABLE `list_dokumen`
  MODIFY `dokumen_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temuan`
--
ALTER TABLE `temuan`
  MODIFY `temuan_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
