<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Document;

/**
 * OrderSearch represents the model behind the search form about `app\models\Document`.
 */
class CreditSearch extends Credit
{
	public $client_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'client_id', 'created_by', 'updated_by', 'vat_bool'], 'integer'],
            [['document_type', 'name', 'due_date', 'note', 'status', 'created_at', 'updated_at', 'lang', 'reference', 'reference_client'], 'safe'],
            [['price_htva', 'price_tvac'], 'number'],
            [['client_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
	        'client_name' => Yii::t('store', 'Client'),
        ]);
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
        $query = Credit::find();

	    $query->joinWith('client');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider->sort->attributes['client_name'] = [
			'asc'  => ['client.nom' => SORT_ASC],
			'desc' => ['client.nom' => SORT_DESC],
		];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'document.id' => $this->id,
            'document.parent_id' => $this->parent_id,
            'document.client_id' => $this->client_id,
            'document.due_date' => $this->due_date,
            'document.created_at' => $this->created_at,
            'document.updated_at' => $this->updated_at,
            'document.price_htva' => $this->price_htva,
            'document.price_tvac' => $this->price_tvac,
            'document.created_by' => $this->created_by,
            'document.updated_by' => $this->updated_by,
            'document.vat_bool' => $this->vat_bool,
        ]);

        $query->andFilterWhere(['like', 'document.document_type', $this->document_type])
            ->andFilterWhere(['like', 'document.name', $this->name])
            ->andFilterWhere(['like', 'document.note', $this->note])
            ->andFilterWhere(['like', 'document.status', $this->status])
            ->andFilterWhere(['like', 'document.lang', $this->lang])
            ->andFilterWhere(['like', 'document.reference', $this->reference])
            ->andFilterWhere(['like', 'document.reference_client', $this->reference_client])
            ->andFilterWhere(['like', 'client.nom', $this->client_name]);

        return $dataProvider;
    }
}