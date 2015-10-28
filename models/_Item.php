<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property integer $id
 * @property string $yii_category
 * @property string $comptabilite
 * @property string $reference
 * @property string $libelle_court
 * @property string $libelle_long
 * @property string $categorie
 * @property string $prix_de_vente
 * @property string $taux_de_tva
 * @property string $status
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
 * @property string $suivi_de_stock
 * @property string $reassort_possible
 * @property string $seuil_de_commande
 * @property string $site_internet
 * @property string $creation
 * @property string $mise_a_jour
 * @property string $en_cours
 * @property string $stock
 * @property string $commentaires
 * @property string $identification
 * @property string $created_at
 * @property string $updated_at
 * @property string $prix_a
 * @property string $prix_b
 * @property string $prix_min
 *
 * @property DocumentLine[] $documentLines
 * @property DocumentLineDetail[] $documentLineDetails
 * @property DocumentLineDetail[] $documentLineDetails0
 * @property DocumentLineDetail[] $documentLineDetails1
 * @property DocumentLineDetail[] $documentLineDetails2
 * @property DocumentLineDetail[] $documentLineDetails3
 * @property DocumentLineDetail[] $documentLineDetails4
 * @property DocumentLineDetail[] $documentLineDetails5
 * @property DocumentLineDetail[] $documentLineDetails6
 * @property DocumentLineDetail[] $documentLineDetails7
 * @property DocumentLineOption[] $documentLineOptions
 * @property ItemOption[] $itemOptions
 * @property ItemTask[] $itemTasks
 * @property Option[] $options
 * @property PriceListItem[] $priceListItems
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
            [['prix_de_vente', 'taux_de_tva', 'prix_a', 'prix_b', 'prix_min'], 'number'],
            [['quantite'], 'integer'],
            [['date_initiale', 'date_finale', 'creation', 'mise_a_jour', 'created_at', 'updated_at'], 'safe'],
            [['yii_category', 'comptabilite', 'categorie', 'status', 'type_travaux_photos', 'type_numerique', 'fournisseur', 'reference_fournisseur', 'conditionnement', 'prix_d_achat_de_reference', 'suivi_de_stock', 'reassort_possible', 'seuil_de_commande', 'en_cours', 'stock', 'identification'], 'string', 'max' => 20],
            [['reference', 'libelle_court', 'client'], 'string', 'max' => 40],
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
            'comptabilite' => Yii::t('store', 'Comptabilite'),
            'reference' => Yii::t('store', 'Reference'),
            'libelle_court' => Yii::t('store', 'Libelle Court'),
            'libelle_long' => Yii::t('store', 'Libelle Long'),
            'categorie' => Yii::t('store', 'Categorie'),
            'prix_de_vente' => Yii::t('store', 'Prix De Vente'),
            'taux_de_tva' => Yii::t('store', 'Taux De Tva'),
            'status' => Yii::t('store', 'Status'),
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
            'suivi_de_stock' => Yii::t('store', 'Suivi De Stock'),
            'reassort_possible' => Yii::t('store', 'Reassort Possible'),
            'seuil_de_commande' => Yii::t('store', 'Seuil De Commande'),
            'site_internet' => Yii::t('store', 'Site Internet'),
            'creation' => Yii::t('store', 'Creation'),
            'mise_a_jour' => Yii::t('store', 'Mise A Jour'),
            'en_cours' => Yii::t('store', 'En Cours'),
            'stock' => Yii::t('store', 'Stock'),
            'commentaires' => Yii::t('store', 'Commentaires'),
            'identification' => Yii::t('store', 'Identification'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'prix_a' => Yii::t('store', 'Prix A'),
            'prix_b' => Yii::t('store', 'Prix B'),
            'prix_min' => Yii::t('store', 'Prix Min'),
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
        return $this->hasMany(DocumentLineDetail::className(), ['finish_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLineDetails0()
    {
        return $this->hasMany(DocumentLineDetail::className(), ['chroma_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLineDetails1()
    {
        return $this->hasMany(DocumentLineDetail::className(), ['renfort_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLineDetails2()
    {
        return $this->hasMany(DocumentLineDetail::className(), ['frame_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLineDetails3()
    {
        return $this->hasMany(DocumentLineDetail::className(), ['chassis_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLineDetails4()
    {
        return $this->hasMany(DocumentLineDetail::className(), ['support_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLineDetails5()
    {
        return $this->hasMany(DocumentLineDetail::className(), ['tirage_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLineDetails6()
    {
        return $this->hasMany(DocumentLineDetail::className(), ['collage_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLineDetails7()
    {
        return $this->hasMany(DocumentLineDetail::className(), ['protection_id' => 'id']);
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
    public function getPriceListItems()
    {
        return $this->hasMany(PriceListItem::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkLines()
    {
        return $this->hasMany(WorkLine::className(), ['item_id' => 'id']);
    }
}
