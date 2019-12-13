-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2019 at 03:48 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kkp_work`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jenis` varchar(25) NOT NULL,
  `serial_number` varchar(12) NOT NULL,
  `kelengkapan_barang` varchar(60) NOT NULL,
  `keluhan` varchar(255) NOT NULL,
  `diagnosis_kerusakan` varchar(255) NOT NULL,
  `sparepart` varchar(50) NOT NULL,
  `penyelesaian` varchar(255) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `id_karyawan` int(11) NOT NULL,
  `tgl_masuk` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `tgl_keluar` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `harga_sparepart` double NOT NULL,
  `harga_service` double NOT NULL,
  `total_harga` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id_customers` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int(11) NOT NULL,
  `nama_karyawan` varchar(50) NOT NULL,
  `bagian_karyawan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sparepart`
--

CREATE TABLE `sparepart` (
  `id_sparepart` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL,
  `harga` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(256) NOT NULL,
  `image` varchar(50) NOT NULL DEFAULT 'default.jpg',
  `role_id` int(11) NOT NULL,
  `is_active` int(1) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `image`, `role_id`, `is_active`, `date_created`) VALUES
(1, 'Administrator', 'admin@email.com', '$2y$10$EI3dy8qyP/8TH8NvlIJHwuptm3TuAAJuwJGPdHgkANLE5cgJ1UHpK', 'default.jpg', 1, 1, '2019-10-18 16:27:32'),
(2, 'Kurdiansyah Lesmana', 'kurdilesmana@gmail.com', '$2y$10$Hf9BWs.PS0uqvIDL7eYcTO03Snigv5hxCO5bJsPtpQDk7C/BR9inC', 'default.jpg', 2, 0, '2019-10-18 17:24:52');

-- --------------------------------------------------------

--
-- Table structure for table `user_access_menu`
--

CREATE TABLE `user_access_menu` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_access_menu`
--

INSERT INTO `user_access_menu` (`id`, `menu_id`, `role_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 3, 2),
(5, 4, 1),
(6, 4, 2),
(7, 1, 2),
(8, 5, 1),
(9, 6, 1),
(10, 7, 1),
(11, 8, 1),
(13, 9, 1),
(14, 10, 1),
(15, 11, 1),
(16, 11, 2),
(17, 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_header_menu`
--

CREATE TABLE `user_header_menu` (
  `id` int(11) NOT NULL,
  `header_menu` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_header_menu`
--

INSERT INTO `user_header_menu` (`id`, `header_menu`) VALUES
(1, 'Administrator'),
(2, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `user_menu`
--

CREATE TABLE `user_menu` (
  `id` int(11) NOT NULL,
  `header_id` int(11) NOT NULL,
  `no_order` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `is_parent` int(1) DEFAULT 0,
  `parent_id` int(11) DEFAULT NULL,
  `url` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_menu`
--

INSERT INTO `user_menu` (`id`, `header_id`, `no_order`, `title`, `is_parent`, `parent_id`, `url`, `icon`, `is_active`) VALUES
(1, 1, 1, 'Dashboard', 0, NULL, 'dashboard', 'fa fa-dashboard', 1),
(2, 1, 80, 'Users', 0, NULL, 'users', 'fa fa-users', 1),
(3, 2, 1, 'My Profile', 0, NULL, 'users/profile', 'fa fa-user', 1),
(4, 2, 90, 'Log Out', 0, NULL, 'auth/logout', 'fa fa-sign-out', 1),
(5, 1, 90, 'Menu Management', 1, NULL, 'menu', 'fa fa-folder-open', 1),
(6, 1, 91, 'Header Menu', 0, 5, 'menu', 'fa fa-folder', 1),
(7, 1, 92, 'Menu', 0, 5, 'menu/submenu', 'fa fa-folder', 1),
(8, 1, 93, 'Access Menu', 0, 5, 'menu/accessmenu', 'fa fa-folder', 1),
(9, 1, 2, 'Brands', 0, NULL, 'brands', 'fa fa-bookmark', 1),
(10, 1, 3, 'Area', 0, NULL, 'area', 'fa fa-circle', 1),
(11, 1, 4, 'Sales', 0, NULL, 'sales', 'fa fa-tags', 1),
(12, 1, 5, 'Report', 0, NULL, 'report', 'fa fa-book', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `name`) VALUES
(1, 'Administrator'),
(2, 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id_customers`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- Indexes for table `sparepart`
--
ALTER TABLE `sparepart`
  ADD PRIMARY KEY (`id_sparepart`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_header_menu`
--
ALTER TABLE `user_header_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_menu`
--
ALTER TABLE `user_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id_customers` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sparepart`
--
ALTER TABLE `sparepart`
  MODIFY `id_sparepart` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_header_menu`
--
ALTER TABLE `user_header_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_menu`
--
ALTER TABLE `user_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;