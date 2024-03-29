<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Master;

/**
 * MasterSearch represents the model behind the search form about `app\models\Master`.
 */
class MasterSearch extends Master
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'keep'], 'integer'],
            [['work_length'], 'number'],
            [['note', 'created_at', 'updated_at'], 'safe'],
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
        $query = Master::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'work_length' => $this->work_length,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'keep' => $this->keep,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
