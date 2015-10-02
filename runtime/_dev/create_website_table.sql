# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Hôte: imac.local (MySQL 5.6.26)
# Base de données: yii2print
# Temps de génération: 2015-10-01 13:49:36 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Affichage de la table website_order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `website_order`;

CREATE TABLE `website_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) DEFAULT NULL,
  `order_id` varchar(40) DEFAULT NULL,
  `order_name` varchar(80) NOT NULL,
  `order_date` varchar(40) DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `company` varchar(80) DEFAULT NULL,
  `address` varchar(160) DEFAULT NULL,
  `city` varchar(80) DEFAULT NULL,
  `vat` varchar(40) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `delivery` varchar(40) DEFAULT NULL,
  `promocode` varchar(40) DEFAULT NULL,
  `clientcode` varchar(40) DEFAULT NULL,
  `comment` varchar(160) DEFAULT NULL,
  `rawjson` text NOT NULL,
  `convert_errors` text,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_name` (`order_name`),
  KEY `document_id_idx` (`document_id`),
  CONSTRAINT `website_order_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `document` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Affichage de la table website_order_line
# ------------------------------------------------------------

DROP TABLE IF EXISTS `website_order_line`;

CREATE TABLE `website_order_line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_order_id` int(11) NOT NULL,
  `filename` varchar(80) DEFAULT NULL,
  `finish` varchar(20) DEFAULT NULL,
  `profile` varchar(20) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `format` varchar(20) DEFAULT NULL,
  `comment` varchar(160) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `website_order_id_idxfk` (`website_order_id`),
  CONSTRAINT `website_order_line_ibfk_1` FOREIGN KEY (`website_order_id`) REFERENCES `website_order` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
