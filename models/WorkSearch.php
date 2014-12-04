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
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'document_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at', 'status', 'due_date'], 'safe'],
			[['order_name'], 'safe'],
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
		$query->joinWith('document');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider->sort->attributes['order_name'] = [
		    'asc' => ['document.name' => SORT_ASC],
		    'desc' => ['document.name' => SORT_DESC],
		];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'document_id' => $this->document_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'due_date' => $this->due_date,
        ]);

        $query->andFilterWhere(['like', 'work.status', $this->status])
    		  ->andFilterWhere(['like', 'document.name', $this->order_name]);

        return $dataProvider;
    }
}
