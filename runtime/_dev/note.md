INSERT INTO `parameter` (`domain`, `name`, `lang`, `value_text`, `value_number`, `value_int`, `value_date`, `created_at`, `updated_at`)
VALUES
	('legal', 'autoliquidation', 'en', 'Autoliquidation / Reverse charge.', NULL, NULL, NULL, NULL, NULL),
	('legal', 'autoliquidation', 'fr', 'Autoliquidation.', NULL, NULL, NULL, NULL, NULL),
	('legal', 'autoliquidation', 'nl', 'Verleggingsregeling.', NULL, NULL, NULL, NULL, NULL),
	('legal', 'hors_europe', 'en', 'Delivery of goods covered by Article 39 §1, 1 ° of the VAT code. Belgian VAT not due - European Directive 2006/112 / EC Article 146.', NULL, NULL, NULL, NULL, NULL),
	('legal', 'hors_europe', 'fr', 'Livraison de bien visée par l\'article 39 §1, 1° du code TVA. TVA belge non due - Directive européenne 2006/112/CE article 146.', NULL, NULL, NULL, NULL, NULL),
	('legal', 'hors_europe', 'nl', 'Levering goed als bedoeld in artikel 39 § 1, 1 °, van het BTW-wetboek. Belgische BTW niet te wijten - Europese Richtlijn 2006/112 / EG Artikel 146.', NULL, NULL, NULL, NULL, NULL),
	('legal', 'intra_communautaire', 'en', 'Delivery of goods inside EEC: Belgian VAT not due on the basis of Article 39bis §1, 1 ° of the VAT Code - European Directive 2006/112 / EC Article 138.', NULL, NULL, NULL, NULL, NULL),
	('legal', 'intra_communautaire', 'fr', 'Livraison de bien intracommunautaire: TVA belge non due sur base de l’article 39bis §1, 1° du code TVA - Directive européenne 2006/112/CE article 138.', NULL, NULL, NULL, NULL, NULL),
	('legal', 'intra_communautaire', 'nl', 'Levering intra EEC: Belgische BTW niet verschuldigd op grond van artikel 39bis § 1, 1 °, van het BTW-wetboek - Europese Richtlijn 2006/112 / EG Artikel 138.', NULL, NULL, NULL, NULL, NULL),
	('legal', 'no_vat_eu', 'en', 'Export: Exempt from VAT by Art.42 §3 3 d) of the Code of VAT.', NULL, NULL, NULL, NULL, NULL),
	('legal', 'no_vat_eu', 'fr', 'Exportation: Exempt de TVA selon Art.42 §3 3° d) du code de la TVA.', NULL, NULL, NULL, NULL, NULL),
	('legal', 'no_vat_eu', 'nl', 'Export: Vrijgesteld van BTW Art.42 §3 3 d), van het Wetboek van de BTW.', NULL, NULL, NULL, NULL, NULL),
	('legal', 'no_vat_intra', 'en', 'Export: Exempt from VAT by Art.21 §3 7 ° d) of the Code of VAT.', NULL, NULL, NULL, NULL, NULL),
	('legal', 'no_vat_intra', 'fr', 'Exportation: Exempt de TVA selon Art.21 §3 7° d) du code de la TVA.', NULL, NULL, NULL, NULL, NULL),
	('legal', 'no_vat_intra', 'nl', 'Export: Vrijgesteld van BTW In artikel 21, § 3 7 ° d), van het Wetboek van de BTW.', NULL, NULL, NULL, NULL, NULL);
