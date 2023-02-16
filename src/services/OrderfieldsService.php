<?php

/**
 * Split Deliveryplugin for Craft CMS 3.x
 *
 * get order fields for products
 *
 * @link      https://digilabe.co.uk
 * @copyright Copyright (c) 2023 Dene Hewings
 */

namespace digilab\split\services;

use Craft;
use craft\base\Field;
use digilab\split\Split;
use craft\base\Component;
use craft\commerce\elements\Order;

/**
 * OrderfieldsService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Dene Hewings
 * @package   Split Delivery
 * @since     1.0.0
 */
class OrderfieldsService extends Component
{
    // Public Methods
    // =========================================================================

    private $orderfields;
    private $settings;

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Orderfields::$plugin->orderfieldsService->exampleService()
     *
     * @return mixed
     */
    public function __construct()
    {
        $order = new Order;
        $this->orderfields = $order->getFieldValues();
        $this->settings = Split::getInstance()->getSettings()->fields;
    }

    public function getOrderFields()
    {
        return $this->orderfields;
    }

    public function getField($field)
    {
        return Craft::$app->getFields()->getFieldByHandle($field);
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getFieldHtml($field)
    {
        $field = $this->getField($field);
        return Craft::$app->formHtml($field, null);
    }
}
