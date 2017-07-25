# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Hôte: imac.local (MySQL 5.6.26)
# Base de données: yii2print
# Temps de génération: 2017-07-25 14:19:56 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Affichage de la table account
# ------------------------------------------------------------

DROP TABLE IF EXISTS `account`;

CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `cash_id` int(11) DEFAULT NULL,
  `bank_transaction_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` datetime DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `note` varchar(160) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id_idx` (`client_id`),
  KEY `created_by_idx` (`created_by`),
  KEY `updated_by_idx` (`updated_by`),
  KEY `bank_transaction_id_idxfk` (`bank_transaction_id`),
  KEY `cash_id_idxfk_1` (`cash_id`),
  CONSTRAINT `account_ibfk_81` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  CONSTRAINT `account_ibfk_83` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  CONSTRAINT `account_ibfk_84` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`),
  CONSTRAINT `account_ibfk_92` FOREIGN KEY (`bank_transaction_id`) REFERENCES `bank_transaction` (`id`),
  CONSTRAINT `account_ibfk_93` FOREIGN KEY (`cash_id`) REFERENCES `cash` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table accounting_journal
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accounting_journal`;

CREATE TABLE `accounting_journal` (
  `code` varchar(40) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table backup
# ------------------------------------------------------------

DROP TABLE IF EXISTS `backup`;

CREATE TABLE `backup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(250) NOT NULL DEFAULT '',
  `note` varchar(160) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table bank_transaction
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bank_transaction`;

CREATE TABLE `bank_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `execution_date` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(20) NOT NULL,
  `source` varchar(40) NOT NULL,
  `note` varchar(160) DEFAULT NULL,
  `account` varchar(40) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table bi_line
# ------------------------------------------------------------

DROP VIEW IF EXISTS `bi_line`;

CREATE TABLE `bi_line` (
   `document_type` VARCHAR(20) NULL DEFAULT NULL,
   `date_year` VARCHAR(4) NULL DEFAULT NULL,
   `date_month` VARCHAR(2) NULL DEFAULT NULL,
   `pays` VARCHAR(80) NULL DEFAULT NULL,
   `lang` VARCHAR(20) NULL DEFAULT NULL,
   `work_width` FLOAT NULL DEFAULT NULL,
   `work_height` FLOAT NULL DEFAULT NULL,
   `unit_price` DECIMAL(10) NULL DEFAULT NULL,
   `quantity` FLOAT NOT NULL,
   `extra_type` VARCHAR(20) NULL DEFAULT NULL,
   `extra_amount` DECIMAL(10) NULL DEFAULT NULL,
   `extra_htva` DECIMAL(10) NULL DEFAULT NULL,
   `price_htva` DECIMAL(10) NULL DEFAULT NULL,
   `item_id` INT(11) NOT NULL DEFAULT '0',
   `categorie` VARCHAR(20) NULL DEFAULT NULL,
   `yii_category` VARCHAR(20) NULL DEFAULT NULL,
   `comptabilite` VARCHAR(20) NULL DEFAULT NULL
) ENGINE=MyISAM;



# Affichage de la table bi_sale
# ------------------------------------------------------------

DROP VIEW IF EXISTS `bi_sale`;

CREATE TABLE `bi_sale` (
   `document_type` VARCHAR(20) NULL DEFAULT NULL,
   `created_at` DATETIME NULL DEFAULT NULL,
   `updated_at` DATETIME NULL DEFAULT NULL,
   `due_date` DATETIME NOT NULL,
   `date_year` VARCHAR(4) NULL DEFAULT NULL,
   `date_month` VARCHAR(2) NULL DEFAULT NULL,
   `price_htva` DECIMAL(10) NULL DEFAULT NULL,
   `client_fn` VARCHAR(80) NULL DEFAULT NULL,
   `client_ln` VARCHAR(80) NOT NULL,
   `client_an` VARCHAR(80) NULL DEFAULT NULL,
   `country` VARCHAR(80) NULL DEFAULT NULL,
   `language` VARCHAR(20) NULL DEFAULT NULL,
   `client_id` INT(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM;



# Affichage de la table bi_work
# ------------------------------------------------------------

DROP VIEW IF EXISTS `bi_work`;

CREATE TABLE `bi_work` (
   `date_start` DATETIME NULL DEFAULT NULL,
   `date_finish` DATETIME NULL DEFAULT NULL,
   `task_name` VARCHAR(80) NULL DEFAULT NULL,
   `categorie` VARCHAR(20) NULL DEFAULT NULL,
   `yii_category` VARCHAR(20) NULL DEFAULT NULL
) ENGINE=MyISAM;



# Affichage de la table cash
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cash`;

CREATE TABLE `cash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) DEFAULT NULL,
  `sale` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` datetime DEFAULT NULL,
  `note` varchar(160) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_id_idx` (`document_id`),
  KEY `created_by_idx` (`created_by`),
  KEY `updated_by_idx` (`updated_by`),
  CONSTRAINT `cash_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `document` (`id`),
  CONSTRAINT `cash_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  CONSTRAINT `cash_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table client
# ------------------------------------------------------------

DROP TABLE IF EXISTS `client`;

CREATE TABLE `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_interne` varchar(80) DEFAULT NULL,
  `comptabilite` varchar(80) NOT NULL,
  `titre` varchar(80) DEFAULT NULL,
  `nom` varchar(80) NOT NULL,
  `prenom` varchar(80) DEFAULT NULL,
  `autre_nom` varchar(80) DEFAULT NULL,
  `adresse` varchar(80) DEFAULT NULL,
  `code_postal` varchar(80) DEFAULT NULL,
  `localite` varchar(80) DEFAULT NULL,
  `pays` varchar(80) DEFAULT NULL,
  `langue` varchar(80) DEFAULT NULL,
  `numero_tva` varchar(80) DEFAULT NULL,
  `lang` varchar(20) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `site_web` varchar(80) DEFAULT NULL,
  `domicile` varchar(80) DEFAULT NULL,
  `bureau` varchar(80) DEFAULT NULL,
  `gsm` varchar(80) DEFAULT NULL,
  `fax_prive` varchar(80) DEFAULT NULL,
  `fax_bureau` varchar(80) DEFAULT NULL,
  `pc` varchar(80) DEFAULT NULL,
  `autre` varchar(80) DEFAULT NULL,
  `remise` varchar(80) DEFAULT NULL,
  `escompte` varchar(80) DEFAULT NULL,
  `delais_de_paiement` varchar(80) DEFAULT NULL,
  `mentions` varchar(80) DEFAULT NULL,
  `exemplaires` varchar(80) DEFAULT NULL,
  `limite_de_credit` varchar(80) DEFAULT NULL,
  `formule` varchar(80) DEFAULT NULL,
  `type` varchar(80) DEFAULT NULL,
  `execution` varchar(80) DEFAULT NULL,
  `support` varchar(80) DEFAULT NULL,
  `format` varchar(80) DEFAULT NULL,
  `mise_a_jour` date DEFAULT NULL,
  `mailing` varchar(80) DEFAULT NULL,
  `outlook` varchar(80) DEFAULT NULL,
  `categorie_de_client` varchar(80) DEFAULT NULL,
  `operation` varchar(80) DEFAULT NULL,
  `categorie_de_prix_de_vente` varchar(80) DEFAULT NULL,
  `reference_1` varchar(80) DEFAULT NULL,
  `date_limite_1` varchar(80) DEFAULT NULL,
  `reference_2` varchar(80) DEFAULT NULL,
  `date_limite_2` varchar(80) DEFAULT NULL,
  `reference_3` varchar(80) DEFAULT NULL,
  `date_limite_3` varchar(80) DEFAULT NULL,
  `commentaires` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `comm_pref` varchar(20) DEFAULT NULL,
  `comm_format` varchar(20) DEFAULT NULL,
  `assujetti_tva` varchar(20) DEFAULT NULL,
  `numero_tva_norm` varchar(80) DEFAULT NULL,
  `non_assujetti_tva` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comptabilite` (`comptabilite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table client_nvb
# ------------------------------------------------------------

DROP TABLE IF EXISTS `client_nvb`;

CREATE TABLE `client_nvb` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT NULL,
  `no_nvb` varchar(40) DEFAULT NULL,
  `nom` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table document
# ------------------------------------------------------------

DROP TABLE IF EXISTS `document`;

CREATE TABLE `document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_type` varchar(20) DEFAULT NULL,
  `name` varchar(20) NOT NULL,
  `sale` int(11) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `reference` varchar(40) DEFAULT NULL,
  `reference_client` varchar(40) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `bill_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `due_date` datetime NOT NULL,
  `priority` int(11) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `lang` varchar(20) DEFAULT NULL,
  `legal` varchar(160) DEFAULT NULL,
  `price_htva` decimal(10,2) DEFAULT NULL,
  `price_tvac` decimal(10,2) DEFAULT NULL,
  `vat` decimal(10,2) DEFAULT NULL,
  `vat_bool` tinyint(1) DEFAULT NULL,
  `bom_bool` tinyint(1) DEFAULT NULL,
  `note` varchar(160) DEFAULT NULL,
  `credit_bool` tinyint(1) DEFAULT NULL,
  `notified_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id_idx` (`parent_id`),
  KEY `client_id_idx` (`client_id`),
  KEY `created_by_idx` (`created_by`),
  KEY `updated_by_idx` (`updated_by`),
  KEY `bill_id_idxfk` (`bill_id`),
  CONSTRAINT `document_ibfk_146` FOREIGN KEY (`parent_id`) REFERENCES `document` (`id`),
  CONSTRAINT `document_ibfk_147` FOREIGN KEY (`bill_id`) REFERENCES `document` (`id`),
  CONSTRAINT `document_ibfk_148` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  CONSTRAINT `document_ibfk_149` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  CONSTRAINT `document_ibfk_150` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table document_account_line
# ------------------------------------------------------------

DROP VIEW IF EXISTS `document_account_line`;

CREATE TABLE `document_account_line` (
   `document_id` INT(11) NOT NULL,
   `comptabilite` VARCHAR(20) NULL DEFAULT NULL,
   `taux_de_tva` DECIMAL(10) NULL DEFAULT NULL,
   `total_vat` DECIMAL(42) NULL DEFAULT NULL,
   `total_price_htva` DECIMAL(32) NULL DEFAULT NULL,
   `total_extra_htva` DECIMAL(32) NULL DEFAULT NULL,
   `total_htva` DECIMAL(33) NULL DEFAULT NULL,
   `total_vat_ctrl` DECIMAL(42) NULL DEFAULT NULL
) ENGINE=MyISAM;



# Affichage de la table document_archive
# ------------------------------------------------------------

DROP TABLE IF EXISTS `document_archive`;

CREATE TABLE `document_archive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_type` varchar(20) DEFAULT NULL,
  `name` varchar(20) NOT NULL,
  `sale` int(11) DEFAULT NULL,
  `reference` varchar(40) DEFAULT NULL,
  `reference_client` varchar(40) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `due_date` datetime NOT NULL,
  `price_htva` decimal(10,2) DEFAULT NULL,
  `price_tvac` decimal(10,2) NOT NULL,
  `vat` decimal(10,2) DEFAULT NULL,
  `vat_bool` tinyint(1) DEFAULT NULL,
  `bom_bool` tinyint(1) DEFAULT NULL,
  `note` varchar(160) DEFAULT NULL,
  `lang` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `legal` varchar(160) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `credit_bool` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table document_line
# ------------------------------------------------------------

DROP TABLE IF EXISTS `document_line`;

CREATE TABLE `document_line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `work_width` float DEFAULT NULL,
  `work_height` float DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `quantity` float NOT NULL,
  `extra_type` varchar(20) DEFAULT NULL,
  `extra_amount` decimal(10,2) DEFAULT NULL,
  `extra_htva` decimal(10,2) DEFAULT NULL,
  `price_htva` decimal(10,2) DEFAULT NULL,
  `price_tvac` decimal(10,2) DEFAULT NULL,
  `vat` decimal(10,2) DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `location` varchar(40) DEFAULT NULL,
  `note` varchar(160) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_id_idxfk` (`document_id`),
  KEY `parent_id_idxfk` (`parent_id`),
  KEY `item_id_idx` (`item_id`),
  CONSTRAINT `document_line_ibfk_118` FOREIGN KEY (`document_id`) REFERENCES `document` (`id`),
  CONSTRAINT `document_line_ibfk_119` FOREIGN KEY (`parent_id`) REFERENCES `document_line` (`id`),
  CONSTRAINT `document_line_ibfk_120` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table document_line_detail
# ------------------------------------------------------------

DROP TABLE IF EXISTS `document_line_detail`;

CREATE TABLE `document_line_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_line_id` int(11) NOT NULL,
  `note` varchar(160) DEFAULT NULL,
  `chroma_id` int(11) DEFAULT NULL,
  `price_chroma` decimal(10,2) DEFAULT NULL,
  `corner_bool` tinyint(1) DEFAULT NULL,
  `price_corner` decimal(10,2) DEFAULT NULL,
  `renfort_bool` tinyint(1) DEFAULT NULL,
  `price_renfort` decimal(10,2) DEFAULT NULL,
  `frame_id` int(11) DEFAULT NULL,
  `price_frame` decimal(10,2) DEFAULT NULL,
  `montage_bool` tinyint(1) DEFAULT NULL,
  `price_montage` decimal(10,2) DEFAULT NULL,
  `finish_id` int(11) DEFAULT NULL,
  `support_id` int(11) DEFAULT NULL,
  `price_support` decimal(10,2) DEFAULT NULL,
  `tirage_id` int(11) DEFAULT NULL,
  `price_tirage` decimal(10,2) DEFAULT NULL,
  `collage_id` int(11) DEFAULT NULL,
  `price_collage` decimal(10,2) DEFAULT NULL,
  `protection_id` int(11) DEFAULT NULL,
  `price_protection` decimal(10,2) DEFAULT NULL,
  `chassis_id` int(11) DEFAULT NULL,
  `price_chassis` decimal(10,2) DEFAULT NULL,
  `filmuv_bool` tinyint(1) DEFAULT NULL,
  `price_filmuv` decimal(10,2) DEFAULT NULL,
  `tirage_factor` decimal(10,2) DEFAULT NULL,
  `renfort_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_line_id_idx` (`document_line_id`),
  KEY `chroma_id_idxfk` (`chroma_id`),
  KEY `frame_id_idxfk` (`frame_id`),
  KEY `chassis_id_idxfk` (`chassis_id`),
  KEY `support_id_idxfk` (`support_id`),
  KEY `tirage_id_idxfk` (`tirage_id`),
  KEY `collage_id_idxfk` (`collage_id`),
  KEY `protection_id_idxfk` (`protection_id`),
  KEY `finish_id_idxfk` (`finish_id`),
  KEY `renfort_id_idxfk` (`renfort_id`),
  CONSTRAINT `document_line_detail_ibfk_224` FOREIGN KEY (`chroma_id`) REFERENCES `item` (`id`),
  CONSTRAINT `document_line_detail_ibfk_225` FOREIGN KEY (`renfort_id`) REFERENCES `item` (`id`),
  CONSTRAINT `document_line_detail_ibfk_226` FOREIGN KEY (`frame_id`) REFERENCES `item` (`id`),
  CONSTRAINT `document_line_detail_ibfk_227` FOREIGN KEY (`chassis_id`) REFERENCES `item` (`id`),
  CONSTRAINT `document_line_detail_ibfk_228` FOREIGN KEY (`support_id`) REFERENCES `item` (`id`),
  CONSTRAINT `document_line_detail_ibfk_229` FOREIGN KEY (`tirage_id`) REFERENCES `item` (`id`),
  CONSTRAINT `document_line_detail_ibfk_230` FOREIGN KEY (`collage_id`) REFERENCES `item` (`id`),
  CONSTRAINT `document_line_detail_ibfk_231` FOREIGN KEY (`protection_id`) REFERENCES `item` (`id`),
  CONSTRAINT `document_line_detail_ibfk_232` FOREIGN KEY (`finish_id`) REFERENCES `item` (`id`),
  CONSTRAINT `document_line_detail_ibfk_3` FOREIGN KEY (`document_line_id`) REFERENCES `document_line` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table document_line_option
# ------------------------------------------------------------

DROP TABLE IF EXISTS `document_line_option`;

CREATE TABLE `document_line_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_line_id` int(11) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `note` varchar(160) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_line_id_idxfk` (`document_line_id`),
  KEY `option_id_idxfk` (`option_id`),
  KEY `item_id_idx` (`item_id`),
  CONSTRAINT `document_line_option_ibfk_133` FOREIGN KEY (`document_line_id`) REFERENCES `document_line` (`id`),
  CONSTRAINT `document_line_option_ibfk_134` FOREIGN KEY (`option_id`) REFERENCES `option` (`id`),
  CONSTRAINT `document_line_option_ibfk_135` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table document_size
# ------------------------------------------------------------

DROP TABLE IF EXISTS `document_size`;

CREATE TABLE `document_size` (
  `quantity` tinyint(4) NOT NULL,
  `largest` tinyint(4) NOT NULL,
  `shortest` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Affichage de la table event
# ------------------------------------------------------------

DROP TABLE IF EXISTS `event`;

CREATE TABLE `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `date_from` datetime DEFAULT NULL,
  `date_to` datetime DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `event_type` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table extraction
# ------------------------------------------------------------

DROP TABLE IF EXISTS `extraction`;

CREATE TABLE `extraction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `extraction_type` varchar(20) DEFAULT NULL,
  `extraction_method` varchar(20) DEFAULT NULL,
  `date_from` datetime DEFAULT NULL,
  `date_to` datetime DEFAULT NULL,
  `document_from` int(11) DEFAULT NULL,
  `document_to` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_from_idxfk` (`document_from`),
  KEY `document_to_idxfk` (`document_to`),
  KEY `document_from_idx` (`document_from`),
  KEY `document_to_idx` (`document_to`),
  CONSTRAINT `extraction_ibfk_21` FOREIGN KEY (`document_from`) REFERENCES `document` (`id`),
  CONSTRAINT `extraction_ibfk_22` FOREIGN KEY (`document_to`) REFERENCES `document` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `history`;

CREATE TABLE `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_type` text,
  `object_id` int(11) DEFAULT NULL,
  `action` varchar(40) NOT NULL,
  `note` varchar(160) DEFAULT NULL,
  `performer_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `payload` text,
  `summary` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table item
# ------------------------------------------------------------

DROP TABLE IF EXISTS `item`;

CREATE TABLE `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yii_category` varchar(20) DEFAULT NULL,
  `comptabilite` varchar(20) DEFAULT NULL,
  `reference` varchar(40) DEFAULT NULL,
  `libelle_court` varchar(40) DEFAULT NULL,
  `libelle_long` varchar(80) DEFAULT NULL,
  `categorie` varchar(20) DEFAULT NULL,
  `prix_de_vente` decimal(10,2) DEFAULT NULL,
  `taux_de_tva` decimal(10,2) DEFAULT NULL,
  `prix_a` decimal(10,2) DEFAULT NULL,
  `prix_b` decimal(10,2) DEFAULT NULL,
  `prix_min` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `fournisseur` varchar(20) DEFAULT NULL,
  `type_travaux_photos` varchar(20) DEFAULT NULL,
  `type_numerique` varchar(20) DEFAULT NULL,
  `reference_fournisseur` varchar(20) DEFAULT NULL,
  `conditionnement` varchar(20) DEFAULT NULL,
  `prix_d_achat_de_reference` varchar(20) DEFAULT NULL,
  `client` varchar(40) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `date_initiale` date DEFAULT NULL,
  `date_finale` date DEFAULT NULL,
  `suivi_de_stock` varchar(20) DEFAULT NULL,
  `reassort_possible` varchar(20) DEFAULT NULL,
  `seuil_de_commande` varchar(20) DEFAULT NULL,
  `site_internet` varchar(80) DEFAULT NULL,
  `creation` date DEFAULT NULL,
  `mise_a_jour` date DEFAULT NULL,
  `en_cours` varchar(20) DEFAULT NULL,
  `stock` varchar(20) DEFAULT NULL,
  `commentaires` varchar(80) DEFAULT NULL,
  `identification` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table item_option
# ------------------------------------------------------------

DROP TABLE IF EXISTS `item_option`;

CREATE TABLE `item_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `note` varchar(160) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `mandatory` tinyint(1) DEFAULT NULL,
  `shownote` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id_idx` (`item_id`),
  KEY `option_id_idx` (`option_id`),
  CONSTRAINT `item_option_ibfk_111` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  CONSTRAINT `item_option_ibfk_112` FOREIGN KEY (`option_id`) REFERENCES `option` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table item_task
# ------------------------------------------------------------

DROP TABLE IF EXISTS `item_task`;

CREATE TABLE `item_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `note` varchar(160) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_task_un` (`item_id`,`task_id`),
  KEY `task_id_idxfk` (`task_id`),
  CONSTRAINT `item_task_ibfk_161` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  CONSTRAINT `item_task_ibfk_162` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `master`;

CREATE TABLE `master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `work_length` float NOT NULL,
  `keep` tinyint(1) DEFAULT '0',
  `note` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table migration
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migration`;

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table option
# ------------------------------------------------------------

DROP TABLE IF EXISTS `option`;

CREATE TABLE `option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `option_type` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `categorie` varchar(20) NOT NULL,
  `label` varchar(20) NOT NULL,
  `note` varchar(160) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id_idx` (`item_id`),
  CONSTRAINT `option_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table parameter
# ------------------------------------------------------------

DROP TABLE IF EXISTS `parameter`;

CREATE TABLE `parameter` (
  `domain` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(40) NOT NULL DEFAULT '',
  `lang` varchar(20) NOT NULL DEFAULT '',
  `value_text` varchar(2000) DEFAULT NULL,
  `value_number` float DEFAULT NULL,
  `value_int` int(11) DEFAULT NULL,
  `value_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`domain`,`name`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table payment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `payment`;

CREATE TABLE `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `sale` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `cash_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `note` varchar(160) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id_idx` (`client_id`),
  KEY `created_by_idx` (`created_by`),
  KEY `updated_by_idx` (`updated_by`),
  KEY `cash_id_idxfk` (`cash_id`),
  KEY `account_id_idxfk` (`account_id`),
  CONSTRAINT `payment_ibfk_103` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  CONSTRAINT `payment_ibfk_104` FOREIGN KEY (`cash_id`) REFERENCES `cash` (`id`),
  CONSTRAINT `payment_ibfk_105` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`),
  CONSTRAINT `payment_ibfk_106` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  CONSTRAINT `payment_ibfk_107` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table payment_link
# ------------------------------------------------------------

DROP TABLE IF EXISTS `payment_link`;

CREATE TABLE `payment_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_id` int(11) NOT NULL,
  `linked_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_id_idxfk` (`payment_id`),
  KEY `linked_id_idxfk` (`linked_id`),
  CONSTRAINT `payment_link_ibfk_11` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`id`),
  CONSTRAINT `payment_link_ibfk_12` FOREIGN KEY (`linked_id`) REFERENCES `payment` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table pdf
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pdf`;

CREATE TABLE `pdf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_type` varchar(40) DEFAULT NULL,
  `document_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `sent_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id_idxfk` (`client_id`),
  KEY `document_id_idx` (`document_id`),
  CONSTRAINT `pdf_ibfk_23` FOREIGN KEY (`document_id`) REFERENCES `document` (`id`),
  CONSTRAINT `pdf_ibfk_24` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table picture
# ------------------------------------------------------------

DROP TABLE IF EXISTS `picture`;

CREATE TABLE `picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_line_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `mimetype` varchar(80) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_line_id_idx` (`document_line_id`),
  CONSTRAINT `picture_ibfk_1` FOREIGN KEY (`document_line_id`) REFERENCES `document_line` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table price_list
# ------------------------------------------------------------

DROP TABLE IF EXISTS `price_list`;

CREATE TABLE `price_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `note` varchar(160) DEFAULT NULL,
  `sizes` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table price_list_item
# ------------------------------------------------------------

DROP TABLE IF EXISTS `price_list_item`;

CREATE TABLE `price_list_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price_list_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_list_id_idx` (`price_list_id`),
  KEY `item_id_idx` (`item_id`),
  CONSTRAINT `price_list_item_ibfk_15` FOREIGN KEY (`price_list_id`) REFERENCES `price_list` (`id`),
  CONSTRAINT `price_list_item_ibfk_16` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table profile
# ------------------------------------------------------------

DROP TABLE IF EXISTS `profile`;

CREATE TABLE `profile` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `public_email` varchar(255) DEFAULT NULL,
  `gravatar_email` varchar(255) DEFAULT NULL,
  `gravatar_id` varchar(32) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `bio` text,
  `timezone` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_user_profile` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table provider
# ------------------------------------------------------------

DROP TABLE IF EXISTS `provider`;

CREATE TABLE `provider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `email` varchar(80) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table segment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `segment`;

CREATE TABLE `segment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_line_id` int(11) NOT NULL,
  `master_id` int(11) DEFAULT NULL,
  `work_length` float NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_line_id_idx` (`document_line_id`),
  KEY `master_id_idx` (`master_id`),
  CONSTRAINT `segment_ibfk_1` FOREIGN KEY (`document_line_id`) REFERENCES `document_line` (`id`),
  CONSTRAINT `segment_ibfk_2` FOREIGN KEY (`master_id`) REFERENCES `master` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table sequence_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sequence_data`;

CREATE TABLE `sequence_data` (
  `sequence_name` varchar(100) NOT NULL,
  `sequence_increment` int(11) unsigned NOT NULL DEFAULT '1',
  `sequence_min_value` int(11) unsigned NOT NULL DEFAULT '1',
  `sequence_max_value` bigint(20) unsigned NOT NULL DEFAULT '18446744073709551615',
  `sequence_cur_value` bigint(20) unsigned DEFAULT '1',
  `sequence_cycle` tinyint(1) NOT NULL DEFAULT '0',
  `sequence_year` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`sequence_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Affichage de la table social_account
# ------------------------------------------------------------

DROP TABLE IF EXISTS `social_account`;

CREATE TABLE `social_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `provider` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `data` text,
  `code` varchar(32) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_unique` (`provider`,`client_id`),
  UNIQUE KEY `account_unique_code` (`code`),
  KEY `user_id_idx` (`user_id`),
  CONSTRAINT `fk_user_account` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table split
# ------------------------------------------------------------

DROP TABLE IF EXISTS `split`;

CREATE TABLE `split` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id1` int(11) DEFAULT NULL,
  `id2` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id1_idx` (`id1`),
  KEY `id2_idx` (`id2`),
  CONSTRAINT `split_ibfk_15` FOREIGN KEY (`id1`) REFERENCES `segment` (`id`),
  CONSTRAINT `split_ibfk_16` FOREIGN KEY (`id2`) REFERENCES `segment` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table task
# ------------------------------------------------------------

DROP TABLE IF EXISTS `task`;

CREATE TABLE `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) DEFAULT NULL,
  `icon` varchar(40) DEFAULT NULL,
  `note` varchar(160) DEFAULT NULL,
  `first_run` decimal(10,2) DEFAULT NULL,
  `next_run` decimal(10,2) DEFAULT NULL,
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table token
# ------------------------------------------------------------

DROP TABLE IF EXISTS `token`;

CREATE TABLE `token` (
  `user_id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `type` smallint(6) NOT NULL,
  `created_at` int(11) NOT NULL,
  UNIQUE KEY `token_unique` (`user_id`,`code`,`type`),
  CONSTRAINT `fk_user_token` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(60) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `confirmed_at` int(11) DEFAULT NULL,
  `unconfirmed_email` varchar(255) DEFAULT NULL,
  `blocked_at` int(11) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `registration_ip` bigint(20) DEFAULT NULL,
  `flags` int(11) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `last_login_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_unique_email` (`email`),
  UNIQUE KEY `user_unique_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table website_order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `website_order`;

CREATE TABLE `website_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) DEFAULT NULL,
  `order_id` varchar(40) DEFAULT NULL,
  `order_type` varchar(20) DEFAULT NULL,
  `order_name` varchar(80) NOT NULL,
  `order_date` varchar(40) DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `company` varchar(80) DEFAULT NULL,
  `address` varchar(160) DEFAULT NULL,
  `postcode` varchar(20) DEFAULT NULL,
  `city` varchar(80) DEFAULT NULL,
  `country` varchar(40) DEFAULT NULL,
  `vat` varchar(40) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `delivery` varchar(40) DEFAULT NULL,
  `promocode` varchar(40) DEFAULT NULL,
  `clientcode` varchar(40) DEFAULT NULL,
  `comment` varchar(160) DEFAULT NULL,
  `convert_errors` text,
  `rawjson` text NOT NULL,
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
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `format` varchar(20) DEFAULT NULL,
  `comment` varchar(160) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `website_order_id_idxfk` (`website_order_id`),
  CONSTRAINT `website_order_line_ibfk_1` FOREIGN KEY (`website_order_id`) REFERENCES `website_order` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table work
# ------------------------------------------------------------

DROP TABLE IF EXISTS `work`;

CREATE TABLE `work` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL,
  `due_date` datetime NOT NULL,
  `priority` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by_idxfk` (`created_by`),
  KEY `updated_by_idxfk` (`updated_by`),
  KEY `document_id_idx` (`document_id`),
  CONSTRAINT `work_ibfk_309` FOREIGN KEY (`document_id`) REFERENCES `document` (`id`),
  CONSTRAINT `work_ibfk_310` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  CONSTRAINT `work_ibfk_311` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table work_line
# ------------------------------------------------------------

DROP TABLE IF EXISTS `work_line`;

CREATE TABLE `work_line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `work_id` int(11) NOT NULL,
  `document_line_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `due_date` datetime NOT NULL,
  `priority` int(11) DEFAULT NULL,
  `note` varchar(160) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_id_idxfk` (`work_id`),
  KEY `created_by_idx` (`created_by`),
  KEY `updated_by_idx` (`updated_by`),
  KEY `task_id_idx` (`task_id`),
  KEY `item_id_idx` (`item_id`),
  KEY `document_line_id_idx` (`document_line_id`),
  CONSTRAINT `work_line_ibfk_528` FOREIGN KEY (`work_id`) REFERENCES `work` (`id`),
  CONSTRAINT `work_line_ibfk_529` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`),
  CONSTRAINT `work_line_ibfk_530` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  CONSTRAINT `work_line_ibfk_531` FOREIGN KEY (`document_line_id`) REFERENCES `document_line` (`id`),
  CONSTRAINT `work_line_ibfk_532` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  CONSTRAINT `work_line_ibfk_533` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;





# Replace placeholder table for bi_line with correct view syntax
# ------------------------------------------------------------

DROP TABLE `bi_line`;

CREATE ALGORITHM=UNDEFINED DEFINER=`yii2print`@`%` SQL SECURITY DEFINER VIEW `bi_line`
AS SELECT
   `d`.`document_type` AS `document_type`,date_format(`d`.`created_at`,'%Y') AS `date_year`,date_format(`d`.`created_at`,'%m') AS `date_month`,
   `c`.`pays` AS `pays`,
   `c`.`lang` AS `lang`,
   `dl`.`work_width` AS `work_width`,
   `dl`.`work_height` AS `work_height`,
   `dl`.`unit_price` AS `unit_price`,
   `dl`.`quantity` AS `quantity`,
   `dl`.`extra_type` AS `extra_type`,
   `dl`.`extra_amount` AS `extra_amount`,
   `dl`.`extra_htva` AS `extra_htva`,
   `dl`.`price_htva` AS `price_htva`,
   `i`.`id` AS `item_id`,
   `i`.`categorie` AS `categorie`,
   `i`.`yii_category` AS `yii_category`,
   `i`.`comptabilite` AS `comptabilite`
FROM (((`document_line` `dl` join `document` `d`) join `item` `i`) join `client` `c`) where ((`dl`.`document_id` = `d`.`id`) and (`dl`.`item_id` = `i`.`id`) and (`d`.`client_id` = `c`.`id`));


# Replace placeholder table for bi_sale with correct view syntax
# ------------------------------------------------------------

DROP TABLE `bi_sale`;

CREATE ALGORITHM=UNDEFINED DEFINER=`yii2print`@`%` SQL SECURITY DEFINER VIEW `bi_sale`
AS SELECT
   `d`.`document_type` AS `document_type`,
   `d`.`created_at` AS `created_at`,
   `d`.`updated_at` AS `updated_at`,
   `d`.`due_date` AS `due_date`,date_format(`d`.`created_at`,'%Y') AS `date_year`,date_format(`d`.`created_at`,'%m') AS `date_month`,
   `d`.`price_htva` AS `price_htva`,
   `c`.`prenom` AS `client_fn`,
   `c`.`nom` AS `client_ln`,
   `c`.`autre_nom` AS `client_an`,
   `c`.`pays` AS `country`,
   `c`.`lang` AS `language`,
   `c`.`id` AS `client_id`
FROM (`document` `d` join `client` `c`) where (`d`.`client_id` = `c`.`id`);


# Replace placeholder table for document_account_line with correct view syntax
# ------------------------------------------------------------

DROP TABLE `document_account_line`;

CREATE ALGORITHM=UNDEFINED DEFINER=`yii2print`@`localhost` SQL SECURITY DEFINER VIEW `document_account_line`
AS SELECT
   `dl`.`document_id` AS `document_id`,
   `i`.`comptabilite` AS `comptabilite`,
   `dl`.`vat` AS `taux_de_tva`,if(isnull(`dl`.`vat`),0,sum(round(((if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`) + if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) * (`dl`.`vat` / 100)),2))) AS `total_vat`,sum(if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) AS `total_price_htva`,sum(if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`)) AS `total_extra_htva`,(sum(if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) + sum(if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`))) AS `total_htva`,if(isnull(`dl`.`vat`),0,round(((sum(if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) + sum(if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`))) * (`dl`.`vat` / 100)),2)) AS `total_vat_ctrl`
FROM (`document_line` `dl` join `item` `i`) where (`dl`.`item_id` = `i`.`id`) group by `dl`.`document_id`,`i`.`comptabilite`,`dl`.`vat`;


# Replace placeholder table for bi_work with correct view syntax
# ------------------------------------------------------------

DROP TABLE `bi_work`;

CREATE ALGORITHM=UNDEFINED DEFINER=`yii2print`@`%` SQL SECURITY DEFINER VIEW `bi_work`
AS SELECT
   `wl`.`created_at` AS `date_start`,
   `wl`.`updated_at` AS `date_finish`,
   `t`.`name` AS `task_name`,
   `i`.`categorie` AS `categorie`,
   `i`.`yii_category` AS `yii_category`
FROM (((`work` `w` join `work_line` `wl`) join `item` `i`) join `task` `t`) where ((`wl`.`work_id` = `w`.`id`) and (`wl`.`item_id` = `i`.`id`) and (`wl`.`task_id` = `t`.`id`));

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
