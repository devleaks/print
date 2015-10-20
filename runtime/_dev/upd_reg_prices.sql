create table item_a as 
select i1.id, i1.reference, i2.prix_de_vente
from item i1, item i2
where i2.reference = concat(i1.reference, '_A');

create table item_b as 
select i1.id, i1.reference, i2.prix_de_vente
from item i1, item i2
where i2.reference = concat(i1.reference, '_B');

update item set prix_a = (select prix_de_vente from item_a where id = item.id);

update item set prix_b = (select prix_de_vente from item_b where id = item.id)

	-- Create syntax for TABLE 'website_order'
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
	) ENGINE=InnoDB AUTO_INCREMENT=264 DEFAULT CHARSET=utf8;

	-- Create syntax for TABLE 'website_order_line'
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
	) ENGINE=InnoDB AUTO_INCREMENT=218 DEFAULT CHARSET=utf8;