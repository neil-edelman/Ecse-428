-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 19, 2014 at 04:33 PM
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
-- Table structure for table `Bills`
--

CREATE TABLE IF NOT EXISTS `Bills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `revenue` decimal(10,2) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `Bills`
--

INSERT INTO `Bills` (`id`, `date`, `revenue`) VALUES
(6, '2014-03-14', 65.20),
(1, '2014-03-15', 32.95),
(2, '2014-03-15', 42.10),
(3, '2014-03-15', 32.00);

-- --------------------------------------------------------

--
-- Table structure for table `MenuItems`
--

CREATE TABLE IF NOT EXISTS `MenuItems` (
  `Item ID` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Cost` int(11) NOT NULL,
  `Description` varchar(800) NOT NULL,
  PRIMARY KEY (`Item ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `MenuItems`
--

INSERT INTO `MenuItems` (`Item ID`, `Name`, `Cost`, `Description`) VALUES
(36, 'fdffd', 6, 'dvdv'),
(89, 'yumm yumm', 56, 'yummmmm'),
(3, 'dragon succotash', 6, 'hhhhhhhhhhhhhhhhhhhhh'),
(90, 'Frog legs', 56, 'mmmmm'),
(0, '', 0, ''),
(100, 'Chicken', 3, 'Premium chicken egg'),
(7, 'hot dog blaster', 89, 'god'),
(8, 'banana bomb', 77, 'Explosive banana whip'),
(45, 'Snake', 150, 'Rattle snake extract'),
(34, 'Badger Meat', 87, 'BEST MEAT EVER!!!!'),
(78, 'Pineapple', 23, 'sweet pineapple'),
(79, 'whip2', 23, 'whipping stuff ++'),
(44444, 'Noodles', 1, 'spagetti'),
(6666, '6666', 6666, '66666');

-- --------------------------------------------------------

--
-- Table structure for table `Order`
--

CREATE TABLE IF NOT EXISTS `Order` (
  `orderid` int(11) NOT NULL,
  `tableid` int(11) NOT NULL,
  `situation` varchar(15) NOT NULL,
  PRIMARY KEY (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Order`
--

INSERT INTO `Order` (`orderid`, `tableid`, `situation`) VALUES
(5, 14, 'placed'),
(4, 1, 'done'),
(3, 13, 'placed'),
(1, 1, 'done'),
(2, 55, 'placed'),
(6, 97, 'ready'),
(8, 45, 'placed'),
(7, 50, 'placed'),
(9, 240, 'done'),
(10, 555, 'placed'),
(11, 1000, 'placed'),
(24, 99, 'placed'),
(23, 99, 'placed'),
(14, 1250, 'placed'),
(15, 15, 'placed'),
(16, 4, 'ready'),
(17, 8, 'ready'),
(18, 53, 'placed'),
(20, 22, 'ready'),
(19, 17, 'placed'),
(21, 1, 'done'),
(22, 300, 'unavailable'),
(13, 99, 'placed'),
(12, 99, 'placed');

-- --------------------------------------------------------

--
-- Table structure for table `OrderContain`
--

CREATE TABLE IF NOT EXISTS `OrderContain` (
  `containid` varchar(20) NOT NULL,
  `orderid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `comment` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`containid`),
  UNIQUE KEY `containid` (`containid`),
  UNIQUE KEY `containid_2` (`containid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OrderContain`
--

INSERT INTO `OrderContain` (`containid`, `orderid`, `itemid`, `quantity`, `comment`) VALUES
('531786941813a', 9, 11, 111, 'No Salt Please.'),
('5317868e5f168', 9, 22, 222, '2222'),
('5312381d463cb', 3, 8, 1, ''),
('53123816494d3', 3, 2, 2, 'Wants water by the side'),
('5312356f40da2', 2, 1, 3, 'Mustard please'),
('5312387ff40ac', 4, 2, 3, 'Testing Comments Like These'),
('53123b20dcccd', 5, 3, 2, 'Extra ketchup'),
('53123b267d9b0', 5, 5, 3, ''),
('53123d08888e4', 6, 5, 4, 'No sugar!'),
('53123d0bb49f4', 6, 3, 2, 'Simple'),
('53138789301ed', 7, 3, 2, 'nomnom'),
('5313bba8d96e6', 4, 1, 17, 'Do NOT put salt!'),
('5313dedca96ea', 7, 4, 2, 'yum'),
('531786971caf1', 9, 3, 33, '333'),
('5317869985e27', 9, 4, 44, '444'),
('531cf8fb0cef4', 14, 4, 2, 'BLAH'),
('531d166cde4ff', 14, 12, 1, 'BLAAAHHH'),
('531d185819ef1', 15, 10, 3, 'No salt please'),
('531d193f961a7', 15, 100, 100, 'ADDED?'),
('531d258a50cd2', 16, 6, 99, 'Extra ketchups'),
('531d2592ccd09', 16, 2, 3, ''),
('531d2d876f16b', 17, 1, 2, 'No mustard'),
('531d2d8de188e', 17, 4, 1, 'Extra sauce'),
('531d326d92d03', 18, 2, 1, 'Test'),
('531ea659c4ca1', 20, 3, 3, '3'),
('531ea65ddfc08', 20, 2, 2, '2'),
('531f211770662', 21, 1, 1, '1'),
('531f28689299d', 22, 1, 2, 'No ketchup'),
('53249452f067c', 24, 9, 99, ''),
('5324945140921', 24, 8, 88, '888'),
('53249447dd34e', 23, 5, 55, '555'),
('532494441b865', 23, 4, 44, '444');

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
('9879lq9dfn9gh773ks7e14o5c3', 'user', '142.157.35.20', '2014-02-11 16:19:08'),
('h542f4dievf08s3puof1ni2qn1', 'qqq', '142.157.35.77', '2014-03-11 16:01:48'),
('2qla4c5i2djskr40j5ndjnnql4', 'payom', '142.157.72.65', '2014-03-11 16:58:27'),
('rkaunnpv92d9s7f0t86r3l3de5', 'JonathanBouchard', '216.221.58.22', '2014-03-19 19:53:08'),
('3urb7r9hr6hg20e9i6vuhmrij0', 'PayomPayomPayom', '192.171.42.176', '2014-03-14 21:58:41'),
('cpavrsf96dje9s041ejo3vvvf7', 'zzz', '192.222.147.39', '2014-03-16 12:36:04'),
('idcuem5ai50t1c96cc4le3egp0', 'zzz', '192.222.147.39', '2014-03-16 13:51:27'),
('nm39g5ok9vpjhp3tvbnf46tsm0', 'sasau', '142.157.141.146', '2014-03-17 21:22:58'),
('sr4ou5506nkq6o82uku9sc6266', 'zzz', '142.157.169.72', '2014-03-18 15:46:33'),
('pmhad26ul5edg12ascr8k2pb27', 'sasau', '142.157.167.48', '2014-03-18 17:00:40'),
('4l87r717rpacb6749vr6mboio3', 'zzz', '142.157.144.77', '2014-03-19 02:15:14'),
('kmqt5uhq3lguv5nvr0vkan8ml4', 'sasau', '142.157.45.93', '2014-03-18 19:12:04'),
('48ko2bhim503dfbqgvqjvobb10', 'sasau', '206.248.162.216', '2014-03-19 02:36:53'),
('lg1hi41q0od8c5t5sj5smigbu7', 'sasau', '108.175.224.142', '2014-03-19 17:19:41'),
('hanq1hkkrjdp9ig111po89i0o6', 'lol', '142.157.147.116', '2014-03-19 23:25:57');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `Shifts`
--

INSERT INTO `Shifts` (`id`, `username`, `checkin`, `checkout`) VALUES
(1, 'PayomPayomPayom', '2014-03-02 04:11:00', '2014-03-02 06:13:47'),
(2, 'PayomPayomPayom', '2014-03-02 06:14:57', '2014-03-02 06:15:37'),
(3, 'PayomPayomPayom', '2014-03-02 18:38:47', '2014-03-02 18:38:55'),
(4, 'PayomPayomPayom', '2014-03-02 18:40:10', '2014-03-09 00:00:17'),
(5, 'PayomPayomPayom', '2014-03-09 00:00:19', '2014-03-09 00:00:21'),
(13, 'PayomPayomPayom', '2014-03-10 16:44:00', '2014-03-10 16:16:00'),
(7, 'PayomPayomPayom', '2014-03-10 02:30:01', '2014-03-10 02:30:03'),
(8, 'PayomPayomPayom', '2014-03-10 02:30:08', '2014-03-10 02:30:10'),
(9, 'PayomPayomPayom', '2014-03-10 02:30:53', '2014-03-10 02:30:54'),
(10, 'PayomPayomPayom', '2014-03-10 02:30:55', '2014-03-10 02:30:56'),
(11, 'PayomPayomPayom', '2014-03-10 02:33:45', '2014-03-10 03:15:00'),
(12, 'PayomPayomPayom', '2014-03-10 03:42:47', '2014-03-10 04:07:59'),
(14, 'PayomPayomPayom', '2014-03-10 16:12:39', '2014-03-10 16:15:35'),
(15, 'PayomPayomPayom', '2014-03-10 16:50:35', '2014-03-10 16:50:48'),
(16, 'PayomPayomPayom', '2014-03-10 16:56:19', '2014-03-10 16:56:47'),
(17, 'PayomPayomPayom', '2014-03-10 17:58:26', '2014-03-10 17:58:35'),
(23, 'PayomPayomPayom', '2014-03-11 15:25:51', '2014-03-11 15:30:14'),
(19, 'zzz', '2014-03-10 21:25:16', '2014-03-10 21:25:28'),
(20, 'PayomPayomPayom', '2014-03-11 05:47:01', '2014-03-11 06:01:28'),
(21, 'PayomPayomPayom', '2014-03-11 14:43:17', '2014-03-11 14:44:07'),
(22, 'PayomPayomPayom', '2014-03-11 15:00:00', '2014-03-11 15:24:51'),
(24, 'qqq', '2014-03-11 15:31:14', '2014-03-11 15:32:23'),
(25, 'PayomPayomPayom', '2014-03-11 15:32:46', '2014-03-15 17:59:03'),
(26, 'zzz', '2014-03-10 21:25:31', '2014-03-16 08:27:52'),
(27, 'PayomPayomPayom', '2014-03-17 19:34:24', '2014-03-17 20:42:15'),
(28, 'PayomPayomPayom', '2014-03-17 23:43:31', '2014-03-17 23:44:07'),
(29, 'lol', '2014-03-18 15:46:57', '2014-03-18 15:47:02'),
(30, 'lol', '2014-03-18 18:40:26', '2014-03-19 23:04:02'),
(31, 'lol', '2014-03-19 23:04:11', '2014-03-19 23:04:13');

-- --------------------------------------------------------

--
-- Table structure for table `Tables`
--

CREATE TABLE IF NOT EXISTS `Tables` (
  `tablenumber` int(11) NOT NULL,
  `maxsize` int(11) NOT NULL,
  `currentsize` int(11) NOT NULL,
  `status` enum('vacant','occupied') NOT NULL DEFAULT 'vacant',
  PRIMARY KEY (`tablenumber`),
  UNIQUE KEY `tablenumber` (`tablenumber`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Tables`
--

INSERT INTO `Tables` (`tablenumber`, `maxsize`, `currentsize`, `status`) VALUES
(65, 32, 22, 'occupied'),
(2, 2, 0, 'vacant'),
(6, 4, 0, 'vacant'),
(1, 7, 0, 'vacant'),
(20, 15, 0, 'vacant'),
(8, 8, 4, 'occupied'),
(9, 10, 0, 'vacant'),
(7, 4, 0, 'vacant'),
(15, 4, 1, 'occupied'),
(99, 1, 0, 'vacant'),
(101, 4, 0, 'vacant'),
(87, 8, 0, 'vacant'),
(82, 15, 0, 'vacant'),
(100, 4, 0, 'vacant'),
(104, 4, 0, 'vacant'),
(102, 8, 0, 'vacant'),
(103, 10, 0, 'vacant'),
(300, 3, 1, 'occupied'),
(105, 78, 5, 'occupied'),
(232, 2323, 222, 'occupied'),
(0, 0, 0, 'vacant');

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

INSERT INTO `Users` (`username`, `password`, `FirstName`, `LastName`, `Email`, `Privilege`, `checkin`) VALUES
('PayomPayomPayom', '$2a$07$ec123ba411443c936abb7unk5GtS52ff3MZ9oTMsyJqJ3qZMOLdBm', 'Payom', 'Meshgin', 'payom.meshgin@whip.org', 'admin', NULL),
('user', '$2a$07$46af97b2794bb8a5f81a4ubUd/Nt0XRDFvO5zZL.Wl4PbOR.4Mi8q', 'USer', 'User', 'mail@payom.ca', 'wait', '0000-00-00 00:00:00'),
('dshfjsdk', '$2a$07$e85155a597017fdc1b091OKeHR80ZWNKwyLou2oFV3IQnIO3Q8..m', 'ads', 'asd', 'payom@mail.com', 'wait', '0000-00-00 00:00:00'),
('dude', '$2a$07$35cc869465423479c6e73OQYyq5iptqKtZVu.A5pN4.apoL17AdjC', 'Dude', 'McDude', 'Dude.Dude@Dude.Dude', 'manager', NULL),
('fsdfs', '$2a$07$34cc049bc49a4eb1f7d77O4BUIRCOFWkNgHuhiYRmCwIAfmYhvzN2', 'sasit', 'fdsfsd', 'sasi@sasi.com', 'wait', '0000-00-00 00:00:00'),
('JonathanBouchard', '$2a$07$6184f8c7c0cfc48540a03uwLuapkfkOpQq2jwyaRfI3WEQv5jh9b2', 'Jonathan', 'Bouchard', 'jonathan.bouchard2@mail.mcgill.c', 'admin', '0000-00-00 00:00:00'),
('foo', '$2a$07$9b066fdb7d1fc381b2b5bumHQJ2RftoPneLHPaV/AmKdrBwUt1caa', 'Foo', 'Bar', 'foo@bar.com', 'manager', '0000-00-00 00:00:00'),
('bar', '$2a$07$89634767873a2001d2a74uw81ArvbkhwsUpOER/rGXW815e6q3uJC', 'bar', 'bar', 'bar', 'manager', '0000-00-00 00:00:00'),
('baz', '$2a$07$a85f20f36fe5b97cf93c4uL8.74iUgE6l7nm.zzpHdIlQLs8FVKky', 'sfdg', 'sfgd', 'fgsd', 'wait', '0000-00-00 00:00:00'),
('lol', '$2a$07$bb34c8fc7c088db8ddadduCFWFGxXy7bQkbiXGG0rH/G/5KoTnAXK', 'Lola', 'Unicorn', 'lol@gmail.com', 'admin', '2014-03-19 23:10:13'),
('payom', '$2a$07$8d7199f9b6eaf96ce0087uzVuzXGsx5PY5qFsIZ6Q4z7knWop1g1W', 'payom', 'payom', 'payom@gmail.com', 'admin', '2014-03-11 15:34:43'),
('zzz', '$2a$07$4c864e58f5e618f303c4fuxKFZl/ykmrjwFX.sEvQ65WSgeyGWSEC', 'zzz', 'zzz', 'zzz@zzz.zzz', 'admin', '2014-03-16 10:39:23'),
('aaa', '$2a$07$636a6aed075c47efba722etSnkmVSYIdf3GiK6pwVC.SYO1otdm9C', 'aaa', 'aaa', 'aaa@aaa.aaa', 'cook', '2014-03-16 08:27:58'),
('qqq', '$2a$07$368ec152e849142cafc89OMUo7m1lz1xE7Gsnymt13FHYtyFkap6y', 'qqq', 'qqq', 'qqq@qqq.com', 'wait', '2014-03-11 15:45:41'),
('bob', '$2a$07$977e780090e09d558d0ffufX8B6/PBwtYtUCFdlkUzsSuhmkoLlRW', 'bob', 'bob', 'bob@gmail.com', 'wait', NULL),
('kk', '$2a$07$69f0729b65554423205c8ulQoiWRFyzvOYiinBsTREcUAh04XDwIm', 'kk', 'kk', 'pizza@gmail.com', 'wait', NULL),
('sasau', '$2a$07$6f244e623ef60ac5979fcu7rSQACm5OerHdtTCedJe5YEfdoVj/Ey', 'Sasithra', 'Thanabalan', 'sasithra@gmail.com', 'cook', '2014-03-17 20:42:30'),
('wait', '$2a$07$34575780f250513f9e403u884H7WPeMshyPDe7sG2kz0jillRk5nC', 'Wait', 'No', 'wait@wait.com', 'wait', NULL),
('cook', '$2a$07$c8e8e629ea493da22ebdbevxtZ7tFRu23gGToElXjcRpGOG1RqINW', 'Steven', 'Seagal', 'cook@cook.com', 'cook', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
