<?php
/**
 * Geetest plugin for Craft 3
 *
 * @link https://github.com/panlatent/craft-geetest/
 * @copyright Copyright (c) 2018 Panlatent
 */

namespace panlatent\craft\geetest;

use Craft;
use craft\controllers\UsersController;
use craft\events\TemplateEvent;
use craft\web\Controller;
use craft\web\View;
use panlatent\craft\geetest\assets\login\LoginAsset;
use panlatent\craft\geetest\helpers\Geetest;
use panlatent\craft\geetest\models\Settings;
use panlatent\craft\geetest\services\Api;
use panlatent\craft\geetest\web\twig\Extension;
use yii\base\ActionEvent;
use yii\base\Event;

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
    public $schemaVersion = '0.2.0';

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

        if ($this->getSettings()->embedInLogin) {
            Event::on(View::class, View::EVENT_BEFORE_RENDER_TEMPLATE, function(TemplateEvent $event) {
                if (Craft::$app->request->isCpRequest && $event->template == 'login') {
                    Geetest::registerApiPrepareJsVar();
                    Craft::$app->view->registerAssetBundle(LoginAsset::class, View::POS_END);
                    Craft::$app->view->registerCss('#geetest-embed-captcha {margin-top: 8px; }');
                }
            });

            Event::on(UsersController::class, Controller::EVENT_BEFORE_ACTION, function(ActionEvent $event) {
                if ($event->action->id == 'login'
                    && Craft::$app->getUser()->getIsGuest()
                    && Craft::$app->getRequest()->getIsPost()) {

                    // Throw HttpForbiddenException (403) error if invalid.
                    Geetest::requireValidated();
                }
            });
        }

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
