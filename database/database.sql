-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2026 at 03:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `returns_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `created_at`) VALUES
(1, 'Arben', 'arben@test.com', '044123123', '2026-01-22 12:19:50');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_number` varchar(100) NOT NULL,
  `order_date` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `shop_id`, `customer_id`, `order_number`, `order_date`, `created_at`) VALUES
(1, 1, 1, 'ORD-001', '2026-01-22 12:27:41', '2026-01-22 12:27:41');

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `return_type` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`id`, `order_id`, `reason`, `status`, `return_type`, `created_at`, `updated_at`) VALUES
(1, 1, 'Size too small', 'approved', 'exchange', '2026-01-22 12:30:18', '2026-01-22 12:30:18'),
(2, 1, 'damaged item', 'rejected', 'refund', '2026-01-23 14:15:50', '0000-00-00 00:00:00'),
(4, 1, 'damaged item', 'pending', '', '2026-01-23 14:50:33', '0000-00-00 00:00:00'),
(5, 1, 'damaged item', 'approved', 'refund', '2026-01-23 14:50:44', '0000-00-00 00:00:00'),
(6, 1, 'damaged item', 'approved', 'refund', '2026-01-23 14:50:47', '0000-00-00 00:00:00'),
(7, 1, 'damaged item', 'approved', 'refund', '2026-01-23 14:50:51', '0000-00-00 00:00:00'),
(8, 1, '\'); DROP TABLE returns;--', 'rejected', 'refund', '2026-01-23 14:51:21', '0000-00-00 00:00:00'),
(10, 1, 'damaged item', 'pending', 'exchange', '2026-01-26 17:29:23', '0000-00-00 00:00:00'),
(11, 1, 'i dont like it anymore', 'pending', 'refund', '2026-01-26 17:37:28', '0000-00-00 00:00:00'),
(12, 1, 'i dont like it anymore', 'pending', 'exchange', '2026-01-27 13:31:32', '0000-00-00 00:00:00'),
(13, 1, 'small', 'pending', 'exchange', '2026-01-28 14:29:30', '0000-00-00 00:00:00'),
(14, 1, 'big', 'pending', 'exchange', '2026-01-28 14:29:38', '0000-00-00 00:00:00'),
(15, 1, 'wide', 'pending', 'exchange', '2026-01-28 14:29:44', '0000-00-00 00:00:00'),
(16, 1, 'wrong color', 'pending', 'exchange', '2026-01-28 14:29:53', '0000-00-00 00:00:00'),
(17, 1, 'missing item', 'pending', 'exchange', '2026-01-28 14:30:05', '0000-00-00 00:00:00'),
(18, 1, 'not what I expected', 'pending', 'exchange', '2026-01-28 14:30:20', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Test shop', 'test@test.com', 'test123', '2026-01-22 12:20:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') NOT NULL DEFAULT 'staff',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(2, 'staff1', '$2y$10$g7i8erW.wqEuVC6Qv0xTXuTbZogL/0KwiozjBTR4OSqCjl20Hsdcy', 'staff', NULL),
(5, 'admin', '$2y$10$q3P5x/.sBw9AnLXHoXF2BeUsUryh4EK01R3319DbxZxWNNJ8XCgA.', 'admin', NULL),
(6, 'staff3', '$2y$10$LFRpviNHHIWL9ikezqq//uxrLINLDB14THrg/.LlNe109sx0Fx87G', 'staff', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
