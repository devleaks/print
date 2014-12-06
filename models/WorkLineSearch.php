<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WorkLine;

/**
 * WorkLineSearch represents the model behind the search form about `app\models\WorkLine`.
 */
class WorkLineSearch extends WorkLine
{
	public $item_name;
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
		$query->joinWith(['item','task']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

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