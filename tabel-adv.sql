-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 17, 2022 at 05:35 PM
-- Server version: 10.6.7-MariaDB-log
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tests`
--

-- --------------------------------------------------------

--
-- Table structure for table `hobi`
--

CREATE TABLE `hobi` (
  `hobi_id` int(11) NOT NULL,
  `hobi_name` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hobi`
--

INSERT INTO `hobi` (`hobi_id`, `hobi_name`, `user_id`) VALUES
(1, 'Masak', 1),
(2, 'Olahraga', 1),
(3, 'Joging', 1),
(4, 'Mancing', 2),
(5, 'Bersepeda', 2),
(6, 'Olahraga', 2),
(7, 'Joging', 3),
(8, 'Membaca', 3),
(9, 'Mabok', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tim`
--

CREATE TABLE `tim` (
  `tim_id` int(11) NOT NULL,
  `tim_name` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tim`
--

INSERT INTO `tim` (`tim_id`, `tim_name`, `user_id`) VALUES
(1, 'Jeni', 1),
(2, 'Michael', 1),
(3, 'Dodi', 2),
(4, 'Bambang', 3),
(5, 'Ivan', 3),
(6, 'Lala', 3);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_address`) VALUES
(1, 'Tomi', 'Jakarta'),
(2, 'Roni', 'Medan'),
(3, 'Imam', 'Aceh');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hobi`
--
ALTER TABLE `hobi`
  ADD PRIMARY KEY (`hobi_id`);

--
-- Indexes for table `tim`
--
ALTER TABLE `tim`
  ADD PRIMARY KEY (`tim_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hobi`
--
ALTER TABLE `hobi`
  MODIFY `hobi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tim`
--
ALTER TABLE `tim`
  MODIFY `tim_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
