<?php

/**
 * Split Delivery plugin for Craft CMS 3.x
 *
 * Split Delivery field to add in tab on orders
 *
 * @link      https://digilab.co.uk
 * @copyright Copyright (c) 2023 Dene Hewings
 */

namespace digilab\split\fields;

use Craft;
use yii\db\Schema;
use craft\base\Field;
use craft\base\ElementInterface;
use putyourlightson\logtofile\LogToFile;
use digilab\split\helpers\SplitDeliveryHelper;

class SplitDeliveryField extends Field
{

    public static function displayName(): string
    {
        return "Split Delivery Options";
    }


    public function getContentColumnType(): string
    {
        return Schema::TYPE_STRING;
    }

    public function normalizeValue($value, ElementInterface $element = null)
    {
        return parent::normalizeValue($value, $element);
    }


    public function serializeValue($value, ElementInterface $element = null)
    {
        return parent::serializeValue($value, $element);
    }

    public function afterElementSave(ElementInterface $element, bool $isNew)
    {
        parent::afterElementSave($element, $isNew);
    }


    public function getInputHtml($order, ElementInterface $element = null): string
    {
        $absoluteUrl = Craft::$app->request->absoluteUrl;
        $orderId = null;
        if (str_contains($absoluteUrl, "orders/")) {
            $orderId = explode("orders/", $absoluteUrl)[1];
        }
        LogToFile::info($orderId, 'regg');
        $helper = new SplitDeliveryHelper();
        $order = $helper->getItemsInOrder($orderId);

        return Craft::$app->getView()->renderTemplate(
            'split-delivery/_split_delivery',
            compact('order')
        );
    }
}
