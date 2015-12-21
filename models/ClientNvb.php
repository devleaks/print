<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_nvb".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $no_nvb
 * @property string $nom
 */
class ClientNvb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client_nvb';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id'], 'integer'],
            [['no_nvb'], 'string', 'max' => 40],
            [['nom'], 'string', 'max' => 80]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'client_id' => Yii::t('store', 'Client ID'),
            'no_nvb' => Yii::t('store', 'No Nvb'),
            'nom' => Yii::t('store', 'Nom'),
        ];
    }
}
