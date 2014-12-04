<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DocumentLine;

/**
 * DocumentLineSearch represents the model behind the search form about `app\models\DocumentLine`.
 */
class DocumentLineSearch extends DocumentLine
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'document_id', 'position', 'item_id'], 'integer'],
            [['quantity', 'unit_price', 'vat', 'work_width', 'work_height', 'price_htva', 'price_tvac', 'extra_htva', 'extra_amount'], 'number'],
            [['note', 'status', 'created_at', 'updated_at', 'extra_type'], 'safe'],
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
        $query = DocumentLine::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'document_id' => $this->document_id,
            'position' => $this->position,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'vat' => $this->vat,
            'work_width' => $this->work_width,
            'work_height' => $this->work_height,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'price_htva' => $this->price_htva,
            'price_tvac' => $this->price_tvac,
            'item_id' => $this->item_id,
            'extra_htva' => $this->extra_htva,
            'extra_amount' => $this->extra_amount,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'extra_type', $this->extra_type]);

        return $dataProvider;
    }
}
