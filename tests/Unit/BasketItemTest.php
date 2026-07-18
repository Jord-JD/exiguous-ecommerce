<?php

namespace JordJD\ExiguousEcommerce\Tests\Unit;

use JordJD\ExiguousEcommerce\BasketItem;
use PHPUnit\Framework\TestCase;

class BasketItemTest extends TestCase
{
    public function testCalculatesUnitAndLineTotals()
    {
        $price = new \stdClass();
        $price->currency = 'GBP';
        $price->value = 12.5;

        $data = new \stdClass();
        $data->prices = array($price);

        $product = new \stdClass();
        $product->data = $data;

        $item = new BasketItem($product, 3);

        $this->assertSame(12.5, $item->unitCost('GBP'));
        $this->assertSame(37.5, $item->lineTotal('GBP'));
    }

    public function testOrderTotalsAreDeclaredPropertiesForPhp82AndNewer()
    {
        $this->assertTrue(property_exists('JordJD\ExiguousEcommerce\BasketItem', 'unitCost'));
        $this->assertTrue(property_exists('JordJD\ExiguousEcommerce\BasketItem', 'lineTotal'));
    }
}
