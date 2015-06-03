<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DocumentArchive;

/**
 * DocumentArchiveSearch represents the model behind the search form about `app\models\DocumentArchive`.
 */
class DocumentArchiveSearch extends DocumentArchive
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sale', 'parent_id', 'client_id', 'vat_bool', 'bom_bool', 'created_by', 'updated_by', 'priority', 'credit_bool'], 'integer'],
            [['document_type', 'name', 'reference', 'reference_client', 'due_date', 'note', 'lang', 'status', 'created_at', 'updated_at', 'legal', 'email'], 'safe'],
            [['price_htva', 'price_tvac', 'vat'], 'number'],
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
        $query = DocumentArchive::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'sale' => $this->sale,
            'parent_id' => $this->parent_id,
            'client_id' => $this->client_id,
            'due_date' => $this->due_date,
            'price_htva' => $this->price_htva,
            'price_tvac' => $this->price_tvac,
            'vat' => $this->vat,
            'vat_bool' => $this->vat_bool,
            'bom_bool' => $this->bom_bool,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'priority' => $this->priority,
            'credit_bool' => $this->credit_bool,
        ]);

        $query->andFilterWhere(['like', 'document_type', $this->document_type])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'reference_client', $this->reference_client])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'lang', $this->lang])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'legal', $this->legal])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
