<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bi_work".
 *
 * @property string $date_start
 * @property string $date_finish
 * @property string $task_name
 * @property string $categorie
 * @property string $yii_category
 */
class BiWork extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bi_work';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_start', 'date_finish'], 'safe'],
            [['task_name'], 'string', 'max' => 80],
            [['categorie', 'yii_category'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'date_start' => 'Date Start',
            'date_finish' => 'Date Finish',
            'task_name' => 'Task Name',
            'categorie' => 'Categorie',
            'yii_category' => 'Yii Category',
        ];
    }
}
