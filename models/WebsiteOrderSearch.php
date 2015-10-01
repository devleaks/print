<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WebsiteOrder;

/**
 * WebsiteOrderSearch represents the model behind the search form about `app\models\WebsiteOrder`.
 */
class WebsiteOrderSearch extends WebsiteOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'document_id'], 'integer'],
            [['rawjson', 'status', 'created_at', 'updated_at', 'order_date', 'name', 'company', 'address', 'city', 'vat', 'phone', 'email', 'promocode', 'comment', 'order_name', 'clientcode', 'convert_errors'], 'safe'],
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
        $query = WebsiteOrder::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'document_id' => $this->document_id,
        ]);

        $query->andFilterWhere(['like', 'rawjson', $this->rawjson])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'order_date', $this->order_date])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'vat', $this->vat])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'promocode', $this->promocode])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'order_name', $this->order_name])
            ->andFilterWhere(['like', 'clientcode', $this->clientcode])
            ->andFilterWhere(['like', 'convert_errors', $this->convert_errors]);

        return $dataProvider;
    }
}
