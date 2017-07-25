<?php

namespace app\assets;

use yii\web\AssetBundle;

class BiAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';

    public $js = [
        'dc/crossfilter.js',
    	'dc/d3.js',
        'dc/dc.js',
    ];

    public $css = [
        'dc/dc.css',
    	'dc/dclocal.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
