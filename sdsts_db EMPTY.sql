-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2024 at 11:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sdsts_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignee`
--

CREATE TABLE `assignee` (
  `assignid` int(11) NOT NULL,
  `taskid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `projectid` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_desc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `p_members`
--

CREATE TABLE `p_members` (
  `memberid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `projectid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `taskid` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_desc` text NOT NULL,
  `label` enum('In Progress','For Testing','Reopened','For Checking','For Publish','Hidden') DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `est_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `due_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `projectid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `f_name` varchar(50) NOT NULL,
  `l_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `privilege` enum('User','Admin','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignee`
--
ALTER TABLE `assignee`
  ADD PRIMARY KEY (`assignid`),
  ADD KEY `assignee_fk_user` (`userid`),
  ADD KEY `assignee_fk_task` (`taskid`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`projectid`);

--
-- Indexes for table `p_members`
--
ALTER TABLE `p_members`
  ADD PRIMARY KEY (`memberid`),
  ADD KEY `pmem_fk_user` (`userid`),
  ADD KEY `pmem_fk_project` (`projectid`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`taskid`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `projectid` (`projectid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignee`
--
ALTER TABLE `assignee`
  MODIFY `assignid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `projectid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `p_members`
--
ALTER TABLE `p_members`
  MODIFY `memberid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `taskid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignee`
--
ALTER TABLE `assignee`
  ADD CONSTRAINT `assignee_fk_task` FOREIGN KEY (`taskid`) REFERENCES `tasks` (`taskid`),
  ADD CONSTRAINT `assignee_fk_user` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `p_members`
--
ALTER TABLE `p_members`
  ADD CONSTRAINT `pmem_fk_project` FOREIGN KEY (`projectid`) REFERENCES `projects` (`projectid`),
  ADD CONSTRAINT `pmem_fk_user` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_fk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`projectid`) REFERENCES `projects` (`projectid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
