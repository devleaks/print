<?php

namespace app\assets;

use yii\web\AssetBundle;

class BadgeAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';

    public $css = [
        'css/badge.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
