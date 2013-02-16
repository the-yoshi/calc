-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 16. Februar 2013 um 21:06
-- Server Version: 5.5.8
-- PHP-Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


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
(2, 'guest', '084e0343a0486ff05530df6c705c8bb4', 'guest@gusti.com', 'schueler', 'gu', 'est'),
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
  `creator` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `assignment`
--

INSERT INTO `assignment` (`id`, `description`, `type`, `termscheme`, `creator`) VALUES
(1, 'TestAssignment', 'calc', 'a+b+c', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `assignment_variable`
--

CREATE TABLE IF NOT EXISTS `assignment_variable` (
  `assignmentid` int(11) NOT NULL,
  `variableid` int(11) NOT NULL,
  KEY `assignmentid` (`assignmentid`),
  KEY `variableid` (`variableid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `assignment_variable`
--

INSERT INTO `assignment_variable` (`assignmentid`, `variableid`) VALUES
(1, 1),
(1, 2),
(1, 3);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `exam`
--

INSERT INTO `exam` (`id`, `name`, `duration`, `durationtype`, `creator`, `lowerboundz`, `upperboundz`) VALUES
(1, 'TestUebung', 10, 'assignments', 1, 1, 100);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `exam_assignments`
--

CREATE TABLE IF NOT EXISTS `exam_assignments` (
  `examid` int(11) NOT NULL,
  `assignmentid` int(11) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `exam_assignments`
--

INSERT INTO `exam_assignments` (`examid`, `assignmentid`, `count`) VALUES
(1, 1, 10);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

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
(31, 4, 1, 1, '6+7+10', '23', '23', '2013-02-16 19:54:08');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `variable`
--

CREATE TABLE IF NOT EXISTS `variable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(1) NOT NULL,
  `lowerbound` int(11) NOT NULL,
  `upperbound` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `variable`
--

INSERT INTO `variable` (`id`, `name`, `lowerbound`, `upperbound`) VALUES
(1, 'a', 1, 10),
(2, 'b', 1, 10),
(3, 'c', 1, 10);
