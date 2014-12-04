<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bill;

/**
 * BillSearch represents the model behind the search form about `app\models\Bill`.
 */
class BillSearch extends Bill
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'client_id', 'vat_bool', 'bom_bool', 'created_by', 'updated_by'], 'integer'],
            [['name', 'reference', 'reference_client', 'due_date', 'paiement_method', 'note', 'lang', 'status', 'created_at', 'updated_at', 'document_type'], 'safe'],
            [['price_htva', 'price_tvac', 'prepaid', 'vat'], 'number'],
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
        $query = Bill::find();

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
            'price_htva' => $this->price_htva,
            'price_tvac' => $this->price_tvac,
            'prepaid' => $this->prepaid,
            'vat' => $this->vat,
            'vat_bool' => $this->vat_bool,
            'bom_bool' => $this->bom_bool,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'reference_client', $this->reference_client])
            ->andFilterWhere(['like', 'paiement_method', $this->paiement_method])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'lang', $this->lang])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'document_type', $this->document_type]);

        return $dataProvider;
    }
}
