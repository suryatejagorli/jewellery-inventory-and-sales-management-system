-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql202.infinityfree.com
-- Generation Time: Feb 28, 2026 at 07:56 AM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41195486_sbjdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(3, 'SBJ', '$2y$10$/Cf2UK4/R.hARDflbZr1s.o4vivbZP7SIHKzWWjjVTGligOEJtpeO');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Gold'),
(2, 'Silver'),
(3, 'Diamond');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `email`, `address`) VALUES
(6, 'SURYA TEJA', '7036581697', '', 'srm ktr'),
(7, 'user', '9876543210', '', 'class'),
(9, 'user 2', '9123456780', '', 'danger zone '),
(10, 'Rohan sai', '6304924452', '', 'mi inti pakkana'),
(11, 'K MONISH DATH', '9010517655', '', 'AVITA PG thailavram chennai'),
(12, 'R Kishore', '7075471594', '', 'avita residency'),
(13, 'yaswanth kumar', '9876543456', '', '53-29625-18/3\r\nmadhigapalem, vijayawada\r\nandhra pradesh'),
(14, 'test customer', '9876543210', '', 'customer address'),
(15, 'test customer', '9876543210', '', 'customer address'),
(16, 'SURYA TEJA', '7036581697', '', 'srm university'),
(17, 'user', '9999999999', '', 'delivery address'),
(18, 'SURYA TEJA', '9876543210', '', 'asdfghwerty'),
(19, 'user', '9876543210', '', 'kattankulathur'),
(20, 'user', '9876543210', '', 'pondicherry'),
(21, 'user', '9876543210', '', 'pondi cherry'),
(22, 'cash', '9876543210', '', 'pondi cherry'),
(23, 'user', '9876543210', '', 'pondi cherry'),
(24, 'user', '9876543210', '', 'pondi cherry, rock beach villa'),
(25, 'user', '9876543210', '', 'pondi merina beach restaurent villa'),
(26, 'user', '9876543210', '', 't.nagar, chennai'),
(27, 'SURYA TEJA', '7036581697', '', 'kerala backwaters'),
(28, 'Anirudh Rampalli', '8074360367', '', 'kanchipuram'),
(29, 'Akhil Mattay', '8498891116', '', 'potheri, chennai'),
(30, 'Rishitha', '7893240159', '', 'tadepalligudem  andhra pradhesh'),
(31, 'R . K I S H O R E', '9618074370', '', 'avita recidency');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `gst_amount` decimal(10,2) DEFAULT NULL,
  `delivery_charge` decimal(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `order_status` enum('Pending','Packed','Cancelled') DEFAULT 'Pending',
  `order_source` enum('Online','Offline') NOT NULL DEFAULT 'Online',
  `payment_status` enum('Unpaid','Partially Paid','Paid') DEFAULT 'Unpaid',
  `is_emi` tinyint(1) DEFAULT 0,
  `emi_total_amount` decimal(10,2) DEFAULT 0.00,
  `emi_paid_amount` decimal(10,2) DEFAULT 0.00,
  `emi_remaining_amount` decimal(10,2) DEFAULT 0.00,
  `emi_months` int(11) DEFAULT 0,
  `device_type` varchar(20) DEFAULT NULL,
  `payment_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `customer_name`, `phone`, `address`, `payment_method`, `total_amount`, `subtotal`, `gst_amount`, `delivery_charge`, `order_date`, `order_status`, `order_source`, `payment_status`, `is_emi`, `emi_total_amount`, `emi_paid_amount`, `emi_remaining_amount`, `emi_months`, `device_type`, `payment_time`) VALUES
(59, 6, 'SURYA TEJA', '7036581697', 'srm ktr', 'Cash', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-14 01:03:14', 'Packed', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(60, 7, 'user', '9876543210', 'class', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-14 01:04:25', 'Pending', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(62, 7, 'user.1 1', '9876543210', 'college', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-14 01:08:24', 'Pending', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(63, 9, 'user 2', '9123456780', 'danger zone ', 'Cash', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-14 01:26:58', 'Pending', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(64, 6, 'SURYA TEJA', '7036581697', 'home', 'Cash', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-14 22:31:05', 'Packed', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(65, 10, 'Rohan sai', '6304924452', 'mi inti pakkana', 'Cash', '528075.00', '512500.00', '15375.00', '200.00', '2026-02-14 22:50:30', 'Packed', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(66, 11, 'K MONISH DATH', '9010517655', 'AVITA PG thailavram chennai', 'UPI', '218560.00', '212000.00', '6360.00', '200.00', '2026-02-14 23:07:34', 'Pending', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(67, 12, 'R Kishore', '7075471594', 'avita residency', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-14 23:11:43', 'Packed', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(68, 6, 'SURYA TEJA', '7036581697', 'Room no: 408, 4th floor, TP2, SRM University, KTR, Chennai, Tamil Nadu, 603203.', 'Cash', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-15 13:18:01', 'Packed', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(69, 6, 'SURYA TEJA', '7036581697', 'Secret Valley', 'Cash', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-15 17:18:19', 'Cancelled', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(70, 6, 'SURYA TEJA', '7036581697', 'avita , residency', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-15 19:42:40', 'Packed', 'Online', 'Paid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(71, 7, 'Teja', '9876543210', 'Kerala, backwaters', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-15 19:43:30', 'Packed', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(72, 7, 'SURYA TEJA', '9876543210', 'avita chennai', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-15 21:48:33', 'Packed', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(73, 6, 'SURYA TEJA', '7036581697', 'avita chennai', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-16 00:03:33', 'Packed', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(74, 7, 'Anirudh', '9876543210', 'kanchipuram', 'Cash', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-16 00:54:18', 'Packed', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(75, 10, 'Rohan', '6304924452', 'chengalpattu', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-16 01:01:48', 'Pending', 'Online', 'Paid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(76, 6, 'SURYA TEJA', '7036581697', 'thailavaram', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-16 01:28:08', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '0.00', '0.00', 0, NULL, NULL),
(77, 7, 'SURYA TEJA', '9876543210', 'mumbai, maharastra', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-16 08:51:01', 'Packed', 'Online', 'Partially Paid', 1, '105775.00', '0.00', '0.00', 0, NULL, NULL),
(78, 13, 'yaswanth kumar', '9876543456', '53-29625-18/3\\r\\nmadhigapalem, vijayawada\\r\\nandhra pradesh', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-16 09:14:06', 'Pending', 'Online', 'Unpaid', 1, '105775.00', '0.00', '0.00', 0, NULL, NULL),
(81, 14, 'test customer', '9876543210', 'customer address', 'EMI', '102500.00', '102500.00', '3075.00', '0.00', '2026-02-16 23:35:06', 'Pending', 'Online', 'Partially Paid', 1, '102500.00', '20500.00', '84460.00', 3, NULL, NULL),
(82, 15, 'test customer', '9876543210', 'customer address', 'EMI', '102500.00', '102500.00', '3075.00', '0.00', '2026-02-16 23:37:24', 'Pending', 'Online', 'Partially Paid', 1, '102500.00', '20500.00', '84460.00', 3, NULL, NULL),
(83, 16, 'SURYA TEJA', '7036581697', 'srm university', 'EMI', '102500.00', '102500.00', '3075.00', '0.00', '2026-02-16 23:41:46', 'Pending', 'Online', 'Partially Paid', 1, '102500.00', '20500.00', '84460.00', 3, NULL, NULL),
(84, 17, 'user', '9999999999', 'delivery address', 'EMI', '102500.00', '102500.00', '3075.00', '0.00', '2026-02-17 00:04:02', 'Packed', 'Online', 'Partially Paid', 1, '102500.00', '20500.00', '84460.00', 3, NULL, NULL),
(85, 18, 'SURYA TEJA', '9876543210', 'asdfghwerty', 'EMI', '102500.00', '102500.00', '3075.00', '0.00', '2026-02-17 01:12:14', 'Packed', 'Online', 'Partially Paid', 1, '102500.00', '20500.00', '86920.00', 6, NULL, NULL),
(86, 19, 'user', '9876543210', 'kattankulathur', 'EMI', '102500.00', '102500.00', '3075.00', '0.00', '2026-02-17 01:19:29', 'Pending', 'Online', 'Partially Paid', 1, '102500.00', '20500.00', '86920.00', 6, NULL, NULL),
(87, 20, 'user', '9876543210', 'pondicherry', 'EMI', '102500.00', '102500.00', '3075.00', '0.00', '2026-02-17 01:26:05', 'Pending', 'Online', 'Partially Paid', 1, '102500.00', '20500.00', '89380.00', 9, NULL, NULL),
(88, 21, 'user', '9876543210', 'pondi cherry', 'Cash', '102500.00', '102500.00', '3075.00', '0.00', '2026-02-17 01:27:05', 'Packed', 'Online', 'Paid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(89, 22, 'cash', '9876543210', 'pondi cherry', 'Cash', '102500.00', '102500.00', '3075.00', '0.00', '2026-02-17 01:41:22', 'Pending', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(90, 23, 'user', '9876543210', 'pondi cherry', 'Cash', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-17 01:47:20', 'Packed', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(91, 24, 'user', '9876543210', 'pondi cherry, rock beach villa', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-17 01:48:07', 'Packed', 'Online', 'Paid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(92, 25, 'user', '9876543210', 'pondi merina beach restaurent villa', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-17 01:49:59', 'Packed', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '91840.00', 12, NULL, NULL),
(93, 26, 'user', '9876543210', 't.nagar, chennai', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-17 02:21:21', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '88560.00', 12, NULL, NULL),
(94, 27, 'SURYA TEJA', '7036581697', 'kerala backwaters', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-19 13:52:14', 'Packed', 'Online', 'Unpaid', 1, '105775.00', '20500.00', '83640.00', 3, NULL, NULL),
(95, 6, 'SURYA TEJA', '7036581697', 'avita residency, potheri', 'Cash', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-19 06:49:40', 'Pending', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(96, 7, 'user 2', '9876543210', 'user address', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-19 06:50:34', 'Pending', 'Online', '', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(97, 7, 'user 2', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-19 06:51:53', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '83640.00', 3, NULL, NULL),
(98, 6, 'SURYA TEJA', '7036581697', 'SRM University, Chennai', 'Cash', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-19 06:52:44', 'Pending', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(99, 6, 'SURYA TEJA', '7036581697', 'potheri, chennai', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-19 09:43:41', 'Pending', 'Online', '', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(100, 6, 'SURYA TEJA', '7036581697', 'avita residency', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-19 11:00:21', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '83640.00', 3, NULL, '2026-02-19 12:26:45'),
(101, 6, 'SURYA TEJA', '7036581697', 'customer delivery', 'Cash', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-19 12:28:40', 'Pending', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(102, 7, 'SURYA TEJA', '9876543210', 'customer delivery', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-19 12:29:23', 'Packed', 'Online', 'Paid', 0, '0.00', '0.00', '0.00', 0, NULL, '2026-02-19 12:29:36'),
(103, 7, 'user', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-19 12:42:16', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '88560.00', 12, NULL, '2026-02-19 12:42:30'),
(104, 7, 'user', '9876543210', 'user address', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-19 12:50:03', 'Pending', 'Online', '', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(105, 7, 'user', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-19 12:50:56', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '83640.00', 3, NULL, '2026-02-19 12:51:03'),
(106, 7, 'user', '9876543210', 'amalapuram', 'Cash', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-20 02:29:08', 'Cancelled', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(107, 7, 'user', '9876543210', 'user address', 'Cash', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-20 02:40:47', 'Packed', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(108, 6, 'Surya', '7036581697', '408, avita Residency, thailavaram, chennai , tamil nadu', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-20 22:00:05', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, '2026-02-20 22:00:27'),
(109, 6, 'SURYA TEJA', '7036581697', 'My address', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-20 22:01:56', 'Pending', 'Online', 'Paid', 0, '0.00', '0.00', '0.00', 0, NULL, '2026-02-20 22:02:05'),
(110, 10, 'rohan', '6304924452', 'edhuru illu', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-20 23:15:28', 'Packed', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '88560.00', 12, NULL, '2026-02-20 23:15:58'),
(111, 10, 'rohan', '6304924452', 'pondycherry', 'Cash', '131010.00', '127000.00', '3810.00', '200.00', '2026-02-21 00:05:14', 'Packed', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(112, 6, 'SURYA TEJA', '7036581697', 'vijayawada', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 00:30:19', 'Packed', 'Online', 'Paid', 0, '0.00', '0.00', '0.00', 0, NULL, '2026-02-21 00:30:36'),
(113, 6, 'SURYA TEJA', '7036581697', 'hyderabad,', 'Cash', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 00:31:17', 'Pending', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(114, 6, 'SURYA TEJA', '7036581697', 'rajahmundry', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 00:31:59', 'Pending', 'Online', 'Paid', 1, '105775.00', '20500.00', '83640.00', 3, NULL, '2026-02-21 00:32:11'),
(115, 6, 'SURYA TEJA', '7036581697', 'tadepalligudem', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 00:37:42', 'Packed', 'Online', 'Paid', 0, '0.00', '0.00', '0.00', 0, NULL, '2026-02-21 00:37:54'),
(116, 6, 'SURYA TEJA', '7036581697', 'tadepalligudem', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 00:39:08', 'Packed', 'Online', 'Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, '2026-02-21 00:39:14'),
(117, 6, 'SURYA TEJA', '7036581697', 'chennai, potheri', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 00:46:44', 'Pending', 'Online', 'Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, '2026-02-21 00:46:53'),
(118, 6, 'SURYA TEJA', '7036581697', 'munnar, kerala', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 00:59:11', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, '2026-02-21 00:59:18'),
(119, 6, 'SURYA TEJA', '7036581697', 'my address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 02:04:45', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, NULL),
(120, 7, 'user', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 02:09:22', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '83640.00', 3, NULL, NULL),
(121, 7, 'user', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 02:17:04', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, NULL),
(122, 7, 'user', '9876543210', 'user address', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 02:22:16', 'Pending', 'Online', 'Paid', 0, '0.00', '0.00', '0.00', 0, NULL, '2026-02-21 02:22:35'),
(123, 7, 'user', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 02:23:35', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, '2026-02-21 02:41:38'),
(124, 7, 'user', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 02:45:54', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, '2026-02-21 02:46:10'),
(125, 7, 'user', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 02:54:59', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '86920.00', 9, NULL, NULL),
(126, 7, 'user', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 02:59:16', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, NULL),
(127, 7, 'user', '9876543210', 'useraddress', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 11:53:00', 'Pending', 'Online', 'Paid', 0, '0.00', '0.00', '0.00', 0, NULL, '2026-02-21 11:53:20'),
(128, 7, 'user', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 11:54:16', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '83640.00', 3, NULL, '2026-02-21 12:05:41'),
(129, 7, 'user', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 12:17:38', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, NULL),
(130, 7, 'user', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 12:19:24', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, '2026-02-21 12:26:13'),
(131, 7, 'user', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 12:31:10', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, NULL),
(132, 7, 'user1', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 12:45:35', 'Packed', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, NULL),
(133, 7, 'user', '9876543210', 'user address', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 14:36:13', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, '2026-02-21 14:36:29'),
(134, 7, 'user', '9876543210', 'user address', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 14:38:02', 'Packed', 'Online', 'Paid', 0, '0.00', '0.00', '0.00', 0, NULL, '2026-02-21 14:38:19'),
(135, 7, 'user', '9876543210', 'user address', 'Cash', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 14:39:07', 'Packed', 'Online', 'Unpaid', 0, '0.00', '0.00', '0.00', 0, NULL, NULL),
(136, 6, 'SURYA TEJA', '7036581697', 'srm ub 12 thfloor', 'UPI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-21 14:41:30', 'Packed', 'Online', 'Paid', 0, '0.00', '0.00', '0.00', 0, NULL, '2026-02-21 14:42:01'),
(137, 7, 'SURYA TEJA', '9876543210', 'tp2 14th floor 11406', 'EMI', '146460.00', '142000.00', '4260.00', '200.00', '2026-02-21 14:44:48', 'Packed', 'Online', 'Partially Paid', 1, '146460.00', '28400.00', '115872.00', 3, NULL, '2026-02-21 14:45:12'),
(138, 6, 'SURYA TEJA', '7036581697', 'Potheri, chennai', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-22 17:42:04', 'Pending', 'Online', 'Unpaid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, NULL),
(139, 28, 'Anirudh Rampalli', '8074360367', 'kanchipuram', 'EMI', '45520.00', '44000.00', '1320.00', '200.00', '2026-02-22 17:44:49', 'Pending', 'Online', 'Unpaid', 1, '45520.00', '8800.00', '36608.00', 6, NULL, NULL),
(140, 28, 'Anirudh Rampalli', '8074360367', 'Kanchipuram', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-22 17:46:14', 'Packed', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '83640.00', 3, NULL, '2026-02-22 17:47:05'),
(141, 29, 'Akhil Mattay', '8498891116', 'potheri, chennai', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-23 00:09:16', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, '2026-02-23 00:09:34'),
(142, 30, 'Rishitha', '7893240159', 'tadepalligudem  andhra pradhesh', 'EMI', '41400.00', '40000.00', '1200.00', '200.00', '2026-02-23 21:53:52', 'Pending', 'Online', 'Partially Paid', 1, '41400.00', '8000.00', '33920.00', 9, NULL, '2026-02-23 21:54:28'),
(143, 31, 'R . K I S H O R E', '9618074370', 'avita recidency', 'EMI', '105775.00', '102500.00', '3075.00', '200.00', '2026-02-27 21:11:07', 'Pending', 'Online', 'Partially Paid', 1, '105775.00', '20500.00', '85280.00', 6, NULL, '2026-02-27 21:11:31');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(61, 59, 2, 1, '102500.00'),
(62, 60, 2, 1, '102500.00'),
(64, 62, 2, 1, '102500.00'),
(65, 63, 2, 1, '102500.00'),
(66, 64, 2, 1, '102500.00'),
(67, 65, 2, 5, '102500.00'),
(68, 66, 18, 2, '106000.00'),
(69, 67, 2, 1, '102500.00'),
(70, 68, 2, 1, '102500.00'),
(71, 69, 2, 1, '102500.00'),
(72, 70, 2, 1, '102500.00'),
(73, 71, 2, 1, '102500.00'),
(74, 72, 2, 1, '102500.00'),
(75, 73, 2, 1, '102500.00'),
(76, 74, 2, 1, '102500.00'),
(77, 75, 2, 1, '102500.00'),
(78, 76, 2, 1, '102500.00'),
(79, 77, 2, 1, '102500.00'),
(80, 78, 2, 1, '102500.00'),
(81, 81, 2, 1, '102500.00'),
(82, 82, 2, 1, '102500.00'),
(83, 83, 2, 1, '102500.00'),
(84, 84, 2, 1, '102500.00'),
(85, 85, 2, 1, '102500.00'),
(86, 86, 2, 1, '102500.00'),
(87, 87, 2, 1, '102500.00'),
(88, 88, 2, 1, '102500.00'),
(89, 89, 2, 1, '102500.00'),
(90, 90, 2, 1, '102500.00'),
(91, 91, 2, 1, '102500.00'),
(92, 92, 2, 1, '102500.00'),
(93, 93, 2, 1, '102500.00'),
(94, 94, 2, 1, '102500.00'),
(95, 95, 2, 1, '102500.00'),
(96, 96, 2, 1, '102500.00'),
(97, 97, 2, 1, '102500.00'),
(98, 98, 2, 1, '102500.00'),
(99, 99, 2, 1, '102500.00'),
(100, 100, 2, 1, '102500.00'),
(101, 101, 2, 1, '102500.00'),
(102, 102, 2, 1, '102500.00'),
(103, 103, 2, 1, '102500.00'),
(104, 104, 2, 1, '102500.00'),
(105, 105, 2, 1, '102500.00'),
(106, 106, 2, 1, '102500.00'),
(107, 107, 2, 1, '102500.00'),
(108, 108, 2, 1, '102500.00'),
(109, 109, 2, 1, '102500.00'),
(110, 110, 2, 1, '102500.00'),
(111, 111, 7, 1, '127000.00'),
(112, 115, 2, 1, '102500.00'),
(113, 116, 2, 1, '102500.00'),
(114, 117, 2, 1, '102500.00'),
(115, 118, 2, 1, '102500.00'),
(116, 119, 2, 1, '102500.00'),
(117, 120, 2, 1, '102500.00'),
(118, 121, 2, 1, '102500.00'),
(119, 122, 2, 1, '102500.00'),
(120, 123, 2, 1, '102500.00'),
(121, 124, 2, 1, '102500.00'),
(122, 125, 2, 1, '102500.00'),
(123, 126, 2, 1, '102500.00'),
(124, 127, 2, 1, '102500.00'),
(125, 128, 2, 1, '102500.00'),
(126, 129, 2, 1, '102500.00'),
(127, 130, 2, 1, '102500.00'),
(128, 131, 2, 1, '102500.00'),
(129, 132, 2, 1, '102500.00'),
(130, 133, 2, 1, '102500.00'),
(131, 134, 2, 1, '102500.00'),
(132, 135, 2, 1, '102500.00'),
(133, 136, 2, 1, '102500.00'),
(134, 137, 19, 1, '142000.00'),
(135, 138, 2, 1, '102500.00'),
(136, 139, 13, 1, '44000.00'),
(137, 140, 2, 1, '102500.00'),
(138, 141, 2, 1, '102500.00'),
(139, 142, 17, 1, '40000.00'),
(140, 143, 2, 1, '102500.00');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_method`, `transaction_id`, `amount`, `payment_status`, `payment_date`) VALUES
(2, 69, 'Cash', NULL, '105775.00', 'Pending', '2026-02-15 17:18:19'),
(3, 70, 'UPI', 'UPI-225527', '105775.00', 'Paid', '2026-02-15 19:42:40'),
(4, 71, 'EMI', 'EMI-581440', '105775.00', 'Paid', '2026-02-15 19:43:30'),
(5, 72, 'UPI', 'UPI-739754', '105775.00', 'Paid', '2026-02-15 21:48:33');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `purity` enum('916','999') NOT NULL,
  `huid_code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `weight`, `price`, `stock`, `image`, `description`, `featured`, `purity`, `huid_code`) VALUES
(2, 1, 'Gold Ring', '6.00', '102500.00', 13, 'Screenshot 2026-01-30 000237.png', '6 gm of Gold Ring ', 1, '916', 'SBJ00101'),
(3, 1, 'Gold Ring', '8.00', '135000.00', 10, 'Screenshot 2026-01-30 142201.png', '8 gm of Gold Ring', 0, '916', 'SBJ00102'),
(4, 1, 'Gold Ring', '10.00', '166000.00', 10, 'Screenshot 2026-01-30 142230.png', '10 gm of Gold Ring', 0, '916', 'SBJ00103'),
(5, 1, 'Gold Ring', '5.00', '88000.00', 10, 'Screenshot 2026-01-30 142328.png', '5 gm of Gold Ring', 0, '916', 'SBJ00104'),
(6, 1, 'Gold Ring', '12.00', '202500.00', 10, 'Screenshot 2026-01-30 143212.png', '12 gm of Gold Ring', 0, '916', 'SBJ00105'),
(7, 1, 'Ear Rings', '7.50', '127000.00', 2, 'Screenshot 2026-01-30 143737.png', '7.5 gm of Ear Rings', 1, '916', 'SBJ00106'),
(8, 1, 'Ear Rings', '10.00', '166000.00', 10, 'Screenshot 2026-01-30 143823.png', '10 gm of Ear Rings', 0, '916', 'SBJ00107'),
(9, 1, 'Ear Rings', '8.00', '135000.00', 10, 'Screenshot 2026-01-30 143941.png', '8 gm of Ear Rings', 0, '916', 'SBJ00108'),
(10, 1, 'Bangles', '24.00', '406000.00', 10, 'Screenshot 2026-01-30 144419.png', '24 gm of Bangles', 0, '916', 'SBJ00109'),
(11, 1, 'Necklace', '24.00', '406000.00', 3, 'Screenshot 2026-01-30 144456.png', '24 gm of Necklace', 0, '916', 'SBJ00110'),
(12, 2, 'Anklets', '80.00', '29000.00', 10, 'Screenshot 2026-01-30 144608.png', '80 gm of Silver Anklet', 0, '', 'SBJ00111'),
(13, 2, 'Anklets', '120.00', '44000.00', 9, 'Screenshot 2026-01-30 144709.png', '120 gm of Silver Anklet', 0, '', 'SBJ00112'),
(14, 2, 'Anklets', '35.00', '13000.00', 2, 'Screenshot 2026-01-30 144853.png', '35 gm of Silver Anklet', 0, '', 'SBJ00113'),
(15, 2, 'Chains', '22.00', '8200.00', 0, 'Screenshot 2026-01-30 145548.png', '22 gm of Silver Chain', 1, '', 'SBJ00114'),
(16, 2, 'Chains', '18.00', '6800.00', 10, 'Screenshot 2026-01-30 145815.png', '18 gm of Silver Chain', 0, '', 'SBJ00115'),
(17, 1, 'Gold Ring', '2.00', '40000.00', 9, 'Screenshot 2026-01-30 151313.png', '2 gm of Gold Ring', 1, '916', 'SBJ00116'),
(18, 3, 'Diamond Ring', '4.50', '106000.00', 10, 'Screenshot 2026-01-30 155503.png', '4.5 gm of Diamond Ring (Gold)', 0, '', 'SBJ00117'),
(19, 3, 'Diamond Ring', '7.50', '142000.00', 2, 'Screenshot 2026-01-30 160814.png', '7.5 gm of Diamond Ring (Gold)', 0, '', 'SBJ00118'),
(21, 1, 'Gold Biscuit', '100.00', '1573100.00', 5, 'Screenshot 2026-02-20 230334.png', '100 gms of 24 carat Gold Biscuit ', 0, '999', 'SBJ00119');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `gst_percent` decimal(5,2) NOT NULL DEFAULT 3.00,
  `delivery_charge` decimal(10,2) NOT NULL DEFAULT 200.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `gst_percent`, `delivery_charge`) VALUES
(1, '3.00', '200.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_payment` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `huid_code` (`huid_code`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_order_payment` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
