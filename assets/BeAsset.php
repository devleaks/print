<?php

namespace app\assets;

use yii\web\AssetBundle;

class BeAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';

    public $js = [
		'js/be.js'
    ];

    public $css = [
    	'css/dclocal.css',
    ];

    public $depends = [
        'yii2mod\c3\chart\ChartAsset'
    ];
}
