<?php

namespace app\assets;

use yii\web\AssetBundle;

class AutocompleteAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/autoComplete.css',
    ];
    public $js = [
        'js/autoComplete.min.js',
        'js/location-autocomplete.js'
    ];
    public $depends = [
        AppAsset::class,
    ];
}