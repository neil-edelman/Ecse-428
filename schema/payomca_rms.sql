-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 05, 2014 at 08:54 PM
-- Server version: 5.5.34-cll-lve
-- PHP Version: 5.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `payomca_rms`
--

-- --------------------------------------------------------

--
-- Table structure for table `Item`
--

CREATE TABLE IF NOT EXISTS `Item` (
  `Item ID` int(11) NOT NULL,
  `Cost` int(11) NOT NULL,
  `Comment` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Quantity` int(11) NOT NULL,
  PRIMARY KEY (`Item ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Menu`
--

CREATE TABLE IF NOT EXISTS `Menu` (
  `Item ID` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  PRIMARY KEY (`Item ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Order`
--

CREATE TABLE IF NOT EXISTS `Order` (
  `Order ID` int(11) NOT NULL,
  `Table Number` int(11) NOT NULL,
  `Status` enum('placed','ready','cooking','delivered','failed','cancel') NOT NULL,
  PRIMARY KEY (`Order ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Order Contain`
--

CREATE TABLE IF NOT EXISTS `Order Contain` (
  `Order ID` int(11) NOT NULL,
  `Item ID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Comment` varchar(300) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SessionID`
--

CREATE TABLE IF NOT EXISTS `SessionID` (
  `session_id` varchar(100) NOT NULL,
  `username` varchar(64) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `activity` datetime NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `SessionID`
--

INSERT INTO `SessionID` (`session_id`, `username`, `ip`, `activity`) VALUES
('rkaunnpv92d9s7f0t86r3l3de5', 'lol', '142.157.112.83', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `Table`
--

CREATE TABLE IF NOT EXISTS `Table` (
  `Table Number` int(11) NOT NULL,
  `Maximum Size` int(11) NOT NULL,
  `Current Size` int(11) NOT NULL,
  `Status` enum('vacant','occupied') NOT NULL DEFAULT 'vacant',
  PRIMARY KEY (`Table Number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `Username` varchar(15) NOT NULL,
  `password` varchar(70) NOT NULL,
  `FirstName` varchar(32) NOT NULL,
  `LastName` varchar(32) NOT NULL,
  `Email` varchar(64) NOT NULL,
  `Privilege` enum('wait','manager','cook','admin') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Username`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`Username`, `password`, `FirstName`, `LastName`, `Email`, `Privilege`) VALUES
('lol', '$2a$07$42100a8b31770f2f1f22eeULujNxiQ2Lr80HocJthWv8.JmiT4l.S', 'lol', 'lol', 'lol@gmail.com', 'wait');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
