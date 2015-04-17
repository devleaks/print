<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Payment;

/**
 * PaymentSearch represents the model behind the search form about `app\models\Payment`.
 */
class PaymentSearch extends Payment
{
	public $client_name;
	public $order_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sale', 'created_by', 'updated_by'], 'integer'],
            [['amount'], 'number'],
            [['payment_method', 'status', 'created_at', 'updated_at'], 'safe'],
            [['client_name', 'order_name'], 'safe'],
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
        $query = Payment::find();
		$query->joinWith(['client'])->leftJoin('document', 'document.sale = payment.sale');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider->sort->attributes['client_name'] = [
		    'asc' => ['client.nom' => SORT_ASC],
		    'desc' => ['client.nom' => SORT_DESC],
		];

		$dataProvider->sort->attributes['order_name'] = [
		    'asc' => ['document.name' => SORT_ASC],
		    'desc' => ['document.name' => SORT_DESC],
		];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'payment.id' => $this->id,
            'payment.sale' => $this->sale,
            'payment.amount' => $this->amount,
            'payment.created_at' => $this->created_at,
            'payment.created_by' => $this->created_by,
            'payment.updated_at' => $this->updated_at,
            'payment.updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'payment_method', $this->payment_method])
            ->andFilterWhere(['like', 'status', $this->status]);

        $query->andFilterWhere(['like', 'client.nom', $this->client_name]);
		$query->andFilterWhere(['like', 'document.name', $this->order_name]);
  
       return $dataProvider;
    }
}
