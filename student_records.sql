-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2026 at 05:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_records`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_number` varchar(30) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `course` varchar(100) NOT NULL,
  `year_level` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `campus_branch` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_number`, `first_name`, `middle_name`, `last_name`, `email`, `course`, `year_level`, `created_at`, `campus_branch`) VALUES
(4, '2024-02556-MN-0', 'Jowelrich', NULL, 'Luzano', 'luzanorich@gmail.com', 'BSIT - Bachelor of Science in Information Technology', 3, '2026-10-20 14:55:00', 'Sta. Mesa'),
(5, '2024-02558-MN-0', 'Evelyn', 'Bello', 'Soliven', 'evelynb.soliven@gmail.com', 'BSIT - Bachelor of Science in Information Technology', 3, '2026-10-20 14:55:30', 'Sta. Mesa'),
(6, '2024-10227-MN-0', 'Syrus Nico', 'Ascutia', 'Versoza', 'syrusnicoversoza@gmail.com', 'BSECE - Bachelor of Science in Electronics Engineering', 3, '2026-10-20 14:57:23', 'Sta. Mesa'),
(7, '2024-03729-MN-0', 'Savannah Tricia', 'Lee', 'Masmela', 'savannahmasmela@iskolarngbayan.pup.edu.ph', 'BSIT - Bachelor of Science in Information Technology', 3, '2026-10-20 14:58:39', 'Sta. Mesa'),
(8, '2024-18229-MN-0', 'Jasmine', 'Baler', 'Salinas', 'jjasminesalinas@gmail.com', 'BSPSY - Bachelor of Science in Psychology', 3, '2026-10-20 15:00:04', 'Sta. Mesa'),
(9, '2024-01826-MN-0', 'Kate Margarette', NULL, 'Maglalang', 'katiemaglalang@yahoo.com', 'BSIT - Bachelor of Science in Information Technology', 4, '2026-10-20 15:01:12', 'Sta. Mesa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_number` (`student_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
