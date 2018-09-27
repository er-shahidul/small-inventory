-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 27, 2018 at 09:38 AM
-- Server version: 5.7.23-0ubuntu0.16.04.1
-- PHP Version: 7.1.14-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL,
  `type_id` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `event_time` datetime NOT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `profile_info` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `object_class` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `object_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `object_id` bigint(20) DEFAULT NULL,
  `change_meta` longtext COLLATE utf8_unicode_ci,
  `ip_address` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`id`, `type_id`, `type_name`, `description`, `event_time`, `user_name`, `profile_info`, `object_class`, `object_type`, `object_id`, `change_meta`, `ip_address`) VALUES
(1, 'security.interactive_login', 'security.interactive_login', 'security.interactive_login', '2018-09-26 06:21:41', 'admin', NULL, NULL, NULL, NULL, NULL, '127.0.0.1'),
(2, 'security.interactive_login', 'security.interactive_login', 'security.interactive_login', '2018-09-27 03:52:01', 'admin', NULL, NULL, NULL, NULL, NULL, '127.0.0.1');

-- --------------------------------------------------------

--
-- Table structure for table `inv_histories`
--

CREATE TABLE `inv_histories` (
  `ID` int(11) NOT NULL,
  `INSTITUTIONS` int(11) DEFAULT NULL,
  `PRODUCTS` int(11) DEFAULT NULL,
  `QUANTITY` int(11) NOT NULL,
  `TYPE` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `WORK_ORDER` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CHALLAN` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `IN_OUT` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `NOTE` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `inv_histories`
--

INSERT INTO `inv_histories` (`ID`, `INSTITUTIONS`, `PRODUCTS`, `QUANTITY`, `TYPE`, `WORK_ORDER`, `CHALLAN`, `IN_OUT`, `NOTE`) VALUES
(1, 1, 1, 11, 'PLASTIC', '564', NULL, 'OUT', NULL),
(2, 1, 7, 11, 'PIN', '564', NULL, 'OUT', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inv_institutions`
--

CREATE TABLE `inv_institutions` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `SHORT_NAME` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `WEB_URL` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `NOTE` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `LOGO` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `inv_institutions`
--

INSERT INTO `inv_institutions` (`ID`, `NAME`, `SHORT_NAME`, `WEB_URL`, `NOTE`, `LOGO`) VALUES
(1, 'Agrani Bank Ltd.', 'AGBL', 'http://www.agranibank.org', NULL, NULL),
(2, 'Ific Bank Ltd', 'IFIC', 'http://www.ificbank.com.bd', NULL, NULL),
(3, 'Basic Bank Limited', 'BBL', 'http://www.basicbanklimited.com', NULL, NULL),
(4, 'Mercantile Bank Ltd', 'MBL', 'http://www.mblbd.com', NULL, NULL),
(5, 'Dutch-Bangla Bank Ltd', 'DBBL', 'https://www.dutchbanglabank.com', NULL, NULL),
(6, 'Janata Bank Ltd', 'JANA', 'http://www.janatabank-bd.com', NULL, NULL),
(7, 'Sonali Bank Limited', 'SONA', 'http://www.sonalibank.com.bd', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inv_inventories`
--

CREATE TABLE `inv_inventories` (
  `ID` int(11) NOT NULL,
  `INSTITUTIONS` int(11) DEFAULT NULL,
  `PRODUCTS` int(11) DEFAULT NULL,
  `TYPE` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `QUANTITY` int(11) DEFAULT NULL,
  `ON_HAND` int(11) DEFAULT NULL,
  `ON_LOCK` int(11) DEFAULT NULL,
  `NOTE` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `inv_inventories`
--

INSERT INTO `inv_inventories` (`ID`, `INSTITUTIONS`, `PRODUCTS`, `TYPE`, `QUANTITY`, `ON_HAND`, `ON_LOCK`, `NOTE`) VALUES
(1, 1, 1, 'PLASTIC', 52, 52, 0, NULL),
(2, 1, 7, 'PIN', 52, 52, 0, NULL),
(3, 1, 2, 'PLASTIC', 0, 0, 0, NULL),
(4, 1, 3, 'PLASTIC', 0, 0, 0, NULL),
(5, 1, 4, 'PLASTIC', 0, 0, 0, NULL),
(6, 1, 5, 'PLASTIC', 0, 0, 0, NULL),
(7, 1, 6, 'PLASTIC', 0, 0, 0, NULL),
(8, 2, 1, 'PLASTIC', 0, 0, 0, NULL),
(9, 2, 2, 'PLASTIC', 0, 0, 0, NULL),
(10, 2, 3, 'PLASTIC', 0, 0, 0, NULL),
(11, 2, 4, 'PLASTIC', 0, 0, 0, NULL),
(12, 2, 5, 'PLASTIC', 0, 0, 0, NULL),
(13, 2, 6, 'PLASTIC', 0, 0, 0, NULL),
(14, 2, 7, 'PIN', 0, 0, 0, NULL),
(15, 3, 1, 'PLASTIC', 0, 0, 0, NULL),
(16, 3, 2, 'PLASTIC', 0, 0, 0, NULL),
(17, 3, 3, 'PLASTIC', 0, 0, 0, NULL),
(18, 3, 4, 'PLASTIC', 0, 0, 0, NULL),
(19, 3, 5, 'PLASTIC', 0, 0, 0, NULL),
(20, 3, 6, 'PLASTIC', 0, 0, 0, NULL),
(21, 3, 7, 'PIN', 0, 0, 0, NULL),
(22, 4, 1, 'PLASTIC', 0, 0, 0, NULL),
(23, 4, 2, 'PLASTIC', 0, 0, 0, NULL),
(24, 4, 3, 'PLASTIC', 0, 0, 0, NULL),
(25, 4, 4, 'PLASTIC', 0, 0, 0, NULL),
(26, 4, 5, 'PLASTIC', 0, 0, 0, NULL),
(27, 4, 6, 'PLASTIC', 0, 0, 0, NULL),
(28, 4, 7, 'PIN', 0, 0, 0, NULL),
(29, 5, 1, 'PLASTIC', 0, 0, 0, NULL),
(30, 5, 2, 'PLASTIC', 0, 0, 0, NULL),
(31, 5, 3, 'PLASTIC', 0, 0, 0, NULL),
(32, 5, 4, 'PLASTIC', 0, 0, 0, NULL),
(33, 5, 5, 'PLASTIC', 0, 0, 0, NULL),
(34, 5, 6, 'PLASTIC', 0, 0, 0, NULL),
(35, 5, 7, 'PIN', 0, 0, 0, NULL),
(36, 6, 1, 'PLASTIC', 0, 0, 0, NULL),
(37, 6, 2, 'PLASTIC', 0, 0, 0, NULL),
(38, 6, 3, 'PLASTIC', 0, 0, 0, NULL),
(39, 6, 4, 'PLASTIC', 0, 0, 0, NULL),
(40, 6, 5, 'PLASTIC', 0, 0, 0, NULL),
(41, 6, 6, 'PLASTIC', 0, 0, 0, NULL),
(42, 6, 7, 'PIN', 0, 0, 0, NULL),
(43, 7, 1, 'PLASTIC', 0, 0, 0, NULL),
(44, 7, 2, 'PLASTIC', 0, 0, 0, NULL),
(45, 7, 3, 'PLASTIC', 0, 0, 0, NULL),
(46, 7, 4, 'PLASTIC', 0, 0, 0, NULL),
(47, 7, 5, 'PLASTIC', 0, 0, 0, NULL),
(48, 7, 6, 'PLASTIC', 0, 0, 0, NULL),
(49, 7, 7, 'PIN', 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inv_products`
--

CREATE TABLE `inv_products` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CATEGORY` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `TYPE` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `NOTE` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `PICTURE` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `inv_products`
--

INSERT INTO `inv_products` (`ID`, `NAME`, `CATEGORY`, `TYPE`, `NOTE`, `PICTURE`) VALUES
(1, 'Gold', 'Visa', 'PLASTIC', NULL, NULL),
(2, 'Silver', 'Visa', 'PLASTIC', NULL, NULL),
(3, 'Platinum', 'Visa', 'PLASTIC', NULL, NULL),
(4, 'Gold', 'Master', 'PLASTIC', NULL, NULL),
(5, 'Silver', 'Master', 'PLASTIC', NULL, NULL),
(6, 'Platinum', 'Master', 'PLASTIC', NULL, NULL),
(7, 'PIN', 'PIN', 'PIN', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inv_work_orders`
--

CREATE TABLE `inv_work_orders` (
  `ID` int(11) NOT NULL,
  `WORK_ORDER_NO` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `INSTITUTIONS` int(11) DEFAULT NULL,
  `PRODUCTS` int(11) DEFAULT NULL,
  `BATCH_NO` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `QUANTITY` int(11) NOT NULL,
  `STATUS` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `NOTE` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `inv_work_orders`
--

INSERT INTO `inv_work_orders` (`ID`, `WORK_ORDER_NO`, `INSTITUTIONS`, `PRODUCTS`, `BATCH_NO`, `QUANTITY`, `STATUS`, `NOTE`) VALUES
(1, '254', 1, 1, '999888777', 10, 'PRINT', 'test'),
(2, '658', 1, 1, '99008866', 15, 'PRINT', NULL),
(3, '889', 1, 1, '77882233', 12, 'PRINT', NULL),
(4, '564', 1, 1, '446724', 11, 'PRINT', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `name`, `roles`, `description`, `created_by`, `updated_by`) VALUES
(1, 'admin', 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}', 'admin', NULL, NULL),
(2, 'user', 'a:1:{i:0;s:9:\"ROLE_USER\";}', 'user', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_join_users_groups`
--

CREATE TABLE `user_join_users_groups` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_join_users_groups`
--

INSERT INTO `user_join_users_groups` (`user_id`, `group_id`) VALUES
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name_en` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cellphone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `blood_group` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `current_address` longtext COLLATE utf8_unicode_ci,
  `permanent_address` longtext COLLATE utf8_unicode_ci,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `full_name_en`, `cellphone`, `gender`, `designation`, `dob`, `blood_group`, `current_address`, `permanent_address`, `photo`) VALUES
(1, 1, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'Shahidul Hasan', '019', 'Male', 'nai', '1991-02-01', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_users`
--

CREATE TABLE `user_users` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_users`
--

INSERT INTO `user_users` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `created_by`, `updated_by`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', 'admin@admin.com', 1, NULL, '$2y$13$XpBI2ur2UrUlm2pahZFZqODLQGCFUFerhpZidl65qPWoUChVvFYEe', '2018-09-27 03:52:01', NULL, NULL, 'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}', NULL, NULL),
(2, 'shanto', 'shanto', 'shanto@gmail.com', 'shanto@gmail.com', 1, NULL, '$2y$13$vtzWp6G4G1hX6ozthCcCH.5toj2CtI22n6D2wg7R6gFVKHcflxA1q', NULL, NULL, NULL, 'a:0:{}', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inv_histories`
--
ALTER TABLE `inv_histories`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDX_3001B65A5E660F74` (`INSTITUTIONS`),
  ADD KEY `IDX_3001B65A7589C616` (`PRODUCTS`);

--
-- Indexes for table `inv_institutions`
--
ALTER TABLE `inv_institutions`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `inv_inventories`
--
ALTER TABLE `inv_inventories`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDX_8CCDA375E660F74` (`INSTITUTIONS`),
  ADD KEY `IDX_8CCDA377589C616` (`PRODUCTS`);

--
-- Indexes for table `inv_products`
--
ALTER TABLE `inv_products`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `inv_work_orders`
--
ALTER TABLE `inv_work_orders`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDX_D57667C65E660F74` (`INSTITUTIONS`),
  ADD KEY `IDX_D57667C67589C616` (`PRODUCTS`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_953F224D5E237E06` (`name`),
  ADD KEY `IDX_953F224DDE12AB56` (`created_by`),
  ADD KEY `IDX_953F224D16FE72E1` (`updated_by`);

--
-- Indexes for table `user_join_users_groups`
--
ALTER TABLE `user_join_users_groups`
  ADD PRIMARY KEY (`user_id`,`group_id`),
  ADD KEY `IDX_1E3D3392A76ED395` (`user_id`),
  ADD KEY `IDX_1E3D3392FE54D947` (`group_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_6BBD6130A76ED395` (`user_id`);

--
-- Indexes for table `user_users`
--
ALTER TABLE `user_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F6415EB192FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_F6415EB1A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_F6415EB1C05FB297` (`confirmation_token`),
  ADD KEY `IDX_F6415EB1DE12AB56` (`created_by`),
  ADD KEY `IDX_F6415EB116FE72E1` (`updated_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `inv_histories`
--
ALTER TABLE `inv_histories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `inv_institutions`
--
ALTER TABLE `inv_institutions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `inv_inventories`
--
ALTER TABLE `inv_inventories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `inv_products`
--
ALTER TABLE `inv_products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `inv_work_orders`
--
ALTER TABLE `inv_work_orders`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user_users`
--
ALTER TABLE `user_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `inv_histories`
--
ALTER TABLE `inv_histories`
  ADD CONSTRAINT `FK_3001B65A5E660F74` FOREIGN KEY (`INSTITUTIONS`) REFERENCES `inv_institutions` (`ID`),
  ADD CONSTRAINT `FK_3001B65A7589C616` FOREIGN KEY (`PRODUCTS`) REFERENCES `inv_products` (`ID`);

--
-- Constraints for table `inv_inventories`
--
ALTER TABLE `inv_inventories`
  ADD CONSTRAINT `FK_8CCDA375E660F74` FOREIGN KEY (`INSTITUTIONS`) REFERENCES `inv_institutions` (`ID`),
  ADD CONSTRAINT `FK_8CCDA377589C616` FOREIGN KEY (`PRODUCTS`) REFERENCES `inv_products` (`ID`);

--
-- Constraints for table `inv_work_orders`
--
ALTER TABLE `inv_work_orders`
  ADD CONSTRAINT `FK_D57667C65E660F74` FOREIGN KEY (`INSTITUTIONS`) REFERENCES `inv_institutions` (`ID`),
  ADD CONSTRAINT `FK_D57667C67589C616` FOREIGN KEY (`PRODUCTS`) REFERENCES `inv_products` (`ID`);

--
-- Constraints for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD CONSTRAINT `FK_953F224D16FE72E1` FOREIGN KEY (`updated_by`) REFERENCES `user_users` (`id`),
  ADD CONSTRAINT `FK_953F224DDE12AB56` FOREIGN KEY (`created_by`) REFERENCES `user_users` (`id`);

--
-- Constraints for table `user_join_users_groups`
--
ALTER TABLE `user_join_users_groups`
  ADD CONSTRAINT `FK_1E3D3392A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user_users` (`id`),
  ADD CONSTRAINT `FK_1E3D3392FE54D947` FOREIGN KEY (`group_id`) REFERENCES `user_groups` (`id`);

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `FK_6BBD6130A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_users`
--
ALTER TABLE `user_users`
  ADD CONSTRAINT `FK_F6415EB116FE72E1` FOREIGN KEY (`updated_by`) REFERENCES `user_users` (`id`),
  ADD CONSTRAINT `FK_F6415EB1DE12AB56` FOREIGN KEY (`created_by`) REFERENCES `user_users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
