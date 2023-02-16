<?php

namespace digilab\split\helpers;

use yii\base\Component;
use craft\commerce\elements\Order;

class SplitDeliveryHelper extends Component
{

    public function __construct()
    {
    }

    public function getItemsInOrder($orderId)
    {
        $order = Order::find()->id($orderId)->one();
        return $order;
    }

    public function updateDelivery($params)
    {
        // if (isset($params['orderId'])) {
        //     $orderId = $params['orderId'];
        //     OrderSerialNumberRecord::deleteAll(['order_id' => $orderId]);

        //     if ($params && isset($params["fields"])) {

        //         $productsInParams = array_filter($params["fields"], function ($key) {
        //             return strpos($key, '?#') > 0;
        //         }, ARRAY_FILTER_USE_KEY);

        //         foreach ($productsInParams as $skuKey => $serialNumber) {
        //             $sku = explode("?#", $skuKey)[0];

        //             $serialNumberEntry = array(
        //                 'order_id' => $orderId,
        //                 'variant_id' => "",
        //                 'sku' => $sku,
        //                 'serial_number' => $serialNumber
        //             );

        //             $serialNumberRecord = new OrderSerialNumberRecord($serialNumberEntry);
        //             $serialNumberRecord->save();
        //         }
        //     }
        // }
    }
}
