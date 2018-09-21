<?php
/**
 * Geetest plugin for Craft 3
 *
 * @link https://github.com/panlatent/craft-geetest/
 * @copyright Copyright (c) 2018 Panlatent
 */

namespace panlatent\craft\geetest\services;

use craft\helpers\Json;
use GuzzleHttp\Client;
use panlatent\craft\geetest\models\Settings;
use panlatent\craft\geetest\Plugin;
use yii\base\Component;

class Api extends Component
{
    const CLIENT_TYPE_WEB = 'web';
    const CLIENT_TYPE_MOBILE = 'h5';
    const CLIENT_TYPE_APP = 'native';

    const PREPARE_URL = 'http://api.geetest.com/register.php';

    const VALIDATE_URL = 'http://api.geetest.com/validate.php';

    public function prepare(string $id, string $ip, string $clientType = self::CLIENT_TYPE_WEB, bool $isNew = true): string
    {
        /** @var Settings $settings */
        $settings = Plugin::$plugin->getSettings();

        $response = $this->_createHttpClient()
            ->get(static::PREPARE_URL, [
                'query' => [
                    'gt' => $settings->accessId,
                    'new_captcha' => $isNew ? 1 : 0,
                    'user_id' => $id,
                    'client_type' => $clientType,
                    'ip_address' => $ip,
                ]
            ]);

        $challenge = $response->getBody()->getContents();

        return md5($challenge . $settings->accessKey);
    }

    public function validate(string $id, string $ip, string $challenge, string $validateValue, string $secCode,
                             string $clientType = self::CLIENT_TYPE_WEB): bool
    {
        if (!$this->_validateChallenge($challenge, $validateValue)) {
            return false;
        }

        $response = $this->_createHttpClient()
            ->post(static::VALIDATE_URL, [
                'form_params' => [
                    'user_id' => $id,$clientType,
                    'ip_address' => $ip,
                    'seccode' => $secCode,
                    'timestamp'=> time(),
                    'challenge'=> $challenge,
                    'captchaid'=> Plugin::$plugin->getSettings()->accessId,
                    'json_format'=> 1,
                    'sdk'     => '3.0'
                ]
            ]);

        $result = Json::decode($response->getBody()->getContents());

        return $result ? $result['seccode'] == md5($secCode) : false;
    }

    /**
     * @param string $challenge
     * @param string $value
     * @return bool
     */
    private function _validateChallenge(string $challenge, string $value): bool
    {
        if (strlen($value) != 32) {
            return false;
        } elseif (md5(Plugin::$plugin->getSettings()->accessKey . 'geetest' . $challenge) != $value) {
            return false;
        }

        return true;
    }

    /**
     * @return Client
     */
    private function _createHttpClient(): Client
    {
        return new Client();
    }
}