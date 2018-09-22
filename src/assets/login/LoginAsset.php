<?php
/**
 * Geetest plugin for Craft 3
 *
 * @link https://github.com/panlatent/craft-geetest/
 * @copyright Copyright (c) 2018 Panlatent
 */

namespace panlatent\craft\geetest\assets\login;

use craft\web\AssetBundle;
use panlatent\craft\geetest\assets\geetest\GeetestAsset;

class LoginAsset extends AssetBundle
{
    public $sourcePath = '@geetest/assets/login/dist';

    public $js = [
        'xceptor.js',
        'login.js',
    ];

    public $depends = [
        GeetestAsset::class,
    ];
}