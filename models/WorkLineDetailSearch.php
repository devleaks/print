<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WorkLine;

/**
 * WorkLineSearch represents the model behind the search form about `app\models\WorkLine`.
 */
class WorkLineDetailSearch extends WorkLine
{
	public $order_name;
	public $client_name;
	public $due_date;
	public $item_name;
	public $quantity;
	public $work_width;
	public $work_height;
	public $task_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'work_id', 'created_by', 'updated_by', 'document_line_id', 'task_id', 'position', 'item_id'], 'integer'],
            [['created_at', 'updated_at', 'status', 'note', 'due_date'], 'safe'],
			[['item_name', 'task_name'], 'safe'],
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
        $query = WorkLine::find();
		$query->joinWith(['item','task','documentLine']);
		$query->leftJoin('document', 'document_line.document_id = document.id');
		$query->leftJoin('client', 'document.client_id = client.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider->sort->attributes['order_name'] = [
		    'asc' => ['document.name' => SORT_ASC],
		    'desc' => ['document.name' => SORT_DESC],
		];

		$dataProvider->sort->attributes['client_name'] = [
		    'asc' => ['client.nom' => SORT_ASC],
		    'desc' => ['client.nom' => SORT_DESC],
		];

		$dataProvider->sort->attributes['due_date'] = [
		    'asc' => ['document_line.due_date' => SORT_ASC],
		    'desc' => ['document_line.due_date' => SORT_DESC],
		];

		$dataProvider->sort->attributes['quantity'] = [
		    'asc' => ['document_line.quantity' => SORT_ASC],
		    'desc' => ['document_line.quantity' => SORT_DESC],
		];

		$dataProvider->sort->attributes['work_width'] = [
		    'asc' => ['document_line.work_width' => SORT_ASC],
		    'desc' => ['document_line.work_width' => SORT_DESC],
		];

		$dataProvider->sort->attributes['work_height'] = [
		    'asc' => ['document_line.work_height' => SORT_ASC],
		    'desc' => ['document_line.work_height' => SORT_DESC],
		];

		$dataProvider->sort->attributes['item_name'] = [
		    'asc' => ['item.libelle_court' => SORT_ASC],
		    'desc' => ['item.libelle_court' => SORT_DESC],
		];

		$dataProvider->sort->attributes['task_name'] = [
		    'asc' => ['task.name' => SORT_ASC],
		    'desc' => ['task.name' => SORT_DESC],
		];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'work_line.id' => $this->id,
            'work_line.work_id' => $this->work_id,
            'work_line.item_id' => $this->item_id,
            'work_line.task_id' => $this->task_id,
            'work_line.position' => $this->position,
            'work_line.due_date' => $this->due_date,
            'work_line.document_line_id' => $this->document_line_id,
            'work_line.created_at' => $this->created_at,
            'work_line.updated_at' => $this->updated_at,
            'work_line.created_by' => $this->created_by,
            'work_line.updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'work_line.status', $this->status])
              ->andFilterWhere(['like', 'work_line.note', $this->note]);

		$query->andFilterWhere(['like', 'item.libelle_long', $this->item_name]);
		$query->andFilterWhere(['like', 'task.name', $this->task_name]);

        return $dataProvider;
    }
}