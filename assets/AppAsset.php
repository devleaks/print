<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
		'css/dev.css',
    ];
		
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
		'raoul2000\bootswatch\BootswatchAsset',
    ];

	public static function register($view) {
		$c = Yii::$app->params['bannerColor'];
		$c = $c ? $c : "#222";
		$view->registerCss(".navbar-inverse { background-color: ".Yii::$app->params['bannerColor'].";}");	
		parent::register($view);
	}
}
