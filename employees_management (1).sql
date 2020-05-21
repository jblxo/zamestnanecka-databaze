-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2020 at 02:53 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `employees_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(80) COLLATE utf8_czech_ci NOT NULL,
  `abbreviation` char(3) COLLATE utf8_czech_ci NOT NULL,
  `city` char(80) COLLATE utf8_czech_ci NOT NULL,
  `color` char(50) COLLATE utf8_czech_ci NOT NULL,
  `user` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `abbreviation`, `city`, `color`, `user`) VALUES
(8, 'Kanceláře', 'KNC', 'Praha', '#39c6b0', 3),
(9, 'Sklad', 'SKL', 'Kladno', '#158aea', 3),
(10, 'Výroba', 'VÝR', 'Brno', '#53ac88', 4);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `firstName` char(50) COLLATE utf8_czech_ci NOT NULL,
  `lastName` char(50) COLLATE utf8_czech_ci NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `user` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `firstName`, `lastName`, `createdAt`, `user`) VALUES
(42, 'David', 'Duong', '2020-04-21 13:54:19', 3),
(43, 'Josef', 'Katič', '2020-04-21 13:57:12', 3),
(44, 'Matěj', 'Lajtkep', '2020-04-21 13:57:47', 4),
(45, 'Jiří &quot;Zlatíčko&quot;', 'Zajíček', '2020-04-21 13:57:57', 4);

-- --------------------------------------------------------

--
-- Table structure for table `employees_departments`
--

CREATE TABLE `employees_departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee` int(10) UNSIGNED NOT NULL,
  `department` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `employees_departments`
--

INSERT INTO `employees_departments` (`id`, `employee`, `department`) VALUES
(113, 42, 8),
(114, 42, 9),
(115, 43, 9),
(116, 44, 10),
(167, 45, 10),
(169, 43, 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `firstName` char(50) COLLATE utf8_czech_ci NOT NULL,
  `lastName` char(50) COLLATE utf8_czech_ci NOT NULL,
  `password` char(255) COLLATE utf8_czech_ci NOT NULL,
  `email` char(80) COLLATE utf8_czech_ci NOT NULL,
  `companyName` char(80) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `password`, `email`, `companyName`) VALUES
(3, 'František', 'Vomáčka', '$2y$10$32z4HtcZm6ZhU5wpqVEq3u5Z0BJrDKJ3XDbFBnIwNDq/VlaioQ/9W', 'franta.vomacka@seznam.cz', 'Vomacky s.r.o.'),
(4, 'Prokop', 'Buben', '$2y$10$tH6W.bUDTSwyxTUjxClkS.Jm2FzN8gh4HtPRn6jhhbaDpgN5S0fc.', 'prokop.buben@seznam.cz', 'Bubny a.s.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_UserDepartment` (`user`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_EmployeeUser` (`user`);

--
-- Indexes for table `employees_departments`
--
ALTER TABLE `employees_departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_EmployeeDepartment` (`employee`),
  ADD KEY `FK_DepartmentEmployee` (`department`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `employees_departments`
--
ALTER TABLE `employees_departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `FK_UserDepartment` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `FK_EmployeeUser` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees_departments`
--
ALTER TABLE `employees_departments`
  ADD CONSTRAINT `FK_DepartmentEmployee` FOREIGN KEY (`department`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_EmployeeDepartment` FOREIGN KEY (`employee`) REFERENCES `employees` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
