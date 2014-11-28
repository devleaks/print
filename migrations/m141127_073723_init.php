<?php

use yii\db\Schema;
use jamband\migrations\Migration;

class m141127_073723_init extends Migration
{
    public function safeUp()
    {
		// backup
		$this->createTable('{{%backup}}', [
		    'id' => Schema::TYPE_PK . " UNSIGNED",
		    'filename' => Schema::TYPE_STRING . "(250) NOT NULL DEFAULT ''",
		    'status' => Schema::TYPE_STRING . "(20) NOT NULL DEFAULT ''",
		    'created_at' => Schema::TYPE_DATETIME . " NOT NULL",
		    'updated_at' => Schema::TYPE_DATETIME . " NOT NULL",
		], $this->tableOptions);

		// book
		$this->createTable('{{%book}}', [
		    'id' => Schema::TYPE_INTEGER . "(11)",
		    'book_code' => Schema::TYPE_STRING . "(30)",
		    'synopsis' => Schema::TYPE_TEXT,
		    'color' => Schema::TYPE_STRING . "(10)",
		    'publish_date' => Schema::TYPE_DATE,
		    'status' => Schema::TYPE_BOOLEAN,
		    'sale_amount' => Schema::TYPE_DECIMAL . "(11)",
		    'buy_amount' => Schema::TYPE_DECIMAL . "(11)",
		], $this->tableOptions);

		// cities
		$this->createTable('{{%cities}}', [
		    'id' => Schema::TYPE_PK . " UNSIGNED",
		    'zip' => Schema::TYPE_STRING . "(4) NOT NULL",
		    'name' => Schema::TYPE_STRING . "(255) NOT NULL",
		], $this->tableOptions);

		// client
		$this->createTable('{{%client}}', [
		    'id' => Schema::TYPE_PK,
		    'reference_interne' => Schema::TYPE_STRING . "(80)",
		    'titre' => Schema::TYPE_STRING . "(80)",
		    'nom' => Schema::TYPE_STRING . "(80)",
		    'prenom' => Schema::TYPE_STRING . "(80)",
		    'autre_nom' => Schema::TYPE_STRING . "(80)",
		    'adresse' => Schema::TYPE_STRING . "(80)",
		    'code_postal' => Schema::TYPE_STRING . "(80)",
		    'localite' => Schema::TYPE_STRING . "(80)",
		    'pays' => Schema::TYPE_STRING . "(80)",
		    'langue' => Schema::TYPE_STRING . "(80)",
		    'numero_tva' => Schema::TYPE_STRING . "(80)",
		    'email' => Schema::TYPE_STRING . "(80)",
		    'site_web' => Schema::TYPE_STRING . "(80)",
		    'domicile' => Schema::TYPE_STRING . "(80)",
		    'bureau' => Schema::TYPE_STRING . "(80)",
		    'gsm' => Schema::TYPE_STRING . "(80)",
		    'fax_prive' => Schema::TYPE_STRING . "(80)",
		    'fax_bureau' => Schema::TYPE_STRING . "(80)",
		    'pc' => Schema::TYPE_STRING . "(80)",
		    'autre' => Schema::TYPE_STRING . "(80)",
		    'remise' => Schema::TYPE_STRING . "(80)",
		    'escompte' => Schema::TYPE_STRING . "(80)",
		    'delais_de_paiement' => Schema::TYPE_STRING . "(80)",
		    'mentions' => Schema::TYPE_STRING . "(80)",
		    'exemplaires' => Schema::TYPE_STRING . "(80)",
		    'limite_de_credit' => Schema::TYPE_STRING . "(80)",
		    'formule' => Schema::TYPE_STRING . "(80)",
		    'type' => Schema::TYPE_STRING . "(80)",
		    'execution' => Schema::TYPE_STRING . "(80)",
		    'support' => Schema::TYPE_STRING . "(80)",
		    'format' => Schema::TYPE_STRING . "(80)",
		    'mise_a_jour' => Schema::TYPE_DATE,
		    'mailing' => Schema::TYPE_STRING . "(80)",
		    'outlook' => Schema::TYPE_STRING . "(80)",
		    'categorie_de_client' => Schema::TYPE_STRING . "(80)",
		    'comptabilite' => Schema::TYPE_STRING . "(80)",
		    'operation' => Schema::TYPE_STRING . "(80)",
		    'categorie_de_prix_de_vente' => Schema::TYPE_STRING . "(80)",
		    'reference_1' => Schema::TYPE_STRING . "(80)",
		    'date_limite_1' => Schema::TYPE_STRING . "(80)",
		    'reference_2' => Schema::TYPE_STRING . "(80)",
		    'date_limite_2' => Schema::TYPE_STRING . "(80)",
		    'reference_3' => Schema::TYPE_STRING . "(80)",
		    'date_limite_3' => Schema::TYPE_STRING . "(80)",
		    'commentaires' => Schema::TYPE_STRING . "(255)",
		    'status' => Schema::TYPE_STRING . "(20)",
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		    'lang' => Schema::TYPE_STRING . "(20)",
		    'comm_pref' => Schema::TYPE_STRING . "(20)",
		    'comm_format' => Schema::TYPE_STRING . "(20)",
		], $this->tableOptions);

		// extraction
		$this->createTable('{{%extraction}}', [
		    'id' => Schema::TYPE_PK,
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		    'extraction_type' => Schema::TYPE_STRING . "(20)",
		    'date_from' => Schema::TYPE_DATETIME,
		    'date_to' => Schema::TYPE_DATETIME,
		    'order_from' => Schema::TYPE_INTEGER . "(11)",
		    'order_to' => Schema::TYPE_INTEGER . "(11)",
		], $this->tableOptions);

		// extraction_line
		$this->createTable('{{%extraction_line}}', [
		    'id' => Schema::TYPE_PK,
		    'extraction_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'status' => Schema::TYPE_STRING . "(20)",
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		    'object_type' => Schema::TYPE_STRING . "(20)",
		    'object_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		], $this->tableOptions);

		// history
		$this->createTable('{{%history}}', [
		    'id' => Schema::TYPE_PK,
		    'object_type' => Schema::TYPE_TEXT,
		    'object_id' => Schema::TYPE_INTEGER . "(11)",
		    'action' => Schema::TYPE_STRING . "(20) NOT NULL",
		    'old_value' => Schema::TYPE_STRING . "(40)",
		    'name' => Schema::TYPE_STRING . "(80)",
		    'note' => Schema::TYPE_STRING . "(255)",
		    'performer_id' => Schema::TYPE_INTEGER . "(11)",
		    'created_at' => Schema::TYPE_DATETIME,
		], $this->tableOptions);

		// item
		$this->createTable('{{%item}}', [
		    'id' => Schema::TYPE_PK,
		    'yii_category' => Schema::TYPE_STRING . "(20)",
		    'reference' => Schema::TYPE_STRING . "(20)",
		    'libelle_court' => Schema::TYPE_STRING . "(40)",
		    'libelle_long' => Schema::TYPE_STRING . "(80)",
		    'categorie' => Schema::TYPE_STRING . "(20)",
		    'prix_de_vente' => Schema::TYPE_FLOAT,
		    'taux_de_tva' => Schema::TYPE_FLOAT,
		    'type_travaux_photos' => Schema::TYPE_STRING . "(20)",
		    'type_numerique' => Schema::TYPE_STRING . "(20)",
		    'fournisseur' => Schema::TYPE_STRING . "(20)",
		    'reference_fournisseur' => Schema::TYPE_STRING . "(20)",
		    'conditionnement' => Schema::TYPE_STRING . "(20)",
		    'prix_d_achat_de_reference' => Schema::TYPE_STRING . "(20)",
		    'client' => Schema::TYPE_STRING . "(40)",
		    'quantite' => Schema::TYPE_INTEGER . "(11)",
		    'date_initiale' => Schema::TYPE_DATE,
		    'date_finale' => Schema::TYPE_DATE,
		    'identification' => Schema::TYPE_STRING . "(20)",
		    'suivi_de_stock' => Schema::TYPE_STRING . "(20)",
		    'reassort_possible' => Schema::TYPE_STRING . "(20)",
		    'seuil_de_commande' => Schema::TYPE_STRING . "(20)",
		    'site_internet' => Schema::TYPE_STRING . "(80)",
		    'creation' => Schema::TYPE_DATE,
		    'mise_a_jour' => Schema::TYPE_DATE,
		    'en_cours' => Schema::TYPE_STRING . "(20)",
		    'stock' => Schema::TYPE_STRING . "(20)",
		    'commentaires' => Schema::TYPE_STRING . "(80)",
		    'status' => Schema::TYPE_STRING . "(20)",
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		], $this->tableOptions);

		// item_copy
		$this->createTable('{{%item_copy}}', [
		    'id' => Schema::TYPE_PK,
		    'reference' => Schema::TYPE_STRING . "(20)",
		    'libelle_court' => Schema::TYPE_STRING . "(40)",
		    'libelle_long' => Schema::TYPE_STRING . "(80)",
		    'categorie' => Schema::TYPE_STRING . "(20)",
		    'prix_de_vente' => Schema::TYPE_FLOAT,
		    'taux_de_tva' => Schema::TYPE_FLOAT,
		    'type_travaux_photos' => Schema::TYPE_STRING . "(20)",
		    'type_numerique' => Schema::TYPE_STRING . "(20)",
		    'fournisseur' => Schema::TYPE_STRING . "(20)",
		    'reference_fournisseur' => Schema::TYPE_STRING . "(20)",
		    'conditionnement' => Schema::TYPE_STRING . "(20)",
		    'prix_d_achat_de_reference' => Schema::TYPE_STRING . "(20)",
		    'client' => Schema::TYPE_STRING . "(40)",
		    'quantite' => Schema::TYPE_INTEGER . "(11)",
		    'date_initiale' => Schema::TYPE_DATE,
		    'date_finale' => Schema::TYPE_DATE,
		    'identification' => Schema::TYPE_STRING . "(20)",
		    'suivi_de_stock' => Schema::TYPE_STRING . "(20)",
		    'reassort_possible' => Schema::TYPE_STRING . "(20)",
		    'seuil_de_commande' => Schema::TYPE_STRING . "(20)",
		    'site_internet' => Schema::TYPE_STRING . "(80)",
		    'creation' => Schema::TYPE_DATE,
		    'mise_a_jour' => Schema::TYPE_DATE,
		    'en_cours' => Schema::TYPE_STRING . "(20)",
		    'stock' => Schema::TYPE_STRING . "(20)",
		    'commentaires' => Schema::TYPE_STRING . "(80)",
		    'status' => Schema::TYPE_STRING . "(20)",
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		], $this->tableOptions);

		// item_option
		$this->createTable('{{%item_option}}', [
		    'id' => Schema::TYPE_PK,
		    'item_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'option_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'position' => Schema::TYPE_INTEGER . "(11)",
		    'note' => Schema::TYPE_STRING . "(160)",
		    'status' => Schema::TYPE_STRING . "(20)",
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		    'mandatory' => Schema::TYPE_BOOLEAN,
		    'shownote' => Schema::TYPE_BOOLEAN,
		], $this->tableOptions);

		// item_task
		$this->createTable('{{%item_task}}', [
		    'id' => Schema::TYPE_PK,
		    'item_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'task_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'note' => Schema::TYPE_STRING . "(160)",
		    'status' => Schema::TYPE_STRING . "(20)",
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		    'position' => Schema::TYPE_INTEGER . "(11)",
		], $this->tableOptions);

		// option
		$this->createTable('{{%option}}', [
		    'id' => Schema::TYPE_PK,
		    'item_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'option_type' => Schema::TYPE_STRING . "(20) NOT NULL",
		    'note' => Schema::TYPE_STRING . "(160)",
		    'status' => Schema::TYPE_STRING . "(20)",
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		    'name' => Schema::TYPE_STRING . "(20) NOT NULL",
		    'categorie' => Schema::TYPE_STRING . "(20) NOT NULL DEFAULT ''",
		    'label' => Schema::TYPE_STRING . "(20) NOT NULL",
		], $this->tableOptions);

		// order
		$this->createTable('{{%order}}', [
		    'id' => Schema::TYPE_PK,
		    'order_type' => Schema::TYPE_STRING . "(20)",
		    'parent_id' => Schema::TYPE_INTEGER . "(11)",
		    'name' => Schema::TYPE_STRING . "(20) NOT NULL",
		    'client_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'note' => Schema::TYPE_STRING . "(160)",
		    'status' => Schema::TYPE_STRING . "(20)",
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		    'price_htva' => Schema::TYPE_FLOAT,
		    'price_tvac' => Schema::TYPE_FLOAT,
		    'created_by' => Schema::TYPE_INTEGER . "(11)",
		    'updated_by' => Schema::TYPE_INTEGER . "(11)",
		    'vat_bool' => Schema::TYPE_BOOLEAN,
		    'lang' => Schema::TYPE_STRING . "(20)",
		    'reference' => Schema::TYPE_STRING . "(40)",
		    'reference_client' => Schema::TYPE_STRING . "(40)",
		    'due_date' => Schema::TYPE_DATETIME . " NOT NULL",
		    'vat' => Schema::TYPE_FLOAT,
		    'bom_bool' => Schema::TYPE_BOOLEAN,
		], $this->tableOptions);

		// order_line
		$this->createTable('{{%order_line}}', [
		    'id' => Schema::TYPE_PK,
		    'order_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'position' => Schema::TYPE_INTEGER . "(11)",
		    'quantity' => Schema::TYPE_FLOAT . " NOT NULL",
		    'unit_price' => Schema::TYPE_FLOAT,
		    'vat' => Schema::TYPE_FLOAT,
		    'note' => Schema::TYPE_STRING . "(160)",
		    'work_width' => Schema::TYPE_FLOAT,
		    'work_height' => Schema::TYPE_FLOAT,
		    'status' => Schema::TYPE_STRING . "(20)",
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		    'price_htva' => Schema::TYPE_FLOAT,
		    'price_tvac' => Schema::TYPE_FLOAT,
		    'item_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'extra_htva' => Schema::TYPE_FLOAT,
		    'extra_amount' => Schema::TYPE_FLOAT,
		    'extra_type' => Schema::TYPE_STRING . "(20)",
		    'due_date' => Schema::TYPE_DATETIME,
		], $this->tableOptions);

		// order_line_detail
		$this->createTable('{{%order_line_detail}}', [
		    'id' => Schema::TYPE_PK,
		    'order_line_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'chroma_id' => Schema::TYPE_INTEGER . "(11)",
		    'price_chroma' => Schema::TYPE_FLOAT,
		    'collage_id' => Schema::TYPE_INTEGER . "(11)",
		    'price_collage' => Schema::TYPE_FLOAT,
		    'frame_id' => Schema::TYPE_INTEGER . "(11)",
		    'price_frame' => Schema::TYPE_FLOAT,
		    'montage_bool' => Schema::TYPE_BOOLEAN,
		    'price_montage' => Schema::TYPE_FLOAT,
		    'protection_id' => Schema::TYPE_INTEGER . "(11)",
		    'price_protection' => Schema::TYPE_FLOAT,
		    'corner_bool' => Schema::TYPE_BOOLEAN,
		    'price_corner' => Schema::TYPE_FLOAT,
		    'support_id' => Schema::TYPE_INTEGER . "(11)",
		    'price_support' => Schema::TYPE_FLOAT,
		    'tirage_id' => Schema::TYPE_INTEGER . "(11)",
		    'price_tirage' => Schema::TYPE_FLOAT,
		    'renfort_bool' => Schema::TYPE_BOOLEAN,
		    'price_renfort' => Schema::TYPE_FLOAT,
		    'finish_id' => Schema::TYPE_INTEGER . "(11)",
		    'note' => Schema::TYPE_STRING . "(160)",
		], $this->tableOptions);

		// order_line_option
		$this->createTable('{{%order_line_option}}', [
		    'id' => Schema::TYPE_PK,
		    'order_line_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'option_id' => Schema::TYPE_INTEGER . "(11)",
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		    'item_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'price' => Schema::TYPE_FLOAT,
		    'note' => Schema::TYPE_STRING . "(160)",
		], $this->tableOptions);

		// parameter
		$this->createTable('{{%parameter}}', [
		    'domain' => Schema::TYPE_PK,
		    'name' => Schema::TYPE_PK,
		    'value_text' => Schema::TYPE_STRING . "(80)",
		    'value_number' => Schema::TYPE_FLOAT,
		    'value_int' => Schema::TYPE_INTEGER . "(11)",
		    'value_date' => Schema::TYPE_DATETIME,
		], $this->tableOptions);

		// picture
		$this->createTable('{{%picture}}', [
		    'id' => Schema::TYPE_PK,
		    'name' => Schema::TYPE_STRING . "(80) NOT NULL",
		    'order_line_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		    'mimetype' => Schema::TYPE_STRING . "(80) NOT NULL",
		    'filename' => Schema::TYPE_STRING . "(255) NOT NULL",
		], $this->tableOptions);

		// profile
		$this->createTable('{{%profile}}', [
		    'user_id' => Schema::TYPE_PK,
		    'name' => Schema::TYPE_STRING . "(255)",
		    'public_email' => Schema::TYPE_STRING . "(255)",
		    'gravatar_email' => Schema::TYPE_STRING . "(255)",
		    'gravatar_id' => Schema::TYPE_STRING . "(32)",
		    'location' => Schema::TYPE_STRING . "(255)",
		    'website' => Schema::TYPE_STRING . "(255)",
		    'bio' => Schema::TYPE_TEXT,
		], $this->tableOptions);

		// sequence_data
		$this->createTable('{{%sequence_data}}', [
		    'sequence_name' => Schema::TYPE_PK,
		    'sequence_increment' => Schema::TYPE_INTEGER . "(11) UNSIGNED NOT NULL DEFAULT '1'",
		    'sequence_min_value' => Schema::TYPE_INTEGER . "(11) UNSIGNED NOT NULL DEFAULT '1'",
		    'sequence_max_value' => Schema::TYPE_BIGINT . "(20) UNSIGNED NOT NULL DEFAULT '18446744073709551615'",
		    'sequence_cur_value' => Schema::TYPE_BIGINT . "(20) UNSIGNED DEFAULT '1'",
		    'sequence_cycle' => Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT '0'",
		], $this->tableOptions);

		// social_account
		$this->createTable('{{%social_account}}', [
		    'id' => Schema::TYPE_PK,
		    'user_id' => Schema::TYPE_INTEGER . "(11)",
		    'provider' => Schema::TYPE_STRING . "(255) NOT NULL",
		    'client_id' => Schema::TYPE_STRING . "(255) NOT NULL",
		    'data' => Schema::TYPE_TEXT,
		], $this->tableOptions);

		// task
		$this->createTable('{{%task}}', [
		    'id' => Schema::TYPE_PK,
		    'name' => Schema::TYPE_STRING . "(80)",
		    'note' => Schema::TYPE_STRING . "(160)",
		    'first_run' => Schema::TYPE_FLOAT,
		    'next_run' => Schema::TYPE_FLOAT,
		    'unit_cost' => Schema::TYPE_FLOAT,
		    'status' => Schema::TYPE_STRING . "(20)",
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		    'icon' => Schema::TYPE_STRING . "(40)",
		], $this->tableOptions);

		// token
		$this->createTable('{{%token}}', [
		    'user_id' => Schema::TYPE_PK,
		    'code' => Schema::TYPE_PK,
		    'created_at' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'type' => Schema::TYPE_PK,
		], $this->tableOptions);

		// user
		$this->createTable('{{%user}}', [
		    'id' => Schema::TYPE_PK,
		    'username' => Schema::TYPE_STRING . "(25) NOT NULL",
		    'email' => Schema::TYPE_STRING . "(255) NOT NULL",
		    'password_hash' => Schema::TYPE_STRING . "(60) NOT NULL",
		    'auth_key' => Schema::TYPE_STRING . "(32) NOT NULL",
		    'confirmed_at' => Schema::TYPE_INTEGER . "(11)",
		    'unconfirmed_email' => Schema::TYPE_STRING . "(255)",
		    'blocked_at' => Schema::TYPE_INTEGER . "(11)",
		    'role' => Schema::TYPE_STRING . "(255)",
		    'registration_ip' => Schema::TYPE_BIGINT . "(20)",
		    'created_at' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'updated_at' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'flags' => Schema::TYPE_INTEGER . "(11) NOT NULL DEFAULT '0'",
		], $this->tableOptions);

		// work
		$this->createTable('{{%work}}', [
		    'id' => Schema::TYPE_PK,
		    'order_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		    'created_by' => Schema::TYPE_INTEGER . "(11)",
		    'updated_by' => Schema::TYPE_INTEGER . "(11)",
		    'status' => Schema::TYPE_STRING . "(20)",
		    'due_date' => Schema::TYPE_DATETIME . " NOT NULL",
		], $this->tableOptions);

		// work_line
		$this->createTable('{{%work_line}}', [
		    'id' => Schema::TYPE_PK,
		    'work_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'created_at' => Schema::TYPE_DATETIME,
		    'updated_at' => Schema::TYPE_DATETIME,
		    'created_by' => Schema::TYPE_INTEGER . "(11)",
		    'updated_by' => Schema::TYPE_INTEGER . "(11)",
		    'status' => Schema::TYPE_STRING . "(20)",
		    'note' => Schema::TYPE_STRING . "(160)",
		    'order_line_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'task_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'position' => Schema::TYPE_INTEGER . "(11)",
		    'item_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
		    'due_date' => Schema::TYPE_DATETIME . " NOT NULL",
		], $this->tableOptions);

		// fk: extraction
		$this->addForeignKey('fk_extraction_order_to', '{{%extraction}}', 'order_to', '{{%order}}', 'id');
		$this->addForeignKey('fk_extraction_order_from', '{{%extraction}}', 'order_from', '{{%order}}', 'id');

		// fk: extraction_line
		$this->addForeignKey('fk_extraction_line_object_id', '{{%extraction_line}}', 'object_id', '{{%extraction}}', 'id');
		$this->addForeignKey('fk_extraction_line_extraction_id', '{{%extraction_line}}', 'extraction_id', '{{%extraction}}', 'id');

		// fk: item_option
		$this->addForeignKey('fk_item_option_option_id', '{{%item_option}}', 'option_id', '{{%option}}', 'id');
		$this->addForeignKey('fk_item_option_item_id', '{{%item_option}}', 'item_id', '{{%item}}', 'id');

		// fk: item_task
		$this->addForeignKey('fk_item_task_task_id', '{{%item_task}}', 'task_id', '{{%task}}', 'id');
		$this->addForeignKey('fk_item_task_item_id', '{{%item_task}}', 'item_id', '{{%item}}', 'id');

		// fk: option
		$this->addForeignKey('fk_option_item_id', '{{%option}}', 'item_id', '{{%item}}', 'id');

		// fk: order
		$this->addForeignKey('fk_order_updated_by', '{{%order}}', 'updated_by', '{{%user}}', 'id');
		$this->addForeignKey('fk_order_parent_id', '{{%order}}', 'parent_id', '{{%order}}', 'id');
		$this->addForeignKey('fk_order_client_id', '{{%order}}', 'client_id', '{{%client}}', 'id');
		$this->addForeignKey('fk_order_created_by', '{{%order}}', 'created_by', '{{%user}}', 'id');

		// fk: order_line
		$this->addForeignKey('fk_order_line_item_id', '{{%order_line}}', 'item_id', '{{%item}}', 'id');
		$this->addForeignKey('fk_order_line_order_id', '{{%order_line}}', 'order_id', '{{%order}}', 'id');

		// fk: order_line_detail
		$this->addForeignKey('fk_order_line_detail_protection_id', '{{%order_line_detail}}', 'protection_id', '{{%item}}', 'id');
		$this->addForeignKey('fk_order_line_detail_order_line_id', '{{%order_line_detail}}', 'order_line_id', '{{%order_line}}', 'id');
		$this->addForeignKey('fk_order_line_detail_finish_id', '{{%order_line_detail}}', 'finish_id', '{{%item}}', 'id');
		$this->addForeignKey('fk_order_line_detail_support_id', '{{%order_line_detail}}', 'support_id', '{{%item}}', 'id');
		$this->addForeignKey('fk_order_line_detail_tirage_id', '{{%order_line_detail}}', 'tirage_id', '{{%item}}', 'id');
		$this->addForeignKey('fk_order_line_detail_collage_id', '{{%order_line_detail}}', 'collage_id', '{{%item}}', 'id');
		$this->addForeignKey('fk_order_line_detail_chroma_id', '{{%order_line_detail}}', 'chroma_id', '{{%item}}', 'id');
		$this->addForeignKey('fk_order_line_detail_frame_id', '{{%order_line_detail}}', 'frame_id', '{{%item}}', 'id');

		// fk: order_line_option
		$this->addForeignKey('fk_order_line_option_item_id', '{{%order_line_option}}', 'item_id', '{{%item}}', 'id');
		$this->addForeignKey('fk_order_line_option_order_line_id', '{{%order_line_option}}', 'order_line_id', '{{%order_line}}', 'id');
		$this->addForeignKey('fk_order_line_option_option_id', '{{%order_line_option}}', 'option_id', '{{%option}}', 'id');

		// fk: picture
		$this->addForeignKey('fk_picture_order_line_id', '{{%picture}}', 'order_line_id', '{{%order_line}}', 'id');

		// fk: profile
		$this->addForeignKey('fk_profile_user_id', '{{%profile}}', 'user_id', '{{%user}}', 'id');

		// fk: social_account
		$this->addForeignKey('fk_social_account_user_id', '{{%social_account}}', 'user_id', '{{%user}}', 'id');

		// fk: token
		$this->addForeignKey('fk_token_user_id', '{{%token}}', 'user_id', '{{%user}}', 'id');

		// fk: work
		$this->addForeignKey('fk_work_updated_by', '{{%work}}', 'updated_by', '{{%user}}', 'id');
		$this->addForeignKey('fk_work_order_id', '{{%work}}', 'order_id', '{{%order}}', 'id');
		$this->addForeignKey('fk_work_created_by', '{{%work}}', 'created_by', '{{%user}}', 'id');

		// fk: work_line
		$this->addForeignKey('fk_work_line_updated_by', '{{%work_line}}', 'updated_by', '{{%user}}', 'id');
		$this->addForeignKey('fk_work_line_work_id', '{{%work_line}}', 'work_id', '{{%work}}', 'id');
		$this->addForeignKey('fk_work_line_task_id', '{{%work_line}}', 'task_id', '{{%task}}', 'id');
		$this->addForeignKey('fk_work_line_item_id', '{{%work_line}}', 'item_id', '{{%item}}', 'id');
		$this->addForeignKey('fk_work_line_order_line_id', '{{%work_line}}', 'order_line_id', '{{%order_line}}', 'id');
		$this->addForeignKey('fk_work_line_created_by', '{{%work_line}}', 'created_by', '{{%user}}', 'id');
    }

    public function safeDown()
    {
		$this->dropTable('{{%backup}}');
		$this->dropTable('{{%book}}');
		$this->dropTable('{{%cities}}');
		$this->dropTable('{{%client}}');
		$this->dropTable('{{%extraction}}'); // fk: order_to, order_from
		$this->dropTable('{{%extraction_line}}'); // fk: object_id, extraction_id
		$this->dropTable('{{%history}}');
		$this->dropTable('{{%item}}');
		$this->dropTable('{{%item_copy}}');
		$this->dropTable('{{%item_option}}'); // fk: option_id, item_id
		$this->dropTable('{{%item_task}}'); // fk: task_id, item_id
		$this->dropTable('{{%option}}'); // fk: item_id
		$this->dropTable('{{%order}}'); // fk: updated_by, parent_id, client_id, created_by
		$this->dropTable('{{%order_line}}'); // fk: item_id, order_id
		$this->dropTable('{{%order_line_detail}}'); // fk: protection_id, order_line_id, finish_id, support_id, tirage_id, collage_id, chroma_id, frame_id
		$this->dropTable('{{%order_line_option}}'); // fk: item_id, order_line_id, option_id
		$this->dropTable('{{%parameter}}');
		$this->dropTable('{{%picture}}'); // fk: order_line_id
		$this->dropTable('{{%profile}}'); // fk: user_id
		$this->dropTable('{{%sequence_data}}');
		$this->dropTable('{{%social_account}}'); // fk: user_id
		$this->dropTable('{{%task}}');
		$this->dropTable('{{%token}}'); // fk: user_id
		$this->dropTable('{{%user}}');
		$this->dropTable('{{%work}}'); // fk: updated_by, order_id, created_by
		$this->dropTable('{{%work_line}}'); // fk: updated_by, work_id, task_id, item_id, order_line_id, created_by
    }
}
