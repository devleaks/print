<?php

namespace app\assets;

use yii\web\AssetBundle;

class BiAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';

    public $css = [
    	'css/dclocal.css'
    ];

    public $depends = [
        'app\assets\DcAsset'
    ];
}
