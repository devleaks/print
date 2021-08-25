<?php

namespace app\assets;

use yii\web\AssetBundle;

class DcAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $js = [
        'crossfilter2/crossfilter.js',
    	'd3/d3.js',
        'dcjs/dc.js'
    ];

    public $css = [
        'dcjs/dc.css'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
