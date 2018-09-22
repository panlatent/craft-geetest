<?php
/**
 * Geetest plugin for Craft 3
 *
 * @link https://github.com/panlatent/craft-geetest/
 * @copyright Copyright (c) 2018 Panlatent
 */

namespace panlatent\craft\geetest\models;

use craft\base\Model;

class Settings extends Model
{
    /**
     * @var string|null
     */
    public $accessId;

    /**
     * @var string|null
     */
    public $accessKey;

    /**
     * @var bool Show captcha in login page and validate input
     */
    public $embedInLogin = false;
}