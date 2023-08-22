-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2021 at 04:41 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ticket_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `department` varchar(255) NOT NULL,
  `status` enum('enable','disable') NOT NULL DEFAULT 'enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `department`, `status`) VALUES
(1, 'Technical', 'enable'),
(2, 'Development', 'enable'),
(3, 'Design', 'enable'),
(4, 'QA', 'enable'),
(5, 'Support', 'enable');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `userid` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `mentioned` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('open','closed','resolved') NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `title`, `message`, `userid`, `department_id`, `mentioned`, `created`, `status`) VALUES
(2, 'File uploading not working', 'I am facing issue with file uploading. please fix this.', 1, 1, ',smith@coderszine.com', '2021-08-22 20:56:48', 'open'),
(3, 'dgsdg', 'dgsdgsds', 1, 2, ',smith@coderszine.com', '2021-10-23 21:11:19', 'open'),
(4, 'zfsafasfasfas', 'fhdfhdfhdfhdfhdfhdfhdf', 1, 3, '', '2021-10-23 21:13:20', 'closed'),
(5, 'dgdsgsdgs', 'dsgsdgsdgs', 1, 1, ',smith@coderszine.com', '2021-10-23 21:39:50', 'open'),
(6, 'sfsafasf', 'fasfasfasfas', 1, 1, '', '2021-11-14 16:53:55', 'open'),
(7, 'user login not working', 'User login functionality not working. please look into this issue.', 2, 1, '', '2021-12-12 17:13:10', 'open'),
(8, 'user register returning error', 'The use register functionality return error and register not completing.', 2, 1, ',,,,adam@coderszine.com,smith@coderszine.com', '2021-12-12 17:14:10', 'open'),
(9, 'There are issue in forum', 'I am faicng issue in forums login', 1, 1, ',,smith@coderszine.com', '2021-12-24 20:26:21', 'closed');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `comments` text NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ticket_replies`
--

INSERT INTO `ticket_replies` (`id`, `ticket_id`, `comments`, `created_by`, `created`) VALUES
(2, 2, 'I am working on this.', '1', '2021-11-27 21:30:19'),
(3, 2, 'Now issue has been fixed. Please check and let me know. thanks', '2', '2021-11-28 12:10:35'),
(4, 6, 'zsfagagasg', '1', '2021-11-28 22:13:08'),
(5, 6, 'aaaaaaaaaaa', '1', '2021-11-28 22:13:15'),
(6, 2, 'Have you checked this? it should be fixed now.', '1', '2021-11-28 22:14:06'),
(7, 6, 'fasfasfasf', '1', '2021-12-12 11:26:54'),
(8, 6, 'aaaaaaaaaaaaa', '1', '2021-12-12 11:27:07'),
(9, 9, 'I am looking into this issue', '1', '2021-12-24 20:27:01');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_user`
--

CREATE TABLE `ticket_user` (
  `userid` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `role` enum('admin','member') NOT NULL,
  `status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ticket_user`
--

INSERT INTO `ticket_user` (`userid`, `email`, `password`, `name`, `role`, `status`) VALUES
(1, 'adam@coderszine.com', '202cb962ac59075b964b07152d234b70', 'Adam', 'admin', 'Active'),
(2, 'smith@coderszine.com', '202cb962ac59075b964b07152d234b70', 'Smith', 'member', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_user`
--
ALTER TABLE `ticket_user`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ticket_user`
--
ALTER TABLE `ticket_user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
