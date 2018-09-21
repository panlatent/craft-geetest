<?php
/**
 * Geetest plugin for Craft 3
 *
 * @link https://github.com/panlatent/craft-geetest/
 * @copyright Copyright (c) 2018 Panlatent
 */

namespace panlatent\craft\geetest;

use Craft;
use panlatent\craft\geetest\models\Settings;
use panlatent\craft\geetest\services\Api;
use panlatent\craft\geetest\web\twig\Extension;

/**
 * Plugin class.
 *
 * @author    Panlatent <panlatent@gmail.com>
 * @package   Geetest
 * @method    Settings getSettings()
 * @property  Api $api
 * @property  Settings $settings
 * @since     0.1.0
 */
class Plugin extends \craft\base\Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Plugin
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '0.1.0';

    /**
     * @var string
     */
    public $t9Category = 'geetest';

    // Public Methods
    // =========================================================================
    public function __construct($id, $parent = null, array $config = [])
    {
        if (!isset($config['components']['api'])) {
            $config['components']['api'] = [
                'class' => Api::class,
            ];
        }

        parent::__construct($id, $parent, $config);
    }

    /**
     * Init.
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::setAlias('@geetest', $this->getBasePath());

        Craft::$app->view->registerTwigExtension(new Extension());

        Craft::info(
            Craft::t(
                'geetest',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    /**
     * @return Api
     */
    public function getApi()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->get('api');
    }

    // Protected Methods
    // =========================================================================

    /**
     * @return Settings
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml()
    {
        return Craft::$app->view->renderTemplate('geetest/settings', [
            'settings' => $this->getSettings(),
        ]);
    }
}
