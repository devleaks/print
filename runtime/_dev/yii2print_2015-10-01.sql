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

LOCK TABLES `website_order` WRITE;
/*!40000 ALTER TABLE `website_order` DISABLE KEYS */;

INSERT INTO `website_order` (`id`, `document_id`, `order_id`, `order_name`, `order_date`, `name`, `company`, `address`, `city`, `vat`, `phone`, `email`, `promocode`, `clientcode`, `comment`, `rawjson`, `convert_errors`, `status`, `created_at`, `updated_at`)
VALUES
	(1,1384,NULL,'30-09-2015_7df5657617c1fba5879ced392f2630e4','30-09-2015','COENE Elliot','E-telier','test 4','test test','be 0821 228 922','0475','elliot.coene@e-telier.be','PRO-MO','','Koerier - Niks','{\r\n	\"date\":\"30-09-2015\",\r\n	\"name\":\"COENE Elliot\",\r\n	\"company\":\"E-telier\",\r\n	\"address\":\"test 4\",\r\n	\"city\":\"test test\",\r\n	\"vat\":\"be 0821 228 922\",\r\n	\"client\":\"\",\r\n	\"language\":\"nl\",\r\n	\"phone\":\"0475\",\r\n	\"email\":\"elliot.coene@e-telier.be\",\r\n	\"comments\":\"Koerier - Niks\",\r\n	\"promocode\":\"PRO-MO\",\r\n	\"products\":[\r\n	\r\n		{\r\n			\"filename\": \"test 1 : 50x75 / 5 / Mat / Pro\",\r\n			\"format\": \"50x75\",\r\n			\"quantity\": 5,\r\n			\"finish\": \"Mat\",\r\n			\"profile\": \"PRO\",\r\n			\"comments\": \"test 1 : 50x75 / 5 / Mat / Pro\"\r\n		}\r\n				,\r\n		{\r\n			\"filename\": \"test 2 : 50x50 / 2 / Mat / Ja\",\r\n			\"format\": \"50x50\",\r\n			\"quantity\": 2,\r\n			\"finish\": \"Mat\",\r\n			\"profile\": \"Ja\",\r\n			\"comments\": \"test 2 : 50x50 / 2 / Mat / Ja\"\r\n		}\r\n				,\r\n		{\r\n			\"filename\": \"test 3 : 30x30 / 3 / Zilver mat / PRO\",\r\n			\"format\": \"30x30\",\r\n			\"quantity\": 3,\r\n			\"finish\": \"Zilver mat\",\r\n			\"profile\": \"PRO\",\r\n			\"comments\": \"test 3 : 30x30 / 3 / Zilver mat / PRO\"\r\n		}\r\n				\r\n	]	\r\n}',NULL,'CLOSED','2015-10-01 14:31:08','2015-10-01 15:31:56'),
	(2,1385,'151000001','01-10-2015_0eb04d80b1df3d3048c3e6799f267aa4','01-10-2015','COENE Elliot','E-telier','test 4','test test','be 0821 228 922','0475','elliot.coene@e-telier.be','PRO-MO','','Koerier - Niks','{\r\n	\"date\":\"01-10-2015\",\r\n	\"order_id\":\"151000001\",\r\n	\"name\":\"COENE Elliot\",\r\n	\"company\":\"E-telier\",\r\n	\"address\":\"test 4\",\r\n	\"city\":\"test test\",\r\n	\"vat\":\"be 0821 228 922\",\r\n	\"client\":\"\",\r\n	\"language\":\"nl\",\r\n	\"phone\":\"0475\",\r\n	\"email\":\"elliot.coene@e-telier.be\",\r\n	\"comments\":\"Koerier - Niks\",\r\n	\"promocode\":\"PRO-MO\",\r\n	\"products\":[\r\n	\r\n		{\r\n			\"filename\": \"test 1 : 40x60 / 1 / Glanzend / Nee\",\r\n			\"format\": \"40x60\",\r\n			\"quantity\": 1,\r\n			\"finish\": \"Glanzend\",\r\n			\"profile\": \"Nee\",\r\n			\"comments\": \"test 1 : 40x60 / 1 / Glanzend / Nee\"\r\n		}\r\n				,\r\n		{\r\n			\"filename\": \"test 2 : 50x50 / 2 / Mat / Ja\",\r\n			\"format\": \"50x50\",\r\n			\"quantity\": 2,\r\n			\"finish\": \"Mat\",\r\n			\"profile\": \"Ja\",\r\n			\"comments\": \"test 2 : 50x50 / 2 / Mat / Ja\"\r\n		}\r\n				,\r\n		{\r\n			\"filename\": \"test 3 : 30x30 / 3 / Zilver mat / PRO\",\r\n			\"format\": \"30x30\",\r\n			\"quantity\": 3,\r\n			\"finish\": \"Zilver mat\",\r\n			\"profile\": \"PRO\",\r\n			\"comments\": \"test 3 : 30x30 / 3 / Zilver mat / PRO\"\r\n		}\r\n				\r\n	]	\r\n}',NULL,'CLOSED','2015-10-01 15:15:04','2015-10-01 15:31:56');

/*!40000 ALTER TABLE `website_order` ENABLE KEYS */;
UNLOCK TABLES;


# Affichage de la table website_order_line
# ------------------------------------------------------------

DROP TABLE IF EXISTS `website_order_line`;

CREATE TABLE `website_order_line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_order_id` int(11) NOT NULL,
  `filename` varchar(80) DEFAULT NULL,
  `finish` varchar(20) DEFAULT NULL,
  `profile_bool` tinyint(4) DEFAULT NULL,
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

LOCK TABLES `website_order_line` WRITE;
/*!40000 ALTER TABLE `website_order_line` DISABLE KEYS */;

INSERT INTO `website_order_line` (`id`, `website_order_id`, `filename`, `finish`, `profile_bool`, `quantity`, `format`, `comment`, `status`, `created_at`, `updated_at`)
VALUES
	(1,1,'test 1 : 50x75 / 5 / Mat / Pro','Mat',0,5,'50x75','test 1 : 50x75 / 5 / Mat / Pro',NULL,'2015-10-01 14:31:08','2015-10-01 14:31:08'),
	(2,1,'test 2 : 50x50 / 2 / Mat / Ja','Mat',1,2,'50x50','test 2 : 50x50 / 2 / Mat / Ja',NULL,'2015-10-01 14:31:08','2015-10-01 14:31:08'),
	(3,1,'test 3 : 30x30 / 3 / Zilver mat / PRO','Zilver mat',0,3,'30x30','test 3 : 30x30 / 3 / Zilver mat / PRO',NULL,'2015-10-01 14:31:08','2015-10-01 14:31:08'),
	(4,2,'test 1 : 40x60 / 1 / Glanzend / Nee','Glanzend',0,1,'40x60','test 1 : 40x60 / 1 / Glanzend / Nee',NULL,'2015-10-01 15:15:04','2015-10-01 15:15:04'),
	(5,2,'test 2 : 50x50 / 2 / Mat / Ja','Mat',1,2,'50x50','test 2 : 50x50 / 2 / Mat / Ja',NULL,'2015-10-01 15:15:04','2015-10-01 15:15:04'),
	(6,2,'test 3 : 30x30 / 3 / Zilver mat / PRO','Zilver mat',0,3,'30x30','test 3 : 30x30 / 3 / Zilver mat / PRO',NULL,'2015-10-01 15:15:04','2015-10-01 15:15:04');

/*!40000 ALTER TABLE `website_order_line` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
