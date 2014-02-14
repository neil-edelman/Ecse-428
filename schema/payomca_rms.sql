-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 08, 2014 at 11:26 AM
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
  `username` varchar(64) NOT NULL,
  `password` varchar(70) NOT NULL,
  `FirstName` varchar(32) NOT NULL,
  `LastName` varchar(32) NOT NULL,
  `Email` varchar(64) NOT NULL,
  `Privilege` enum('wait','manager','cook','admin') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`username`),
  UNIQUE KEY `Username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`username`, `password`, `FirstName`, `LastName`, `Email`, `Privilege`) VALUES
('lol', '$2a$07$f89f264852d120402d333OyGDQNH.vWHkfSC5ZX8VtIBZ5aR1gLzu', 'lol', 'lol', 'lol@lol.lol', 'cook'),
('lola', '$2a$07$6b92d3c9af94ad3583b13euygxg0fSADpN36OLcl0kfbNsU5H.Ffi', 'lola', 'lola', 'lola@gho.com', 'wait'),
('Dude', '$2a$07$0554b5383910c0454659duHkm7lnB7hIVd0LVpWZUY4CtW5QKaB5e', 'Capitaine', 'Mec', 'SupremeCaptain@mail.com', 'manager'),
('Zealot', '$2a$07$05b418beafe1d36bd705euvaosDlwHUrBJfsJPmKU0PkX7EZ1PQFG', 'Executor', 'Psiblade', 'MyLifeForAiur@mail.aiur.sc', 'wait'),
('nemo', '$2a$07$ea2ca2c74082818d7d203u0SEF9SCGvWujqIwQJkN4Hd0e9TKDpIy', 'nemo', 'nemo', '', 'wait');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
