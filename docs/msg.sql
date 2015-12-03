/* alter table parameter value_text varchar(2000) */
INSERT INTO `parameter` (`domain`, `name`, `lang`, `value_text`, `value_number`, `value_int`, `value_date`, `created_at`, `updated_at`)
VALUES
	('communication', 'signature', 'en', 'Please check your order, dimensions and options. Please mention your order number on all communications and when paying by bank transfer. Thank You.', NULL, NULL, NULL, NULL, NULL),
	('communication', 'signature', 'fr', 'Veuillez, s\'il vous plaît, vérifier votre commande, les dimensions, et les options de finition. Veuillez également rappeler le numéro du bon de commande lors de toutes communications et lors du paiement de l\'acompte.', NULL, NULL, NULL, NULL, NULL),
	('communication', 'signature', 'nl', 'Beste,\n\nwe bedanken u voor uw bestelling en bezorgen u in bijlage de daarbij behorende bestelbon.\nMogen we u vragen om alle gegevens nogmaals te checken op persoonlijke gegevens, formaat, afwerking en verzending? \nGelieve bij eventuele vragen of wijzigingen het ordernummer te vermelden in uw communicatie.\n\nVriendelijke groeten,', NULL, NULL, NULL, NULL, NULL);
