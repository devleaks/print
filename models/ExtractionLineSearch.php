<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExtractionLine;

/**
 * ExtractionLineSearch represents the model behind the search form about `app\models\ExtractionLine`.
 */
class ExtractionLineSearch extends ExtractionLine
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'extraction_id'], 'integer'],
            [['extraction_type', 'status', 'created_at', 'updated_at'], 'safe'],
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
        $query = ExtractionLine::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'extraction_id' => $this->extraction_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'extraction_type', $this->extraction_type])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
