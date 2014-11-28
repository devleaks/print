<?php

namespace app\assets;

use yii\web\AssetBundle;

class PrintAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';

    public $css = [
        'css/print.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
