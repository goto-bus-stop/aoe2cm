-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: wm91.wedos.net:3306
-- Generation Time: Mar 19, 2016 at 10:57 PM
-- Server version: 5.6.23
-- PHP Version: 5.4.23

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `d104513_aoecm`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `current_turn`
--
DROP VIEW IF EXISTS `current_turn`;
CREATE TABLE IF NOT EXISTS `current_turn` (
`game_id` int(11)
,`current_turn` smallint(6)
);
-- --------------------------------------------------------

--
-- Table structure for table `game`
--

DROP TABLE IF EXISTS `game`;
CREATE TABLE IF NOT EXISTS `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `preset_id` int(11) DEFAULT NULL,
  `aoe_version` int(11) NOT NULL DEFAULT '1',
  `state` tinyint(4) NOT NULL DEFAULT '0',
  `code` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `date_started` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `fk_preset` (`preset_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1013 ;

-- --------------------------------------------------------

--
-- Table structure for table `preset`
--

DROP TABLE IF EXISTS `preset`;
CREATE TABLE IF NOT EXISTS `preset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `state` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `preset_item`
--

DROP TABLE IF EXISTS `preset_item`;
CREATE TABLE IF NOT EXISTS `preset_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `preset_id` int(11) NOT NULL,
  `datai` int(11) DEFAULT NULL,
  `datas` text,
  PRIMARY KEY (`id`),
  KEY `fk_preset_id` (`preset_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83 ;

-- --------------------------------------------------------

--
-- Table structure for table `shortcut`
--

DROP TABLE IF EXISTS `shortcut`;
CREATE TABLE IF NOT EXISTS `shortcut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(63) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `query` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `tournament`
--

DROP TABLE IF EXISTS `tournament`;
CREATE TABLE IF NOT EXISTS `tournament` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` smallint(6) NOT NULL DEFAULT '0',
  `name` varchar(63) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `code` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tournament_data`
--

DROP TABLE IF EXISTS `tournament_data`;
CREATE TABLE IF NOT EXISTS `tournament_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tournament_id` int(11) NOT NULL DEFAULT '0',
  `html` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `TournamentFK` (`tournament_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `turn`
--

DROP TABLE IF EXISTS `turn`;
CREATE TABLE IF NOT EXISTS `turn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `turn_no` smallint(6) NOT NULL,
  `time_created` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `civ` smallint(6) DEFAULT NULL,
  `player` tinyint(4) NOT NULL DEFAULT '-1',
  `action` tinyint(4) NOT NULL DEFAULT '0',
  `hidden` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `game_id` (`game_id`,`turn_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2781 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `session_id` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_usergame` (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1473 ;

-- --------------------------------------------------------

--
-- Structure for view `current_turn`
--
DROP TABLE IF EXISTS `current_turn`;

CREATE VIEW `current_turn` AS select `turn`.`game_id` AS `game_id`,max(`turn`.`turn_no`) AS `current_turn` from `turn` group by `turn`.`game_id`;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `game`
--
ALTER TABLE `game`
  ADD CONSTRAINT `fk_preset` FOREIGN KEY (`preset_id`) REFERENCES `preset` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `preset_item`
--
ALTER TABLE `preset_item`
  ADD CONSTRAINT `fk_preset_id` FOREIGN KEY (`preset_id`) REFERENCES `preset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tournament_data`
--
ALTER TABLE `tournament_data`
  ADD CONSTRAINT `TournamentFK` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `turn`
--
ALTER TABLE `turn`
  ADD CONSTRAINT `fk_game` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_usergame` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
