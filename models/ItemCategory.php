<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for new segments.
 */
class ItemCategory {

	/** */
	const CHROMALUXE = 'ChromaLuxe';
	/** */
	const CHROMALUXE_PARAM = 'ChromaParam';
	/** */
	const CHROMALUXE_TYPE = 'ChromaType';

	/** */
	const TIRAGE = 'Tirage';
	/** */
	const TIRAGE_PARAM = 'TirageParam';

	/** */
	const SUPPORT = 'Support';
	/** */
	const SUPPORT_PARAM = 'SupportParam';

	/** */
	const FRAME = 'Cadre';
	/** */
	const FRAME_PARAM = 'CadreParam';

	/** */
	const CANVAS = 'Canvas';
	/** */
	const CHASSIS = 'Chassis';

	/** */
	const RENFORT = 'Renfort';
	/** */
	const RENFORT_PARAM = 'RenfortParam';

	/** */
	const MONTAGE = 'Montage';
	/** */
	const MONTAGE_PARAM = 'MontageParam';

	/** */
	const CORNER_PARAM = 'CornerParam';

	/** */
	const PROTECTION = 'Vernis de protection';
	/** */
	const PROTECTION_PARAM = 'ProtectionParam';
	/** */
	const UV = 'UV';

	/** */
	const SPECIAL = 'SPECIAL';
	
	
	/**
	 * returns associative array of yii_category, status localized display for all possible yii_category values
	 *
	 * @return array()
	 */
	public static function getCategories() {
		return [
			self::SPECIAL => Yii::t('store', self::SPECIAL),
			self::CHROMALUXE => Yii::t('store', self::CHROMALUXE),
			self::CHROMALUXE_PARAM => Yii::t('store', self::CHROMALUXE_PARAM),
			self::CHROMALUXE_TYPE => Yii::t('store', self::CHROMALUXE_TYPE),
			self::CANVAS => Yii::t('store', self::CANVAS),
			self::CHASSIS => Yii::t('store', self::CHASSIS),
			self::FRAME => Yii::t('store', self::FRAME),
			self::FRAME_PARAM => Yii::t('store', self::FRAME_PARAM),
			self::RENFORT => Yii::t('store', self::RENFORT),
			self::RENFORT_PARAM => Yii::t('store', self::RENFORT_PARAM),
			self::SUPPORT => Yii::t('store', self::SUPPORT),
			self::SUPPORT_PARAM => Yii::t('store', self::SUPPORT_PARAM),
			self::TIRAGE => Yii::t('store', self::TIRAGE),
			self::TIRAGE_PARAM => Yii::t('store', self::TIRAGE_PARAM),
			self::MONTAGE_PARAM => Yii::t('store', self::MONTAGE_PARAM),
			self::CORNER_PARAM => Yii::t('store', self::CORNER_PARAM),
			self::PROTECTION => Yii::t('store', self::PROTECTION),
			self::PROTECTION_PARAM => Yii::t('store', self::PROTECTION_PARAM),
			self::UV => Yii::t('store', self::UV),
		];
	}
	

	
}
