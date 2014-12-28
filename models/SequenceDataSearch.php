<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SequenceData;

/**
 * SequenceDataSearch represents the model behind the search form about `app\models\SequenceData`.
 */
class SequenceDataSearch extends SequenceData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sequence_name'], 'safe'],
            [['sequence_increment', 'sequence_min_value', 'sequence_max_value', 'sequence_cur_value', 'sequence_cycle', 'sequence_year'], 'integer'],
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
        $query = SequenceData::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'sequence_increment' => $this->sequence_increment,
            'sequence_min_value' => $this->sequence_min_value,
            'sequence_max_value' => $this->sequence_max_value,
            'sequence_cur_value' => $this->sequence_cur_value,
            'sequence_cycle' => $this->sequence_cycle,
            'sequence_year' => $this->sequence_year,
        ]);

        $query->andFilterWhere(['like', 'sequence_name', $this->sequence_name]);

        return $dataProvider;
    }
}
