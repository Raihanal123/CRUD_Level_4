-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2026 at 08:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_alumni`
--

-- --------------------------------------------------------

--
-- Table structure for table `alumni`
--

CREATE TABLE `alumni` (
  `id_alumni` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama` varchar(50) NOT NULL,
  `angkatan` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT 'default.png',
  `jurusan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumni`
--

INSERT INTO `alumni` (`id_alumni`, `id_user`, `nama`, `angkatan`, `foto`, `jurusan`) VALUES
(22, 17, 'Raihan Alfiansyah', 2021, '69eb1db5ede0b.jpg', 'Rekayasa Perangkat Lunak'),
(23, 18, 'Akhdan', 2020, 'profile.jpg', 'Teknik Jaringan Akses Telekomunikasi'),
(24, 19, 'wisnu', 2020, 'profile.jpg', 'Teknik Komputer & Jaringan'),
(25, 20, 'kinanti', 2022, 'profile.jpg', 'Rekayasa Perangkat Lunak');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`) VALUES
(12, 'admin', '$2y$10$wqztFP8XjbOLDFmLndcS9uC0PMtX3MgkDR34MAgmCYoCVWMUN6BXS', 'admin'),
(17, 'raihan', '$2y$10$hgw1SBDJkqVjUzpjXDAk4OUokG2gLrkk.t.m1yR05nei/Gypr9rK2', 'user'),
(18, 'akhdan', '$2y$10$BcocPo8lMKxc4wpj6xBlQutAtHFVDo9uPCr/T4uTuSWWKRqf/cu9q', 'user'),
(19, 'wisnu', '$2y$10$fJ6fbUslwJdXH7ZgZ1wVc.lDXMEQSInZS5VGGv1d5Q91Bm1CVZ8fK', 'user'),
(20, 'kinanti', '$2y$10$DkROw/gcjfM1XdbTjNEh0.IvmMhw1JFTZhmiQAYQBg4FmWsjc8JMy', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alumni`
--
ALTER TABLE `alumni`
  ADD PRIMARY KEY (`id_alumni`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alumni`
--
ALTER TABLE `alumni`
  MODIFY `id_alumni` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
