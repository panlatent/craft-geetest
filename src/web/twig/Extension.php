<?php
/**
 * Geetest plugin for Craft 3
 *
 * @link https://github.com/panlatent/craft-geetest/
 * @copyright Copyright (c) 2018 Panlatent
 */

namespace panlatent\craft\geetest\web\twig;

use Craft;
use panlatent\craft\geetest\assets\GeetestAsset;
use panlatent\craft\geetest\Plugin;

class Extension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('geetestInput', function(string $id = null, string $ip = null) {
                Craft::$app->view->registerAssetBundle(GeetestAsset::class);

                if ($id === null) {
                    if (Craft::$app->user->getIsGuest()) {
                        $id = 'guest';
                    } else {
                        $id = Craft::$app->user->identity->getId();
                    }
                }

                if ($ip === null) {
                    $ip = Craft::$app->request->getUserIP();
                }

                $challenge = Plugin::$plugin->getApi()->prepare(md5($id), $ip);

                Craft::$app->view->registerJsVar('geetest', [
                    'success'   => 1,
                    'gt'        => Plugin::$plugin->getSettings()->accessId,
                    'challenge' => $challenge,
                    'new_captcha' => 1
                ]);

                return '<div id="geetest-embed-captcha"></div>';
            }),
        ];
    }
}