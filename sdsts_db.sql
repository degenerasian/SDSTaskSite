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

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`projectid`, `project_name`, `project_desc`) VALUES
(1, 'Project 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
(2, 'Project 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
(3, 'Project 3', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');

-- --------------------------------------------------------

--
-- Table structure for table `p_members`
--

CREATE TABLE `p_members` (
  `memberid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `projectid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `p_members`
--

INSERT INTO `p_members` (`memberid`, `userid`, `projectid`) VALUES
(1, 2, 1),
(2, 2, 2),
(3, 1, 2);

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

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`taskid`, `task_name`, `task_desc`, `label`, `category`, `est_time`, `due_date`, `created_by`, `projectid`) VALUES
(1, 'Task 1', 'Task 1 Desc', 'In Progress', 'For Publish', '2024-10-28 09:45:13', '2024-10-31', 2, 2),
(2, 'Task 2', 'Task 2 Desc', 'In Progress', 'For Publish', '2024-10-28 09:45:38', '2024-10-31', 2, 2),
(3, 'Task 3', 'Task 3 Desc', 'In Progress', 'For Publish', '2024-10-28 09:45:47', '2024-10-31', 2, 2),
(4, 'Task 4', 'Task 4 Desc', 'In Progress', 'For Publish', '2024-10-28 09:45:53', '2024-10-31', 2, 2),
(5, 'Task 5', 'Task 5 Desc', 'In Progress', 'For Publish', '2024-10-28 09:45:59', '2024-10-31', 2, 2),
(6, 'Task 1', 'Task 1 Desc', 'In Progress', 'For Publish', '2024-10-28 09:46:10', '2024-10-31', 2, 1),
(7, 'Task 2', 'Task 2 Desc', 'In Progress', 'For Publish', '2024-10-28 09:46:18', '2024-10-31', 2, 1),
(8, 'Task 3', 'Task 3 Desc', 'In Progress', 'For Publish', '2024-10-28 09:46:24', '2024-10-31', 2, 1),
(9, 'Task 4', 'Task 4 Desc', 'In Progress', 'For Publish', '2024-10-28 09:46:30', '2024-10-31', 2, 1),
(10, 'Task 5', 'Task 5 Desc', 'In Progress', 'For Publish', '2024-10-28 09:46:37', '2024-10-31', 2, 1);

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
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `f_name`, `l_name`, `email`, `password`, `privilege`) VALUES
(1, 'Cedric', 'Ty (User)', 'cedricuser@sds.com', 'user123', 'User'),
(2, 'Cedric', 'Ty (Admin)', 'cedricadmin@sds.com', 'admin123', 'Admin');

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
