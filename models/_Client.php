<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property string $reference_interne
 * @property string $titre
 * @property string $nom
 * @property string $prenom
 * @property string $autre_nom
 * @property string $adresse
 * @property string $code_postal
 * @property string $localite
 * @property string $pays
 * @property string $langue
 * @property string $numero_tva
 * @property string $email
 * @property string $site_web
 * @property string $domicile
 * @property string $bureau
 * @property string $gsm
 * @property string $fax_prive
 * @property string $fax_bureau
 * @property string $pc
 * @property string $autre
 * @property string $remise
 * @property string $escompte
 * @property string $delais_de_paiement
 * @property string $mentions
 * @property string $exemplaires
 * @property string $limite_de_credit
 * @property string $formule
 * @property string $type
 * @property string $execution
 * @property string $support
 * @property string $format
 * @property string $mise_a_jour
 * @property string $mailing
 * @property string $outlook
 * @property string $categorie_de_client
 * @property string $comptabilite
 * @property string $operation
 * @property string $categorie_de_prix_de_vente
 * @property string $reference_1
 * @property string $date_limite_1
 * @property string $reference_2
 * @property string $date_limite_2
 * @property string $reference_3
 * @property string $date_limite_3
 * @property string $commentaires
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $lang
 * @property string $comm_pref
 * @property string $comm_format
 *
 * @property Account[] $accounts
 * @property Document[] $documents
 * @property Payment[] $payments
 */
class _Client extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mise_a_jour', 'created_at', 'updated_at'], 'safe'],
            [['reference_interne', 'titre', 'nom', 'prenom', 'autre_nom', 'adresse', 'code_postal', 'localite', 'pays', 'langue', 'numero_tva', 'email', 'site_web', 'domicile', 'bureau', 'gsm', 'fax_prive', 'fax_bureau', 'pc', 'autre', 'remise', 'escompte', 'delais_de_paiement', 'mentions', 'exemplaires', 'limite_de_credit', 'formule', 'type', 'execution', 'support', 'format', 'mailing', 'outlook', 'categorie_de_client', 'comptabilite', 'operation', 'categorie_de_prix_de_vente', 'reference_1', 'date_limite_1', 'reference_2', 'date_limite_2', 'reference_3', 'date_limite_3'], 'string', 'max' => 80],
            [['commentaires'], 'string', 'max' => 255],
            [['status', 'lang', 'comm_pref', 'comm_format'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'reference_interne' => Yii::t('store', 'Reference Interne'),
            'titre' => Yii::t('store', 'Titre'),
            'nom' => Yii::t('store', 'Nom'),
            'prenom' => Yii::t('store', 'Prenom'),
            'autre_nom' => Yii::t('store', 'Autre Nom'),
            'adresse' => Yii::t('store', 'Adresse'),
            'code_postal' => Yii::t('store', 'Code Postal'),
            'localite' => Yii::t('store', 'Localite'),
            'pays' => Yii::t('store', 'Pays'),
            'langue' => Yii::t('store', 'Langue'),
            'numero_tva' => Yii::t('store', 'Numero Tva'),
            'email' => Yii::t('store', 'Email'),
            'site_web' => Yii::t('store', 'Site Web'),
            'domicile' => Yii::t('store', 'Domicile'),
            'bureau' => Yii::t('store', 'Bureau'),
            'gsm' => Yii::t('store', 'Gsm'),
            'fax_prive' => Yii::t('store', 'Fax Prive'),
            'fax_bureau' => Yii::t('store', 'Fax Bureau'),
            'pc' => Yii::t('store', 'Pc'),
            'autre' => Yii::t('store', 'Autre'),
            'remise' => Yii::t('store', 'Remise'),
            'escompte' => Yii::t('store', 'Escompte'),
            'delais_de_paiement' => Yii::t('store', 'Delais De Paiement'),
            'mentions' => Yii::t('store', 'Mentions'),
            'exemplaires' => Yii::t('store', 'Exemplaires'),
            'limite_de_credit' => Yii::t('store', 'Limite De Credit'),
            'formule' => Yii::t('store', 'Formule'),
            'type' => Yii::t('store', 'Type'),
            'execution' => Yii::t('store', 'Execution'),
            'support' => Yii::t('store', 'Support'),
            'format' => Yii::t('store', 'Format'),
            'mise_a_jour' => Yii::t('store', 'Mise A Jour'),
            'mailing' => Yii::t('store', 'Mailing'),
            'outlook' => Yii::t('store', 'Outlook'),
            'categorie_de_client' => Yii::t('store', 'Categorie De Client'),
            'comptabilite' => Yii::t('store', 'Comptabilite'),
            'operation' => Yii::t('store', 'Operation'),
            'categorie_de_prix_de_vente' => Yii::t('store', 'Categorie De Prix De Vente'),
            'reference_1' => Yii::t('store', 'Reference 1'),
            'date_limite_1' => Yii::t('store', 'Date Limite 1'),
            'reference_2' => Yii::t('store', 'Reference 2'),
            'date_limite_2' => Yii::t('store', 'Date Limite 2'),
            'reference_3' => Yii::t('store', 'Reference 3'),
            'date_limite_3' => Yii::t('store', 'Date Limite 3'),
            'commentaires' => Yii::t('store', 'Commentaires'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'lang' => Yii::t('store', 'Lang'),
            'comm_pref' => Yii::t('store', 'Comm Pref'),
            'comm_format' => Yii::t('store', 'Comm Format'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['client_id' => 'id']);
    }
}
