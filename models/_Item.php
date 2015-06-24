<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property integer $id
 * @property string $yii_category
 * @property string $reference
 * @property string $libelle_court
 * @property string $libelle_long
 * @property string $categorie
 * @property double $prix_de_vente
 * @property double $taux_de_tva
 * @property string $type_travaux_photos
 * @property string $type_numerique
 * @property string $fournisseur
 * @property string $reference_fournisseur
 * @property string $conditionnement
 * @property string $prix_d_achat_de_reference
 * @property string $client
 * @property integer $quantite
 * @property string $date_initiale
 * @property string $date_finale
 * @property string $identification
 * @property string $suivi_de_stock
 * @property string $reassort_possible
 * @property string $seuil_de_commande
 * @property string $site_internet
 * @property string $creation
 * @property string $mise_a_jour
 * @property string $en_cours
 * @property string $stock
 * @property string $commentaires
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $comptabilite
 *
 * @property DocumentLine[] $documentLines
 * @property DocumentLineDetail[] $documentLineDetails
 * @property DocumentLineOption[] $documentLineOptions
 * @property ItemOption[] $itemOptions
 * @property ItemTask[] $itemTasks
 * @property Option[] $options
 * @property WorkLine[] $workLines
 */
class _Item extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prix_de_vente', 'taux_de_tva'], 'number'],
            [['quantite'], 'integer'],
            [['date_initiale', 'date_finale', 'creation', 'mise_a_jour', 'created_at', 'updated_at'], 'safe'],
            [['yii_category', 'reference', 'categorie', 'type_travaux_photos', 'type_numerique', 'fournisseur', 'reference_fournisseur', 'conditionnement', 'prix_d_achat_de_reference', 'identification', 'suivi_de_stock', 'reassort_possible', 'seuil_de_commande', 'en_cours', 'stock', 'status', 'comptabilite'], 'string', 'max' => 20],
            [['libelle_court', 'client'], 'string', 'max' => 40],
            [['libelle_long', 'site_internet', 'commentaires'], 'string', 'max' => 80],
            [['reference'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'yii_category' => Yii::t('store', 'Yii Category'),
            'reference' => Yii::t('store', 'Reference'),
            'libelle_court' => Yii::t('store', 'Libelle Court'),
            'libelle_long' => Yii::t('store', 'Libelle Long'),
            'categorie' => Yii::t('store', 'Categorie'),
            'prix_de_vente' => Yii::t('store', 'Prix De Vente'),
            'taux_de_tva' => Yii::t('store', 'Taux De TVA'),
            'type_travaux_photos' => Yii::t('store', 'Type Travaux Photos'),
            'type_numerique' => Yii::t('store', 'Type Numerique'),
            'fournisseur' => Yii::t('store', 'Fournisseur'),
            'reference_fournisseur' => Yii::t('store', 'Reference Fournisseur'),
            'conditionnement' => Yii::t('store', 'Conditionnement'),
            'prix_d_achat_de_reference' => Yii::t('store', 'Prix D Achat De Reference'),
            'client' => Yii::t('store', 'Client'),
            'quantite' => Yii::t('store', 'Quantite'),
            'date_initiale' => Yii::t('store', 'Date Initiale'),
            'date_finale' => Yii::t('store', 'Date Finale'),
            'identification' => Yii::t('store', 'Identification'),
            'suivi_de_stock' => Yii::t('store', 'Suivi De Stock'),
            'reassort_possible' => Yii::t('store', 'Reassort Possible'),
            'seuil_de_commande' => Yii::t('store', 'Seuil De Commande'),
            'site_internet' => Yii::t('store', 'Site Internet'),
            'creation' => Yii::t('store', 'Creation'),
            'mise_a_jour' => Yii::t('store', 'Mise A Jour'),
            'en_cours' => Yii::t('store', 'En Cours'),
            'stock' => Yii::t('store', 'Stock'),
            'commentaires' => Yii::t('store', 'Commentaires'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'comptabilite' => Yii::t('store', 'Comptabilite'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLines()
    {
        return $this->hasMany(DocumentLine::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLineDetails()
    {
        return $this->hasMany(DocumentLineDetail::className(), ['collage_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLineOptions()
    {
        return $this->hasMany(DocumentLineOption::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemOptions()
    {
        return $this->hasMany(ItemOption::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemTasks()
    {
        return $this->hasMany(ItemTask::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(Option::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkLines()
    {
        return $this->hasMany(WorkLine::className(), ['item_id' => 'id']);
    }
}
