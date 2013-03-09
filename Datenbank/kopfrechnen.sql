-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 28. Februar 2013 um 13:17
-- Server Version: 5.5.8
-- PHP-Version: 5.3.5


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `kopfrechnen`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `password` char(32) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `role` enum('admin','lehrer','schueler') DEFAULT NULL,
  `firstname` varchar(30) DEFAULT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `account`
--

INSERT INTO `account` (`id`, `name`, `password`, `email`, `role`, `firstname`, `lastname`) VALUES
(1, 'master', 'eb0a191797624dd3a48fa681d3061212', 'master@commander.de', 'admin', 'Mr T.', 'Bacon'),
(3, 'lehrer', '18a90f2c2b4484de555feb4b02904a7a', 'lehrer@ma.th', 'lehrer', 'Le', 'hrer'),
(4, 'schueler', '2d7a486f1e0c643890f817dd6764bc7b', 'schueler@asd.com', 'schueler', 'Mum', 'Pitz');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `account_class`
--

CREATE TABLE IF NOT EXISTS `account_class` (
  `accountid` int(11) NOT NULL,
  `classid` int(11) NOT NULL,
  KEY `accountid` (`accountid`),
  KEY `classid` (`classid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `account_class`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `account_exam`
--

CREATE TABLE IF NOT EXISTS `account_exam` (
  `accountid` int(11) NOT NULL,
  `examid` int(11) NOT NULL,
  KEY `accountid` (`accountid`),
  KEY `examid` (`examid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `account_exam`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `assignment`
--

CREATE TABLE IF NOT EXISTS `assignment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  `type` enum('calc','round','estimate','evaluate') NOT NULL,
  `termscheme` text NOT NULL,
  `examid` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `assignment`
--

INSERT INTO `assignment` (`id`, `description`, `type`, `termscheme`, `examid`, `count`) VALUES
(1, 'Rechne aus:', 'calc', 'a+b+c', 1, 10),
(2, 'Sch&auml;tze:', 'estimate', 'a*b - c', 1, 10),
(3, 'Richtig oder falsch?', 'evaluate', 'a*b - c', 2, 5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `class`
--

CREATE TABLE IF NOT EXISTS `class` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `class`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `exam`
--

CREATE TABLE IF NOT EXISTS `exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `duration` int(11) NOT NULL,
  `durationtype` enum('assignments','seconds') NOT NULL,
  `creator` int(11) NOT NULL,
  `lowerboundz` int(11) NOT NULL,
  `upperboundz` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `exam`
--

INSERT INTO `exam` (`id`, `name`, `duration`, `durationtype`, `creator`, `lowerboundz`, `upperboundz`) VALUES
(1, 'TestUebung', 10, 'assignments', 1, 1, 100),
(2, 'Bewerten', 5, 'assignments', 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `historyitem`
--

CREATE TABLE IF NOT EXISTS `historyitem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accountid` int(11) NOT NULL,
  `examid` int(11) NOT NULL,
  `assignmentid` int(11) NOT NULL,
  `term` text NOT NULL,
  `correctresult` text NOT NULL,
  `givenresult` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=616 ;

--
-- Daten für Tabelle `historyitem`
--

INSERT INTO `historyitem` (`id`, `accountid`, `examid`, `assignmentid`, `term`, `correctresult`, `givenresult`, `date`) VALUES
(12, 4, 1, 1, '2+9+4', '15', '15', '2013-02-16 19:23:30'),
(13, 4, 1, 1, '8+10+9', '27', '25', '2013-02-16 19:23:30'),
(14, 4, 1, 1, '8+1+5', '14', '14', '2013-02-16 19:23:30'),
(15, 4, 1, 1, '4+6+10', '20', '20', '2013-02-16 19:23:30'),
(16, 4, 1, 1, '7+1+9', '17', '1', '2013-02-16 19:23:30'),
(17, 4, 1, 1, '8+9+6', '23', '1', '2013-02-16 19:23:30'),
(18, 4, 1, 1, '10+6+7', '23', '1', '2013-02-16 19:23:30'),
(19, 4, 1, 1, '1+6+2', '9', '1', '2013-02-16 19:23:30'),
(20, 4, 1, 1, '2+4+7', '13', '1', '2013-02-16 19:23:30'),
(21, 4, 1, 1, '2+4+2', '8', '1', '2013-02-16 19:23:30'),
(22, 4, 1, 1, '4+3+4', '11', '11', '2013-02-16 19:54:08'),
(23, 4, 1, 1, '3+9+10', '22', '22', '2013-02-16 19:54:08'),
(24, 4, 1, 1, '10+4+2', '16', '16', '2013-02-16 19:54:08'),
(25, 4, 1, 1, '1+6+4', '11', '11', '2013-02-16 19:54:08'),
(26, 4, 1, 1, '3+1+2', '6', '6', '2013-02-16 19:54:08'),
(27, 4, 1, 1, '4+1+10', '15', '15', '2013-02-16 19:54:08'),
(28, 4, 1, 1, '5+9+10', '24', '24', '2013-02-16 19:54:08'),
(29, 4, 1, 1, '1+1+1', '3', '3', '2013-02-16 19:54:08'),
(30, 4, 1, 1, '7+4+10', '21', '21', '2013-02-16 19:54:08'),
(31, 4, 1, 1, '6+7+10', '23', '23', '2013-02-16 19:54:08'),
(32, 1, 1, 1, '3+5+2', '10', '123123', '2013-02-16 22:06:41'),
(33, 1, 1, 1, '3+1+7', '11', '123', '2013-02-16 22:06:41'),
(34, 1, 1, 1, '10+5+5', '20', '123', '2013-02-16 22:06:41'),
(35, 1, 1, 1, '2+7+9', '18', '123', '2013-02-16 22:06:41'),
(36, 1, 1, 1, '6+4+2', '12', '123', '2013-02-16 22:06:41'),
(37, 1, 1, 1, '6+4+1', '11', '123', '2013-02-16 22:06:41'),
(38, 1, 1, 1, '4+8+1', '13', '123', '2013-02-16 22:06:41'),
(39, 1, 1, 1, '7+2+8', '17', '123', '2013-02-16 22:06:41'),
(40, 1, 1, 1, '5+5+9', '19', '123', '2013-02-16 22:06:41'),
(41, 1, 1, 1, '10+4+8', '22', '123', '2013-02-16 22:06:41'),
(42, 4, 1, 1, '9+4+2', '15', '1', '2013-02-16 22:10:29'),
(43, 4, 1, 1, '5+6+4', '15', '1', '2013-02-16 22:10:29'),
(44, 4, 1, 1, '8+6+4', '18', '1', '2013-02-16 22:10:29'),
(45, 4, 1, 1, '8+5+7', '20', '1', '2013-02-16 22:10:29'),
(46, 4, 1, 1, '8+10+4', '22', '1', '2013-02-16 22:10:29'),
(47, 4, 1, 1, '2+7+2', '11', '1', '2013-02-16 22:10:29'),
(48, 4, 1, 1, '6+5+9', '20', '1', '2013-02-16 22:10:29'),
(49, 4, 1, 1, '4+8+8', '20', '1', '2013-02-16 22:10:29'),
(50, 4, 1, 1, '2+7+6', '15', '1', '2013-02-16 22:10:29'),
(51, 4, 1, 1, '2+1+6', '9', '', '2013-02-16 22:10:29'),
(52, 3, 1, 1, '4+5+4', '13', '13', '2013-02-16 22:31:26'),
(53, 3, 1, 1, '10+8+1', '19', '19', '2013-02-16 22:31:26'),
(54, 3, 1, 1, '8+10+4', '22', '22', '2013-02-16 22:31:26'),
(55, 3, 1, 1, '1+3+6', '10', '10', '2013-02-16 22:31:26'),
(56, 3, 1, 1, '6+10+10', '26', '26', '2013-02-16 22:31:26'),
(57, 3, 1, 1, '4+6+10', '20', '20', '2013-02-16 22:31:26'),
(58, 3, 1, 1, '6+8+8', '22', '22', '2013-02-16 22:31:26'),
(59, 3, 1, 1, '2+5+3', '10', '10', '2013-02-16 22:31:26'),
(60, 3, 1, 1, '7+1+9', '17', '17', '2013-02-16 22:31:26'),
(61, 3, 1, 1, '4+1+8', '13', '13', '2013-02-16 22:31:26'),
(62, 3, 1, 1, '4+6+2', '12', '1', '2013-02-16 22:31:57'),
(63, 3, 1, 1, '3+5+2', '10', '1', '2013-02-16 22:31:57'),
(64, 3, 1, 1, '6+10+7', '23', '1', '2013-02-16 22:31:57'),
(65, 3, 1, 1, '2+2+2', '6', '1', '2013-02-16 22:31:57'),
(66, 3, 1, 1, '9+3+8', '20', '1', '2013-02-16 22:31:57'),
(67, 3, 1, 1, '3+6+5', '14', '1', '2013-02-16 22:31:57'),
(68, 3, 1, 1, '5+3+5', '13', '1', '2013-02-16 22:31:57'),
(69, 3, 1, 1, '6+10+10', '26', '1', '2013-02-16 22:31:57'),
(70, 3, 1, 1, '5+9+3', '17', '1', '2013-02-16 22:31:57'),
(71, 3, 1, 1, '1+1+3', '5', '1', '2013-02-16 22:31:57'),
(72, 4, 1, 1, '2+1+9', '12', '1', '2013-02-17 11:57:54'),
(73, 4, 1, 1, '2+7+5', '14', '1', '2013-02-17 11:57:54'),
(74, 4, 1, 1, '8+6+9', '23', '1', '2013-02-17 11:57:54'),
(75, 4, 1, 1, '1+8+8', '17', '1', '2013-02-17 11:57:54'),
(76, 4, 1, 1, '2+3+5', '10', '1', '2013-02-17 11:57:54'),
(77, 4, 1, 1, '6+8+7', '21', '1', '2013-02-17 11:57:54'),
(78, 4, 1, 1, '1+1+10', '12', '1', '2013-02-17 11:57:54'),
(79, 4, 1, 1, '7+7+5', '19', '1', '2013-02-17 11:57:54'),
(80, 4, 1, 1, '5+8+4', '17', '1', '2013-02-17 11:57:54'),
(81, 4, 1, 1, '9+5+5', '19', '1', '2013-02-17 11:57:54'),
(82, 4, 1, 2, '1*3-5', '-2', '-2', '2013-02-17 11:57:54'),
(83, 4, 1, 2, '2*2-3', '1', '1', '2013-02-17 11:57:54'),
(84, 4, 1, 2, '3*4-4', '8', '1', '2013-02-17 11:57:54'),
(85, 4, 1, 2, '3*4-4', '8', '1', '2013-02-17 11:57:54'),
(86, 4, 1, 2, '4*2-2', '6', '1', '2013-02-17 11:57:54'),
(87, 4, 1, 2, '2*3-4', '2', '11', '2013-02-17 11:57:54'),
(88, 4, 1, 2, '5*1-1', '4', '1', '2013-02-17 11:57:54'),
(89, 4, 1, 2, '5*1-3', '2', '1', '2013-02-17 11:57:54'),
(90, 4, 1, 2, '1*2-3', '-1', '1', '2013-02-17 11:57:54'),
(91, 4, 1, 2, '4*2-4', '4', '1', '2013-02-17 11:57:54'),
(92, 4, 1, 1, '10+10+6', '26', '412', '2013-02-17 12:17:35'),
(93, 4, 1, 1, '10+9+4', '23', '435', '2013-02-17 12:17:35'),
(94, 4, 1, 1, '3+2+8', '13', '234', '2013-02-17 12:17:35'),
(95, 4, 1, 1, '6+7+3', '16', '123', '2013-02-17 12:17:35'),
(96, 4, 1, 1, '1+5+2', '8', '8', '2013-02-17 12:17:35'),
(97, 4, 1, 1, '6+1+5', '12', '12', '2013-02-17 12:17:35'),
(98, 4, 1, 1, '7+10+1', '18', '18', '2013-02-17 12:17:35'),
(99, 4, 1, 1, '1+7+3', '11', '11', '2013-02-17 12:17:35'),
(100, 4, 1, 1, '3+7+5', '15', '15', '2013-02-17 12:17:35'),
(101, 4, 1, 1, '2+10+9', '21', '21', '2013-02-17 12:17:35'),
(102, 4, 1, 2, '4*1-1', '3', '3', '2013-02-17 12:17:35'),
(103, 4, 1, 2, '2*4-2', '6', '6', '2013-02-17 12:17:35'),
(104, 4, 1, 2, '1*2-2', '0', '0', '2013-02-17 12:17:35'),
(105, 4, 1, 2, '1*4-1', '3', '3', '2013-02-17 12:17:35'),
(106, 4, 1, 2, '5*2-2', '8', '8', '2013-02-17 12:17:35'),
(107, 4, 1, 2, '2*3-2', '4', '4', '2013-02-17 12:17:35'),
(108, 4, 1, 2, '5*4-2', '18', '18', '2013-02-17 12:17:35'),
(109, 4, 1, 2, '5*5-3', '22', '22', '2013-02-17 12:17:35'),
(110, 4, 1, 2, '4*1-4', '0', '0', '2013-02-17 12:17:35'),
(111, 4, 1, 2, '1*1-4', '-3', '-3', '2013-02-17 12:17:35'),
(112, 4, 1, 2, '3*2-3', '3', '3', '2013-02-17 12:17:35'),
(113, 4, 1, 2, '2*2-5', '-1', '-1', '2013-02-17 12:17:35'),
(114, 4, 1, 2, '3*1-3', '0', '0', '2013-02-17 12:17:35'),
(115, 4, 1, 2, '3*5-5', '10', '10', '2013-02-17 12:17:35'),
(116, 4, 1, 2, '5*3-2', '13', '13', '2013-02-17 12:17:35'),
(117, 4, 1, 2, '1*2-1', '1', '1', '2013-02-17 12:17:35'),
(118, 4, 1, 2, '1*1-4', '-3', '-3', '2013-02-17 12:17:35'),
(119, 4, 1, 2, '5*5-2', '23', '23', '2013-02-17 12:17:35'),
(120, 4, 1, 2, '2*5-5', '5', '5', '2013-02-17 12:17:35'),
(121, 4, 1, 2, '1*2-2', '0', '0', '2013-02-17 12:17:35'),
(122, 4, 1, 1, '8+10+5', '23', '1', '2013-02-17 12:24:01'),
(123, 4, 1, 1, '4+10+8', '22', '1', '2013-02-17 12:24:01'),
(124, 4, 1, 1, '2+4+2', '8', '1', '2013-02-17 12:24:01'),
(125, 4, 1, 1, '6+4+2', '12', '1', '2013-02-17 12:24:01'),
(126, 4, 1, 1, '7+9+3', '19', '1', '2013-02-17 12:24:01'),
(127, 4, 1, 1, '4+4+8', '16', '1', '2013-02-17 12:24:01'),
(128, 4, 1, 1, '8+8+9', '25', '1', '2013-02-17 12:24:01'),
(129, 4, 1, 1, '7+3+4', '14', '1', '2013-02-17 12:24:01'),
(130, 4, 1, 1, '4+3+3', '10', '1', '2013-02-17 12:24:01'),
(131, 4, 1, 1, '4+2+10', '16', '1', '2013-02-17 12:24:01'),
(132, 4, 1, 2, '1*3-2', '1', '1', '2013-02-17 12:24:01'),
(133, 4, 1, 2, '5*3-2', '13', '1', '2013-02-17 12:24:01'),
(134, 4, 1, 2, '1*2-3', '-1', '1', '2013-02-17 12:24:01'),
(135, 4, 1, 2, '3*3-5', '4', '1', '2013-02-17 12:24:01'),
(136, 4, 1, 2, '1*5-3', '2', '1', '2013-02-17 12:24:01'),
(137, 4, 1, 2, '4*1-3', '1', '1', '2013-02-17 12:24:01'),
(138, 4, 1, 2, '4*3-3', '9', '1', '2013-02-17 12:24:01'),
(139, 4, 1, 2, '3*3-5', '4', '1', '2013-02-17 12:24:01'),
(140, 4, 1, 2, '5*5-4', '21', '1', '2013-02-17 12:24:01'),
(141, 4, 1, 2, '3*2-4', '2', '1', '2013-02-17 12:24:01'),
(142, 4, 1, 2, '3*3-3', '6', '1', '2013-02-17 12:24:01'),
(143, 4, 1, 2, '2*3-3', '3', '1', '2013-02-17 12:24:01'),
(144, 4, 1, 2, '4*2-2', '6', '1', '2013-02-17 12:24:01'),
(145, 4, 1, 2, '4*5-5', '15', '1', '2013-02-17 12:24:01'),
(146, 4, 1, 2, '2*5-1', '9', '1', '2013-02-17 12:24:01'),
(147, 4, 1, 2, '4*1-3', '1', '1', '2013-02-17 12:24:01'),
(148, 4, 1, 2, '3*5-4', '11', '1', '2013-02-17 12:24:01'),
(149, 4, 1, 2, '2*3-4', '2', '1', '2013-02-17 12:24:01'),
(150, 4, 1, 2, '4*1-5', '-1', '1', '2013-02-17 12:24:01'),
(151, 4, 1, 2, '1*3-1', '2', '1', '2013-02-17 12:24:01'),
(152, 4, 1, 1, '2+1+9', '12', '1', '2013-02-17 12:25:13'),
(153, 4, 1, 1, '2+6+2', '10', '1', '2013-02-17 12:25:13'),
(154, 4, 1, 1, '9+2+10', '21', '1', '2013-02-17 12:25:13'),
(155, 4, 1, 1, '4+1+1', '6', '1', '2013-02-17 12:25:13'),
(156, 4, 1, 1, '8+2+6', '16', '1', '2013-02-17 12:25:13'),
(157, 4, 1, 1, '8+10+7', '25', '1', '2013-02-17 12:25:13'),
(158, 4, 1, 1, '10+7+9', '26', '1', '2013-02-17 12:25:13'),
(159, 4, 1, 1, '7+7+8', '22', '1', '2013-02-17 12:25:13'),
(160, 4, 1, 1, '2+3+1', '6', '1', '2013-02-17 12:25:13'),
(161, 4, 1, 1, '3+9+3', '15', '1', '2013-02-17 12:25:13'),
(162, 4, 1, 2, '4*5-5', '15', '1', '2013-02-17 12:25:13'),
(163, 4, 1, 2, '4*3-4', '8', '1', '2013-02-17 12:25:13'),
(164, 4, 1, 2, '3*4-5', '7', '1', '2013-02-17 12:25:13'),
(165, 4, 1, 2, '1*1-2', '-1', '1', '2013-02-17 12:25:13'),
(166, 4, 1, 2, '4*4-5', '11', '1', '2013-02-17 12:25:13'),
(167, 4, 1, 2, '1*2-5', '-3', '1', '2013-02-17 12:25:13'),
(168, 4, 1, 2, '1*5-1', '4', '1', '2013-02-17 12:25:13'),
(169, 4, 1, 2, '3*5-3', '12', '1', '2013-02-17 12:25:13'),
(170, 4, 1, 2, '5*5-2', '23', '1', '2013-02-17 12:25:13'),
(171, 4, 1, 2, '4*4-1', '15', '1', '2013-02-17 12:25:13'),
(172, 4, 1, 2, '5*3-3', '12', '1', '2013-02-17 12:25:13'),
(173, 4, 1, 2, '3*1-3', '0', '1', '2013-02-17 12:25:13'),
(174, 4, 1, 2, '1*5-4', '1', '1', '2013-02-17 12:25:13'),
(175, 4, 1, 2, '5*4-4', '16', '1', '2013-02-17 12:25:13'),
(176, 4, 1, 2, '4*4-2', '14', '1', '2013-02-17 12:25:13'),
(177, 4, 1, 2, '2*4-1', '7', '1', '2013-02-17 12:25:13'),
(178, 4, 1, 2, '1*3-3', '0', '1', '2013-02-17 12:25:13'),
(179, 4, 1, 2, '4*3-1', '11', '1', '2013-02-17 12:25:13'),
(180, 4, 1, 2, '3*3-1', '8', '1', '2013-02-17 12:25:13'),
(181, 4, 1, 2, '3*4-4', '8', '1', '2013-02-17 12:25:13'),
(182, 4, 1, 1, '2+4+10', '16', '23+56', '2013-02-17 14:01:58'),
(183, 4, 1, 1, '2+7+7', '16', '16', '2013-02-17 14:01:58'),
(184, 4, 1, 1, '4+4+8', '16', '16', '2013-02-17 14:01:58'),
(185, 4, 1, 1, '8+2+8', '18', '', '2013-02-17 14:01:58'),
(186, 4, 1, 1, '3+5+3', '11', '11', '2013-02-17 14:01:58'),
(187, 4, 1, 1, '6+3+10', '19', '19', '2013-02-17 14:01:58'),
(188, 4, 1, 1, '3+8+2', '13', '13', '2013-02-17 14:01:58'),
(189, 4, 1, 1, '9+4+6', '19', '19', '2013-02-17 14:01:58'),
(190, 4, 1, 1, '7+6+6', '19', '19', '2013-02-17 14:01:58'),
(191, 4, 1, 1, '9+4+2', '15', '15', '2013-02-17 14:01:58'),
(192, 4, 1, 2, '3*2-3', '3', '3', '2013-02-17 14:01:58'),
(193, 4, 1, 2, '4*1-5', '-1', '1', '2013-02-17 14:01:58'),
(194, 4, 1, 2, '1*2-5', '-3', '', '2013-02-17 14:01:58'),
(195, 4, 1, 2, '1*3-2', '1', '', '2013-02-17 14:01:58'),
(196, 4, 1, 2, '2*3-2', '4', '', '2013-02-17 14:01:58'),
(197, 4, 1, 2, '5*2-1', '9', '', '2013-02-17 14:01:58'),
(198, 4, 1, 2, '4*5-4', '16', '', '2013-02-17 14:01:58'),
(199, 4, 1, 2, '4*5-5', '15', '', '2013-02-17 14:01:58'),
(200, 4, 1, 2, '3*1-4', '-1', '', '2013-02-17 14:01:58'),
(201, 4, 1, 2, '4*4-4', '12', '', '2013-02-17 14:01:58'),
(202, 4, 1, 2, '4*1-1', '3', '2', '2013-02-17 14:01:58'),
(203, 4, 1, 2, '5*4-5', '15', '', '2013-02-17 14:01:58'),
(204, 4, 1, 2, '2*3-1', '5', '4', '2013-02-17 14:01:58'),
(205, 4, 1, 2, '5*3-2', '13', '1', '2013-02-17 14:01:58'),
(206, 4, 1, 2, '2*1-5', '-3', '3', '2013-02-17 14:01:58'),
(207, 4, 1, 2, '1*3-2', '1', '6', '2013-02-17 14:01:58'),
(208, 4, 1, 2, '1*5-2', '3', '8', '2013-02-17 14:01:58'),
(209, 4, 1, 2, '1*1-2', '-1', '1', '2013-02-17 14:01:58'),
(210, 4, 1, 2, '2*4-5', '3', '3', '2013-02-17 14:01:58'),
(211, 4, 1, 2, '1*3-2', '1', '1', '2013-02-17 14:01:58'),
(212, 4, 1, 1, '3+6+4', '13', 'kacka', '2013-02-17 14:12:18'),
(213, 4, 1, 1, '6+9+6', '21', 'pupsi', '2013-02-17 14:12:18'),
(214, 4, 1, 1, '4+8+1', '13', '13', '2013-02-17 14:12:18'),
(215, 4, 1, 1, '1+2+6', '9', '1', '2013-02-17 14:12:18'),
(216, 4, 1, 1, '2+5+1', '8', '1', '2013-02-17 14:12:18'),
(217, 4, 1, 1, '1+7+2', '10', '1', '2013-02-17 14:12:18'),
(218, 4, 1, 1, '9+3+7', '19', '1', '2013-02-17 14:12:18'),
(219, 4, 1, 1, '7+9+4', '20', '1', '2013-02-17 14:12:18'),
(220, 4, 1, 1, '3+5+1', '9', '1', '2013-02-17 14:12:18'),
(221, 4, 1, 1, '6+6+3', '15', '1', '2013-02-17 14:12:18'),
(222, 4, 1, 2, '5*5-3', '22', '1', '2013-02-17 14:12:18'),
(223, 4, 1, 2, '4*1-3', '1', '1', '2013-02-17 14:12:18'),
(224, 4, 1, 2, '2*4-2', '6', '3', '2013-02-17 14:12:18'),
(225, 4, 1, 2, '1*1-3', '-2', '2', '2013-02-17 14:12:18'),
(226, 4, 1, 2, '5*3-1', '14', '1', '2013-02-17 14:12:18'),
(227, 4, 1, 2, '4*1-2', '2', '3', '2013-02-17 14:12:18'),
(228, 4, 1, 2, '2*2-4', '0', '4', '2013-02-17 14:12:18'),
(229, 4, 1, 2, '2*2-4', '0', '2', '2013-02-17 14:12:18'),
(230, 4, 1, 2, '1*1-1', '0', '1', '2013-02-17 14:12:18'),
(231, 4, 1, 2, '1*1-2', '-1', '2', '2013-02-17 14:12:18'),
(232, 4, 1, 2, '2*5-1', '9', '1', '2013-02-17 14:12:18'),
(233, 4, 1, 2, '4*4-4', '12', '2', '2013-02-17 14:12:18'),
(234, 4, 1, 2, '5*1-3', '2', '1', '2013-02-17 14:12:18'),
(235, 4, 1, 2, '1*4-5', '-1', '2', '2013-02-17 14:12:18'),
(236, 4, 1, 2, '3*3-4', '5', '1', '2013-02-17 14:12:18'),
(237, 4, 1, 2, '5*5-5', '20', '2', '2013-02-17 14:12:18'),
(238, 4, 1, 2, '5*5-5', '20', '1', '2013-02-17 14:12:18'),
(239, 4, 1, 2, '3*3-3', '6', '1', '2013-02-17 14:12:18'),
(240, 4, 1, 2, '4*2-2', '6', '2', '2013-02-17 14:12:18'),
(241, 4, 1, 2, '4*2-4', '4', '1', '2013-02-17 14:12:18'),
(242, 4, 1, 1, '2+3+3', '8', '8', '2013-02-19 00:34:26'),
(243, 4, 1, 1, '2+5+10', '17', '17', '2013-02-19 00:34:26'),
(244, 4, 1, 1, '4+1+4', '9', '9', '2013-02-19 00:34:26'),
(245, 4, 1, 1, '3+3+4', '10', '10', '2013-02-19 00:34:26'),
(246, 4, 1, 1, '5+8+4', '17', '17', '2013-02-19 00:34:26'),
(247, 4, 1, 1, '7+1+10', '18', '18', '2013-02-19 00:34:26'),
(248, 4, 1, 1, '3+10+1', '14', '651', '2013-02-19 00:34:26'),
(249, 4, 1, 1, '1+2+8', '11', '0', '2013-02-19 00:34:26'),
(250, 4, 1, 1, '8+9+1', '18', '230', '2013-02-19 00:34:26'),
(251, 4, 1, 1, '8+1+3', '12', '1', '2013-02-19 00:34:26'),
(252, 4, 1, 2, '4*5-4', '16', '2', '2013-02-19 00:34:26'),
(253, 4, 1, 2, '2*4-5', '3', '1', '2013-02-19 00:34:26'),
(254, 4, 1, 2, '2*1-5', '-3', '2', '2013-02-19 00:34:26'),
(255, 4, 1, 2, '4*2-2', '6', '3', '2013-02-19 00:34:26'),
(256, 4, 1, 2, '3*3-2', '7', '4', '2013-02-19 00:34:26'),
(257, 4, 1, 2, '1*3-4', '-1', '5', '2013-02-19 00:34:26'),
(258, 4, 1, 2, '5*5-4', '21', '6', '2013-02-19 00:34:26'),
(259, 4, 1, 2, '5*3-3', '12', '7', '2013-02-19 00:34:26'),
(260, 4, 1, 2, '4*5-3', '17', '8', '2013-02-19 00:34:26'),
(261, 4, 1, 2, '2*2-5', '-1', '9', '2013-02-19 00:34:26'),
(262, 4, 1, 2, '1*5-4', '1', '4', '2013-02-19 00:34:26'),
(263, 4, 1, 2, '2*4-5', '3', '5', '2013-02-19 00:34:26'),
(264, 4, 1, 2, '2*3-2', '4', '6', '2013-02-19 00:34:26'),
(265, 4, 1, 2, '5*2-3', '7', '1', '2013-02-19 00:34:26'),
(266, 4, 1, 2, '2*5-5', '5', '2', '2013-02-19 00:34:26'),
(267, 4, 1, 2, '1*4-1', '3', '3', '2013-02-19 00:34:26'),
(268, 4, 1, 2, '3*4-3', '9', '54', '2013-02-19 00:34:26'),
(269, 4, 1, 2, '2*5-4', '6', '4', '2013-02-19 00:34:26'),
(270, 4, 1, 2, '5*2-3', '7', '5', '2013-02-19 00:34:26'),
(271, 4, 1, 2, '5*5-5', '20', '6', '2013-02-19 00:34:26'),
(272, 4, 1, 1, '10+3+8', '21', '4', '2013-02-23 17:22:17'),
(273, 4, 1, 1, '7+6+2', '15', '79', '2013-02-23 17:22:17'),
(274, 4, 1, 1, '7+7+5', '19', '19', '2013-02-23 17:22:17'),
(275, 4, 1, 1, '9+5+7', '21', '21', '2013-02-23 17:22:17'),
(276, 4, 1, 1, '7+2+7', '16', '1', '2013-02-23 17:22:17'),
(277, 4, 1, 1, '7+6+1', '14', '', '2013-02-23 17:22:17'),
(278, 4, 1, 1, '5+8+2', '15', '12', '2013-02-23 17:22:17'),
(279, 4, 1, 1, '7+4+10', '21', '', '2013-02-23 17:22:17'),
(280, 4, 1, 1, '7+2+4', '13', '12', '2013-02-23 17:22:17'),
(281, 4, 1, 1, '2+10+7', '19', '123', '2013-02-23 17:22:17'),
(282, 4, 1, 2, '1*3-4', '-1', '123', '2013-02-23 17:22:17'),
(283, 4, 1, 2, '4*2-2', '6', '123', '2013-02-23 17:22:17'),
(284, 4, 1, 2, '3*5-1', '14', '2', '2013-02-23 17:22:17'),
(285, 4, 1, 2, '5*1-5', '0', '3', '2013-02-23 17:22:17'),
(286, 4, 1, 2, '3*3-4', '5', '4', '2013-02-23 17:22:17'),
(287, 4, 1, 2, '1*4-2', '2', '1', '2013-02-23 17:22:17'),
(288, 4, 1, 2, '1*1-2', '-1', '2', '2013-02-23 17:22:17'),
(289, 4, 1, 2, '2*4-1', '7', '4', '2013-02-23 17:22:17'),
(290, 4, 1, 2, '4*2-3', '5', '1', '2013-02-23 17:22:17'),
(291, 4, 1, 2, '2*1-1', '1', '2', '2013-02-23 17:22:17'),
(292, 4, 1, 2, '3*4-2', '10', '4', '2013-02-23 17:22:17'),
(293, 4, 1, 2, '2*3-1', '5', '2', '2013-02-23 17:22:17'),
(294, 4, 1, 2, '5*1-2', '3', '3', '2013-02-23 17:22:17'),
(295, 4, 1, 2, '3*2-4', '2', '1', '2013-02-23 17:22:17'),
(296, 4, 1, 2, '4*4-2', '14', '2', '2013-02-23 17:22:17'),
(297, 4, 1, 2, '5*2-1', '9', '3', '2013-02-23 17:22:17'),
(298, 4, 1, 2, '3*1-4', '-1', '5', '2013-02-23 17:22:17'),
(299, 4, 1, 2, '4*2-2', '6', '42', '2013-02-23 17:22:17'),
(300, 4, 1, 2, '3*1-2', '1', '1', '2013-02-23 17:22:17'),
(301, 4, 1, 2, '4*1-1', '3', '2', '2013-02-23 17:22:17'),
(302, 1, 1, 1, '2+4+6', '12', '12', '2013-02-26 00:14:20'),
(303, 1, 1, 1, '1+4+1', '6', '6', '2013-02-26 00:14:20'),
(304, 1, 1, 1, '2+10+9', '21', '21', '2013-02-26 00:14:20'),
(305, 1, 1, 1, '1+4+8', '13', '13', '2013-02-26 00:14:20'),
(306, 1, 1, 1, '2+6+1', '9', '1', '2013-02-26 00:14:20'),
(307, 1, 1, 1, '2+10+9', '21', '1', '2013-02-26 00:14:20'),
(308, 1, 1, 1, '2+2+3', '7', '1', '2013-02-26 00:14:20'),
(309, 1, 1, 1, '1+7+9', '17', '1', '2013-02-26 00:14:20'),
(310, 1, 1, 1, '2+6+5', '13', '1', '2013-02-26 00:14:20'),
(311, 1, 1, 1, '1+2+6', '9', '1', '2013-02-26 00:14:20'),
(312, 1, 1, 2, '5*1-1', '4', '1', '2013-02-26 00:14:20'),
(313, 1, 1, 2, '1*1-4', '-3', '1', '2013-02-26 00:14:20'),
(314, 1, 1, 2, '3*2-5', '1', '1', '2013-02-26 00:14:20'),
(315, 1, 1, 2, '3*2-4', '2', '1', '2013-02-26 00:14:20'),
(316, 1, 1, 2, '4*3-3', '9', '1', '2013-02-26 00:14:20'),
(317, 1, 1, 2, '5*3-2', '13', '1', '2013-02-26 00:14:20'),
(318, 1, 1, 2, '2*5-4', '6', '1', '2013-02-26 00:14:20'),
(319, 1, 1, 2, '1*1-5', '-4', '1', '2013-02-26 00:14:20'),
(320, 1, 1, 2, '1*1-4', '-3', '1', '2013-02-26 00:14:20'),
(321, 1, 1, 2, '3*1-5', '-2', '1', '2013-02-26 00:14:20'),
(322, 1, 1, 2, '5*2-1', '9', '1', '2013-02-26 00:14:20'),
(323, 1, 1, 2, '1*5-4', '1', '1', '2013-02-26 00:14:20'),
(324, 1, 1, 2, '5*4-5', '15', '1', '2013-02-26 00:14:20'),
(325, 1, 1, 2, '4*4-1', '15', '1', '2013-02-26 00:14:20'),
(326, 1, 1, 2, '3*2-1', '5', '1', '2013-02-26 00:14:20'),
(327, 1, 1, 2, '2*5-2', '8', '1', '2013-02-26 00:14:20'),
(328, 1, 1, 2, '4*2-1', '7', '1', '2013-02-26 00:14:20'),
(329, 1, 1, 2, '4*3-1', '11', '1', '2013-02-26 00:14:20'),
(330, 1, 1, 2, '1*5-3', '2', '1', '2013-02-26 00:14:20'),
(331, 1, 1, 2, '2*3-1', '5', '1', '2013-02-26 00:14:20'),
(332, 1, 1, 1, '2+4+4', '10', '10', '2013-02-26 00:46:04'),
(333, 1, 1, 1, '2+9+10', '21', '123', '2013-02-26 00:46:04'),
(334, 1, 1, 1, '1+10+2', '13', '123', '2013-02-26 00:46:04'),
(335, 1, 1, 1, '1+6+5', '12', '123', '2013-02-26 00:46:04'),
(336, 1, 1, 1, '1+5+10', '16', '151', '2013-02-26 00:46:04'),
(337, 1, 1, 1, '2+9+2', '13', '151', '2013-02-26 00:46:04'),
(338, 1, 1, 1, '2+4+8', '14', '123', '2013-02-26 00:46:04'),
(339, 1, 1, 1, '1+4+3', '8', '123', '2013-02-26 00:46:04'),
(340, 1, 1, 1, '2+9+7', '18', '123', '2013-02-26 00:46:04'),
(341, 1, 1, 1, '2+4+8', '14', '123', '2013-02-26 00:46:04'),
(342, 1, 1, 2, '4*3-1', '11', '123', '2013-02-26 00:46:04'),
(343, 1, 1, 2, '1*4-3', '1', '123', '2013-02-26 00:46:04'),
(344, 1, 1, 2, '5*1-1', '4', '123', '2013-02-26 00:46:04'),
(345, 1, 1, 2, '2*5-5', '5', '123', '2013-02-26 00:46:04'),
(346, 1, 1, 2, '1*2-5', '-3', '123', '2013-02-26 00:46:04'),
(347, 1, 1, 2, '4*1-1', '3', '123', '2013-02-26 00:46:04'),
(348, 1, 1, 2, '1*3-3', '0', '123', '2013-02-26 00:46:04'),
(349, 1, 1, 2, '1*4-3', '1', '11', '2013-02-26 00:46:04'),
(350, 1, 1, 2, '4*4-3', '13', '1', '2013-02-26 00:46:04'),
(351, 1, 1, 2, '5*1-3', '2', '1', '2013-02-26 00:46:04'),
(352, 1, 1, 2, '1*5-1', '4', '1', '2013-02-26 00:46:04'),
(353, 1, 1, 2, '2*4-4', '4', '1', '2013-02-26 00:46:04'),
(354, 1, 1, 2, '1*2-5', '-3', '1', '2013-02-26 00:46:04'),
(355, 1, 1, 2, '1*2-1', '1', '1', '2013-02-26 00:46:04'),
(356, 1, 1, 2, '5*1-5', '0', '1', '2013-02-26 00:46:04'),
(357, 1, 1, 2, '3*3-4', '5', '1', '2013-02-26 00:46:04'),
(358, 1, 1, 2, '3*5-4', '11', '1', '2013-02-26 00:46:04'),
(359, 1, 1, 2, '4*5-5', '15', '1', '2013-02-26 00:46:04'),
(360, 1, 1, 2, '4*3-2', '10', '1', '2013-02-26 00:46:04'),
(361, 1, 1, 2, '3*2-5', '1', '1', '2013-02-26 00:46:04'),
(362, 1, 1, 1, '2+6+8', '16', '16', '2013-02-26 12:18:20'),
(363, 1, 1, 1, '2+1+1', '4', '4', '2013-02-26 12:18:20'),
(364, 1, 1, 1, '2+3+2', '7', '7', '2013-02-26 12:18:20'),
(365, 1, 1, 1, '2+3+7', '12', '1', '2013-02-26 12:18:20'),
(366, 1, 1, 1, '2+1+7', '10', '1', '2013-02-26 12:18:20'),
(367, 1, 1, 1, '1+7+1', '9', '1', '2013-02-26 12:18:20'),
(368, 1, 1, 1, '2+3+5', '10', '1', '2013-02-26 12:18:20'),
(369, 1, 1, 1, '1+8+2', '11', '1', '2013-02-26 12:18:20'),
(370, 1, 1, 1, '1+5+4', '10', '1', '2013-02-26 12:18:20'),
(371, 1, 1, 1, '1+9+3', '13', '1', '2013-02-26 12:18:20'),
(372, 1, 1, 2, '3*2-4', '2', '1', '2013-02-26 12:18:20'),
(373, 1, 1, 2, '5*2-4', '6', '1', '2013-02-26 12:18:20'),
(374, 1, 1, 2, '2*5-3', '7', '1', '2013-02-26 12:18:20'),
(375, 1, 1, 2, '2*4-4', '4', '1', '2013-02-26 12:18:20'),
(376, 1, 1, 2, '4*5-5', '15', '1', '2013-02-26 12:18:20'),
(377, 1, 1, 2, '3*4-2', '10', '1', '2013-02-26 12:18:20'),
(378, 1, 1, 2, '1*4-4', '0', '1', '2013-02-26 12:18:20'),
(379, 1, 1, 2, '5*4-2', '18', '1', '2013-02-26 12:18:20'),
(380, 1, 1, 2, '3*2-1', '5', '1', '2013-02-26 12:18:20'),
(381, 1, 1, 2, '2*3-3', '3', '1', '2013-02-26 12:18:20'),
(382, 1, 1, 1, '1+6+5', '12', '12', '2013-02-26 12:25:38'),
(383, 1, 1, 1, '1+3+1', '5', '5', '2013-02-26 12:25:38'),
(384, 1, 1, 1, '1+9+1', '11', '11', '2013-02-26 12:25:38'),
(385, 1, 1, 1, '2+4+5', '11', '11', '2013-02-26 12:25:38'),
(386, 1, 1, 1, '2+6+3', '11', '11', '2013-02-26 12:25:38'),
(387, 1, 1, 1, '2+9+5', '16', '16', '2013-02-26 12:25:38'),
(388, 1, 1, 1, '2+3+6', '11', '11', '2013-02-26 12:25:38'),
(389, 1, 1, 1, '1+6+5', '12', '12', '2013-02-26 12:25:38'),
(390, 1, 1, 1, '2+4+8', '14', '14', '2013-02-26 12:25:38'),
(391, 1, 1, 1, '1+9+2', '12', '12', '2013-02-26 12:25:38'),
(392, 1, 1, 2, '4*2-3', '5', '5', '2013-02-26 12:25:38'),
(393, 1, 1, 2, '4*5-5', '15', '15', '2013-02-26 12:25:38'),
(394, 1, 1, 2, '1*5-3', '2', '2', '2013-02-26 12:25:38'),
(395, 1, 1, 2, '1*1-1', '0', '0', '2013-02-26 12:25:38'),
(396, 1, 1, 2, '2*2-3', '1', '1', '2013-02-26 12:25:38'),
(397, 1, 1, 2, '1*5-3', '2', '2', '2013-02-26 12:25:38'),
(398, 1, 1, 2, '2*2-5', '-1', '-1', '2013-02-26 12:25:38'),
(399, 1, 1, 2, '5*5-2', '23', '23', '2013-02-26 12:25:38'),
(400, 1, 1, 2, '5*1-3', '2', '2', '2013-02-26 12:25:38'),
(401, 1, 1, 2, '4*3-4', '8', '8', '2013-02-26 12:25:38'),
(439, 1, 1, 1, '2+5+6', '13', '13', '2013-02-26 14:35:57'),
(440, 1, 1, 1, '1+5+7', '13', '13', '2013-02-26 14:35:57'),
(441, 1, 1, 1, '1+6+10', '17', '17', '2013-02-26 14:35:57'),
(442, 1, 1, 1, '1+1+1', '3', '3', '2013-02-26 14:35:57'),
(443, 1, 1, 1, '2+4+9', '15', '15', '2013-02-26 14:35:57'),
(444, 1, 1, 1, '2+6+10', '18', '18', '2013-02-26 14:35:57'),
(445, 1, 1, 1, '1+1+7', '9', '9', '2013-02-26 14:35:57'),
(446, 1, 1, 1, '1+4+9', '14', '14', '2013-02-26 14:35:57'),
(447, 1, 1, 1, '1+10+2', '13', '13', '2013-02-26 14:35:57'),
(448, 1, 1, 1, '2+2+6', '10', '10', '2013-02-26 14:35:57'),
(449, 1, 1, 2, '5*5-2', '23', '23', '2013-02-26 14:35:57'),
(450, 1, 1, 2, '4*2-3', '5', '5', '2013-02-26 14:35:57'),
(451, 1, 1, 2, '4*3-3', '9', '9', '2013-02-26 14:35:57'),
(452, 1, 1, 2, '5*2-2', '8', '8', '2013-02-26 14:35:57'),
(453, 1, 1, 2, '5*5-5', '20', '20', '2013-02-26 14:35:57'),
(454, 1, 1, 2, '1*5-4', '1', '1', '2013-02-26 14:35:57'),
(455, 1, 1, 2, '1*3-5', '-2', '-2', '2013-02-26 14:35:57'),
(456, 1, 1, 2, '1*2-4', '-2', '-2', '2013-02-26 14:35:57'),
(457, 1, 1, 2, '4*5-5', '15', '15', '2013-02-26 14:35:57'),
(458, 1, 1, 2, '4*5-3', '17', '17', '2013-02-26 14:35:57'),
(459, 1, 1, 1, '2+5+7', '14', '12', '2013-02-26 14:41:10'),
(460, 1, 1, 1, '1+10+6', '17', '12', '2013-02-26 14:41:10'),
(461, 1, 1, 1, '2+7+4', '13', '12', '2013-02-26 14:41:10'),
(462, 1, 1, 1, '2+1+1', '4', '12', '2013-02-26 14:41:10'),
(463, 1, 1, 1, '2+9+10', '21', '12', '2013-02-26 14:41:10'),
(464, 1, 1, 1, '1+7+7', '15', '12', '2013-02-26 14:41:10'),
(465, 1, 1, 1, '1+9+3', '13', '12', '2013-02-26 14:41:10'),
(466, 1, 1, 1, '2+1+5', '8', '12', '2013-02-26 14:41:10'),
(467, 1, 1, 1, '1+1+4', '6', '12', '2013-02-26 14:41:10'),
(468, 1, 1, 1, '1+5+7', '13', '12', '2013-02-26 14:41:10'),
(469, 1, 1, 2, '3*3-4', '5', '12', '2013-02-26 14:41:10'),
(470, 1, 1, 2, '3*3-4', '5', '12', '2013-02-26 14:41:10'),
(471, 1, 1, 2, '5*1-1', '4', '12', '2013-02-26 14:41:10'),
(472, 1, 1, 2, '2*2-3', '1', '12', '2013-02-26 14:41:10'),
(473, 1, 1, 2, '4*1-1', '3', '12', '2013-02-26 14:41:10'),
(474, 1, 1, 2, '2*5-2', '8', '12', '2013-02-26 14:41:10'),
(475, 1, 1, 2, '4*3-1', '11', '12', '2013-02-26 14:41:10'),
(476, 1, 1, 2, '4*2-2', '6', '12', '2013-02-26 14:41:10'),
(477, 1, 1, 2, '3*3-3', '6', '12', '2013-02-26 14:41:10'),
(478, 1, 1, 2, '2*5-2', '8', '12', '2013-02-26 14:41:10'),
(497, 1, 1, 1, '1+10+5', '16', '123', '2013-02-26 15:30:49'),
(498, 1, 1, 1, '2+3+7', '12', '123', '2013-02-26 15:30:49'),
(499, 1, 1, 1, '2+6+9', '17', '123', '2013-02-26 15:30:49'),
(500, 1, 1, 1, '1+8+1', '10', '231', '2013-02-26 15:30:49'),
(501, 1, 1, 1, '2+7+8', '17', '2312', '2013-02-26 15:30:49'),
(502, 1, 1, 1, '2+10+2', '14', '312', '2013-02-26 15:30:49'),
(503, 1, 1, 1, '1+6+10', '17', '12', '2013-02-26 15:30:49'),
(504, 1, 1, 1, '1+7+7', '15', '312', '2013-02-26 15:30:49'),
(505, 1, 1, 1, '2+2+2', '6', '31', '2013-02-26 15:30:49'),
(506, 1, 1, 1, '2+2+6', '10', '23', '2013-02-26 15:30:49'),
(507, 1, 1, 2, '4&times;2-4', '4', '23', '2013-02-26 15:30:49'),
(508, 1, 1, 2, '1&times;5-4', '1', '123', '2013-02-26 15:30:49'),
(509, 1, 1, 2, '5&times;3-4', '11', '12', '2013-02-26 15:30:49'),
(510, 1, 1, 2, '4&times;1-3', '1', '312', '2013-02-26 15:30:49'),
(511, 1, 1, 2, '2&times;4-3', '5', '312', '2013-02-26 15:30:49'),
(512, 1, 1, 2, '5&times;4-5', '15', '312', '2013-02-26 15:30:49'),
(513, 1, 1, 2, '2&times;2-5', '-1', '312', '2013-02-26 15:30:49'),
(514, 1, 1, 2, '5&times;1-1', '4', '3', '2013-02-26 15:30:49'),
(515, 1, 1, 2, '5&times;4-4', '16', '12', '2013-02-26 15:30:49'),
(516, 1, 1, 2, '1&times;1-2', '-1', '3', '2013-02-26 15:30:49'),
(546, 1, 1, 1, '1+7+8', '16', '16', '2013-02-28 01:11:03'),
(547, 1, 1, 1, '1+8+8', '17', '17', '2013-02-28 01:11:03'),
(548, 1, 1, 1, '2+7+2', '11', '11', '2013-02-28 01:11:03'),
(549, 1, 1, 1, '1+3+5', '9', '9', '2013-02-28 01:11:03'),
(550, 1, 1, 1, '1+5+10', '16', '16', '2013-02-28 01:11:03'),
(551, 1, 1, 1, '2+5+9', '16', '16', '2013-02-28 01:11:03'),
(552, 1, 1, 1, '2+6+9', '17', '17', '2013-02-28 01:11:03'),
(553, 1, 1, 1, '2+9+9', '20', '20', '2013-02-28 01:11:03'),
(554, 1, 1, 1, '2+8+2', '12', '12', '2013-02-28 01:11:03'),
(555, 1, 1, 1, '2+8+8', '18', '18', '2013-02-28 01:11:03'),
(556, 1, 1, 2, '5&times;3&times;2', '30', '30', '2013-02-28 01:11:03'),
(557, 1, 1, 2, '5&times;5&times;1', '25', '25', '2013-02-28 01:11:03'),
(558, 1, 1, 2, '1&times;2+1', '3', '3', '2013-02-28 01:11:03'),
(559, 1, 1, 2, '4&times;1+1', '5', '5', '2013-02-28 01:11:03'),
(560, 1, 1, 2, '2&times;5+3', '13', '13', '2013-02-28 01:11:03'),
(561, 1, 1, 2, '5&times;2&times;5', '50', '50', '2013-02-28 01:11:03'),
(562, 1, 1, 2, '5&times;5+2', '27', '27', '2013-02-28 01:11:03'),
(563, 1, 1, 2, '2&times;5+1', '11', '11', '2013-02-28 01:11:03'),
(564, 1, 1, 2, '3&times;4&times;2', '24', '24', '2013-02-28 01:11:03'),
(565, 1, 1, 2, '3&times;4-2', '10', '10', '2013-02-28 01:11:03'),
(571, 1, 1, 1, '2+2+4', '8', '8', '2013-02-28 01:43:40'),
(572, 1, 1, 1, '1+5+9', '15', '15', '2013-02-28 01:43:40'),
(573, 1, 1, 1, '1+9+8', '18', '18', '2013-02-28 01:43:40'),
(574, 1, 1, 1, '2+8+1', '11', '11', '2013-02-28 01:43:40'),
(575, 1, 1, 1, '2+10+2', '14', '14', '2013-02-28 01:43:40'),
(576, 1, 1, 1, '2+10+1', '13', '13', '2013-02-28 01:43:40'),
(577, 1, 1, 1, '1+1+1', '3', '3', '2013-02-28 01:43:40'),
(578, 1, 1, 1, '1+3+7', '11', '11', '2013-02-28 01:43:40'),
(579, 1, 1, 1, '1+3+8', '12', '12', '2013-02-28 01:43:40'),
(580, 1, 1, 1, '2+3+9', '14', '14', '2013-02-28 01:43:40'),
(581, 1, 1, 2, '4&times;2&times;1', '8', '8', '2013-02-28 01:43:40'),
(582, 1, 1, 2, '4&times;3+1', '13', '13', '2013-02-28 01:43:40'),
(583, 1, 1, 2, '3&times;5&times;1', '15', '15', '2013-02-28 01:43:40'),
(584, 1, 1, 2, '3&times;1+1', '4', '4', '2013-02-28 01:43:40'),
(585, 1, 1, 2, '4&times;3&times;4', '48', '48', '2013-02-28 01:43:40'),
(586, 1, 1, 2, '4&times;2&times;3', '24', '24', '2013-02-28 01:43:40'),
(587, 1, 1, 2, '1&times;3+2', '5', '-1', '2013-02-28 01:43:40'),
(588, 1, 1, 2, '3&times;5-1', '14', '-1', '2013-02-28 01:43:40'),
(589, 1, 1, 2, '4&times;1-2', '2', '-1', '2013-02-28 01:43:40'),
(590, 1, 1, 2, '5&times;2&times;2', '20', '-1', '2013-02-28 01:43:40'),
(591, 4, 1, 1, '2+10+2', '14', '14', '2013-02-28 10:33:04'),
(592, 4, 1, 1, '2+9+1', '12', '12', '2013-02-28 10:33:04'),
(593, 4, 1, 1, '1+2+9', '12', '12', '2013-02-28 10:33:04'),
(594, 4, 1, 1, '2+2+8', '12', '12', '2013-02-28 10:33:04'),
(595, 4, 1, 1, '1+7+4', '12', '12', '2013-02-28 10:33:04'),
(596, 4, 1, 1, '1+3+5', '9', '9', '2013-02-28 10:33:04'),
(597, 4, 1, 1, '1+10+6', '17', '17', '2013-02-28 10:33:04'),
(598, 4, 1, 1, '1+10+5', '16', '16', '2013-02-28 10:33:04'),
(599, 4, 1, 1, '1+3+6', '10', '10', '2013-02-28 10:33:04'),
(600, 4, 1, 1, '2+9+2', '13', '13', '2013-02-28 10:33:04'),
(601, 4, 1, 2, '2&times;1&times;3', '6', '6', '2013-02-28 10:33:04'),
(602, 4, 1, 2, '1&times;2&times;1', '2', '2', '2013-02-28 10:33:04'),
(603, 4, 1, 2, '4&times;1-3', '1', '1', '2013-02-28 10:33:04'),
(604, 4, 1, 2, '5&times;3&times;1', '15', '14', '2013-02-28 10:33:04'),
(605, 4, 1, 2, '1&times;3&times;2', '6', '6', '2013-02-28 10:33:04'),
(606, 4, 1, 2, '2&times;5&times;3', '30', '27', '2013-02-28 10:33:04'),
(607, 4, 1, 2, '5&times;1&times;5', '25', '25', '2013-02-28 10:33:04'),
(608, 4, 1, 2, '5&times;2+4', '14', '14', '2013-02-28 10:33:04'),
(609, 4, 1, 2, '5&times;1&times;4', '20', '20', '2013-02-28 10:33:04'),
(610, 4, 1, 2, '5&times;3-2', '13', '13', '2013-02-28 10:33:04'),
(611, 1, 2, 3, '10&times;10+29=93', 'Falsch', 'Falsch', '2013-02-28 12:44:34'),
(612, 1, 2, 3, '8&times;10-49=35', 'Falsch', 'Falsch', '2013-02-28 12:44:34'),
(613, 1, 2, 3, '5&times;5+46=85', 'Falsch', 'Falsch', '2013-02-28 12:44:34'),
(614, 1, 2, 3, '6&times;1&times;12=89', 'Falsch', 'Falsch', '2013-02-28 12:44:34'),
(615, 1, 2, 3, '9&times;7+10=77', 'Falsch', 'Falsch', '2013-02-28 12:44:34');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `variable`
--

CREATE TABLE IF NOT EXISTS `variable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(1) NOT NULL,
  `lowerbound` int(11) NOT NULL,
  `upperbound` int(11) NOT NULL,
  `assignmentid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Daten für Tabelle `variable`
--

INSERT INTO `variable` (`id`, `name`, `lowerbound`, `upperbound`, `assignmentid`) VALUES
(1, 'a', 1, 2, 1),
(2, 'b', 1, 10, 1),
(3, 'c', 1, 10, 1),
(4, 'a', 1, 5, 2),
(5, 'b', 1, 5, 2),
(7, 'c', 1, 5, 2),
(8, 'a', 5, 10, 3),
(9, 'b', 1, 10, 3),
(10, 'c', 10, 50, 3);
