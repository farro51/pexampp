-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2014 at 11:50 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pony_mayo_11`
--

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE IF NOT EXISTS `agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(100) NOT NULL,
  `password` varchar(256) NOT NULL,
  `name` varchar(45) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `status` enum('logged','unlogged') NOT NULL,
  `last_position_lat` double DEFAULT NULL,
  `last_position_lon` double DEFAULT NULL,
  `last_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`id`, `mail`, `password`, `name`, `phone`, `status`, `last_position_lat`, `last_position_lon`, `last_update`) VALUES
(1, 'f@gmail.com', '8fa14cdd754f91cc6554c9e71929cce7', 'federico arroyave', '3273774779', 'logged', 45.075597, 7.64406, '2013-11-16 12:34:26'),
(2, '', '62bdcf34476056d1bbb117e05fe53c22', 'alfonsino', '3896230785', 'unlogged', 45.0669537, NULL, '2013-11-19 03:16:54'),
(3, 'admin@ponyexpress.com', 'admin', 'admin', '1234567890', 'unlogged', NULL, NULL, '2014-05-17 14:44:28'),
(4, 'federico@ponyexpress.com', '616706c4d6f7bdf68b30893f860cbb2b', 'alfonsintintin', '3273774779', 'unlogged', 45.051499, 7.674659, '2014-05-21 18:28:49'),
(5, 'admin@ponyexpress.com', '21232f297a57a5a743894a0e4a801fc3', 'admin', '3124567890', 'unlogged', NULL, NULL, '2014-06-02 14:50:20'),
(6, 'root', '63a9f0ea7bb98050796b649e85481845', 'root', '1234567890', 'unlogged', NULL, NULL, '2014-06-02 15:23:58');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE IF NOT EXISTS `delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tracking_code` varchar(45) NOT NULL,
  `delivery_code` varchar(45) NOT NULL,
  `sender_address` varchar(100) NOT NULL,
  `s_address_lon` double DEFAULT NULL,
  `s_address_lat` double DEFAULT NULL,
  `sender_info` varchar(145) DEFAULT NULL,
  `sender_email` varchar(100) NOT NULL,
  `recipient_address` varchar(100) NOT NULL,
  `r_address_lon` double DEFAULT NULL,
  `r_address_lat` double DEFAULT NULL,
  `recipient_info` varchar(145) DEFAULT NULL,
  `recipient_email` varchar(100) NOT NULL,
  `state` enum('waiting','delivering','delivered') NOT NULL,
  `submission_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pickup_time` int(15) DEFAULT NULL,
  `delivery_time` int(15) DEFAULT NULL,
  `recip_sign` varchar(150) DEFAULT NULL,
  `pickup_time_est` int(15) DEFAULT NULL,
  `delivery_time_est` int(15) DEFAULT NULL,
  `agent_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_delivery_agent_idx` (`agent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`id`, `tracking_code`, `delivery_code`, `sender_address`, `s_address_lon`, `s_address_lat`, `sender_info`, `sender_email`, `recipient_address`, `r_address_lon`, `r_address_lat`, `recipient_info`, `recipient_email`, `state`, `submission_time`, `pickup_time`, `delivery_time`, `recip_sign`, `pickup_time_est`, `delivery_time_est`, `agent_id`) VALUES
(8, '1234', '2345', 'via magenta 51', NULL, NULL, 'federico', 'farroyave51@gmail.com', 'via gorizia 96', NULL, NULL, 'Cristina ', 'malevilema@gmail.com', 'delivered', '2014-08-12 08:49:14', NULL, 0, NULL, 1407834866, 1407940831, 1),
(9, '6789', '3456', 'via magenta 51', NULL, NULL, 'f', 'f@f.com', 'via gorizia 6', NULL, NULL, 's', 's@s.com', 'delivered', '2014-08-13 13:35:30', 0, 0, NULL, 1407938443, 1407939083, 1),
(10, '3dc8', '6921', 'via magenta 51', NULL, NULL, 'fede', 'fa@g.co', 'via gorizia 96', NULL, NULL, 'g', 'g@f.co', 'delivered', '2014-08-13 15:13:54', 1407951313, 1407952954, NULL, 1407944346, 1407953887, 1),
(11, '56d5', 'f39e', 'via magenta 5', NULL, NULL, 'carol', 'c@g.com', 'via gorizia 6', NULL, NULL, 'curi', 'c@g.co', 'waiting', '2014-08-13 18:16:00', 1407969892, NULL, NULL, 1407956143, 1408033532, 1),
(12, '6040', 'dcb6', 'corso sebastopoli 50', NULL, NULL, 'otro', 'otro@o.co', 'via gorizia 51', NULL, NULL, 'uno', 'dos@tres.com', 'waiting', '2014-08-13 18:17:32', NULL, NULL, NULL, 1408100095, 1408101519, 1),
(13, '2@5a1', '4o9@0', 'via magenta 51', NULL, NULL, 'fee', 'farro@gmail.com', 'via gorizia 9', NULL, NULL, 'feo', 'f@g.co', 'waiting', '2014-08-15 09:15:52', NULL, NULL, NULL, 1408096928, 1408101519, 1),
(14, '3e3i3', '1o9@0', 'corso sebastopoli 11', NULL, NULL, 'fede', 'farroyave51@gmail.co', 'via magenta 1', NULL, NULL, 'oto', 'malevilema@gmail.com', 'waiting', '2014-08-15 09:18:53', NULL, NULL, NULL, 1408099920, 1408104401, 1),
(15, 'rm', '@c', 'Corso Francia, 165', NULL, NULL, 'otro', 'otro@gmail.com', 'Corso Re Umberto, 27', NULL, NULL, 'otro', 'otro@gmail.com', 'delivering', '2014-08-15 09:29:59', 1408095319, NULL, NULL, 1408095197, 1408097716, 1);

-- --------------------------------------------------------

--
-- Table structure for table `path_agent`
--

CREATE TABLE IF NOT EXISTS `path_agent` (
  `id_agent` int(11) NOT NULL,
  `p_order` int(2) NOT NULL,
  `id_delivery` int(10) NOT NULL,
  `pick_up` int(2) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `arrival_time_est` int(12) NOT NULL,
  PRIMARY KEY (`id_delivery`,`pick_up`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `path_agent`
--

INSERT INTO `path_agent` (`id_agent`, `p_order`, `id_delivery`, `pick_up`, `latitude`, `longitude`, `arrival_time_est`) VALUES
(1, 5, 12, 0, 45.0501, 7.6414, 6200),
(1, 4, 12, 1, 45.0407, 7.65772, 4776),
(1, 3, 14, 1, 45.0398, 7.66045, 4601),
(1, 2, 15, 0, 45.0626, 7.67301, 2397),
(1, 1, 13, 1, 45.067, 7.66428, 1609),
(1, 6, 13, 0, 45.0501, 7.6414, 6200),
(1, 7, 14, 0, 45.0625, 7.67667, 9082);

--
-- Triggers `path_agent`
--
DROP TRIGGER IF EXISTS `path_agent_AINS`;
DELIMITER //
CREATE TRIGGER `path_agent_AINS` AFTER INSERT ON `path_agent`
 FOR EACH ROW -- Edit trigger body code below this line. Do not edit lines above this one
IF NEW.pick_up = 1 THEN
	UPDATE delivery SET pickup_time_est = UNIX_TIMESTAMP() + NEW.arrival_time_est WHERE id = NEW.id_delivery;
ELSE
	UPDATE delivery SET delivery_time_est = UNIX_TIMESTAMP() + NEW.arrival_time_est WHERE id = NEW.id_delivery;
END IF
//
DELIMITER ;
DROP TRIGGER IF EXISTS `path_agent_AUPD`;
DELIMITER //
CREATE TRIGGER `path_agent_AUPD` AFTER UPDATE ON `path_agent`
 FOR EACH ROW -- Edit trigger body code below this line. Do not edit lines above this one
IF NEW.pick_up = 1 THEN
	UPDATE delivery SET pickup_time_est = UNIX_TIMESTAMP() + NEW.arrival_time_est WHERE id = NEW.id_delivery;
ELSE
	UPDATE delivery SET delivery_time_est = UNIX_TIMESTAMP() + NEW.arrival_time_est WHERE id = NEW.id_delivery;
END IF
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `text`) VALUES
(1, 'Is the packet in a good condition?'),
(2, 'Delivery time is related to the estimated time?'),
(3, 'What is the total score for the quality of the service?');

-- --------------------------------------------------------

--
-- Table structure for table `question_response`
--

CREATE TABLE IF NOT EXISTS `question_response` (
  `questionnaire_id` int(11) NOT NULL,
  `vote` enum('0','1','2','3','4','5') DEFAULT NULL,
  `question_id` int(11) NOT NULL,
  PRIMARY KEY (`questionnaire_id`,`question_id`),
  KEY `fk_question_response_question1_idx` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `fk_delivery_agent` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `question_response`
--
ALTER TABLE `question_response`
  ADD CONSTRAINT `fk_question_response_question1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
