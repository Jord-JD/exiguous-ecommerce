<?php

namespace JordJD\ExiguousEcommerce;

class Order
{
    public static $directory = 'orders';
    public static $class = 'Order';

    public static function find($id)
    {
        return ExiguousEcommerceItem::find(self::$directory, self::$class, $id);
    }

    public static function all()
    {
        return ExiguousEcommerceItem::all(self::$directory, self::$class);
    }

    public static function createFromBasket($basket)
    {
        $orderData = new \stdClass();

        $orderData->datePlaced = time();

        $orderData->currency = $basket->currency;

        if (!$orderData->currency) {
            throw new \Exception('No currency has been specified for the current basket. Unable to convert basket to order.');
        }

        $orderData->customer = $basket->customer;
        $orderData->billingAddress = $basket->billingAddress;
        $orderData->deliveryAddress = $basket->deliveryAddress;
        $orderData->items = $basket->items;
        $orderData->phoneNumber = $basket->additionalDetails->phoneNumber;
        $orderData->emailAddress = $basket->additionalDetails->emailAddress;

        $orderData->subtotal = 0;

        foreach ($orderData->items as $item) {
            $item->unitCost = $item->unitCost($orderData->currency);

            if ($item->unitCost === null) {
                throw new \Exception('Unable to determine unit cost of product when creating order from basket. Check relevant products have prices in the currency defined for this order.');
            }

            $item->lineTotal = $item->lineTotal($orderData->currency);

            $orderData->subtotal += $item->lineTotal;
        }

        $orderData->deliveryOption = $basket->deliveryOption;

        $orderData->total = $orderData->subtotal;

        if ($orderData->deliveryOption != null) {
            $orderData->total += $orderData->deliveryOption->cost;
        }

        $id = ExiguousEcommerceItem::getUsusedId(self::$directory);

        $order = new self($id, $orderData);

        $order->save();

        return self::find($id);
    }

    public $id = null;
    public $data = null;

    public function __construct($id, $data)
    {
        $this->id = $id;
        $this->data = $data;
    }

    public function save()
    {
        ExiguousEcommerceItem::save(self::$directory, $this);
    }
}
