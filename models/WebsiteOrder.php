<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "website_order".
 */
class WebsiteOrder extends _WebsiteOrder
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                'timestamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); },
                ],
        ];
    }

	protected function getClient() {
		return new Client();
	}

	protected function getDocument() {
		return new Document();
	}

	protected function getDocumentLine() {
		return new DocumentLine();
	}

	public function createOrder($json) {
		return new Document();
	}

}
