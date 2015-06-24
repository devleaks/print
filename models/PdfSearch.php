<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pdf;

/**
 * PdfSearch represents the model behind the search form about `app\models\Pdf`.
 */
class PdfSearch extends Pdf
{
	public $client_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'document_id', 'client_id'], 'integer'],
            [['document_type', 'filename', 'created_at', 'sent_at', 'updated_at', 'client_name'], 'safe'],
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
        $query = Pdf::find();

	    $query->joinWith('client');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider->sort->attributes['client_name'] = [
			'asc'  => ['client.nom' => SORT_ASC],
			'desc' => ['client.nom' => SORT_DESC],
		];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'pdf.id' => $this->id,
            'pdf.document_id' => $this->document_id,
            'pdf.client_id' => $this->client_id,
            'pdf.created_at' => $this->created_at,
            'pdf.sent_at' => $this->created_at,
            'pdf.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'pdf.document_type', $this->document_type])
              ->andFilterWhere(['like', 'pdf.filename', $this->filename])
        	  ->andFilterWhere(['like', 'client.nom', $this->client_name]);

        return $dataProvider;
    }
}
