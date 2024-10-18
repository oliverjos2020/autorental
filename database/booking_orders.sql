-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 17, 2024 at 08:28 AM
-- Server version: 10.6.19-MariaDB-cll-lve-log
-- PHP Version: 8.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `akwafeca_autorental`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking_orders`
--

CREATE TABLE `booking_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `pickupDate` date DEFAULT NULL,
  `pickupTime` varchar(255) DEFAULT NULL,
  `dropoffDate` date DEFAULT NULL,
  `dropoffTime` varchar(255) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `amount` decimal(8,2) NOT NULL,
  `payment_status` char(1) NOT NULL DEFAULT '0',
  `status` char(1) NOT NULL DEFAULT '0',
  `wth_driver` char(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pickup_location` varchar(255) DEFAULT NULL,
  `dropoff_location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_orders`
--

INSERT INTO `booking_orders` (`id`, `user_id`, `vehicle_id`, `pickupDate`, `pickupTime`, `dropoffDate`, `dropoffTime`, `duration`, `amount`, `payment_status`, `status`, `wth_driver`, `created_at`, `updated_at`, `pickup_location`, `dropoff_location`) VALUES
(1, 3, 5, '2024-09-04', NULL, '2024-09-08', NULL, NULL, 20000.00, '0', '0', '0', '2024-09-21 12:25:48', '2024-09-21 12:25:48', NULL, NULL),
(6, 2, 5, '2024-09-22', NULL, '2024-09-22', NULL, NULL, 2000.00, '0', '0', '0', '2024-09-22 06:25:16', '2024-09-22 06:25:16', NULL, NULL),
(7, 17, 6, '2024-09-23', NULL, '2024-09-26', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-23 04:02:44', '2024-09-23 04:02:44', NULL, NULL),
(8, 17, 6, '2024-09-23', NULL, '2024-09-26', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-23 04:03:20', '2024-09-23 04:03:20', NULL, NULL),
(9, 17, 6, '2024-09-23', NULL, '2024-09-26', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-23 04:06:07', '2024-09-23 04:06:07', NULL, NULL),
(10, 17, 6, '2024-09-23', NULL, '2024-09-26', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-23 04:11:00', '2024-09-23 04:11:00', NULL, NULL),
(11, 17, 7, '2024-09-23', NULL, '2024-09-24', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-23 04:25:45', '2024-09-23 04:25:45', NULL, NULL),
(12, 17, 7, '2024-09-23', NULL, '2024-09-24', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-23 04:26:16', '2024-09-23 04:26:16', NULL, NULL),
(13, 17, 7, '2024-09-23', NULL, '2024-09-24', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-23 04:26:35', '2024-09-23 04:26:35', NULL, NULL),
(14, 17, 7, '2024-09-23', NULL, '2024-09-23', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-23 19:02:15', '2024-09-23 19:02:15', NULL, NULL),
(15, 17, 6, '2024-09-23', NULL, '2024-09-25', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-24 00:22:01', '2024-09-24 00:22:01', NULL, NULL),
(16, 17, 16, '2024-09-25', NULL, '2024-09-25', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-26 01:17:10', '2024-09-26 01:17:10', NULL, NULL),
(17, 17, 6, '2024-09-25', NULL, '2024-09-25', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-26 01:45:48', '2024-09-26 01:45:48', NULL, NULL),
(18, 17, 7, '2024-09-25', NULL, '2024-09-25', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-26 01:59:04', '2024-09-26 01:59:04', NULL, NULL),
(19, 17, 6, '2024-09-25', NULL, '2024-09-25', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-26 02:44:58', '2024-09-26 02:44:58', NULL, NULL),
(20, 17, 6, '2024-09-25', NULL, '2024-09-25', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-26 03:07:36', '2024-09-26 03:07:36', NULL, NULL),
(21, 17, 6, '2024-09-25', NULL, '2024-09-25', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-26 03:11:18', '2024-09-26 03:11:18', NULL, NULL),
(22, 17, 6, '2024-09-25', NULL, '2024-09-25', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-26 03:26:05', '2024-09-26 03:26:05', NULL, NULL),
(23, 17, 6, '2024-09-25', NULL, '2024-09-25', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-26 03:29:13', '2024-09-26 03:29:13', NULL, NULL),
(24, 17, 6, '2024-09-26', NULL, '2024-09-28', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-26 03:44:42', '2024-09-26 03:44:42', NULL, NULL),
(25, 17, 6, '2024-09-26', NULL, '2024-09-28', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-26 03:46:36', '2024-09-26 03:46:36', NULL, NULL),
(26, 17, 6, '2024-09-26', NULL, '2024-09-28', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-26 03:55:18', '2024-09-26 03:55:18', NULL, NULL),
(27, 17, 6, '2024-09-26', NULL, '2024-09-26', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-27 02:39:58', '2024-09-27 02:39:58', NULL, NULL),
(28, 17, 7, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-27 04:11:17', '2024-09-27 04:11:17', NULL, NULL),
(29, 17, 7, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-27 05:05:45', '2024-09-27 05:05:45', NULL, NULL),
(31, 17, 6, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-27 12:04:19', '2024-09-27 12:04:19', NULL, NULL),
(32, 17, 6, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-27 12:16:01', '2024-09-27 12:16:01', NULL, NULL),
(33, 17, 16, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-27 13:00:40', '2024-09-27 13:00:40', NULL, NULL),
(35, 17, 6, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-27 16:10:18', '2024-09-27 16:10:18', NULL, NULL),
(36, 17, 6, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-27 16:12:58', '2024-09-27 16:12:58', NULL, NULL),
(37, 17, 6, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-27 18:13:41', '2024-09-27 18:13:41', NULL, NULL),
(38, 17, 6, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-27 18:14:05', '2024-09-27 18:14:05', NULL, NULL),
(39, 17, 6, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-27 18:19:39', '2024-09-27 18:19:39', NULL, NULL),
(40, 17, 6, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-27 18:23:05', '2024-09-27 18:23:05', NULL, NULL),
(41, 17, 6, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-27 18:25:45', '2024-09-27 18:25:45', NULL, NULL),
(42, 17, 7, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-27 19:50:38', '2024-09-27 19:50:38', NULL, NULL),
(43, 17, 7, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-27 19:55:40', '2024-09-27 19:55:40', NULL, NULL),
(44, 17, 7, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-27 20:05:25', '2024-09-27 20:05:25', NULL, NULL),
(45, 17, 7, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-27 20:06:21', '2024-09-27 20:06:21', NULL, NULL),
(46, 17, 7, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-27 20:16:38', '2024-09-27 20:16:38', NULL, NULL),
(47, 17, 7, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-27 20:16:53', '2024-09-27 20:16:53', NULL, NULL),
(48, 17, 7, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-27 20:18:02', '2024-09-27 20:18:02', NULL, NULL),
(49, 17, 7, '2024-09-27', NULL, '2024-09-27', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-27 20:27:44', '2024-09-27 20:27:44', NULL, NULL),
(50, 17, 16, '2024-09-28', NULL, '2024-09-29', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-28 18:39:43', '2024-09-28 18:39:43', NULL, NULL),
(51, 17, 16, '2024-09-28', NULL, '2024-09-29', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-28 18:41:03', '2024-09-28 18:41:03', NULL, NULL),
(52, 17, 16, '2024-09-28', NULL, '2024-09-29', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-28 18:41:25', '2024-09-28 18:41:25', NULL, NULL),
(53, 17, 16, '2024-09-28', NULL, '2024-09-28', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-28 19:09:43', '2024-09-28 19:09:43', NULL, NULL),
(54, 17, 16, '2024-09-28', NULL, '2024-09-28', NULL, NULL, 15000.00, '0', '0', '0', '2024-09-28 19:12:23', '2024-09-28 19:12:23', NULL, NULL),
(55, 17, 6, '2024-09-28', NULL, '2024-09-28', NULL, NULL, 25000.00, '0', '0', '0', '2024-09-28 19:48:39', '2024-09-28 19:48:39', NULL, NULL),
(56, 3, 5, '2024-09-04', NULL, '2024-09-08', NULL, NULL, 20000.00, '0', '0', '0', '2024-10-01 12:57:42', '2024-10-01 12:57:42', 'Galadima', NULL),
(57, 3, 5, '2024-09-04', NULL, '2024-09-08', NULL, NULL, 20000.00, '0', '0', '0', '2024-10-02 23:39:33', '2024-10-02 23:39:33', 'Galadima', 'Berger'),
(58, 17, 7, '2024-10-03', NULL, '2024-10-03', NULL, NULL, 15000.00, '0', '0', '0', '2024-10-03 22:38:54', '2024-10-03 22:38:54', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(59, 17, 7, '2024-10-03', NULL, '2024-10-03', NULL, NULL, 15000.00, '0', '0', '0', '2024-10-03 22:45:23', '2024-10-03 22:45:23', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(60, 17, 7, '2024-10-03', NULL, '2024-10-03', NULL, NULL, 15000.00, '0', '0', '0', '2024-10-03 22:49:52', '2024-10-03 22:49:52', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(61, 17, 7, '2024-10-03', NULL, '2024-10-03', NULL, NULL, 15000.00, '0', '0', '0', '2024-10-03 23:00:13', '2024-10-03 23:00:13', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(62, 17, 7, '2024-10-03', NULL, '2024-10-03', NULL, NULL, 15000.00, '0', '0', '0', '2024-10-03 23:17:14', '2024-10-03 23:17:14', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(63, 17, 7, '2024-10-03', NULL, '2024-10-03', NULL, NULL, 15000.00, '0', '0', '0', '2024-10-03 23:24:02', '2024-10-03 23:24:02', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(64, 17, 7, '2024-10-03', NULL, '2024-10-03', NULL, NULL, 15000.00, '0', '0', '0', '2024-10-03 23:24:59', '2024-10-03 23:24:59', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(65, 17, 7, '2024-10-03', NULL, '2024-10-03', NULL, NULL, 15000.00, '0', '0', '0', '2024-10-03 23:25:20', '2024-10-03 23:25:20', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(66, 17, 7, '2024-10-03', NULL, '2024-10-03', NULL, NULL, 15000.00, '0', '0', '0', '2024-10-03 23:26:13', '2024-10-03 23:26:13', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(67, 17, 7, '2024-10-03', NULL, '2024-10-03', NULL, NULL, 15000.00, '0', '0', '0', '2024-10-03 23:59:47', '2024-10-03 23:59:47', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(68, 17, 7, '2024-10-04', NULL, '2024-10-10', NULL, NULL, 15000.00, '0', '0', '0', '2024-10-04 04:20:14', '2024-10-04 04:20:14', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(69, 17, 7, '2024-10-04', NULL, '2024-10-10', NULL, NULL, 15000.00, '0', '0', '0', '2024-10-04 04:36:55', '2024-10-04 04:36:55', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(70, 17, 7, '2024-10-04', NULL, '2024-10-10', NULL, NULL, 15000.00, '0', '0', '0', '2024-10-04 04:50:33', '2024-10-04 04:50:33', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(71, 17, 7, '2024-10-04', NULL, '2024-10-04', NULL, NULL, 15000.00, '0', '0', '0', '2024-10-04 05:17:10', '2024-10-04 05:17:10', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(72, 2, 5, '2024-09-22', NULL, '2024-09-22', NULL, 2, 2000.00, '0', '0', '0', '2024-10-08 15:40:19', '2024-10-08 15:40:19', 'Dutse', 'Kubwa'),
(73, 17, 5, '2024-10-08', NULL, '2024-10-08', NULL, 1, 15000.00, '0', '0', '0', '2024-10-08 18:24:09', '2024-10-08 18:24:09', '497J+8JC, Gwarinpa Estate, Kubwa 900108, Federal Capital Territory, Nigeria', NULL),
(74, 17, 5, '2024-10-08', NULL, '2024-10-08', NULL, 1, 15000.00, '0', '0', '0', '2024-10-08 18:47:47', '2024-10-08 18:47:47', '497J+8JC, Gwarinpa Estate, Kubwa 900108, Federal Capital Territory, Nigeria', NULL),
(75, 17, 6, '2024-10-09', NULL, '2024-10-09', NULL, 1, 25000.00, '0', '0', '0', '2024-10-09 10:16:47', '2024-10-09 10:16:47', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(76, 17, 6, '2024-10-09', NULL, '2024-10-09', NULL, 1, 25000.00, '0', '0', '0', '2024-10-09 10:18:59', '2024-10-09 10:18:59', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(77, 17, 16, '2024-10-09', NULL, '2024-10-09', NULL, 1, 15000.00, '1', '0', '0', '2024-10-09 10:25:25', '2024-10-09 10:25:55', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(78, 17, 16, '2024-10-09', NULL, '2024-10-09', NULL, 1, 15000.00, '0', '0', '0', '2024-10-09 10:30:08', '2024-10-09 10:30:08', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(79, 17, 6, '2024-10-09', NULL, '2024-10-09', NULL, 1, 25000.00, '1', '0', '0', '2024-10-09 11:05:44', '2024-10-09 11:06:02', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(80, 17, 9, '2024-10-09', NULL, '2024-10-09', NULL, 1, 20000.00, '1', '0', '0', '2024-10-09 11:10:12', '2024-10-09 11:10:32', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(81, 17, 7, '2024-10-09', NULL, '2024-10-09', NULL, 1, 15000.00, '1', '0', '0', '2024-10-09 18:16:47', '2024-10-09 18:17:18', '49QQ+X34 Kubwa, Nigeria', NULL),
(82, 17, 6, '2024-10-09', NULL, '2024-10-09', NULL, 1, 25000.00, '1', '0', '0', '2024-10-10 02:19:17', '2024-10-10 02:19:50', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(83, 17, 5, '2024-10-09', NULL, '2024-10-12', NULL, 4, 15000.00, '1', '0', '0', '2024-10-10 03:28:23', '2024-10-10 03:28:41', '74, Zone 7 Dawaki Road After The Brent School Dutse Alh Dutse Alh zone 7 No 74, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(87, 23, 7, '2024-10-13', NULL, '2024-10-15', NULL, 3, 15000.00, '0', '0', '0', '2024-10-13 19:01:41', '2024-10-13 19:01:41', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(88, 23, 7, '2024-10-13', NULL, '2024-10-15', NULL, 3, 15000.00, '0', '0', '0', '2024-10-13 19:02:31', '2024-10-13 19:02:31', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(89, 23, 7, '2024-10-13', NULL, '2024-10-15', NULL, 3, 15000.00, '0', '0', '0', '2024-10-13 19:03:51', '2024-10-13 19:03:51', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(90, 23, 7, '2024-10-13', NULL, '2024-10-15', NULL, 3, 15000.00, '0', '0', '0', '2024-10-13 19:04:39', '2024-10-13 19:04:39', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(91, 23, 7, '2024-10-13', NULL, '2024-10-15', NULL, 3, 15000.00, '0', '0', '0', '2024-10-13 19:04:58', '2024-10-13 19:04:58', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(92, 23, 7, '2024-10-13', NULL, '2024-10-15', NULL, 3, 15000.00, '1', '0', '0', '2024-10-13 19:08:19', '2024-10-13 19:08:55', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL),
(94, 23, 6, '2024-10-14', NULL, '2024-10-14', NULL, 1, 25000.00, '1', '0', '0', '2024-10-14 17:34:01', '2024-10-14 17:34:25', '497J+8JC, Gwarinpa Estate, Kubwa 900108, Federal Capital Territory, Nigeria', NULL),
(95, 25, 17, '2024-10-15', NULL, '2024-10-18', NULL, 4, 20000.00, '1', '0', '0', '2024-10-16 01:26:26', '2024-10-16 01:27:51', '12 Kingsley Abbaya Dr, Kubwa 901101, Federal Capital Territory, Nigeria', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking_orders`
--
ALTER TABLE `booking_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_orders_user_id_foreign` (`user_id`),
  ADD KEY `booking_orders_vehicle_id_foreign` (`vehicle_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking_orders`
--
ALTER TABLE `booking_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_orders`
--
ALTER TABLE `booking_orders`
  ADD CONSTRAINT `booking_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_orders_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
