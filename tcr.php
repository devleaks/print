// account
$this->createTable('{{%account}}', [
    'id' => Schema::TYPE_PK,
    'client_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'document_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'sale' => Schema::TYPE_INTEGER . "(11) NULL",
    'amount' => Schema::TYPE_FLOAT . " NOT NULL",
    'payment_date' => Schema::TYPE_DATETIME . " NULL",
    'payment_method' => Schema::TYPE_STRING . "(20) NULL",
    'note' => Schema::TYPE_STRING . "(160) NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'created_by' => Schema::TYPE_INTEGER . "(11) NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_by' => Schema::TYPE_INTEGER . "(11) NULL",
], $this->tableOptions);

// accounting_journal
$this->createTable('{{%accounting_journal}}', [
    'code' => Schema::TYPE_STRING . "(40) NULL",
    'name' => Schema::TYPE_STRING . "(40) NULL",
], $this->tableOptions);

// backup
$this->createTable('{{%backup}}', [
    'id' => Schema::TYPE_INTEGER . "(11) UNSIGNED NOT NULL AUTO_INCREMENT",
    'filename' => Schema::TYPE_STRING . "(250) NOT NULL DEFAULT ''",
    'status' => Schema::TYPE_STRING . "(20) NOT NULL DEFAULT ''",
    'created_at' => Schema::TYPE_DATETIME . " NOT NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NOT NULL",
    'PRIMARY KEY (id)',
], $this->tableOptions);

// bank_transaction
$this->createTable('{{%bank_transaction}}', [
    'id' => Schema::TYPE_PK,
    'name' => Schema::TYPE_STRING . "(20) NOT NULL",
    'execution_date' => Schema::TYPE_DATETIME . " NOT NULL",
    'amount' => Schema::TYPE_FLOAT . " NOT NULL",
    'currency' => Schema::TYPE_STRING . "(20) NOT NULL",
    'source' => Schema::TYPE_STRING . "(40) NOT NULL",
    'note' => Schema::TYPE_STRING . "(160) NULL",
    'account' => Schema::TYPE_STRING . "(40) NOT NULL",
    'status' => Schema::TYPE_STRING . "(20) NOT NULL",
    'created_at' => Schema::TYPE_DATETIME . " NOT NULL",
], $this->tableOptions);

// cash
$this->createTable('{{%cash}}', [
    'id' => Schema::TYPE_PK,
    'document_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'sale' => Schema::TYPE_INTEGER . "(11) NULL",
    'amount' => Schema::TYPE_FLOAT . " NOT NULL",
    'payment_date' => Schema::TYPE_DATETIME . " NULL",
    'note' => Schema::TYPE_STRING . "(160) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'created_by' => Schema::TYPE_INTEGER . "(11) NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_by' => Schema::TYPE_INTEGER . "(11) NULL",
], $this->tableOptions);

// client
$this->createTable('{{%client}}', [
    'id' => Schema::TYPE_PK,
    'reference_interne' => Schema::TYPE_STRING . "(80) NULL",
    'comptabilite' => Schema::TYPE_STRING . "(80) NOT NULL",
    'titre' => Schema::TYPE_STRING . "(80) NULL",
    'nom' => Schema::TYPE_STRING . "(80) NOT NULL",
    'prenom' => Schema::TYPE_STRING . "(80) NULL",
    'autre_nom' => Schema::TYPE_STRING . "(80) NULL",
    'adresse' => Schema::TYPE_STRING . "(80) NULL",
    'code_postal' => Schema::TYPE_STRING . "(80) NULL",
    'localite' => Schema::TYPE_STRING . "(80) NULL",
    'pays' => Schema::TYPE_STRING . "(80) NULL",
    'langue' => Schema::TYPE_STRING . "(80) NULL",
    'numero_tva' => Schema::TYPE_STRING . "(80) NULL",
    'email' => Schema::TYPE_STRING . "(80) NULL",
    'site_web' => Schema::TYPE_STRING . "(80) NULL",
    'domicile' => Schema::TYPE_STRING . "(80) NULL",
    'bureau' => Schema::TYPE_STRING . "(80) NULL",
    'gsm' => Schema::TYPE_STRING . "(80) NULL",
    'fax_prive' => Schema::TYPE_STRING . "(80) NULL",
    'fax_bureau' => Schema::TYPE_STRING . "(80) NULL",
    'pc' => Schema::TYPE_STRING . "(80) NULL",
    'autre' => Schema::TYPE_STRING . "(80) NULL",
    'remise' => Schema::TYPE_STRING . "(80) NULL",
    'escompte' => Schema::TYPE_STRING . "(80) NULL",
    'delais_de_paiement' => Schema::TYPE_STRING . "(80) NULL",
    'mentions' => Schema::TYPE_STRING . "(80) NULL",
    'exemplaires' => Schema::TYPE_STRING . "(80) NULL",
    'limite_de_credit' => Schema::TYPE_STRING . "(80) NULL",
    'formule' => Schema::TYPE_STRING . "(80) NULL",
    'type' => Schema::TYPE_STRING . "(80) NULL",
    'execution' => Schema::TYPE_STRING . "(80) NULL",
    'support' => Schema::TYPE_STRING . "(80) NULL",
    'format' => Schema::TYPE_STRING . "(80) NULL",
    'mise_a_jour' => Schema::TYPE_DATE . " NULL",
    'mailing' => Schema::TYPE_STRING . "(80) NULL",
    'outlook' => Schema::TYPE_STRING . "(80) NULL",
    'categorie_de_client' => Schema::TYPE_STRING . "(80) NULL",
    'operation' => Schema::TYPE_STRING . "(80) NULL",
    'categorie_de_prix_de_vente' => Schema::TYPE_STRING . "(80) NULL",
    'reference_1' => Schema::TYPE_STRING . "(80) NULL",
    'date_limite_1' => Schema::TYPE_STRING . "(80) NULL",
    'reference_2' => Schema::TYPE_STRING . "(80) NULL",
    'date_limite_2' => Schema::TYPE_STRING . "(80) NULL",
    'reference_3' => Schema::TYPE_STRING . "(80) NULL",
    'date_limite_3' => Schema::TYPE_STRING . "(80) NULL",
    'commentaires' => Schema::TYPE_STRING . "(255) NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
    'lang' => Schema::TYPE_STRING . "(20) NULL",
    'comm_pref' => Schema::TYPE_STRING . "(20) NULL",
    'comm_format' => Schema::TYPE_STRING . "(20) NULL",
], $this->tableOptions);

// document
$this->createTable('{{%document}}', [
    'id' => Schema::TYPE_PK,
    'document_type' => Schema::TYPE_STRING . "(20) NULL",
    'name' => Schema::TYPE_STRING . "(20) NOT NULL",
    'sale' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'reference' => Schema::TYPE_STRING . "(40) NULL",
    'reference_client' => Schema::TYPE_STRING . "(40) NULL",
    'parent_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'client_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'due_date' => Schema::TYPE_DATETIME . " NOT NULL",
    'price_htva' => Schema::TYPE_FLOAT . " NULL",
    'price_tvac' => Schema::TYPE_FLOAT . " NULL",
    'vat' => Schema::TYPE_FLOAT . " NULL",
    'vat_bool' => Schema::TYPE_BOOLEAN . " NULL",
    'bom_bool' => Schema::TYPE_BOOLEAN . " NULL",
    'note' => Schema::TYPE_STRING . "(160) NULL",
    'lang' => Schema::TYPE_STRING . "(20) NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'created_by' => Schema::TYPE_INTEGER . "(11) NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_by' => Schema::TYPE_INTEGER . "(11) NULL",
    'priority' => Schema::TYPE_INTEGER . "(11) NULL",
    'legal' => Schema::TYPE_STRING . "(160) NULL",
], $this->tableOptions);

// document_line
$this->createTable('{{%document_line}}', [
    'id' => Schema::TYPE_PK,
    'document_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'item_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'position' => Schema::TYPE_INTEGER . "(11) NULL",
    'work_width' => Schema::TYPE_FLOAT . " NULL",
    'work_height' => Schema::TYPE_FLOAT . " NULL",
    'unit_price' => Schema::TYPE_FLOAT . " NULL",
    'quantity' => Schema::TYPE_FLOAT . " NOT NULL",
    'extra_type' => Schema::TYPE_STRING . "(20) NULL",
    'extra_amount' => Schema::TYPE_FLOAT . " NULL",
    'extra_htva' => Schema::TYPE_FLOAT . " NULL",
    'price_htva' => Schema::TYPE_FLOAT . " NULL",
    'price_tvac' => Schema::TYPE_FLOAT . " NULL",
    'vat' => Schema::TYPE_FLOAT . " NULL",
    'due_date' => Schema::TYPE_DATETIME . " NULL",
    'note' => Schema::TYPE_STRING . "(160) NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
    'priority' => Schema::TYPE_INTEGER . "(11) NULL",
    'location' => Schema::TYPE_STRING . "(40) NULL",
    'parent_id' => Schema::TYPE_INTEGER . "(11) NULL",
], $this->tableOptions);

// document_line_detail
$this->createTable('{{%document_line_detail}}', [
    'id' => Schema::TYPE_PK,
    'document_line_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'note' => Schema::TYPE_STRING . "(160) NULL",
    'chroma_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'price_chroma' => Schema::TYPE_FLOAT . " NULL",
    'corner_bool' => Schema::TYPE_BOOLEAN . " NULL",
    'price_corner' => Schema::TYPE_FLOAT . " NULL",
    'renfort_bool' => Schema::TYPE_BOOLEAN . " NULL",
    'price_renfort' => Schema::TYPE_FLOAT . " NULL",
    'frame_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'price_frame' => Schema::TYPE_FLOAT . " NULL",
    'montage_bool' => Schema::TYPE_BOOLEAN . " NULL",
    'price_montage' => Schema::TYPE_FLOAT . " NULL",
    'finish_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'support_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'price_support' => Schema::TYPE_FLOAT . " NULL",
    'tirage_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'price_tirage' => Schema::TYPE_FLOAT . " NULL",
    'collage_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'price_collage' => Schema::TYPE_FLOAT . " NULL",
    'protection_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'price_protection' => Schema::TYPE_FLOAT . " NULL",
    'chassis_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'price_chassis' => Schema::TYPE_FLOAT . " NULL",
    'filmuv_bool' => Schema::TYPE_BOOLEAN . " NULL",
    'price_filmuv' => Schema::TYPE_FLOAT . " NULL",
    'tirage_factor' => Schema::TYPE_FLOAT . " NULL",
], $this->tableOptions);

// document_line_option
$this->createTable('{{%document_line_option}}', [
    'id' => Schema::TYPE_PK,
    'document_line_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'option_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'item_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'price' => Schema::TYPE_FLOAT . " NULL",
    'note' => Schema::TYPE_STRING . "(160) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
], $this->tableOptions);

// document_size
$this->createTable('{{%document_size}}', [
    'quantity' => Schema::TYPE_FLOAT . " NOT NULL DEFAULT '0'",
    'largest' => Schema::TYPE_FLOAT . " NULL",
    'shortest' => Schema::TYPE_FLOAT . " NULL",
], $this->tableOptions);

// event
$this->createTable('{{%event}}', [
    'id' => Schema::TYPE_PK,
    'name' => Schema::TYPE_STRING . "(80) NOT NULL",
    'date_from' => Schema::TYPE_DATETIME . " NULL",
    'date_to' => Schema::TYPE_DATETIME . " NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
    'event_type' => Schema::TYPE_STRING . "(40) NULL",
], $this->tableOptions);

// extraction
$this->createTable('{{%extraction}}', [
    'id' => Schema::TYPE_PK,
    'extraction_type' => Schema::TYPE_STRING . "(20) NULL",
    'extraction_method' => Schema::TYPE_STRING . "(20) NULL",
    'date_from' => Schema::TYPE_DATETIME . " NULL",
    'date_to' => Schema::TYPE_DATETIME . " NULL",
    'document_from' => Schema::TYPE_INTEGER . "(11) NULL",
    'document_to' => Schema::TYPE_INTEGER . "(11) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
], $this->tableOptions);

// history
$this->createTable('{{%history}}', [
    'id' => Schema::TYPE_PK,
    'object_type' => Schema::TYPE_TEXT . " NULL",
    'object_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'action' => Schema::TYPE_STRING . "(20) NOT NULL",
    'old_value' => Schema::TYPE_STRING . "(40) NULL",
    'name' => Schema::TYPE_STRING . "(80) NULL",
    'note' => Schema::TYPE_STRING . "(255) NULL",
    'performer_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
], $this->tableOptions);

// item
$this->createTable('{{%item}}', [
    'id' => Schema::TYPE_PK,
    'yii_category' => Schema::TYPE_STRING . "(20) NULL",
    'comptabilite' => Schema::TYPE_STRING . "(20) NULL",
    'reference' => Schema::TYPE_STRING . "(20) NULL",
    'libelle_court' => Schema::TYPE_STRING . "(40) NULL",
    'libelle_long' => Schema::TYPE_STRING . "(80) NULL",
    'categorie' => Schema::TYPE_STRING . "(20) NULL",
    'prix_de_vente' => Schema::TYPE_FLOAT . " NULL",
    'taux_de_tva' => Schema::TYPE_FLOAT . " NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'type_travaux_photos' => Schema::TYPE_STRING . "(20) NULL",
    'type_numerique' => Schema::TYPE_STRING . "(20) NULL",
    'fournisseur' => Schema::TYPE_STRING . "(20) NULL",
    'reference_fournisseur' => Schema::TYPE_STRING . "(20) NULL",
    'conditionnement' => Schema::TYPE_STRING . "(20) NULL",
    'prix_d_achat_de_reference' => Schema::TYPE_STRING . "(20) NULL",
    'client' => Schema::TYPE_STRING . "(40) NULL",
    'quantite' => Schema::TYPE_INTEGER . "(11) NULL",
    'date_initiale' => Schema::TYPE_DATE . " NULL",
    'date_finale' => Schema::TYPE_DATE . " NULL",
    'suivi_de_stock' => Schema::TYPE_STRING . "(20) NULL",
    'reassort_possible' => Schema::TYPE_STRING . "(20) NULL",
    'seuil_de_commande' => Schema::TYPE_STRING . "(20) NULL",
    'site_internet' => Schema::TYPE_STRING . "(80) NULL",
    'creation' => Schema::TYPE_DATE . " NULL",
    'mise_a_jour' => Schema::TYPE_DATE . " NULL",
    'en_cours' => Schema::TYPE_STRING . "(20) NULL",
    'stock' => Schema::TYPE_STRING . "(20) NULL",
    'commentaires' => Schema::TYPE_STRING . "(80) NULL",
    'identification' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
], $this->tableOptions);

// item_copy2
$this->createTable('{{%item_copy2}}', [
    'id' => Schema::TYPE_PK,
    'yii_category' => Schema::TYPE_STRING . "(20) NULL",
    'comptabilite' => Schema::TYPE_STRING . "(20) NULL",
    'reference' => Schema::TYPE_STRING . "(20) NULL",
    'libelle_court' => Schema::TYPE_STRING . "(40) NULL",
    'libelle_long' => Schema::TYPE_STRING . "(80) NULL",
    'categorie' => Schema::TYPE_STRING . "(20) NULL",
    'prix_de_vente' => Schema::TYPE_FLOAT . " NULL",
    'taux_de_tva' => Schema::TYPE_FLOAT . " NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'type_travaux_photos' => Schema::TYPE_STRING . "(20) NULL",
    'type_numerique' => Schema::TYPE_STRING . "(20) NULL",
    'fournisseur' => Schema::TYPE_STRING . "(20) NULL",
    'reference_fournisseur' => Schema::TYPE_STRING . "(20) NULL",
    'conditionnement' => Schema::TYPE_STRING . "(20) NULL",
    'prix_d_achat_de_reference' => Schema::TYPE_STRING . "(20) NULL",
    'client' => Schema::TYPE_STRING . "(40) NULL",
    'quantite' => Schema::TYPE_INTEGER . "(11) NULL",
    'date_initiale' => Schema::TYPE_DATE . " NULL",
    'date_finale' => Schema::TYPE_DATE . " NULL",
    'suivi_de_stock' => Schema::TYPE_STRING . "(20) NULL",
    'reassort_possible' => Schema::TYPE_STRING . "(20) NULL",
    'seuil_de_commande' => Schema::TYPE_STRING . "(20) NULL",
    'site_internet' => Schema::TYPE_STRING . "(80) NULL",
    'creation' => Schema::TYPE_DATE . " NULL",
    'mise_a_jour' => Schema::TYPE_DATE . " NULL",
    'en_cours' => Schema::TYPE_STRING . "(20) NULL",
    'stock' => Schema::TYPE_STRING . "(20) NULL",
    'commentaires' => Schema::TYPE_STRING . "(80) NULL",
    'identification' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
], $this->tableOptions);

// item_option
$this->createTable('{{%item_option}}', [
    'id' => Schema::TYPE_PK,
    'item_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'option_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'position' => Schema::TYPE_INTEGER . "(11) NULL",
    'note' => Schema::TYPE_STRING . "(160) NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
    'mandatory' => Schema::TYPE_BOOLEAN . " NULL",
    'shownote' => Schema::TYPE_BOOLEAN . " NULL",
], $this->tableOptions);

// item_task
$this->createTable('{{%item_task}}', [
    'id' => Schema::TYPE_PK,
    'item_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'task_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'note' => Schema::TYPE_STRING . "(160) NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
    'position' => Schema::TYPE_INTEGER . "(11) NULL",
], $this->tableOptions);

// master
$this->createTable('{{%master}}', [
    'id' => Schema::TYPE_PK,
    'work_length' => Schema::TYPE_FLOAT . " NOT NULL",
    'keep' => Schema::TYPE_BOOLEAN . " NULL DEFAULT '0'",
    'note' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
], $this->tableOptions);

// option
$this->createTable('{{%option}}', [
    'id' => Schema::TYPE_PK,
    'item_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'option_type' => Schema::TYPE_STRING . "(20) NOT NULL",
    'name' => Schema::TYPE_STRING . "(20) NOT NULL",
    'categorie' => Schema::TYPE_STRING . "(20) NOT NULL",
    'label' => Schema::TYPE_STRING . "(20) NOT NULL",
    'note' => Schema::TYPE_STRING . "(160) NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
], $this->tableOptions);

// parameter
$this->createTable('{{%parameter}}', [
    'domain' => Schema::TYPE_STRING . "(20) NOT NULL",
    'name' => Schema::TYPE_STRING . "(40) NOT NULL",
    'lang' => Schema::TYPE_STRING . "(20) NOT NULL",
    'value_text' => Schema::TYPE_STRING . "(160) NULL",
    'value_number' => Schema::TYPE_FLOAT . " NULL",
    'value_int' => Schema::TYPE_INTEGER . "(11) NULL",
    'value_date' => Schema::TYPE_DATETIME . " NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
    'PRIMARY KEY (domain, name, lang)',
], $this->tableOptions);

// payment
$this->createTable('{{%payment}}', [
    'id' => Schema::TYPE_PK,
    'client_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'sale' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'amount' => Schema::TYPE_FLOAT . " NULL",
    'payment_method' => Schema::TYPE_STRING . "(20) NULL",
    'note' => Schema::TYPE_STRING . "(160) NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'created_by' => Schema::TYPE_INTEGER . "(11) NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_by' => Schema::TYPE_INTEGER . "(11) NULL",
], $this->tableOptions);

// pdf
$this->createTable('{{%pdf}}', [
    'id' => Schema::TYPE_PK,
    'document_type' => Schema::TYPE_STRING . "(40) NULL",
    'document_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'client_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'filename' => Schema::TYPE_STRING . "(255) NOT NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
    'sent_at' => Schema::TYPE_DATETIME . " NULL",
], $this->tableOptions);

// picture
$this->createTable('{{%picture}}', [
    'id' => Schema::TYPE_PK,
    'name' => Schema::TYPE_STRING . "(80) NOT NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
    'mimetype' => Schema::TYPE_STRING . "(80) NOT NULL",
    'filename' => Schema::TYPE_STRING . "(255) NOT NULL",
    'document_line_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
], $this->tableOptions);

// price_list
$this->createTable('{{%price_list}}', [
    'id' => Schema::TYPE_PK,
    'name' => Schema::TYPE_STRING . "(80) NOT NULL",
    'note' => Schema::TYPE_STRING . "(160) NULL",
    'sizes' => Schema::TYPE_STRING . "(255) NOT NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
], $this->tableOptions);

// price_list_item
$this->createTable('{{%price_list_item}}', [
    'id' => Schema::TYPE_PK,
    'price_list_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'item_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'position' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
], $this->tableOptions);

// profile
$this->createTable('{{%profile}}', [
    'user_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'name' => Schema::TYPE_STRING . "(255) NULL",
    'public_email' => Schema::TYPE_STRING . "(255) NULL",
    'gravatar_email' => Schema::TYPE_STRING . "(255) NULL",
    'gravatar_id' => Schema::TYPE_STRING . "(32) NULL",
    'location' => Schema::TYPE_STRING . "(255) NULL",
    'website' => Schema::TYPE_STRING . "(255) NULL",
    'bio' => Schema::TYPE_TEXT . " NULL",
    'PRIMARY KEY (user_id)',
], $this->tableOptions);

// provider
$this->createTable('{{%provider}}', [
    'id' => Schema::TYPE_PK,
    'name' => Schema::TYPE_STRING . "(80) NOT NULL",
    'email' => Schema::TYPE_STRING . "(80) NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
], $this->tableOptions);

// segment
$this->createTable('{{%segment}}', [
    'id' => Schema::TYPE_PK,
    'document_line_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'master_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'work_length' => Schema::TYPE_FLOAT . " NOT NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
], $this->tableOptions);

// sequence_data
$this->createTable('{{%sequence_data}}', [
    'sequence_name' => Schema::TYPE_STRING . "(100) NOT NULL",
    'sequence_increment' => Schema::TYPE_INTEGER . "(11) UNSIGNED NOT NULL DEFAULT '1'",
    'sequence_min_value' => Schema::TYPE_INTEGER . "(11) UNSIGNED NOT NULL DEFAULT '1'",
    'sequence_max_value' => Schema::TYPE_BIGINT . "(20) UNSIGNED NOT NULL DEFAULT '18446744073709551615'",
    'sequence_cur_value' => Schema::TYPE_BIGINT . "(20) UNSIGNED NULL DEFAULT '1'",
    'sequence_cycle' => Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT '0'",
    'sequence_year' => Schema::TYPE_INTEGER . "(11) UNSIGNED NOT NULL DEFAULT '1'",
    'PRIMARY KEY (sequence_name)',
], $this->tableOptions);

// social_account
$this->createTable('{{%social_account}}', [
    'id' => Schema::TYPE_PK,
    'user_id' => Schema::TYPE_INTEGER . "(11) NULL",
    'provider' => Schema::TYPE_STRING . "(255) NOT NULL",
    'client_id' => Schema::TYPE_STRING . "(255) NOT NULL",
    'data' => Schema::TYPE_TEXT . " NULL",
], $this->tableOptions);

// split
$this->createTable('{{%split}}', [
    'id' => Schema::TYPE_PK,
    'id1' => Schema::TYPE_INTEGER . "(11) NULL",
    'id2' => Schema::TYPE_INTEGER . "(11) NULL",
], $this->tableOptions);

// task
$this->createTable('{{%task}}', [
    'id' => Schema::TYPE_PK,
    'name' => Schema::TYPE_STRING . "(80) NULL",
    'icon' => Schema::TYPE_STRING . "(40) NULL",
    'note' => Schema::TYPE_STRING . "(160) NULL",
    'first_run' => Schema::TYPE_FLOAT . " NULL",
    'next_run' => Schema::TYPE_FLOAT . " NULL",
    'unit_cost' => Schema::TYPE_FLOAT . " NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
], $this->tableOptions);

// tempo
$this->createTable('{{%tempo}}', [
    'yii_category' => Schema::TYPE_STRING . "(20) NULL",
    'reference' => Schema::TYPE_STRING . "(20) NULL",
], $this->tableOptions);

// token
$this->createTable('{{%token}}', [
    'user_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'code' => Schema::TYPE_STRING . "(32) NOT NULL",
    'created_at' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'type' => Schema::TYPE_SMALLINT . "(6) NOT NULL",
    'PRIMARY KEY (user_id, code, type)',
], $this->tableOptions);

// user
$this->createTable('{{%user}}', [
    'id' => Schema::TYPE_PK,
    'username' => Schema::TYPE_STRING . "(25) NOT NULL",
    'email' => Schema::TYPE_STRING . "(255) NOT NULL",
    'password_hash' => Schema::TYPE_STRING . "(60) NOT NULL",
    'auth_key' => Schema::TYPE_STRING . "(32) NOT NULL",
    'confirmed_at' => Schema::TYPE_INTEGER . "(11) NULL",
    'unconfirmed_email' => Schema::TYPE_STRING . "(255) NULL",
    'blocked_at' => Schema::TYPE_INTEGER . "(11) NULL",
    'role' => Schema::TYPE_STRING . "(255) NULL",
    'registration_ip' => Schema::TYPE_BIGINT . "(20) NULL",
    'created_at' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'updated_at' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'flags' => Schema::TYPE_INTEGER . "(11) NOT NULL DEFAULT '0'",
], $this->tableOptions);

// work
$this->createTable('{{%work}}', [
    'id' => Schema::TYPE_PK,
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
    'created_by' => Schema::TYPE_INTEGER . "(11) NULL",
    'updated_by' => Schema::TYPE_INTEGER . "(11) NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'due_date' => Schema::TYPE_DATETIME . " NOT NULL",
    'document_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'priority' => Schema::TYPE_INTEGER . "(11) NULL",
], $this->tableOptions);

// work_line
$this->createTable('{{%work_line}}', [
    'id' => Schema::TYPE_PK,
    'work_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'created_at' => Schema::TYPE_DATETIME . " NULL",
    'updated_at' => Schema::TYPE_DATETIME . " NULL",
    'created_by' => Schema::TYPE_INTEGER . "(11) NULL",
    'updated_by' => Schema::TYPE_INTEGER . "(11) NULL",
    'status' => Schema::TYPE_STRING . "(20) NULL",
    'note' => Schema::TYPE_STRING . "(160) NULL",
    'task_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'position' => Schema::TYPE_INTEGER . "(11) NULL",
    'item_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'due_date' => Schema::TYPE_DATETIME . " NOT NULL",
    'document_line_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
    'priority' => Schema::TYPE_INTEGER . "(11) NULL",
], $this->tableOptions);

// fk: account
$this->addForeignKey('fk_account_client_id', '{{%account}}', 'client_id', '{{%client}}', 'id');
$this->addForeignKey('fk_account_document_id', '{{%account}}', 'document_id', '{{%document}}', 'id');
$this->addForeignKey('fk_account_created_by', '{{%account}}', 'created_by', '{{%user}}', 'id');
$this->addForeignKey('fk_account_updated_by', '{{%account}}', 'updated_by', '{{%user}}', 'id');

// fk: cash
$this->addForeignKey('fk_cash_document_id', '{{%cash}}', 'document_id', '{{%document}}', 'id');
$this->addForeignKey('fk_cash_created_by', '{{%cash}}', 'created_by', '{{%user}}', 'id');
$this->addForeignKey('fk_cash_updated_by', '{{%cash}}', 'updated_by', '{{%user}}', 'id');

// fk: document
$this->addForeignKey('fk_document_parent_id', '{{%document}}', 'parent_id', '{{%document}}', 'id');
$this->addForeignKey('fk_document_client_id', '{{%document}}', 'client_id', '{{%client}}', 'id');
$this->addForeignKey('fk_document_created_by', '{{%document}}', 'created_by', '{{%user}}', 'id');
$this->addForeignKey('fk_document_updated_by', '{{%document}}', 'updated_by', '{{%user}}', 'id');

// fk: document_line
$this->addForeignKey('fk_document_line_document_id', '{{%document_line}}', 'document_id', '{{%document}}', 'id');
$this->addForeignKey('fk_document_line_parent_id', '{{%document_line}}', 'parent_id', '{{%document_line}}', 'id');
$this->addForeignKey('fk_document_line_item_id', '{{%document_line}}', 'item_id', '{{%item}}', 'id');

// fk: document_line_detail
$this->addForeignKey('fk_document_line_detail_chroma_id', '{{%document_line_detail}}', 'chroma_id', '{{%item}}', 'id');
$this->addForeignKey('fk_document_line_detail_frame_id', '{{%document_line_detail}}', 'frame_id', '{{%item}}', 'id');
$this->addForeignKey('fk_document_line_detail_chassis_id', '{{%document_line_detail}}', 'chassis_id', '{{%item}}', 'id');
$this->addForeignKey('fk_document_line_detail_support_id', '{{%document_line_detail}}', 'support_id', '{{%item}}', 'id');
$this->addForeignKey('fk_document_line_detail_tirage_id', '{{%document_line_detail}}', 'tirage_id', '{{%item}}', 'id');
$this->addForeignKey('fk_document_line_detail_collage_id', '{{%document_line_detail}}', 'collage_id', '{{%item}}', 'id');
$this->addForeignKey('fk_document_line_detail_protection_id', '{{%document_line_detail}}', 'protection_id', '{{%item}}', 'id');
$this->addForeignKey('fk_document_line_detail_finish_id', '{{%document_line_detail}}', 'finish_id', '{{%item}}', 'id');
$this->addForeignKey('fk_document_line_detail_document_line_id', '{{%document_line_detail}}', 'document_line_id', '{{%document_line}}', 'id');

// fk: document_line_option
$this->addForeignKey('fk_document_line_option_document_line_id', '{{%document_line_option}}', 'document_line_id', '{{%document_line}}', 'id');
$this->addForeignKey('fk_document_line_option_option_id', '{{%document_line_option}}', 'option_id', '{{%option}}', 'id');
$this->addForeignKey('fk_document_line_option_item_id', '{{%document_line_option}}', 'item_id', '{{%item}}', 'id');

// fk: extraction
$this->addForeignKey('fk_extraction_document_from', '{{%extraction}}', 'document_from', '{{%document}}', 'id');
$this->addForeignKey('fk_extraction_document_to', '{{%extraction}}', 'document_to', '{{%document}}', 'id');

// fk: item_option
$this->addForeignKey('fk_item_option_option_id', '{{%item_option}}', 'option_id', '{{%option}}', 'id');
$this->addForeignKey('fk_item_option_item_id', '{{%item_option}}', 'item_id', '{{%item}}', 'id');

// fk: item_task
$this->addForeignKey('fk_item_task_item_id', '{{%item_task}}', 'item_id', '{{%item}}', 'id');
$this->addForeignKey('fk_item_task_task_id', '{{%item_task}}', 'task_id', '{{%task}}', 'id');

// fk: option
$this->addForeignKey('fk_option_item_id', '{{%option}}', 'item_id', '{{%item}}', 'id');

// fk: payment
$this->addForeignKey('fk_payment_client_id', '{{%payment}}', 'client_id', '{{%client}}', 'id');
$this->addForeignKey('fk_payment_created_by', '{{%payment}}', 'created_by', '{{%user}}', 'id');
$this->addForeignKey('fk_payment_updated_by', '{{%payment}}', 'updated_by', '{{%user}}', 'id');

// fk: pdf
$this->addForeignKey('fk_pdf_document_id', '{{%pdf}}', 'document_id', '{{%document}}', 'id');
$this->addForeignKey('fk_pdf_client_id', '{{%pdf}}', 'client_id', '{{%client}}', 'id');

// fk: picture
$this->addForeignKey('fk_picture_document_line_id', '{{%picture}}', 'document_line_id', '{{%document_line}}', 'id');

// fk: price_list_item
$this->addForeignKey('fk_price_list_item_price_list_id', '{{%price_list_item}}', 'price_list_id', '{{%price_list}}', 'id');
$this->addForeignKey('fk_price_list_item_item_id', '{{%price_list_item}}', 'item_id', '{{%item}}', 'id');

// fk: profile
$this->addForeignKey('fk_profile_user_id', '{{%profile}}', 'user_id', '{{%user}}', 'id');

// fk: segment
$this->addForeignKey('fk_segment_document_line_id', '{{%segment}}', 'document_line_id', '{{%document_line}}', 'id');
$this->addForeignKey('fk_segment_master_id', '{{%segment}}', 'master_id', '{{%master}}', 'id');

// fk: social_account
$this->addForeignKey('fk_social_account_user_id', '{{%social_account}}', 'user_id', '{{%user}}', 'id');

// fk: split
$this->addForeignKey('fk_split_id1', '{{%split}}', 'id1', '{{%segment}}', 'id');
$this->addForeignKey('fk_split_id2', '{{%split}}', 'id2', '{{%segment}}', 'id');

// fk: token
$this->addForeignKey('fk_token_user_id', '{{%token}}', 'user_id', '{{%user}}', 'id');

// fk: work
$this->addForeignKey('fk_work_document_id', '{{%work}}', 'document_id', '{{%document}}', 'id');
$this->addForeignKey('fk_work_created_by', '{{%work}}', 'created_by', '{{%user}}', 'id');
$this->addForeignKey('fk_work_updated_by', '{{%work}}', 'updated_by', '{{%user}}', 'id');

// fk: work_line
$this->addForeignKey('fk_work_line_work_id', '{{%work_line}}', 'work_id', '{{%work}}', 'id');
$this->addForeignKey('fk_work_line_task_id', '{{%work_line}}', 'task_id', '{{%task}}', 'id');
$this->addForeignKey('fk_work_line_item_id', '{{%work_line}}', 'item_id', '{{%item}}', 'id');
$this->addForeignKey('fk_work_line_document_line_id', '{{%work_line}}', 'document_line_id', '{{%document_line}}', 'id');
$this->addForeignKey('fk_work_line_created_by', '{{%work_line}}', 'created_by', '{{%user}}', 'id');
$this->addForeignKey('fk_work_line_updated_by', '{{%work_line}}', 'updated_by', '{{%user}}', 'id');

[trace]	Bootstrap with dektrium\user\Bootstrap::bootstrap()
[trace]	Loading module: user
[trace]	Bootstrap with mdm\behaviors\ar\Bootstrap::bootstrap()
[trace]	Bootstrap with yii\log\Dispatcher
[trace]	Loading module: gii
[trace]	Bootstrap with yii\gii\Module::bootstrap()
[trace]	Route to run: schemadump/create
[trace]	Running action: jamband\schemadump\SchemaDumpController::actionCreate()
