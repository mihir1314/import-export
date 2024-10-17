-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 17, 2024 at 09:22 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `import-export`
--

-- --------------------------------------------------------

--
-- Table structure for table `random_table`
--

CREATE TABLE `random_table` (
  `id` int(11) NOT NULL,
  `col1` varchar(50) DEFAULT NULL,
  `col2` int(11) DEFAULT NULL,
  `col3` decimal(10,2) DEFAULT NULL,
  `col4` date DEFAULT NULL,
  `col5` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `random_table`
--

INSERT INTO `random_table` (`id`, `col1`, `col2`, `col3`, `col4`, `col5`) VALUES
(1, 'RandomText1', 86, 81.95, '2024-07-20', 0),
(3, 'RandomText3', 65, 22.95, '2024-07-20', 0),
(23, 'RandomText23', 86, 81.95, '2024-07-20', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `random_table`
--
ALTER TABLE `random_table`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `random_table`
--
ALTER TABLE `random_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
