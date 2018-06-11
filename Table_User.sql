-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2018 at 11:08 AM
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
-- Table structure for table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `UserID` int(11) NOT NULL auto_increment,
  `UserName` varchar(50) character set utf8 collate utf8_hungarian_ci NOT NULL,
  `Password` varchar(255) character set utf8 collate utf8_hungarian_ci NOT NULL,
  `Email` varchar(100) character set utf8 collate utf8_hungarian_ci NOT NULL,
  `CreatedAt` datetime NOT NULL,
  `ActiveFlag` int(11) NOT NULL,
  `Session` varchar(255) character set utf8 collate utf8_hungarian_ci NOT NULL,
  PRIMARY KEY  (`UserID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`UserID`, `UserName`, `Password`, `Email`, `CreatedAt`, `ActiveFlag`, `Session`) VALUES
(1, 'teszt1', 'e0bebd22819993425814866b62701e2919ea26f1370499c1037b53b9d49c2c8a', '', '0000-00-00 00:00:00', 1, '52797bcde4d84ee5c555b9c494ca2cfecc9d5c18d6d8825d1a100cbe896c5e92');
