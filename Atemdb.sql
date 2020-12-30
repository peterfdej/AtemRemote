-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 30, 2020 at 10:58 AM
-- Server version: 10.3.27-MariaDB-0+deb10u1
-- PHP Version: 7.3.19-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Atemdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `host`
--

CREATE TABLE `host` (
  `ID` int(11) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `inputname1` varchar(25) NOT NULL,
  `inputname2` varchar(25) NOT NULL,
  `inputname3` varchar(25) NOT NULL,
  `inputname4` varchar(25) NOT NULL,
  `inputname5` varchar(25) NOT NULL,
  `inputname6` varchar(25) NOT NULL,
  `inputname7` varchar(25) NOT NULL,
  `inputname8` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `host`
--

INSERT INTO `host` (`ID`, `hostname`, `inputname1`, `inputname2`, `inputname3`, `inputname4`, `inputname5`, `inputname6`, `inputname7`, `inputname8`) VALUES
(1, '192.168.0.100:1080', 'Input 1', 'Input 2', 'Input 3', 'Input 4', 'Input 5', 'Input 6', 'Input 7', 'Input 8');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `logtext` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------


--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `salt` char(23) NOT NULL,
  `password` char(64) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(39) NOT NULL,
  `admin` enum('0','1') NOT NULL DEFAULT '0',
  `banned` enum('0','1') NOT NULL DEFAULT '0',
  `sources` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `salt`, `password`, `time`, `ip`, `admin`, `banned`, `sources`) VALUES
(1, 'admin@atem.org', '12345678901234', '313cac85a4fac5ba910b71bfe29a8bd54f8df8fa1b5385b7c21af91546b7a7ef', '2020-11-30 23:00:00', '', '1', '0', '1,1,1,1,1,1,1,1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `host`
--
ALTER TABLE `host`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--


--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
