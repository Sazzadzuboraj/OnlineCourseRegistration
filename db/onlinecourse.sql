-- Online Course Registration - Modern Version
-- Database: onlinecourse
-- Compatible with MySQL 5.7+ / MariaDB 10.3+

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `onlinecourse` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `onlinecourse`;

-- --------------------------------------------------------
-- Table: admin
-- --------------------------------------------------------
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullName` varchar(150) DEFAULT 'Administrator',
  `email` varchar(150) DEFAULT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin: username = admin | password = Admin@123
INSERT INTO `admin` (`id`, `username`, `password`, `fullName`, `email`) VALUES
(1, 'admin', '$2y$10$CD32lZ4cCDPJIkPGreM.9OJihR57oJUdMkLihctFGIJec.i/kcuH.', 'System Administrator', 'admin@course.edu');

-- --------------------------------------------------------
-- Table: department
-- --------------------------------------------------------
CREATE TABLE `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department` varchar(150) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `department` (`department`, `code`) VALUES
('Computer Science & Engineering', 'CSE'),
('Electrical & Electronic Engineering', 'EEE'),
('Business Administration', 'BBA'),
('English Language & Literature', 'ELL'),
('Mathematics', 'MATH');

-- --------------------------------------------------------
-- Table: level
-- --------------------------------------------------------
CREATE TABLE `level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(50) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `level` (`level`) VALUES
('Level 1'),
('Level 2'),
('Level 3'),
('Level 4');

-- --------------------------------------------------------
-- Table: semester
-- --------------------------------------------------------
CREATE TABLE `semester` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `semester` varchar(50) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `semester` (`semester`) VALUES
('Spring 2025'),
('Summer 2025'),
('Fall 2025'),
('Spring 2026');

-- --------------------------------------------------------
-- Table: session
-- --------------------------------------------------------
CREATE TABLE `session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` varchar(50) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `session` (`session`) VALUES
('2024-2025'),
('2025-2026'),
('2026-2027');

-- --------------------------------------------------------
-- Table: course
-- --------------------------------------------------------
CREATE TABLE `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `courseCode` varchar(30) NOT NULL,
  `courseName` varchar(200) NOT NULL,
  `courseUnit` int(11) NOT NULL DEFAULT 3,
  `noofSeats` int(11) NOT NULL DEFAULT 40,
  `departmentId` int(11) DEFAULT NULL,
  `description` text,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courseCode` (`courseCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `course` (`courseCode`, `courseName`, `courseUnit`, `noofSeats`, `departmentId`, `description`) VALUES
('CSE-1101', 'Introduction to Programming', 3, 50, 1, 'Fundamentals of programming using Python and C.'),
('CSE-2103', 'Data Structures & Algorithms', 3, 40, 1, 'Arrays, linked lists, trees, graphs and algorithm analysis.'),
('CSE-3101', 'Database Management Systems', 3, 45, 1, 'Relational databases, SQL, normalization and transactions.'),
('EEE-1201', 'Circuit Theory', 3, 40, 2, 'Basic electrical circuits and network theorems.'),
('BBA-1101', 'Principles of Management', 3, 60, 3, 'Introduction to management theories and practices.'),
('ELL-2101', 'Academic Writing', 2, 35, 4, 'Advanced academic writing and research skills.'),
('MATH-1101', 'Calculus I', 3, 55, 5, 'Limits, derivatives and applications of differentiation.');

-- --------------------------------------------------------
-- Table: students
-- --------------------------------------------------------
CREATE TABLE `students` (
  `StudentRegno` varchar(50) NOT NULL,
  `studentPhoto` varchar(255) DEFAULT 'noimage.png',
  `password` varchar(255) NOT NULL,
  `studentName` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `pincode` varchar(10) NOT NULL,
  `session` int(11) DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `cgpa` decimal(4,2) DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Active, 0=Inactive',
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`StudentRegno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample student: RegNo = 2025-CSE-001 | Password = Student@123
INSERT INTO `students` (`StudentRegno`, `password`, `studentName`, `email`, `pincode`, `session`, `department`, `semester`, `level`, `cgpa`) VALUES
('2025-CSE-001', '$2y$10$drb.DTnlyI.LW.zU3Y0.QuO5tarrqS26diSj3GpI1vU2CpMwtpH/S', 'Rahim Ahmed', 'rahim@student.edu', '482913', 2, 1, 1, 1, 3.75);

-- --------------------------------------------------------
-- Table: courseenrolls
-- --------------------------------------------------------
CREATE TABLE `courseenrolls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studentRegno` varchar(50) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `session` int(11) NOT NULL,
  `department` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `course` int(11) NOT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Approved',
  `enrollDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `studentRegno` (`studentRegno`),
  KEY `course` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: userlog
-- --------------------------------------------------------
CREATE TABLE `userlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studentRegno` varchar(50) NOT NULL,
  `userip` varchar(50) NOT NULL,
  `loginTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logout` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: notifications (new feature)
-- --------------------------------------------------------
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userType` enum('student','admin') NOT NULL,
  `userId` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `isRead` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;

-- --------------------------------------------------------
-- Table: ratings (Course Rating feature)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studentRegno` varchar(50) NOT NULL,
  `courseId` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `review` text,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_rating` (`studentRegno`, `courseId`),
  KEY `courseId` (`courseId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `ratings` (`studentRegno`, `courseId`, `rating`, `review`) VALUES
('2025-CSE-001', 1, 5, 'Excellent introductory course!'),
('2025-CSE-001', 3, 4, 'Very useful for DBMS concepts.');
