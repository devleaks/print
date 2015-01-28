<?php

namespace app\assets;

use yii\web\AssetBundle;

class CutAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';

    public $js = [
        'js/cut.js',
    ];

    public $css = [
        'css/cut.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
		'yii\jui\JuiAsset',
    ];
}
