<?php
/**
 * Geetest plugin for Craft 3
 *
 * @link https://github.com/panlatent/craft-geetest/
 * @copyright Copyright (c) 2018 Panlatent
 */

namespace panlatent\craft\geetest\web\twig;

use Craft;
use panlatent\craft\geetest\assets\geetest\GeetestAsset;
use panlatent\craft\geetest\helpers\Geetest;

class Extension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('geetestInput', function(string $id = null, string $ip = null) {
                Craft::$app->view->registerAssetBundle(GeetestAsset::class);
                Geetest::registerApiPrepareJsVar($id, $ip);

                return '<div id="geetest-embed-captcha"></div>';
            }),
        ];
    }
}