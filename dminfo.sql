-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Feb 06, 2022 at 01:54 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dminfo`
--

-- --------------------------------------------------------

--
-- Table structure for table `Comments`
--

CREATE TABLE `Comments` (
  `postID` varchar(255) DEFAULT NULL,
  `userID` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `poster` varchar(90) DEFAULT NULL,
  `post` varchar(20000) DEFAULT NULL,
  `date_string` varchar(65) DEFAULT NULL,
  `uu_id` varchar(45) DEFAULT NULL,
  `tags` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Posts`
--

CREATE TABLE `Posts` (
  `userID` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `poster` varchar(90) DEFAULT NULL,
  `post` varchar(20000) DEFAULT NULL,
  `date_string` varchar(65) DEFAULT NULL,
  `uu_id` varchar(45) DEFAULT NULL,
  `tags` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `poster` varchar(100) DEFAULT NULL,
  `password_hash` varchar(1000) DEFAULT NULL,
  `date_string` varchar(255) DEFAULT NULL,
  `uu_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
