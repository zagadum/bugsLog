-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 29, 2018 at 03:12 PM
-- Server version: 8.0.11
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `awery_bugtracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `bugs`
--

CREATE TABLE `bugs` (
  `id` int(11) NOT NULL,
  `bug_hash` char(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `las_seen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bug_name` varchar(150) NOT NULL,
  `bugs_cnt` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `last_host` varchar(100) DEFAULT NULL,
  `resolved` tinyint(1) NOT NULL DEFAULT '0',
  `resolved_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `error_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bugs_details`
--

CREATE TABLE `bugs_details` (
  `bug_id` int(11) NOT NULL,
  `host` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user` varchar(50) DEFAULT NULL,
  `cdn` varchar(250) DEFAULT NULL,
  `module` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `sw_ver` varchar(10) DEFAULT NULL,
  `opened_tab` varchar(200) DEFAULT NULL,
  `free_mem` varchar(20) DEFAULT NULL,
  `used_mem` varchar(20) DEFAULT NULL,
  `lang` char(2) DEFAULT NULL,
  `os` varchar(20) DEFAULT NULL,
  `screen` varchar(9) DEFAULT NULL,
  `fplayer` varchar(20) DEFAULT NULL,
  `local_time` varchar(32) DEFAULT NULL,
  `error_type` varchar(15) DEFAULT NULL,
  `error_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bugs`
--
ALTER TABLE `bugs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bug_hash` (`bug_hash`),
  ADD KEY `las_seen` (`las_seen`);

--
-- Indexes for table `bugs_details`
--
ALTER TABLE `bugs_details`
  ADD KEY `error_time` (`error_time`),
  ADD KEY `host` (`host`),
  ADD KEY `bug_id` (`bug_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bugs`
--
ALTER TABLE `bugs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
