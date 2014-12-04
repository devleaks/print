<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TicketSearch represents the model behind the search form about `app\models\Ticket`.
 */
class TicketSearch extends Ticket
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'client_id', 'created_by', 'updated_by', 'vat_bool'], 'integer'],
            [['document_type', 'name', 'due_date', 'note', 'status', 'created_at', 'updated_at', 'lang', 'reference', 'reference_client'], 'safe'],
            [['price_htva', 'price_tvac'], 'number'],
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
        $query = Ticket::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'client_id' => $this->client_id,
            'due_date' => $this->due_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'price_htva' => $this->price_htva,
            'price_tvac' => $this->price_tvac,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'vat_bool' => $this->vat_bool,
        ]);

        $query->andFilterWhere(['like', 'document_type', $this->document_type])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'lang', $this->lang])
            ->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'reference_client', $this->reference_client]);

        return $dataProvider;
    }
}
