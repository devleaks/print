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
	public $created_at_range;
	public $updated_at_range;
	public $duedate_range;

	public $client_name;
	public $bill_exists;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'client_id', 'created_by', 'updated_by', 'vat_bool'], 'integer'],
            [['document_type', 'name', 'due_date', 'note', 'status', 'created_at', 'updated_at', 'lang', 'reference', 'reference_client'], 'safe'],
            [['price_htva', 'price_tvac'], 'number'],
            [['client_name', 'bill_exists'], 'safe'],
            [['created_at_range', 'updated_at_range', 'duedate_range'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
        	'client_name' => Yii::t('store', 'Client'),
        	'created_at_range' => Yii::t('store', 'Created At'),
        	'updated_at_range' => Yii::t('store', 'Updated At'),
        	'duedate_range' => Yii::t('store', 'Due Date'),
        ]);
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

		$dataProvider->sort->attributes['client_name'] = [
			'asc'  => ['client.nom' => SORT_ASC],
			'desc' => ['client.nom' => SORT_DESC],
		];

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

		$query = Document::parseDateRange('document.created_at', $this->created_at_range, $query);
		$query = Document::parseDateRange('document.updated_at', $this->updated_at_range, $query);
		$query = Document::parseDateRange('document.due_date',   $this->duedate_range, $query);

		$dataProvider->sort->attributes['created_at_range'] = [
			'asc'  => ['document.created_at' => SORT_ASC],
			'desc' => ['document.created_at' => SORT_DESC],
		];

		$dataProvider->sort->attributes['updated_at_range'] = [
			'asc'  => ['document.updated_at' => SORT_ASC],
			'desc' => ['document.updated_at' => SORT_DESC],
		];

		$dataProvider->sort->attributes['duedate_range'] = [
			'asc'  => ['document.due_date' => SORT_ASC],
			'desc' => ['document.due_date' => SORT_DESC],
		];


        $query->andFilterWhere(['like', 'document.document_type', $this->document_type])
              ->andFilterWhere(['like', 'document.name', $this->name])
              ->andFilterWhere(['like', 'document.note', $this->note])
              ->andFilterWhere(['like', 'document.status', $this->status])
              ->andFilterWhere(['like', 'document.lang', $this->lang])
              ->andFilterWhere(['like', 'document.reference', $this->reference])
              ->andFilterWhere(['like', 'document.reference_client', $this->reference_client])
              ->andFilterWhere(['like', 'client.nom', $this->client_name]);

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
