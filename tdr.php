$this->dropTable('{{%account}}'); // fk: client_id, document_id, created_by, updated_by
$this->dropTable('{{%accounting_journal}}');
$this->dropTable('{{%backup}}');
$this->dropTable('{{%bank_transaction}}');
$this->dropTable('{{%cash}}'); // fk: document_id, created_by, updated_by
$this->dropTable('{{%client}}');
$this->dropTable('{{%document}}'); // fk: parent_id, client_id, created_by, updated_by
$this->dropTable('{{%document_line}}'); // fk: document_id, parent_id, item_id
$this->dropTable('{{%document_line_detail}}'); // fk: chroma_id, frame_id, chassis_id, support_id, tirage_id, collage_id, protection_id, finish_id, document_line_id
$this->dropTable('{{%document_line_option}}'); // fk: document_line_id, option_id, item_id
$this->dropTable('{{%document_size}}');
$this->dropTable('{{%event}}');
$this->dropTable('{{%extraction}}'); // fk: document_from, document_to
$this->dropTable('{{%history}}');
$this->dropTable('{{%item}}');
$this->dropTable('{{%item_copy2}}');
$this->dropTable('{{%item_option}}'); // fk: option_id, item_id
$this->dropTable('{{%item_task}}'); // fk: item_id, task_id
$this->dropTable('{{%master}}');
$this->dropTable('{{%option}}'); // fk: item_id
$this->dropTable('{{%parameter}}');
$this->dropTable('{{%payment}}'); // fk: client_id, created_by, updated_by
$this->dropTable('{{%pdf}}'); // fk: document_id, client_id
$this->dropTable('{{%picture}}'); // fk: document_line_id
$this->dropTable('{{%price_list}}');
$this->dropTable('{{%price_list_item}}'); // fk: price_list_id, item_id
$this->dropTable('{{%profile}}'); // fk: user_id
$this->dropTable('{{%provider}}');
$this->dropTable('{{%segment}}'); // fk: document_line_id, master_id
$this->dropTable('{{%sequence_data}}');
$this->dropTable('{{%social_account}}'); // fk: user_id
$this->dropTable('{{%split}}'); // fk: id1, id2
$this->dropTable('{{%task}}');
$this->dropTable('{{%tempo}}');
$this->dropTable('{{%token}}'); // fk: user_id
$this->dropTable('{{%user}}');
$this->dropTable('{{%work}}'); // fk: document_id, created_by, updated_by
$this->dropTable('{{%work_line}}'); // fk: work_id, task_id, item_id, document_line_id, created_by, updated_by
[trace]	Bootstrap with dektrium\user\Bootstrap::bootstrap()
[trace]	Loading module: user
[trace]	Bootstrap with mdm\behaviors\ar\Bootstrap::bootstrap()
[trace]	Bootstrap with yii\log\Dispatcher
[trace]	Loading module: gii
[trace]	Bootstrap with yii\gii\Module::bootstrap()
[trace]	Route to run: schemadump/drop
[trace]	Running action: jamband\schemadump\SchemaDumpController::actionDrop()
