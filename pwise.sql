-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2023 at 03:15 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

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
(2, 'ADD MEMBER', ' has been added as a new member.', 'users', 12, 2, '2023-05-14 20:41:54', b'0'),
(3, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 1, 2, '2023-05-14 21:39:23', b'0'),
(4, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 2, 2, '2023-05-14 21:40:08', b'0'),
(5, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 3, 12, '2023-05-14 22:34:57', b'0'),
(6, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 4, 12, '2023-05-14 22:35:09', b'0'),
(7, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 5, 2, '2023-05-17 17:00:35', b'0'),
(8, 'ADD MEMBER', ' has been added as a new member.', 'users', 14, 2, '2023-05-17 17:02:36', b'0'),
(9, 'CYCLE STARTED', ' has been a new cycle for the month of May 2023.', 'cycle', 1, 0, '2023-05-19 08:22:50', b'0'),
(10, 'CYCLE STARTED', ' has been a new cycle for the month of May 2023.', 'cycle', 2, 2, '2023-05-19 08:24:10', b'0'),
(11, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 6, 2, '2023-05-19 08:28:25', b'0'),
(12, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 7, 2, '2023-05-19 08:28:40', b'0'),
(13, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 8, 12, '2023-05-21 21:16:12', b'0'),
(14, 'CYCLE STARTED', ' has been a new cycle for the month of May 2023.', 'cycle', 3, 9, '2023-05-21 21:22:00', b'0'),
(15, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 9, 9, '2023-05-21 21:22:09', b'0'),
(16, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 10, 9, '2023-05-21 21:22:35', b'0'),
(17, 'CYCLE STARTED', ' has been a new cycle for the month of May 2023.', 'cycle', 4, 2, '2023-05-24 20:36:49', b'0'),
(18, 'CYCLE STARTED', ' has been a new cycle for the month of May 2023.', 'cycle', 5, 2, '2023-05-24 20:48:00', b'0'),
(19, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 11, 2, '2023-05-24 21:10:32', b'0'),
(20, 'CYCLE STARTED', ' has been a new cycle for the month of June 2023.', 'cycle', 6, 2, '2023-06-04 21:48:29', b'0'),
(21, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 12, 2, '2023-06-04 21:48:45', b'0'),
(22, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 13, 2, '2023-06-04 21:49:52', b'0'),
(23, 'CYCLE STARTED', ' has been a new cycle for the month of June 2023.', 'cycle', 7, 2, '2023-06-05 01:43:51', b'0'),
(24, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 14, 2, '2023-06-05 01:49:39', b'0'),
(25, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 15, 2, '2023-06-14 14:59:13', b'0'),
(26, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 16, 2, '2023-06-14 15:27:15', b'0'),
(27, 'CYCLE STARTED', ' has been a new cycle for the month of June 2023.', 'cycle', 8, 2, '2023-06-15 19:03:31', b'0'),
(28, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 17, 2, '2023-06-15 19:04:10', b'0'),
(29, 'CYCLE STARTED', ' has been a new cycle for the month of June 2023.', 'cycle', 9, 2, '2023-06-22 20:45:25', b'0'),
(30, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 18, 2, '2023-06-23 17:58:12', b'0'),
(31, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 19, 2, '2023-06-25 11:24:00', b'0'),
(32, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 20, 2, '2023-06-25 11:26:24', b'0'),
(33, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 21, 0, '2023-06-25 11:43:06', b'0'),
(34, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 22, 0, '2023-06-25 11:44:10', b'0'),
(35, 'CYCLE STARTED', ' has been a new cycle for the month of June 2023.', 'cycle', 10, 2, '2023-06-25 21:29:45', b'0'),
(36, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 23, 2, '2023-06-25 21:30:12', b'0'),
(37, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 24, 2, '2023-06-25 23:10:27', b'0'),
(38, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 25, 2, '2023-06-25 23:11:00', b'0'),
(39, 'ADD BUDGET', ' has been added to the budget.', 'budgets', 26, 12, '2023-06-26 00:47:00', b'0'),
(40, 'ADD MEMBER', ' has been added as a new member.', 'users', 16, 15, '2023-06-26 01:09:35', b'0');

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
(1, 1000.00, 5, '2023-03-14 21:39:23', 2, b'0'),
(2, 2500.00, 5, '2023-04-14 21:40:08', 2, b'0'),
(3, 2000.00, 5, '2023-04-14 22:34:57', 12, b'0'),
(4, 150.00, 5, '2023-04-14 22:35:09', 12, b'0'),
(5, 100.00, 5, '2023-04-17 17:00:35', 2, b'0'),
(6, 1000.00, 5, '2023-04-19 08:28:25', 2, b'0'),
(7, 500.00, 5, '2023-04-19 08:28:40', 2, b'0'),
(8, 1000.00, 5, '2023-03-21 21:16:12', 12, b'0'),
(9, 2000.00, 5, '2023-03-21 21:22:09', 9, b'0'),
(10, 1000.00, 5, '2023-03-21 21:22:35', 9, b'0'),
(11, 1000.00, 5, '2023-05-24 21:10:32', 2, b'0'),
(12, 5000.00, 6, '2023-05-31 21:48:45', 2, b'0'),
(13, 1000.00, 6, '2023-05-31 21:49:52', 2, b'0'),
(14, 1200.00, 7, '2023-06-05 01:49:39', 2, b'0'),
(15, 2000.00, 7, '2023-06-14 14:59:13', 14, b'0'),
(16, 100.00, 7, '2023-06-14 15:27:15', 2, b'0'),
(17, 1000.00, 8, '2023-06-15 19:04:10', 2, b'0'),
(18, 3000.00, 9, '2023-06-23 17:58:12', 2, b'0'),
(21, 1600.00, 9, '2023-06-25 11:43:06', 0, b'0'),
(22, 1600.00, 9, '2023-06-25 11:44:10', 0, b'0'),
(23, 10000.00, 10, '2023-06-25 21:30:12', 2, b'0'),
(24, 1000.00, 10, '2023-06-25 23:10:27', 2, b'0'),
(25, 2000.00, 10, '2023-06-25 23:11:00', 2, b'0'),
(26, 1500.00, 10, '2023-06-26 00:47:00', 12, b'0');

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
(1, 1, 9, 1600.00, '2023-06-25 12:02:06', 2, b'0'),
(2, 2, 9, 1000.00, '2023-06-25 12:04:02', 2, b'0'),
(3, 1, 10, 5000.00, '2023-06-25 21:30:21', 2, b'0'),
(4, 4, 10, 1000.00, '2023-06-25 21:30:37', 2, b'0');

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
(5, 'May', 2023, '2023-05-24 16:00:00', '2023-05-30 16:00:00', 2, NULL, '2023-05-24 20:48:00', b'0'),
(6, 'June', 2023, '2023-05-31 16:00:00', '2023-06-03 16:00:00', 2, NULL, '2023-06-04 21:48:29', b'0'),
(7, 'June', 2023, '2023-06-04 16:00:00', '2023-06-14 16:00:00', 2, NULL, '2023-06-05 01:43:51', b'0'),
(8, 'June', 2023, '2023-06-15 16:00:00', '2023-06-17 16:00:00', 2, NULL, '2023-06-15 19:03:31', b'0'),
(9, 'June', 2023, '2023-06-22 16:00:00', '2023-06-24 16:00:00', 2, NULL, '2023-06-22 20:45:25', b'0'),
(10, 'June', 2023, '2023-06-25 16:00:00', '2023-06-29 16:00:00', 2, NULL, '2023-06-25 21:29:45', b'0');

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
(1, 1700.00, 5, 1, '2023-05-20 02:40:07', 2),
(2, 1200.00, 5, 2, '2023-05-20 02:53:41', 2),
(3, 3000.00, 5, 3, '2023-05-20 10:09:30', 2),
(4, 300.00, 5, 4, '2023-05-20 10:10:03', 2),
(7, 100.00, 5, 3, '2023-05-21 21:15:23', 12),
(8, 1700.00, 5, 6, '2023-05-21 21:23:35', 9),
(9, 1699.00, 5, 7, '2023-05-21 21:24:04', 9),
(10, 1000.00, 5, 3, '2023-05-24 21:07:02', 2),
(11, 3000.00, 6, 3, '2023-06-04 21:49:35', 2),
(12, 99.00, 6, 5, '2023-06-04 21:50:11', 2),
(13, 3000.00, 7, 3, '2023-06-13 19:09:17', 2),
(14, 99.00, 8, 5, '2023-06-15 19:12:41', 2),
(16, 1500.00, 9, 1, '2023-06-23 18:42:22', 2),
(17, 1100.00, 10, 2, '2023-06-25 21:54:33', 2),
(18, 100.00, 10, 2, '2023-06-25 22:01:16', 2),
(19, 1001.00, 10, 8, '2023-06-26 00:49:01', 12);

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
(1, 'Bills', 1, 2, '2023-05-20 02:40:07', 2),
(2, 'Wants', 2, 2, '2023-05-20 02:53:41', 2),
(3, 'Rent', 1, 2, '2023-05-20 10:09:30', 2),
(4, 'Load', 3, 2, '2023-05-20 17:36:37', 2),
(5, 'Bills', 1, 9, '2023-05-21 21:23:35', 9),
(8, 'Tuition Fee', 1, 2, '2023-06-23 17:03:56', 2);

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
(1, 'PLDT', 1, '2023-05-20 02:40:07', 2),
(2, 'Snacks', 2, '2023-05-20 02:53:41', 2),
(3, 'House', 3, '2023-05-20 10:09:30', 2),
(4, 'Maynilad', 1, '2023-05-20 10:10:03', 2),
(5, 'Go99', 4, '2023-05-20 17:36:37', 2),
(6, 'Meralco', 5, '2023-05-21 21:23:35', 9),
(7, 'PLDT', 5, '2023-05-21 21:24:04', 9),
(8, 'Regular 100', 4, '2023-06-23 18:05:25', 2);

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

--
-- Dumping data for table `savings`
--

INSERT INTO `savings` (`id`, `amount`, `archived`, `created`, `created_by`, `cycle_id`, `action`, `remarks`) VALUES
(1, 1000.00, b'0', '2023-06-25 23:15:08', 2, 10, 'ADD', 'Budget has been added'),
(2, 1000.00, b'0', '2023-06-25 23:15:08', 2, 10, 'ADD', 'Budget has been added'),
(3, 200.00, b'0', '2023-06-25 23:15:22', 2, 10, 'ADD', 'Budget has been added'),
(4, 200.00, b'0', '2023-06-25 23:15:22', 2, 10, 'ADD', 'Budget has been added'),
(5, 300.00, b'0', '2023-06-25 23:17:04', 2, 10, 'ADD', 'Budget has been added'),
(6, 0.00, b'0', '2023-06-25 23:32:00', 2, 10, 'ADD', 'Budget has been added'),
(7, 1200.00, b'0', '2023-06-25 23:32:53', 2, 10, 'ADD', 'Budget has been added'),
(8, 100.00, b'0', '2023-06-25 23:38:49', 2, 10, 'ADD', 'Budget has been added'),
(9, 500.00, b'0', '2023-06-25 23:45:34', 2, 10, 'ADD', 'Budget has been added'),
(10, 2000.00, b'0', '2023-06-19 23:56:02', 2, 10, 'ADJUST', 'Savings has been added'),
(11, 2000.00, b'0', '2023-06-25 23:56:46', 2, 10, 'ADJUST', 'Savings has been moved to buget'),
(12, 700.00, b'0', '2023-06-26 00:24:30', 2, 10, 'ADJUST', 'Savings has been moved to buget'),
(13, 500.00, b'0', '2023-06-26 00:50:48', 12, 10, 'ADD', 'Savings has been added');

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
(2, 'Neng', 'Colaljo', 'rey@dbgurus.net.au', 'nellie.bandal', '123456', NULL, '09123456780', 0, b'1', NULL, NULL, 1, '2023-05-01 14:39:39', NULL, b'0'),
(9, 'Rey Adrian', 'Bandal', 'rey@dbgurus.net.au2', 'rey.bandal2', '123456', NULL, '09123456789', 0, b'1', NULL, NULL, 2, '2023-05-14 19:31:17', NULL, b'0'),
(10, 'Rey', 'Bandal', 'rey@dbgurus.net.au3', 'rey.bandal5', '123456', NULL, '09123456789', 0, b'0', NULL, NULL, 2, '2023-05-14 19:32:24', NULL, b'0'),
(11, 'Mikay', 'Colaljo', 'rey@g.c', 'rey.bandal1', '123456', NULL, '09123456789', 0, b'0', NULL, NULL, 2, '2023-05-14 19:33:40', NULL, b'0'),
(12, 'Rey Adrian', 'Bandal', 'reybandal02@gmail.com', 'rey.bandal', '123456', NULL, '09123456789', 2, b'1', NULL, NULL, 3, '2023-05-14 20:41:54', NULL, b'0'),
(13, 'Diane', 'Llamas1', 'patient.01@comembo.com', 'diane.llamas', '123456', NULL, '09121234567', 0, b'1', NULL, NULL, 2, '2023-05-14 22:36:49', NULL, b'0'),
(14, 'Diane', 'Llamas', 'reybandal027@gmail.com', 'diane', 'new123', NULL, '09123456789', 2, b'0', NULL, NULL, 3, '2023-05-17 17:02:36', NULL, b'0'),
(15, 'Shynee', 'Aragones', 'shynee.aragones@gmail.com', 'shynee.aragones', '123456', NULL, '09123456789', 0, b'1', NULL, NULL, 1, '2023-06-26 01:01:42', NULL, b'0'),
(16, 'Diane', 'Llamas', 'dianne.l@gmail.com', 'dianne.l', '123456', NULL, '09123456789', 15, b'1', NULL, NULL, 3, '2023-06-26 01:09:35', NULL, b'0');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `budget_limit`
--
ALTER TABLE `budget_limit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cycle`
--
ALTER TABLE `cycle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `expenses_categories`
--
ALTER TABLE `expenses_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `expenses_name`
--
ALTER TABLE `expenses_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `priority_level`
--
ALTER TABLE `priority_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `savings`
--
ALTER TABLE `savings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
