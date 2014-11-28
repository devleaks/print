<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Client;

/**
 * ClientSearch represents the model behind the search form about `app\models\Client`.
 */
class ClientSearch extends Client
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['reference_interne', 'titre', 'nom', 'prenom', 'autre_nom', 'adresse', 'code_postal', 'localite', 'pays', 'langue', 'numero_tva', 'email', 'site_web', 'domicile', 'bureau', 'gsm', 'fax_prive', 'fax_bureau', 'pc', 'autre', 'remise', 'escompte', 'delais_de_paiement', 'mentions', 'exemplaires', 'limite_de_credit', 'formule', 'type', 'execution', 'support', 'format', 'mise_a_jour', 'mailing', 'outlook', 'categorie_de_client', 'comptabilite', 'operation', 'categorie_de_prix_de_vente', 'reference_1', 'date_limite_1', 'reference_2', 'date_limite_2', 'reference_3', 'date_limite_3', 'commentaires'], 'safe'],
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
        $query = Client::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'reference_interne', $this->reference_interne])
            ->andFilterWhere(['like', 'titre', $this->titre])
            ->andFilterWhere(['like', 'nom', $this->nom])
            ->andFilterWhere(['like', 'prenom', $this->prenom])
            ->andFilterWhere(['like', 'autre_nom', $this->autre_nom])
            ->andFilterWhere(['like', 'adresse', $this->adresse])
            ->andFilterWhere(['like', 'code_postal', $this->code_postal])
            ->andFilterWhere(['like', 'localite', $this->localite])
            ->andFilterWhere(['like', 'pays', $this->pays])
            ->andFilterWhere(['like', 'langue', $this->langue])
            ->andFilterWhere(['like', 'numero_tva', $this->numero_tva])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'site_web', $this->site_web])
            ->andFilterWhere(['like', 'domicile', $this->domicile])
            ->andFilterWhere(['like', 'bureau', $this->bureau])
            ->andFilterWhere(['like', 'gsm', $this->gsm])
            ->andFilterWhere(['like', 'fax_prive', $this->fax_prive])
            ->andFilterWhere(['like', 'fax_bureau', $this->fax_bureau])
            ->andFilterWhere(['like', 'pc', $this->pc])
            ->andFilterWhere(['like', 'autre', $this->autre])
            ->andFilterWhere(['like', 'remise', $this->remise])
            ->andFilterWhere(['like', 'escompte', $this->escompte])
            ->andFilterWhere(['like', 'delais_de_paiement', $this->delais_de_paiement])
            ->andFilterWhere(['like', 'mentions', $this->mentions])
            ->andFilterWhere(['like', 'exemplaires', $this->exemplaires])
            ->andFilterWhere(['like', 'limite_de_credit', $this->limite_de_credit])
            ->andFilterWhere(['like', 'formule', $this->formule])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'execution', $this->execution])
            ->andFilterWhere(['like', 'support', $this->support])
            ->andFilterWhere(['like', 'format', $this->format])
            ->andFilterWhere(['like', 'mise_a_jour', $this->mise_a_jour])
            ->andFilterWhere(['like', 'mailing', $this->mailing])
            ->andFilterWhere(['like', 'outlook', $this->outlook])
            ->andFilterWhere(['like', 'categorie_de_client', $this->categorie_de_client])
            ->andFilterWhere(['like', 'comptabilite', $this->comptabilite])
            ->andFilterWhere(['like', 'operation', $this->operation])
            ->andFilterWhere(['like', 'categorie_de_prix_de_vente', $this->categorie_de_prix_de_vente])
            ->andFilterWhere(['like', 'reference_1', $this->reference_1])
            ->andFilterWhere(['like', 'date_limite_1', $this->date_limite_1])
            ->andFilterWhere(['like', 'reference_2', $this->reference_2])
            ->andFilterWhere(['like', 'date_limite_2', $this->date_limite_2])
            ->andFilterWhere(['like', 'reference_3', $this->reference_3])
            ->andFilterWhere(['like', 'date_limite_3', $this->date_limite_3])
            ->andFilterWhere(['like', 'commentaires', $this->commentaires]);

        return $dataProvider;
    }
}
