<?php

require '../vendor/autoload.php';

$dataDirectory = __DIR__.'/../data/';

putenv('EXIGUOUS_ECOMMERCE_DATA_DIRECTORY='.$dataDirectory);

$coreSettings = \JordJD\ExiguousEcommerce\Settings::find('core');

$product = \JordJD\ExiguousEcommerce\Product::findBySlug('teddy-bear');

$basket = \JordJD\ExiguousEcommerce\Basket::findCurrent();

$basket->setCurrency($coreSettings->data->primaryCurrency);

$basket->setAdditionalDetails("07999916545","Andrew.McDonald@rapidweb.biz");

$basket->addProduct($product, 10);

$order = $basket->convertToOrder();

var_dump($order);
