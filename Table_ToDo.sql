-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2018 at 11:07 AM
-- Server version: 5.0.96
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `g4besz`
--

-- --------------------------------------------------------

--
-- Table structure for table `ToDo`
--

CREATE TABLE IF NOT EXISTS `ToDo` (
  `ToDoID` int(11) NOT NULL auto_increment,
  `ToDoName` varchar(50) collate utf8_hungarian_ci NOT NULL,
  `UserID` int(11) NOT NULL,
  `Description` varchar(255) collate utf8_hungarian_ci NOT NULL,
  `CreatedAt` datetime NOT NULL,
  `FinishedFlag` int(11) NOT NULL,
  `FinishedAt` datetime NOT NULL,
  PRIMARY KEY  (`ToDoID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=17 ;

--
-- Dumping data for table `ToDo`
--

INSERT INTO `ToDo` (`ToDoID`, `ToDoName`, `UserID`, `Description`, `CreatedAt`, `FinishedFlag`, `FinishedAt`) VALUES
(14, 'feladat1', 1, 'asdas asdasd', '2018-06-08 15:26:01', 0, '0000-00-00 00:00:00'),
(9, 'asd', 1, 'aa asdad asdf', '2018-06-08 13:14:55', 0, '0000-00-00 00:00:00'),
(8, 'Å‘Ã¡Ã©Å±ÃºÃ³', 1, 'asdasd', '2018-06-08 12:06:27', 1, '2018-06-08 14:56:54'),
(13, 'legÃºjabb', 1, '', '2018-06-08 15:09:35', 0, '0000-00-00 00:00:00'),
(16, 'Newest', 1, 'asdasd\r\n\r\nasdas', '2018-06-11 11:05:54', 0, '0000-00-00 00:00:00');
