<?php

/**
 * Split Shipping plugin for Craft CMS 3.x
 *
 * @link      https://digilab.co.uk
 * @copyright Copyright (c) 2023 Dene Hewings
 */

namespace digilab\split;

use Craft;
use yii\base\Event;
use craft\base\Model;
use craft\base\Plugin;
use craft\services\Fields;
use craft\commerce\elements\Order;
use digilab\split\models\Settings;
use craft\web\twig\variables\CraftVariable;
use digilab\split\fields\SplitDeliveryField;
use craft\events\RegisterComponentTypesEvent;
use digilab\split\helpers\SplitDeliveryHelper;
use digilab\split\services\OrderfieldsService;
use digilab\split\variables\OrderfieldsVariable;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://docs.craftcms.com/v3/extend/
 *
 * @author    Dene Hewings
 * @package   split-delivery
 * @since     1.0.0
 *
 * @property  Split $split
 */

class Split extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Split::$plugin
     *
     * @var Split
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '1.0.0';

    /**
     * Set to `true` if the plugin should have a settings view in the control panel.
     *
     * @var bool
     */
    public $hasCpSettings = true;

    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    protected function settingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'split-delivery/settings',
            ['settings' => $this->getSettings()]
        );
    }

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->setComponents([
            'service' => OrderfieldsService::class,
        ]);

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $e) {
                /** @var CraftVariable $variable */
                $variable = $e->sender;

                // Attach a service:
                $variable->set('split', OrderfieldsVariable::class);
            }
        );

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = SplitDeliveryField::class;
            }
        );

        Event::on(Order::class, Order::EVENT_BEFORE_SAVE, function (Event $event) {
            if (Craft::$app->getRequest()->getIsCpRequest()) {
                if ($event->sender instanceof Order) {
                    $serialNumberHelper = new SplitDeliveryHelper();
                    $params = Craft::$app->request->getBodyParams();
                    $serialNumberHelper->updateDelivery($params);
                }
            }
        });



        /**
         * Logging in Craft involves using one of the following methods:
         *
         * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
         * Craft::info(): record a message that conveys some useful information.
         * Craft::warning(): record a warning message that indicates something unexpected has happened.
         * Craft::error(): record a fatal error that should be investigated as soon as possible.
         *
         * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
         *
         * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
         * the category to the method (prefixed with the fully qualified class name) where the constant appears.
         *
         * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
         * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
         *
         * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
         */
        Craft::info(
            Craft::t(
                'split-delivery',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }
}
