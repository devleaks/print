<?php

namespace app\assets;

use yii\web\AssetBundle;

class ItemAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';

    public $js = [
        'js/item.js',
    ];

    public $css = [
        'css/item.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
