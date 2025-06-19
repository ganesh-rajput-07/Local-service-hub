-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2025 at 03:54 AM
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
-- Database: `local_service_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`) VALUES
(1, 'Ganesh Rajput', 'grt7045@gmail.com', '1234567890'),
(2, 'Ayan Memon', 'gr826940@gmail.com', '1234567890');

-- --------------------------------------------------------

--
-- Table structure for table `admin_queries`
--

CREATE TABLE `admin_queries` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_role` enum('user','vendor') NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_queries`
--

INSERT INTO `admin_queries` (`id`, `sender_id`, `sender_role`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 3, 'vendor', 'payment issue', 'payment issue i have ', 'open', '2025-06-17 07:41:48');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `total_amount` float NOT NULL,
  `address` text DEFAULT NULL,
  `booking_status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `payment_status` enum('pending','paid') DEFAULT 'pending',
  `payment_mode` enum('cash','card','upi','paypal') DEFAULT 'cash',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `vendor_id`, `service_id`, `quantity`, `total_amount`, `address`, `booking_status`, `payment_status`, `payment_mode`, `created_at`) VALUES
(34, 6, 3, 14, 1, 110, 'Street: Amroli, Building No: 45, City: Chorasi, District: Surat, State: Gujarat, Country: India, Pincode: 394107', 'completed', 'paid', 'upi', '2025-06-17 07:48:52');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`) VALUES
(2, 'IT Developers'),
(3, 'Web Solutions'),
(4, 'Cloud Services'),
(5, 'clothing india'),
(6, 'Plumber');

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sender_role` enum('user','vendor','admin') NOT NULL DEFAULT 'user',
  `receiver_role` enum('user','vendor','admin') NOT NULL DEFAULT 'vendor',
  `query_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `sender_id`, `receiver_id`, `message`, `sent_at`, `sender_role`, `receiver_role`, `query_id`, `is_read`) VALUES
(1, 1, 1, 'okay i am stoped', '2025-06-15 02:22:46', 'vendor', 'user', NULL, 1),
(2, 1, 1, 'hello sir give us the 5 star ,thank you', '2025-06-15 08:17:45', 'vendor', 'user', NULL, 1),
(3, 1, 1, 'hello sir give us the 5 star ,thank you', '2025-06-15 08:17:45', 'vendor', 'user', NULL, 1),
(4, 1, 1, 'okay sir no problem', '2025-06-15 08:47:20', 'user', 'vendor', NULL, 0),
(5, 1, 1, 'hello sir give us the 5 star ,thank you', '2025-06-15 09:35:51', 'user', 'vendor', NULL, 0),
(6, 1, 1, 'okaay dude', '2025-06-15 09:36:28', 'user', 'vendor', NULL, 0),
(7, 1, 1, 'hello', '2025-06-15 10:00:58', 'user', 'vendor', NULL, 0),
(8, 1, 1, 'kz,fel', '2025-06-15 10:02:14', 'user', 'vendor', NULL, 0),
(9, 1, 1, 'haai dekhi le msg barabar se na', '2025-06-15 10:02:52', 'user', 'vendor', NULL, 0),
(10, 1, 1, 'dczbd', '2025-06-15 10:04:49', 'user', 'vendor', NULL, 0),
(11, 1, 1, 'hello bhai ', '2025-06-15 10:15:29', 'user', 'vendor', NULL, 0),
(12, 1, 1, 'hello kesa hai ', '2025-06-15 10:19:51', 'user', 'vendor', NULL, 0),
(13, 1, 1, 'hello kesa hai be ', '2025-06-15 10:26:54', 'user', 'vendor', NULL, 0),
(14, 1, 1, 'bas majae me ', '2025-06-15 10:27:24', 'vendor', 'user', NULL, 1),
(15, 1, 1, 'sir aap kese ho ', '2025-06-15 10:27:35', 'vendor', 'user', NULL, 1),
(16, 1, 1, 'bas maze me darling', '2025-06-15 10:28:15', 'user', 'vendor', NULL, 0),
(17, 1, 1, 'hello bhai', '2025-06-15 13:45:38', 'user', 'vendor', NULL, 0),
(18, 1, 1, 'hello bhai working hai na', '2025-06-15 17:25:09', 'vendor', 'user', NULL, 1),
(19, 1, 1, 'haa bhai working hai order aya ha', '2025-06-15 17:34:10', 'user', 'vendor', NULL, 0),
(20, 0, 1, 'no problem sir , let me check ', '2025-06-16 07:34:20', 'user', 'vendor', NULL, 0),
(21, 0, 1, 'okay let me check\r\n', '2025-06-16 07:36:08', 'user', 'vendor', NULL, 0),
(22, 1, 1, 'hello', '2025-06-16 08:17:15', 'admin', 'user', NULL, 1),
(23, 1, 1, 'hello sir can we talk', '2025-06-16 08:42:21', 'admin', 'user', 2, 1),
(24, 1, 0, 'dekjfjheorwvbubvrvurbvfvyf[vy[by9[[rp;iecgydoddmz.fvicBMVIEVJFOWD,MBCM Cq  kVJBFGIHD:jgDU;GELHDvI;vc;.FGIUDWDB SMNM,weeoioius<<  djkX X<lkhduouhbasb;db;FKJB', '2025-06-16 08:51:35', 'admin', '', 0, 0),
(25, 1, 1, 'sure bhai bolte hai ', '2025-06-16 08:55:50', 'user', 'admin', NULL, 1),
(26, 1, 1, 'kal', '2025-06-16 08:55:54', 'user', 'admin', NULL, 1),
(27, 1, 1, 'anm,zkjhqalkj', '2025-06-16 08:56:32', 'user', 'admin', 3, 1),
(28, 1, 1, 'payemnetokzln,z', '2025-06-16 08:57:38', 'user', 'admin', 4, 1),
(29, 1, 0, 'payemnetokzln,z', '2025-06-16 09:01:08', 'user', 'admin', 5, 0),
(30, 1, 0, 'samaj me anhi aaraha hai \r\n', '2025-06-16 09:01:31', 'user', 'admin', 6, 0),
(31, 1, 0, 'bolna ?', '2025-06-16 09:02:06', 'user', 'admin', 7, 0),
(32, 1, 1, 'hello', '2025-06-16 09:02:19', 'user', 'admin', NULL, 1),
(33, 1, 1, 'hello', '2025-06-16 09:02:29', 'user', 'admin', NULL, 1),
(34, 1, 1, 'nahi yaar nai ho raha hai ', '2025-06-16 09:09:09', 'admin', 'user', NULL, 1),
(35, 2, 1, 'HELLO BHAI ', '2025-06-16 17:26:31', 'vendor', 'admin', NULL, 0),
(36, 3, 0, 'payment issue i have ', '2025-06-17 07:41:48', 'vendor', 'admin', 1, 0),
(37, 6, 3, 'hello bhai,', '2025-06-17 07:49:51', 'user', 'vendor', NULL, 1),
(38, 6, 3, 'waiting for your services', '2025-06-17 07:50:05', 'user', 'vendor', NULL, 1),
(39, 3, 6, 'hello', '2025-06-17 07:50:37', 'vendor', 'user', NULL, 0),
(40, 3, 6, 'i will be took only 5mins to come', '2025-06-17 07:50:52', 'vendor', 'user', NULL, 0),
(41, 2, 3, 'okay sir no problem , i am here relax', '2025-06-17 07:53:30', 'admin', 'vendor', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('percent','flat') DEFAULT 'percent',
  `discount_value` float NOT NULL,
  `minimum_order` float DEFAULT 0,
  `expiry_date` date NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_type`, `discount_value`, `minimum_order`, `expiry_date`, `status`) VALUES
(1, 'IMMAESIVELABS2000', 'percent', 56, 5000, '2025-06-18', 'active'),
(3, '69', 'percent', 69, 5000, '2025-06-17', 'active'),
(4, '45', 'percent', 45, 150, '2025-06-18', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `vendor_id`, `title`, `message`, `is_read`, `created_at`) VALUES
(2, 1, NULL, 'Booking Accepted', 'Your booking has been accepted by the vendor.', 0, '2025-06-14 14:49:34'),
(3, 1, NULL, 'New Message from Vendor', 'You have a new message from your vendor.', 0, '2025-06-15 08:17:45'),
(4, 1, NULL, 'New Message from Vendor', 'You have a new message from your vendor.', 0, '2025-06-15 08:17:45');

-- --------------------------------------------------------

--
-- Table structure for table `otp_verifications`
--

CREATE TABLE `otp_verifications` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `otp_code` varchar(10) NOT NULL,
  `expiry_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_verifications`
--

INSERT INTO `otp_verifications` (`id`, `email`, `otp_code`, `expiry_time`, `is_verified`) VALUES
(1, 'grt7045@gmail.com', '705329', '2025-06-16 14:55:13', 1),
(2, 'grt7045@gmail.com', '705329', '2025-06-16 14:55:13', 1),
(3, 'gr826940@gmail.com', '115560', '2025-06-17 07:52:47', 1),
(4, 'ganeshrajput7045@gmail.com', '225036', '2025-06-17 06:21:48', 1),
(5, 'ganeshrajput7045@gmail.com', '225036', '2025-06-17 06:21:48', 1),
(6, 'grt7045@gmail.com', '705329', '2025-06-16 14:55:13', 1),
(7, 'grt7045@gmail.com', '705329', '2025-06-16 14:55:13', 1),
(8, 'grt7045@gmail.com', '705329', '2025-06-16 14:55:13', 1),
(9, 'grt7045@gmail.com', '705329', '2025-06-16 14:55:13', 1),
(10, 'ganeshrajput7045@gmail.com', '225036', '2025-06-17 06:21:48', 1),
(11, 'ganeshrajput7045@gmail.com', '225036', '2025-06-17 06:21:48', 1),
(12, 'ganeshrajput7045@gmail.com', '225036', '2025-06-17 06:21:48', 1),
(13, 'ganeshrajput7045@gmail.com', '225036', '2025-06-17 06:21:48', 1),
(14, 'vaibhavrajput006453@gmail.com', '924415', '2025-06-17 07:18:04', 1),
(15, 'ganeshrajput7045@gmail.com', '225036', '2025-06-17 06:21:48', 1),
(16, 'vaibhavrajput006453@gmail.com', '924415', '2025-06-17 07:18:04', 1),
(17, 'raganesh12345678@gmail.com', '552079', '2025-06-17 07:36:03', 1),
(18, 'gr826940@gmail.com', '115560', '2025-06-17 07:52:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `amount` float NOT NULL,
  `payment_mode` enum('cash','card','upi','paypal') DEFAULT 'cash',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `amount`, `payment_mode`, `payment_date`) VALUES
(30, 34, 110, 'upi', '2025-06-17 07:48:52');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` float NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `average_rating` float DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pincode` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `vendor_id`, `shop_id`, `category_id`, `title`, `description`, `image`, `price`, `keywords`, `average_rating`, `status`, `created_at`, `pincode`, `latitude`, `longitude`) VALUES
(12, 2, 3, 5, 'clothese osj.,gaa', 's,zdmsewrwWAPOSA;JL.,CMVFWADSXZL;Ṁ,V Æ', '[\"service_1750094752_8459.png\",\"service_1750094752_4092.jpg\",\"service_1750094752_5159.png\",\"service_1750094752_8344.jpg\"]', 500, 'CLLOTESDSWD', 0, 'active', '2025-06-16 17:25:27', NULL, NULL, NULL),
(14, 3, NULL, 6, 'Plumbing service ', 'i have the 5+ years exp in plumbing sdzm,dbraea', '[\"1750146024_7049_crazy-desktop-9p6ilb33auwou5od.jpg\",\"1750146024_5427_wp2660083-crazy-wallpapers-hd.jpg\",\"service_1750146073_1824.png\",\"service_1750146073_9112.png\"]', 200, 'plumbing, sevices, lsh ', 0, 'active', '2025-06-17 07:40:24', '394107', 21.2429454, 72.8494911);

-- --------------------------------------------------------

--
-- Table structure for table `service_ratings`
--

CREATE TABLE `service_ratings` (
  `id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` float NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `vendor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `title`, `vendor_id`) VALUES
(3, 'Yashu Store', 2),
(4, 'RG Plumbing', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pincode` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `address`, `profile_image`, `created_at`, `pincode`, `latitude`, `longitude`) VALUES
(1, 'Ganesh Rajput', 'grt7045@gmail.com', '09023584367', '$2y$10$raAJH/MXYnOeBkqLhljr7uQRzoVclmokyS1EDBx49Hn3MgNazQDB6', NULL, NULL, '2025-06-14 10:47:35', '394107', 21.2429454, 72.8494911),
(6, 'Paresh patil', 'gr826940@gmail.com', '9988776655', '$2y$10$PFQoBaWCyHDg8wIh7dzKze1RmZ4NLw7L.H6X9LhVck0sM7mUxQ7Dq', NULL, NULL, '2025-06-17 07:43:45', '394107', 21.2429454, 72.8494911);

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `vendor_avg_rating` float DEFAULT 0,
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `skills` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `x` varchar(255) DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `email`, `phone`, `password`, `address`, `profile_image`, `vendor_avg_rating`, `is_approved`, `created_at`, `skills`, `description`, `experience`, `website`, `facebook`, `instagram`, `x`, `github`, `username`, `pincode`, `latitude`, `longitude`) VALUES
(2, 'Yashwant Patil', 'ganeshrajput7045@gmail.com', '9687102078', '$2y$10$GnJfJ7xO08/wITl2LtNcm.MIu4.miEXZe1N0uu3yBfLOttkAMRkPW', 'Amroli', 'myimg.png', 0, 1, '2025-06-16 06:21:53', 'sab kuch ata ahai', '', '20', NULL, NULL, NULL, NULL, NULL, 'yashuyashu', NULL, NULL, NULL),
(3, 'Ganesh Rajput', 'raganesh12345678@gmail.com', '09023584367', '$2y$10$VoZfsE3XGld..gHK7OYE9.NnZoXIbIV4q7sttpMSSM7f5Uaz3x93O', 'Amroli', 'myimg.png', 0, 1, '2025-06-17 07:36:03', 'Plumbing', 'i have the 5+ years of exp in plumbing', '5', 'https://ganesh-rajput-07.github.io/Portfolio/education.html', '', 'https://www.instagram.com/ganeshrajput7045', '', 'https://github.com/ganesh-rajput-07', '', '394107', 21.2429454, 72.8494911);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_followers`
--

CREATE TABLE `vendor_followers` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_followers`
--

INSERT INTO `vendor_followers` (`id`, `vendor_id`, `user_id`, `created_at`) VALUES
(5, 3, 6, '2025-06-17 07:46:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_queries`
--
ALTER TABLE `admin_queries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `shop_id` (`shop_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `service_ratings`
--
ALTER TABLE `service_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vendor_followers`
--
ALTER TABLE `vendor_followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_queries`
--
ALTER TABLE `admin_queries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `service_ratings`
--
ALTER TABLE `service_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vendor_followers`
--
ALTER TABLE `vendor_followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `services_ibfk_2` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `services_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_ratings`
--
ALTER TABLE `service_ratings`
  ADD CONSTRAINT `service_ratings_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shops`
--
ALTER TABLE `shops`
  ADD CONSTRAINT `shops_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_followers`
--
ALTER TABLE `vendor_followers`
  ADD CONSTRAINT `vendor_followers_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_followers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
