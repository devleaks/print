<?
namespace app\models;

use yii\db\ActiveQuery;

class DocumentQuery extends ActiveQuery {

	/**
	 * Creates a query for document that have uncompleted work
	 */
    public function active()
    {
        $this->andWhere(['status' => [
			Document::STATUS_OPEN,
			Document::STATUS_TODO,
			Document::STATUS_BUSY,
			Document::STATUS_WARN,
		]]);
        return $this;
    }
}