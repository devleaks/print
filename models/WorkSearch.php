<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Work;

/**
 * WorkSearch represents the model behind the search form about `app\models\Work`.
 */
class WorkSearch extends Work
{
	public $order_name;
	public $client_name;
	public $order_created_by;
	public $duedate_range;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'document_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at', 'status', 'due_date'], 'safe'],
			[['order_name', 'client_name', 'order_created_by', 'duedate_range'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = Work::find();
		$query->joinWith('document')
			  ->joinWith('document.client')
			  ->joinWith('document.createdBy');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider->sort->attributes['order_name'] = [
		    'asc' => ['document.name' => SORT_ASC],
		    'desc' => ['document.name' => SORT_DESC],
		];

		$dataProvider->sort->attributes['order_created_by'] = [
		    'asc' => ['document.created_by' => SORT_ASC],
		    'desc' => ['document.created_by' => SORT_DESC],
		];

		$dataProvider->sort->attributes['client_name'] = [
		    'asc' => ['client.nom' => SORT_ASC],
		    'desc' => ['client.nom' => SORT_DESC],
		];

		$dataProvider->sort->attributes['duedate_range'] = [
			'asc'  => ['work.due_date' => SORT_ASC],
			'desc' => ['work.due_date' => SORT_DESC],
		];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'work.id' => $this->id,
            'work.document_id' => $this->document_id,
            'work.created_at' => $this->created_at,
            'work.updated_at' => $this->updated_at,
            'work.created_by' => $this->created_by,
            'work.updated_by' => $this->updated_by,
            'work.due_date' => $this->due_date,
        ]);

        $query->andFilterWhere(['like', 'work.status', $this->status])
    		  ->andFilterWhere(['like', 'document.name', $this->order_name])
    		  ->andFilterWhere(['like', 'document.created_by', $this->order_created_by])
    		  ->andFilterWhere(['like', 'client.nom', $this->client_name]);

		$query = Document::parseDateRange('work.due_date',   $this->duedate_range, $query);

        return $dataProvider;
    }
}
