<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;

/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class DocumentSearch extends Document
{
	public $client_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'client_id', 'created_by', 'updated_by', 'vat_bool'], 'integer'],
            [['order_type', 'name', 'due_date', 'note', 'status', 'created_at', 'updated_at', 'lang', 'reference', 'reference_client'], 'safe'],
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
        $query = Document::find();

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
            'order.id' => $this->id,
            'order.parent_id' => $this->parent_id,
            'order.client_id' => $this->client_id,
            'order.due_date' => $this->due_date,
            'order.created_at' => $this->created_at,
            'order.updated_at' => $this->updated_at,
            'order.price_htva' => $this->price_htva,
            'order.price_tvac' => $this->price_tvac,
            'order.created_by' => $this->created_by,
            'order.updated_by' => $this->updated_by,
            'order.vat_bool' => $this->vat_bool,
        ]);

        $query->andFilterWhere(['like', 'order.order_type', $this->order_type])
            ->andFilterWhere(['like', 'order.name', $this->name])
            ->andFilterWhere(['like', 'order.note', $this->note])
            ->andFilterWhere(['like', 'order.status', $this->status])
            ->andFilterWhere(['like', 'order.lang', $this->lang])
            ->andFilterWhere(['like', 'order.reference', $this->reference])
            ->andFilterWhere(['like', 'order.reference_client', $this->reference_client])
            ->andFilterWhere(['like', 'client.nom', $this->client_name]);

        return $dataProvider;
    }
}
