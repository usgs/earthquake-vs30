# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.0.77-community-log)
# Database: ehpdb
# Generation Time: 2016-11-08 22:25:50 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table vs30_us
# ------------------------------------------------------------

CREATE TABLE `vs30_us` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `index` varchar(16) default NULL,
  `net_sta` varchar(16) default NULL,
  `name` varchar(255) default NULL,
  `lat` double default NULL,
  `lng` double default NULL,
  `datum` varchar(16) default NULL,
  `vs30` decimal(10,1) default NULL,
  `method` varchar(255) default NULL,
  `contact` varchar(64) default NULL,
  `rep` varchar(64) default NULL,
  `reference` text,
  `url` varchar(255) default NULL,
  `d_max` decimal(10,1) default NULL,
  `s_geology` text,
  `src_geology` text,
  `comments` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
