-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2026 at 01:59 PM
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
-- Database: `notenest_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `full_name`, `email`, `password`) VALUES
(1, 'NoteNest Administrator', 'admin@notenest.com', '$2y$10$A.WyIcgMwRJN3Lv6u1bTYuPScfh8B6rK/FHLpf4/e/yIf8Nh1OLPq');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Notes'),
(2, 'Reviewer'),
(3, 'Study Material'),
(4, 'Presentation');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `name`, `description`) VALUES
(1, 'Lecture Notes', 'Class notes and lecture materials'),
(2, 'Reviewers', 'Exam reviewers and study guides'),
(3, 'Practice Materials', 'Exercises and practice activities'),
(4, 'Presentations', 'Academic presentations');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `download`
--

CREATE TABLE `download` (
  `download_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `downloaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `favorite_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `file_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_url` varchar(255) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `note_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `subject` varchar(100) NOT NULL,
  `course` varchar(100) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`note_id`, `user_id`, `title`, `description`, `subject`, `course`, `file_name`, `file_path`, `created_at`, `status`) VALUES
(1, 2, 'MATH165', 'MATH165 REVIEWER', 'MATH165', 'BSIT', 'MATH165 CO1 Lesson 1.1 Equations in Single Variable.pdf', 'uploads/notenest_6aba9d37b90929.18816716.pdf', '2026-09-28 17:00:39', 'rejected'),
(2, 1, 'MATH165 1.2', 'TEST1', 'MATH165', 'BSIT', 'MATH165 CO1 Lesson 1.2 Systems of Linear Equations 2by2.pdf', 'uploads/notenest_6abab014ed4b1.pdf', '2026-09-28 18:21:08', 'approved'),
(3, 1, 'math165 1.3', 'test2', 'MATH165', 'BSIT', 'MATH165 CO1 Lesson 1.3 Systems of Linear Equations 3BY3.pdf', 'uploads/notenest_6abab2cd7dc66.pdf', '2026-09-28 18:32:45', 'approved'),
(4, 1, 'math165', 'test3', 'MATH165', 'BSIT', 'MATH165 CO1 Lesson 1.5 Non-Linear Inequalities.pdf', 'uploads/notenest_6abad01ec03c4.pdf', '2026-09-28 20:37:50', 'approved'),
(5, 1, 'Math 1.5', 'reviewer', 'MATH165', 'BSIT', 'MATH165 CO1 Lesson 1.5 Non-Linear Inequalities.pdf', 'uploads/notenest_6abb0aaf7b109.pdf', '2026-09-29 00:47:43', 'pending'),
(6, 6, 'Math165 1.1', 'Reviewer', 'MATH165', 'BSIT', 'CCNA1 Review - IP and SM.pdf', 'uploads/notenest_6abb0ef005895.pdf', '2026-09-29 01:05:52', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `note_categories`
--

CREATE TABLE `note_categories` (
  `note_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `note_categories`
--

INSERT INTO `note_categories` (`note_id`, `category_id`) VALUES
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `rating_value` int(11) NOT NULL,
  `rated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resource`
--

CREATE TABLE `resource` (
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `resource_type` varchar(50) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `subject_code` varchar(20) DEFAULT NULL,
  `uploader_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `course` varchar(100) DEFAULT NULL,
  `year_level` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `full_name`, `email`, `password`, `course`, `year_level`, `created_at`) VALUES
(1, 'Julienne Ampunan', 'test@gmail.com', '$2y$10$Vbek2/WEBQLKwWs2qjJ9cOS6rIPQmrzAWEoR.FUq.86P8iGNCLewG', 'BSIT', '2', '2026-09-28 22:30:57'),
(2, 'Karl Ofrecio', 'KarlOfrecio@gmail.com', '$2y$10$VoKbumWn5xy7qF01uWwQp.BgwNZ.xHiPf/mobN.QbH4PMUYJNQiAq', 'BSCS', '1st Year', '2026-09-28 23:05:20'),
(3, 'Cecile Samaniego', 'SamSam@gmail.com', '$2y$10$RCsZB11qj758sDUCxSndcupjwXeHb8bHLz8.0.mU.qgXlZBIFLyfG', 'BSIT', '4th Year', '2026-09-28 23:07:32');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_code`, `name`, `description`) VALUES
('CSIT101', 'Introduction to Computing', 'Computing fundamentals'),
('GED101', 'Understanding the Self', 'General education materials'),
('GED108', 'Art Appreciation', 'Art and visual culture materials'),
('MATH165', 'Precalculus', 'Mathematics course materials'),
('PCC150', 'Professional Communication', 'Communication and workplace materials');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `course` varchar(100) DEFAULT NULL,
  `year_level` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `username`, `email`, `password`, `course`, `year_level`, `created_at`) VALUES
(1, 'Julienne', 'TEST', 'julienneampunan01@gmail.com', '$2y$10$72IXGYDz9/bdl1dKl0WX0.AfaUQup84fB0AzeAzsATIzQ9xXf.Lum', 'BSIT', '2nd Year', '2026-09-28 16:10:51'),
(2, 'TEST', 'TEST USER', 'test123@gmail.com', '$2y$10$AaaDKy5nOgi/j3CZ7Xt/M.3wFP8qwVjlj1UsEUMJWP.hUi/Cx.oJa', 'BSIT', '1st Year', '2026-09-28 16:57:50'),
(3, 'Julienne Ruth Ampunan', 'Julienne', 'julesojanola@gmail.com', '$2y$10$sC34E53sx.OTYNlZ08AV/.mH4d9p6UMrRvV/hB1mfDFfthLZkQ6IK', 'BSIT', '2nd Year', '2026-09-28 17:28:04'),
(4, 'yen', 'yen', 'yen@gmail.com', '$2y$10$2z/jn5XLsMgVqNC8ZCP8..d3LRVL70cBkVdBViK28sPAfY88Ux0yi', 'BSIT', '2nd Year', '2026-09-28 18:33:18'),
(5, 'Student 1', 'student1', 'student1@gmail.com', '$2y$10$fuM0/ubPB0DwjbnGOm8mOOvLCSokPBRp6pi8x4sjDzq6hXPVTofEi', 'BSCS', '1st Year', '2026-09-29 00:14:38'),
(6, 'Karl Antonio', 'karlantonio', 'KarlAntonio@gmail.com', '$2y$10$z0I6XBmW/HsTT0F2xJsu1eqhk1SW1Dm/saPzLRF4kZdtL3bWzNIB6', 'BSIT', '2nd Year', '2026-09-29 01:04:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `resource_id` (`resource_id`);

--
-- Indexes for table `download`
--
ALTER TABLE `download`
  ADD PRIMARY KEY (`download_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `resource_id` (`resource_id`);

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`favorite_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `resource_id` (`resource_id`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `resource_id` (`resource_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`note_id`);

--
-- Indexes for table `note_categories`
--
ALTER TABLE `note_categories`
  ADD PRIMARY KEY (`note_id`,`category_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `resource_id` (`resource_id`);

--
-- Indexes for table `resource`
--
ALTER TABLE `resource`
  ADD PRIMARY KEY (`resource_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `subject_code` (`subject_code`),
  ADD KEY `uploader_id` (`uploader_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `download`
--
ALTER TABLE `download`
  MODIFY `download_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorite`
--
ALTER TABLE `favorite`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resource`
--
ALTER TABLE `resource`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`resource_id`) ON DELETE CASCADE;

--
-- Constraints for table `download`
--
ALTER TABLE `download`
  ADD CONSTRAINT `download_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `download_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`resource_id`) ON DELETE CASCADE;

--
-- Constraints for table `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `favorite_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`resource_id`) ON DELETE CASCADE;

--
-- Constraints for table `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `file_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`resource_id`) ON DELETE CASCADE;

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`resource_id`) ON DELETE CASCADE;

--
-- Constraints for table `resource`
--
ALTER TABLE `resource`
  ADD CONSTRAINT `resource_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`),
  ADD CONSTRAINT `resource_ibfk_2` FOREIGN KEY (`subject_code`) REFERENCES `subject` (`subject_code`),
  ADD CONSTRAINT `resource_ibfk_3` FOREIGN KEY (`uploader_id`) REFERENCES `student` (`student_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `resource_ibfk_4` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
