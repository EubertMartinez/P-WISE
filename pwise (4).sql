-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2023 at 09:26 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pwise`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_type`
--

CREATE TABLE `account_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_type`
--

INSERT INTO `account_type` (`id`, `name`) VALUES
(1, 'Admin'),
(3, 'Member'),
(2, 'Personal');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `activity` text DEFAULT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `table_id` int(11) DEFAULT 0,
  `created_by` int(11) DEFAULT 0,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `archived` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `type`, `activity`, `table_name`, `table_id`, `created_by`, `created`, `archived`) VALUES
(1, 'CYCLE STARTED', ' has been a new cycle for the month of October 2023.', 'cycle', 1, 1, '2023-10-29 11:14:07', b'0'),
(2, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 1, 1, '2023-10-29 11:14:16', b'0'),
(3, 'CYCLE STARTED', ' has been a new cycle for the month of October 2023.', 'cycle', 2, 3, '2023-10-29 11:47:48', b'0'),
(4, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 2, 3, '2023-10-29 13:01:21', b'0'),
(5, 'CYCLE STARTED', ' has been a new cycle for the month of October 2023.', 'cycle', 3, 1, '2023-10-30 03:28:45', b'0'),
(6, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 3, 1, '2023-10-30 03:28:48', b'0'),
(7, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 4, 1, '2023-10-30 03:29:01', b'0'),
(8, 'CYCLE STARTED', ' has been a new cycle for the month of October 2023.', 'cycle', 4, 3, '2023-10-30 04:45:16', b'0'),
(9, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 5, 3, '2023-10-30 04:45:27', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `cycle_id` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `archived` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `amount`, `cycle_id`, `created`, `created_by`, `archived`) VALUES
(1, 150.00, 1, '2023-10-29 11:14:16', 1, b'0'),
(2, 5.00, 2, '2023-10-29 13:01:21', 3, b'0'),
(3, 1.00, 3, '2023-10-30 03:28:48', 1, b'0'),
(4, 4.00, 3, '2023-10-30 03:29:01', 1, b'0'),
(5, 15.00, 4, '2023-10-30 04:45:27', 3, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `budget_limit`
--

CREATE TABLE `budget_limit` (
  `id` int(11) NOT NULL,
  `expensese_category_id` int(11) DEFAULT NULL,
  `cycle_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `archived` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budget_limit`
--

INSERT INTO `budget_limit` (`id`, `expensese_category_id`, `cycle_id`, `amount`, `created`, `created_by`, `archived`) VALUES
(1, 1, 1, 1.00, '2023-10-29 11:14:27', 1, b'0'),
(2, 4, 2, 1.00, '2023-10-29 13:01:25', 3, b'0'),
(3, 1, 3, 1.00, '2023-10-30 03:29:05', 1, b'0'),
(4, 4, 4, 1.00, '2023-10-30 04:45:31', 3, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `cycle`
--

CREATE TABLE `cycle` (
  `id` int(11) NOT NULL,
  `month` varchar(20) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `start` timestamp NULL DEFAULT NULL,
  `end` timestamp NULL DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `archived` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cycle`
--

INSERT INTO `cycle` (`id`, `month`, `year`, `start`, `end`, `parent_id`, `created_by`, `created`, `archived`) VALUES
(1, 'October', 2023, '2023-10-11 16:00:00', '2023-10-27 16:00:00', 1, NULL, '2023-10-29 11:14:07', b'0'),
(2, 'October', 2023, '2023-10-11 16:00:00', '2023-10-27 16:00:00', 3, NULL, '2023-10-29 11:47:48', b'0'),
(3, 'October', 2023, '2023-10-11 16:00:00', '2023-10-30 16:00:00', 1, NULL, '2023-10-30 03:28:45', b'0'),
(4, 'October', 2023, '2023-10-11 16:00:00', '2023-10-30 16:00:00', 3, NULL, '2023-10-30 04:45:16', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `cycle_id` int(11) DEFAULT NULL,
  `expenses_name_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `amount`, `cycle_id`, `expenses_name_id`, `created`, `created_by`) VALUES
(12, 2.00, 3, 2, '2023-10-30 06:41:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `expenses_categories`
--

CREATE TABLE `expenses_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `priority_level_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses_categories`
--

INSERT INTO `expenses_categories` (`id`, `name`, `priority_level_id`, `parent_id`, `created`, `created_by`) VALUES
(1, 'Transpo', 1, 1, '2023-10-29 11:13:17', 1),
(2, 'Bills', 2, 1, '2023-10-29 11:13:24', 1),
(3, 'Foods', 3, 1, '2023-10-29 11:13:31', 1),
(4, 'Transpo', 1, 3, '2023-10-29 11:47:57', 3);

-- --------------------------------------------------------

--
-- Table structure for table `expenses_name`
--

CREATE TABLE `expenses_name` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `expenses_category_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses_name`
--

INSERT INTO `expenses_name` (`id`, `name`, `expenses_category_id`, `created`, `created_by`) VALUES
(1, 'rice', 3, '2023-10-29 11:13:41', 1),
(2, 'house to moa', 1, '2023-10-29 11:13:49', 1),
(3, 'Electric Bill', 2, '2023-10-29 11:13:56', 1),
(4, 'house to moa', 4, '2023-10-29 13:01:40', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `account_type` int(11) DEFAULT NULL,
  `notification_type` varchar(255) DEFAULT NULL,
  `cycle_id` int(11) DEFAULT NULL,
  `expenses_id` int(11) NOT NULL,
  `expenses_name_id` int(11) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `sender_id`, `account_type`, `notification_type`, `cycle_id`, `expenses_id`, `expenses_name_id`, `is_read`, `created_at`) VALUES
(21, 1, 1, 1, 'overspent', 0, 12, 2, 0, '2023-10-30 06:41:41'),
(22, 5, 1, 3, 'overspent', 0, 12, 2, 0, '2023-10-30 06:41:41'),
(23, 6, 1, 3, 'overspent', 0, 12, 2, 0, '2023-10-30 06:41:41'),
(24, 8, 1, 3, 'overspent', 0, 12, 2, 0, '2023-10-30 06:41:41');

-- --------------------------------------------------------

--
-- Table structure for table `priority_level`
--

CREATE TABLE `priority_level` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `priority_level`
--

INSERT INTO `priority_level` (`id`, `name`) VALUES
(1, 'High Priority'),
(2, 'Medium Priority'),
(3, 'Low Priority');

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE `savings` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `archived` bit(1) NOT NULL DEFAULT b'0',
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL DEFAULT 0,
  `cycle_id` int(11) DEFAULT 0,
  `action` varchar(255) NOT NULL DEFAULT 'ADD',
  `remarks` varchar(255) DEFAULT 'Savings has been added'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suggestion_settings`
--

CREATE TABLE `suggestion_settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_customized` tinyint(1) NOT NULL,
  `num_of_cycle` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suggestion_settings`
--

INSERT INTO `suggestion_settings` (`id`, `user_id`, `is_customized`, `num_of_cycle`) VALUES
(1, 1, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `is_verified` bit(1) NOT NULL DEFAULT b'0',
  `verification_link` varchar(255) DEFAULT NULL,
  `change_password_link` varchar(255) DEFAULT NULL,
  `account_type` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `archived` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `username`, `password`, `image`, `mobile`, `parent_id`, `is_verified`, `verification_link`, `change_password_link`, `account_type`, `created`, `created_by`, `archived`) VALUES
(1, 'homeowner', 'homeowner', 'personal2@gmail.com', 'homeowner', 'qweqwe', NULL, '123123', 0, b'1', NULL, NULL, 1, '2023-10-21 14:39:44', NULL, b'0'),
(3, 'personal', 'personal', 'personal@gmail.com', 'personal', 'qweqwe', NULL, '123123123', 0, b'1', NULL, NULL, 2, '2023-10-16 09:57:10', NULL, b'0'),
(5, 'member', 'member', 'member@gmail.com', 'member', 'qweqwe', NULL, '123123', 1, b'1', NULL, NULL, 3, '2023-10-28 18:54:13', NULL, b'0'),
(6, 'member2', 'member', 'member2@gmail.com', 'member2', 'qweqwe', NULL, '123', 1, b'1', NULL, NULL, 3, '2023-10-29 10:25:33', NULL, b'0'),
(8, 'member3', 'member3', 'member3@gmail.com', 'member3', 'qweqwe', NULL, '123', 1, b'1', NULL, NULL, 3, '2023-10-29 05:17:48', NULL, b'0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_type`
--
ALTER TABLE `account_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `budget_limit`
--
ALTER TABLE `budget_limit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cycle`
--
ALTER TABLE `cycle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses_categories`
--
ALTER TABLE `expenses_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses_name`
--
ALTER TABLE `expenses_name`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `priority_level`
--
ALTER TABLE `priority_level`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `savings`
--
ALTER TABLE `savings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suggestion_settings`
--
ALTER TABLE `suggestion_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_type`
--
ALTER TABLE `account_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `budget_limit`
--
ALTER TABLE `budget_limit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cycle`
--
ALTER TABLE `cycle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `expenses_categories`
--
ALTER TABLE `expenses_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `expenses_name`
--
ALTER TABLE `expenses_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `priority_level`
--
ALTER TABLE `priority_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `savings`
--
ALTER TABLE `savings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suggestion_settings`
--
ALTER TABLE `suggestion_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
