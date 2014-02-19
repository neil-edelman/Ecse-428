-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 18, 2014 at 05:28 PM
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
  PRIMARY KEY (`session_id`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `SessionID`
--

INSERT INTO `SessionID` (`session_id`, `username`, `ip`, `activity`) VALUES
('9879lq9dfn9gh773ks7e14o5c3', 'user', '142.157.35.20', '2014-02-11 16:19:08');

-- --------------------------------------------------------

--
-- Table structure for table `Shifts`
--

CREATE TABLE IF NOT EXISTS `Shifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `checkin` datetime NOT NULL,
  `checkout` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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

--
-- Dumping data for table `Table`
--

INSERT INTO `Table` (`Table Number`, `Maximum Size`, `Current Size`, `Status`) VALUES
(1, 10, 9, 'vacant'),
(2, 2, 2, 'vacant'),
(3, 6, 4, 'vacant'),
(4, 4, 1, 'vacant');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `username` varchar(64) NOT NULL,
  `password` varchar(70) NOT NULL,
  `FirstName` varchar(32) NOT NULL,
  `LastName` varchar(32) NOT NULL,
  `Email` varchar(64) NOT NULL,
  `Privilege` enum('wait','manager','cook','admin') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `checkin` datetime DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`username`, `password`, `FirstName`, `LastName`, `Email`, `Privilege`, `checkin`)
VALUES
	('PayomPayomPayom','$2a$07$ec123ba411443c936abb7unk5GtS52ff3MZ9oTMsyJqJ3qZMOLdBm','Payom','Meshgin','payom.meshgin@whip.org','admin','0000-00-00 00:00:00'),
	('user','$2a$07$46af97b2794bb8a5f81a4ubUd/Nt0XRDFvO5zZL.Wl4PbOR.4Mi8q','USer','User','mail@payom.ca','wait','0000-00-00 00:00:00'),
	('lol','$2a$07$15856ce710660f56bb166e.85l4sV5CglC/2f.s5Vli37J7Q1pxe6','Lola','Unicorn','lol@lol.lol','admin',NULL),
	('sasi','$2a$07$56631bfeb139fc1ce70a6extZAFXZGkefMyCzrNaFgLcYiXfE8yhe','SAsithra','gerg','sasithra@gmail.com','wait','0000-00-00 00:00:00'),
	('JonathanBouchard','$2a$07$6184f8c7c0cfc48540a03uwLuapkfkOpQq2jwyaRfI3WEQv5jh9b2','Jonathan','Bouchard','jonathan.bouchard2@mail.mcgill.c','admin','0000-00-00 00:00:00'),
	('foo','$2a$07$71348fdcf46864d5ef0aeuXUlVan5Kkz3LxzLt6IyzdYe8lZxRAIu','Foo','Bar','foo@bar.baz','wait',NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
