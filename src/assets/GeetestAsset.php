<?php
/**
 * Geetest plugin for Craft 3
 *
 * @link https://github.com/panlatent/craft-geetest/
 * @copyright Copyright (c) 2018 Panlatent
 */

namespace panlatent\craft\geetest\assets;

use craft\web\AssetBundle;
use yii\web\JqueryAsset;

class GeetestAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@geetest/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'gt.js',
        'main.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
    ];
}