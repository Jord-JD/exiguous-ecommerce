<?php

namespace JordJD\ExiguousEcommerce;

class Basket
{
    public static function findCurrent()
    {
        $sessionStarted = @session_start();

        if (!$sessionStarted) {
            throw new \Exception('Unable to start session.');
        }

        if (!isset($_SESSION['ExiguousEcommerce'])) {
            $_SESSION['ExiguousEcommerce'] = new \stdClass();
        }

        if (!isset($_SESSION['ExiguousEcommerce']->Basket) || !is_object($_SESSION['ExiguousEcommerce']->Basket)) {
            $_SESSION['ExiguousEcommerce']->Basket = new self();
        }

        return $_SESSION['ExiguousEcommerce']->Basket;
    }

    /** @var BasketItem[] */
    public $items = array();
    /** @var object|null */
    public $customer = null;
    /** @var object|null */
    public $billingAddress = null;
    /** @var object|null */
    public $deliveryAddress = null;
    /** @var object|null */
    public $deliveryOption = null;
    /** @var string|null */
    public $currency = null;
    /** @var object|null */
    public $additionalDetails = null;

    public function __construct()
    {
    }

    public function subtotal()
    {
        if (!$this->currency) {
            throw new \Exception('Unable to calculate the basket subtotal as the basket\'s currency has not been set.');
        }

        $total = 0;

        foreach ($this->items as $item) {
            $total += $item->lineTotal($this->currency);
        }

        return $total;
    }

    public function total($multiplier = 1)
    {
        $total = $this->subtotal();

        if ($this->deliveryOption && isset($this->deliveryOption->cost)) {
            $total += $this->deliveryOption->cost;
        }

        $total *= $multiplier;

        return $total;
    }

    public function addProduct($product, $quantity = 1)
    {
        if (!$product || !is_object($product) || !isset($product->id)) {
            throw new \Exception('Unable to add an invalid product to the basket.');
        }

        if ($quantity <= 0) {
            throw new \Exception('Unable to add <= 0 quantity of a product to the basket.');
        }

        foreach ($this->items as $item) {
            if ($item->product->id == $product->id) {
                $item->quantity += $quantity;
                $this->save();

                return;
            }
        }

        $this->items[] = new BasketItem($product, $quantity);
        $this->save();
    }

    public function removeProduct($product)
    {
        if (!$product || !is_object($product) || !isset($product->id)) {
            throw new \Exception('Unable to add an invalid product to the basket.');
        }

        foreach ($this->items as $key => $item) {
            if ($item->product->id == $product->id) {
                unset($this->items[$key]);

                $this->save();

                return;
            }
        }
    }

    public function offsetProductQuantity($product, $quantityOffset)
    {
        if (!$product || !is_object($product) || !isset($product->id)) {
            throw new \Exception('Unable to add an invalid product to the basket.');
        }

        foreach ($this->items as $item) {
            if ($item->product->id == $product->id) {
                if ($item->quantity + $quantityOffset < 0) {
                    throw new \Exception('Unable to offset quantity of a product in the basket to below zero.');
                }

                $item->quantity += $quantityOffset;
                $this->save();

                return;
            }
        }
    }

    public function setProductQuantity($product, $quantity)
    {
        if (!$product || !is_object($product) || !isset($product->id)) {
            throw new \Exception('Unable to add an invalid product to the basket.');
        }

        if ($quantity < 0) {
            throw new \Exception('Unable to set quantity of a product in the basket to below zero.');
        }

        foreach ($this->items as $item) {
            if ($item->product->id == $product->id) {
                $item->quantity = $quantity;
                $this->save();

                return;
            }
        }
    }

    public function setCustomerName($firstName, $lastName)
    {
        $this->customer = new \stdClass();

        $this->customer->firstName = $firstName;
        $this->customer->lastName = $lastName;

        $this->save();
    }

    public function setBillingAddress($firstName, $lastName, $line1, $line2, $townCity, $countyState, $postalCode, $country, $countryCode)
    {
        $this->billingAddress = new \stdClass();

        $this->billingAddress->firstName = $firstName;
        $this->billingAddress->lastName = $lastName;
        $this->billingAddress->line1 = $line1;
        $this->billingAddress->line2 = $line2;
        $this->billingAddress->townCity = $townCity;
        $this->billingAddress->countyState = $countyState;
        $this->billingAddress->postalCode = $postalCode;
        $this->billingAddress->country = $country;
        $this->billingAddress->countryCode = $countryCode;

        $this->save();
    }

    public function setDeliveryAddress($firstName, $lastName, $line1, $line2, $townCity, $countyState, $postalCode, $country, $countryCode)
    {
        $this->deliveryAddress = new \stdClass();

        $this->deliveryAddress->firstName = $firstName;
        $this->deliveryAddress->lastName = $lastName;
        $this->deliveryAddress->line1 = $line1;
        $this->deliveryAddress->line2 = $line2;
        $this->deliveryAddress->townCity = $townCity;
        $this->deliveryAddress->countyState = $countyState;
        $this->deliveryAddress->postalCode = $postalCode;
        $this->deliveryAddress->country = $country;
        $this->deliveryAddress->countryCode = $countryCode;

        $this->save();
    }

    public function setDeliveryOption($name, $cost)
    {
        $this->deliveryOption = new \stdClass();

        $this->deliveryOption->name = $name;
        $this->deliveryOption->cost = $cost;

        $this->save();
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    private function save()
    {
        $sessionStarted = @session_start();

        if (!$sessionStarted) {
            throw new \Exception('Unable to start session.');
        }

        $_SESSION['ExiguousEcommerce']->Basket = $this;
    }

    public function convertToOrder()
    {
        $order = Order::createFromBasket($this);

        $_SESSION['ExiguousEcommerce']->Basket = null;

        return $order;
    }

    public function setAdditionalDetails($phoneNumber,$emailAddress)
    {
        $this->additionalDetails = new \stdClass();

        $this->additionalDetails->phoneNumber = $phoneNumber;
        $this->additionalDetails->emailAddress = $emailAddress;

        $this->save();



    }
}
