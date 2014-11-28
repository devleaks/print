<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Item;

/**
 * ItemSearch represents the model behind the search form about `app\models\Item`.
 */
class ItemSearch extends Item
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['reference', 'libelle_court', 'libelle_long', 'categorie', 'type_travaux_photos', 'type_numerique', 'fournisseur', 'reference_fournisseur', 'conditionnement', 'prix_d_achat_de_reference', 'client', 'quantite', 'prix_de_vente', 'date_initiale', 'date_finale', 'taux_de_tva', 'identification', 'suivi_de_stock', 'reassort_possible', 'seuil_de_commande', 'site_internet', 'creation', 'mise_a_jour', 'en_cours', 'stock', 'commentaires', 'status', 'created_at', 'updated_at'], 'safe'],
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
        $query = Item::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'libelle_court', $this->libelle_court])
            ->andFilterWhere(['like', 'libelle_long', $this->libelle_long])
            ->andFilterWhere(['like', 'categorie', $this->categorie])
            ->andFilterWhere(['like', 'type_travaux_photos', $this->type_travaux_photos])
            ->andFilterWhere(['like', 'type_numerique', $this->type_numerique])
            ->andFilterWhere(['like', 'fournisseur', $this->fournisseur])
            ->andFilterWhere(['like', 'reference_fournisseur', $this->reference_fournisseur])
            ->andFilterWhere(['like', 'conditionnement', $this->conditionnement])
            ->andFilterWhere(['like', 'prix_d_achat_de_reference', $this->prix_d_achat_de_reference])
            ->andFilterWhere(['like', 'client', $this->client])
            ->andFilterWhere(['like', 'quantite', $this->quantite])
            ->andFilterWhere(['like', 'prix_de_vente', $this->prix_de_vente])
            ->andFilterWhere(['like', 'date_initiale', $this->date_initiale])
            ->andFilterWhere(['like', 'date_finale', $this->date_finale])
            ->andFilterWhere(['like', 'taux_de_tva', $this->taux_de_tva])
            ->andFilterWhere(['like', 'identification', $this->identification])
            ->andFilterWhere(['like', 'suivi_de_stock', $this->suivi_de_stock])
            ->andFilterWhere(['like', 'reassort_possible', $this->reassort_possible])
            ->andFilterWhere(['like', 'seuil_de_commande', $this->seuil_de_commande])
            ->andFilterWhere(['like', 'site_internet', $this->site_internet])
            ->andFilterWhere(['like', 'creation', $this->creation])
            ->andFilterWhere(['like', 'mise_a_jour', $this->mise_a_jour])
            ->andFilterWhere(['like', 'en_cours', $this->en_cours])
            ->andFilterWhere(['like', 'stock', $this->stock])
            ->andFilterWhere(['like', 'commentaires', $this->commentaires])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
