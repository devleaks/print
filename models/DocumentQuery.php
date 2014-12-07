<?
namespace app\models;

use yii\db\ActiveQuery;

class DocumentQuery extends ActiveQuery {
    public $type;

    public function prepare($builder)
    {
        if ($this->type !== null) {
            $this->andWhere(['document_type' => $this->type]);
        }
        return parent::prepare($builder);
    }
}