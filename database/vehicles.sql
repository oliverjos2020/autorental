-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 17, 2024 at 08:40 AM
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
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `station_id` bigint(20) UNSIGNED NOT NULL,
  `vehicleMake` varchar(255) DEFAULT NULL,
  `vehicleYear` varchar(255) DEFAULT NULL,
  `vehicleModel` varchar(255) DEFAULT NULL,
  `transmission` varchar(255) DEFAULT NULL,
  `doors` varchar(255) DEFAULT NULL,
  `airCondition` enum('yes','no') DEFAULT NULL,
  `keylessEntry` enum('yes','no') DEFAULT NULL,
  `musicPlayer` enum('yes','no') DEFAULT NULL,
  `airBags` enum('yes','no') DEFAULT NULL,
  `fuelCapacity` varchar(255) DEFAULT NULL,
  `maxSpeed` varchar(255) DEFAULT NULL,
  `maxPower` varchar(255) DEFAULT NULL,
  `motor` varchar(255) DEFAULT NULL,
  `seats` varchar(255) DEFAULT NULL,
  `price_setup_id` bigint(20) UNSIGNED NOT NULL,
  `status` char(1) DEFAULT '0',
  `on_trip` char(1) DEFAULT '0',
  `moreInfo` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `user_id`, `station_id`, `vehicleMake`, `vehicleYear`, `vehicleModel`, `transmission`, `doors`, `airCondition`, `keylessEntry`, `musicPlayer`, `airBags`, `fuelCapacity`, `maxSpeed`, `maxPower`, `motor`, `seats`, `price_setup_id`, `status`, `on_trip`, `moreInfo`, `created_at`, `updated_at`) VALUES
(5, 2, 1, 'toyota', '2022', 'Corolla', 'automatic', '2', 'yes', NULL, NULL, NULL, '', '', '', '', '4', 2, '1', '0', '<ul>\n<li>Front View: Capture the full front of your car.</li>\n<li>Back View: Capture the entire back of your car.</li>\n<li>Interior View: Show the interior, highlighting key features.</li>\n<li>All Sides: Provide images of both sides of your car to give a complete view.</li>\n</ul>', '2024-08-21 23:27:50', '2024-08-22 01:04:36'),
(6, 11, 1, 'audi', '2002', 'Sorento', 'automatic', '2', 'yes', 'yes', 'yes', 'yes', '2.5ltr', '280km/hr', '340HP', '2500cc', '2', 3, '3', '0', 'Lorem ipsum odor amet, consectetuer adipiscing elit. At viverra mattis venenatis venenatis nisi diam aliquet? Sodales magna dictum gravida eleifend nec est pulvinar euismod lacus. Lacus dis litora lorem; curae mattis in magna accumsan est. Nam tempor velit, convallis faucibus consectetur leo. ', '2024-09-06 10:56:56', '2024-09-27 16:01:25'),
(7, 7, 1, 'chevrolet', '1981', 'XC90', 'manual', '4', 'no', NULL, NULL, NULL, '', '', '', '', '11', 2, '3', '0', '<p>Dolor voluptate autem debitis non necessitatibus.</p>', '2024-09-06 10:56:56', '2024-09-23 00:13:50'),
(9, 7, 1, 'Mercedes-Benz', '1974', '3 Series', 'manual', '3', 'yes', NULL, NULL, NULL, '', '', '', '', '16', 1, '1', '0', 'Voluptatem occaecati veniam delectus inventore enim hic.', '2024-09-06 10:56:56', '2024-09-06 10:56:56'),
(10, 9, 1, 'Kia', '1970', '3 Series', 'manual', '3', 'no', NULL, NULL, NULL, '', '', '', '', '16', 2, '2', '0', 'Ab ullam quod quibusdam dolorum.', '2024-09-06 10:56:56', '2024-09-06 10:56:56'),
(11, 2, 1, 'BMW', '2018', 'Model S', 'automatic', '2', 'no', NULL, NULL, NULL, '', '', '', '', '16', 4, '2', '0', 'Consectetur non commodi soluta eligendi.', '2024-09-06 10:56:56', '2024-09-06 10:56:56'),
(12, 10, 1, 'Kia', '2005', 'XC90', 'manual', '4', 'yes', NULL, NULL, NULL, '', '', '', '', '7', 4, '3', '0', 'Veniam eius voluptatum esse fugiat.', '2024-09-06 10:56:56', '2024-09-06 10:56:56'),
(13, 6, 1, 'Audi', '2001', '488', 'automatic', '3', 'no', NULL, NULL, NULL, '', '', '', '', '7', 2, '2', '0', 'Sunt mollitia voluptatibus molestias aut iure mollitia.', '2024-09-06 10:56:56', '2024-09-06 10:56:56'),
(14, 3, 1, 'Chevrolet', '1974', '3 Series', 'manual', '2', 'yes', NULL, NULL, NULL, '', '', '', '', '6', 1, '1', '0', 'Eos qui asperiores non et eaque minima.', '2024-09-06 10:56:56', '2024-09-06 10:56:56'),
(15, 3, 1, 'Porsche', '1989', '3 Series', 'automatic', '3', 'no', NULL, NULL, NULL, '', '', '', '', '16', 1, '1', '0', 'Magni voluptatem eveniet quae ut architecto possimus.', '2024-09-06 10:56:56', '2024-09-06 10:56:56'),
(16, 2, 1, 'toyota', '2021', 'Corolla', 'automatic', '4', 'yes', 'no', 'no', 'yes', '2.5ltr', '380km/hr', '340HP', '2500cc', '4', 2, '1', '0', 'Fastttttttt', '2024-09-25 14:23:59', '2024-09-28 05:55:09'),
(17, 2, 1, 'tesla', '2016', 'Tesla', 'automatic', '2', 'yes', 'yes', 'no', 'no', '2.4', '3', '5', '2', '2', 1, '1', '0', 'Tesla is one of the dopest cars', '2024-09-30 02:52:50', '2024-09-30 02:52:50'),
(18, 2, 1, 'tesla', '2018', 'Tesla', 'automatic', '4', 'yes', 'yes', 'yes', 'no', '2.5', '3.5', '4.3', '3', '2', 1, '1', '0', 'This is a powerful car!!!', '2024-09-30 04:52:03', '2024-09-30 04:52:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicles_user_id_foreign` (`user_id`),
  ADD KEY `vehicles_price_setup_id_foreign` (`price_setup_id`),
  ADD KEY `station_id` (`station_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_price_setup_id_foreign` FOREIGN KEY (`price_setup_id`) REFERENCES `price_setups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
