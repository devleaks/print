<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Extraction;

/**
 * ExtractionSearch represents the model behind the search form about `app\models\Extraction`.
 */
class ExtractionSearch extends Extraction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_from', 'order_to'], 'integer'],
            [['created_at', 'updated_at', 'extraction_type', 'date_from', 'date_to'], 'safe'],
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
        $query = Extraction::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'order_from' => $this->order_from,
            'order_to' => $this->order_to,
        ]);

        $query->andFilterWhere(['like', 'extraction_type', $this->extraction_type]);

        return $dataProvider;
    }
}
