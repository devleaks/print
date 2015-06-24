<?php

namespace app\models;

use Yii;
use app\models\Document;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * OrderSearch represents the model behind the search form about `app\models\Document`.
 */
class DocumentSearch extends Document
{
	public $bill_exists;
	public $search;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'client_id', 'created_by', 'updated_by', 'vat_bool'], 'integer'],
            [['document_type', 'name', 'due_date', 'note', 'status', 'created_at', 'updated_at', 'lang', 'reference', 'reference_client'], 'safe'],
            [['price_htva', 'price_tvac'], 'number'],
            [['client_name'], 'safe'],
            [['created_at_range', 'updated_at_range', 'duedate_range'], 'safe'],
            [['bill_exists', 'search'], 'safe'],
        ];
    }

	protected function newSearch($new_type = null) {
		switch($new_type ? $new_type : $this->document_type) {
			case Document::TYPE_BID:	return new SearchBid($this->attributes);	break;
			case Document::TYPE_ORDER:	return new SearchOrder($this->attributes);	break;
			case Document::TYPE_BILL:	return new SearchBill($this->attributes);	break;
			case Document::TYPE_CREDIT:	return new SearchCredit($this->attributes);break;
			case Document::TYPE_TICKET:	return new SearchTicket($this->attributes);break;
		}
		return null;
	}
	
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Document::find();

	    $query->joinWith('client');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$this->addToDataProvider($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'document.id' => $this->id,
            'document.parent_id' => $this->parent_id,
            'document.client_id' => $this->client_id,
            'document.price_htva' => $this->price_htva,
            'document.price_tvac' => $this->price_tvac,
            'document.created_by' => $this->created_by,
            'document.updated_by' => $this->updated_by,
            'document.vat_bool' => $this->vat_bool,
        ]);

        $query->andFilterWhere(['like', 'document.document_type', $this->document_type])
              ->andFilterWhere(['like', 'document.name', $this->name])
              ->andFilterWhere(['like', 'document.note', $this->note])
              ->andFilterWhere(['like', 'document.status', $this->status])
              ->andFilterWhere(['like', 'document.lang', $this->lang])
              ->andFilterWhere(['like', 'document.reference', $this->reference])
              ->andFilterWhere(['like', 'document.reference_client', $this->reference_client]);

		$this->addToQuery($query);

		if($this->bill_exists == 'Y') { // docs that have a bill
			/*select * from document d1 where not exists (select id from document d2 where d2.document_type = 'BILL' and d2.parent_id = d1.id) */
			$q2 = new Query();
			$q2->select('id')
			   ->from(['d2' => 'document'])
			   ->andWhere(['d2.document_type' => Document::TYPE_BILL])
			   ->andwhere('`d2`.`parent_id` = `document`.`id`');
			$query->andWhere(['not', ['document.bom_bool' => 1]])
				  ->andWhere(['exists', $q2]);
		} elseif($this->bill_exists == 'N') {
			$q2 = new Query();
			$q2->select('id')
			   ->from(['d2' => 'document'])
			   ->andWhere(['d2.document_type' => Document::TYPE_BILL])
			   ->andwhere('`d2`.`parent_id` = `document`.`id`');
			$query->andWhere(['not', ['document.bom_bool' => 1]])
				  ->andWhere(['document.document_type' => Document::TYPE_ORDER])
				  ->andWhere(['not exists', $q2]);
		}

        return $dataProvider;
    }
}
