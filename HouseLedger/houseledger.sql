-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2025 at 05:28 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `houseledger`
--

-- --------------------------------------------------------

--
-- Table structure for table `households`
--

CREATE TABLE `households` (
  `household_id` int(11) NOT NULL,
  `household_name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `households`
--

INSERT INTO `households` (`household_id`, `household_name`) VALUES
(1, '1610 Household');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `item_name` varchar(256) NOT NULL,
  `item_cost` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `receipt_id`, `item_name`, `item_cost`) VALUES
(66, 13, 'Butter', '5.99'),
(67, 13, 'Eggs', '6.00'),
(68, 13, 'Cheese', '2.99'),
(69, 14, 'Chai', '7.99'),
(70, 14, 'Wurst', '3.99'),
(71, 14, 'Spaetzle', '4.99'),
(72, 15, 'Oranges', '3.00'),
(73, 15, 'Tomatoes', '2.00'),
(74, 15, 'Hummus', '4.00'),
(75, 16, 'Steak', '99.00');

-- --------------------------------------------------------

--
-- Table structure for table `opt_ins_outs`
--

CREATE TABLE `opt_ins_outs` (
  `opt_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `opt_val` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `opt_ins_outs`
--

INSERT INTO `opt_ins_outs` (`opt_id`, `item_id`, `receipt_id`, `user_id`, `opt_val`) VALUES
(61, 66, 13, 1, 1),
(62, 67, 13, 1, 1),
(63, 68, 13, 1, 1),
(64, 69, 14, 2, 1),
(65, 70, 14, 2, 1),
(66, 71, 14, 2, 1),
(67, 69, 0, 1, 0),
(68, 70, 0, 1, 1),
(69, 71, 0, 1, 1),
(70, 69, 0, 3, 0),
(71, 70, 0, 3, 1),
(72, 71, 0, 3, 0),
(73, 66, 0, 3, 1),
(74, 67, 0, 3, 1),
(75, 68, 0, 3, 0),
(76, 72, 15, 3, 1),
(77, 73, 15, 3, 1),
(78, 74, 15, 3, 1),
(79, 72, 0, 1, 1),
(80, 73, 0, 1, 0),
(81, 74, 0, 1, 0),
(82, 75, 16, 1, 1),
(83, 66, 0, 2, 1),
(84, 67, 0, 2, 1),
(85, 68, 0, 2, 1),
(86, 72, 0, 2, 1),
(87, 73, 0, 2, 1),
(88, 74, 0, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `receipt_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `store_name` varchar(256) NOT NULL,
  `receipt_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`receipt_id`, `user_id`, `store_name`, `receipt_date`) VALUES
(13, 1, ' Hornbachers', '2025-05-02'),
(14, 2, ' Aldi', '2025-04-27'),
(15, 3, ' Walmart', '2025-05-15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(256) NOT NULL,
  `user_password` varchar(256) NOT NULL,
  `household_id` int(11) NOT NULL,
  `user_household_id` int(11) NOT NULL COMMENT 'Indicates position within the household'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_password`, `household_id`, `user_household_id`) VALUES
(1, 'rdfiske', 'rdfiske', 1, 1),
(2, 'Carter', 'Carter', 1, 2),
(3, 'Emiley', 'Emiley', 1, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `households`
--
ALTER TABLE `households`
  ADD PRIMARY KEY (`household_id`),
  ADD UNIQUE KEY `household_id_2` (`household_id`),
  ADD KEY `household_id` (`household_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `opt_ins_outs`
--
ALTER TABLE `opt_ins_outs`
  ADD PRIMARY KEY (`opt_id`,`user_id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`receipt_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `household_id` (`household_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `households`
--
ALTER TABLE `households`
  MODIFY `household_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `opt_ins_outs`
--
ALTER TABLE `opt_ins_outs`
  MODIFY `opt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `receipt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
