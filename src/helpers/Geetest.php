<?php
/**
 * Geetest plugin for Craft 3
 *
 * @link https://github.com/panlatent/craft-geetest/
 * @copyright Copyright (c) 2018 Panlatent
 */

namespace panlatent\craft\geetest\helpers;

use Craft;
use panlatent\craft\geetest\Plugin;
use yii\web\ForbiddenHttpException;

class Geetest
{
    /**
     * @throws ForbiddenHttpException
     */
    public static function requireValidated()
    {
        if (!static::isValidated()) {
            throw new ForbiddenHttpException("Not verified by Geetest");
        }
    }

    /**
     * @return bool
     */
    public static function isValidated(): bool
    {
        $request = Craft::$app->getRequest();

        $challenge = $request->getBodyParam('geetest_challenge');
        $validateValue = $request->getBodyParam('geetest_validate');
        $secCode = $request->getBodyParam('geetest_seccode');

        if (empty($challenge) || empty($validateValue) || empty($secCode)) {
            return false;
        }

        if (Craft::$app->user->getIsGuest()) {
            $id = 'guest';
        } else {
            $id = Craft::$app->user->identity->getId();
        }

        $ip = Craft::$app->request->getUserIP();

        return Plugin::$plugin->getApi()->validate($id, $ip, $challenge, $validateValue, $secCode);
    }

    public static function registerApiPrepareJsVar(string $id = null, string $ip = null)
    {
        if ($id === null) {
            if (Craft::$app->user->getIsGuest()) {
                $id = Craft::$app->session->getId();
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
    }
}