<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrderLineDetail;

/**
 * OrderLineDetailSearch represents the model behind the search form about `app\models\OrderLineDetail`.
 */
class OrderLineDetailSearch extends OrderLineDetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_line_id', 'renfort', 'coin_arrondis', 'frame_id', 'filmuv_id'], 'integer'],
            [['detail_type', 'type_chroma'], 'safe'],
            [['work_length'], 'number'],
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
        $query = OrderLineDetail::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'order_line_id' => $this->order_line_id,
            'renfort' => $this->renfort,
            'coin_arrondis' => $this->coin_arrondis,
            'work_length' => $this->work_length,
            'frame_id' => $this->frame_id,
            'filmuv_id' => $this->filmuv_id,
        ]);

        $query->andFilterWhere(['like', 'detail_type', $this->detail_type])
            ->andFilterWhere(['like', 'type_chroma', $this->type_chroma]);

        return $dataProvider;
    }
}
