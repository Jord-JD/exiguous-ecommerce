# 🛒 Exiguous Ecommerce

Exiguous Ecommerce is a super simple ecommerce library, that uses flat files and takes a very minimalistic approach.

## Installation

Just run the following Composer command to download/install Exiguous Ecommerce and create relevant autoload files.

```
composer require jord-jd/exiguous-ecommerce
```

If your framework does not already do so, you must add `require_once "vendor/autoload.php"` to any files in which you wish to use Exiguous Ecommerce.

## Configuration

Exiguous Ecommerce stores all of its data within a `data` directory. An example `data` directory is provided in this package.

Before use, you should then copy the `data` directory to another location and then specify this location your project's environment.
If you are using Laravel, this can be done by setting an `EXIGUOUS_ECOMMERCE_DATA_DIRECTORY` variable in your `.env` file, as follows.

```
EXIGUOUS_ECOMMERCE_DATA_DIRECTORY=/var/www/ecommerce-site/path-to-data-directory/
```

If you are not using a framework that supports this, you can use the standard PHP function `putenv` to set this environment variable.
Alternatively, you could use [dotenv-loader](https://github.com/Jord-JD/dotenv-loader) to add `.env` file support to your project.

Please note that it is important the `EXIGUOUS_ECOMMERCE_DATA_DIRECTORY` variable is set with a trailing slash present.

For security reasons, you should place the `data` directory in a location which is not web-accessible. In case the data directory is placed in
a web accessible location by accident, a `.htaccess` file is provided that should deny web users access to the directory's content in most
common web server configurations.

## Quick Start Examples

Getting products and categories:

```php
$category = \JordJD\ExiguousEcommerce\Category::findBySlug("fluffy-things");
$products = $category->products();

foreach($products as $product) {
    echo $product->data->name;
}
```

```php
$product = \JordJD\ExiguousEcommerce\Product::findBySlug("teddy-bear");
$categories = $product->categories();

$mainCategoryName = $categories[0]->data->name;
```

Getting the current user's basket and adding a product to it:

```php
$product = \JordJD\ExiguousEcommerce\Product::findBySlug("teddy-bear");

$basket = \JordJD\ExiguousEcommerce\Basket::findCurrent();

$basket->addProduct($product); // Add one Teddy Bear

$basket->addProduct($product, 2); // Add another two Teddy Bears!

var_dump($basket->items); // Outputs an array of, you guessed it, basket items! ^_^

// ^ This would show 1 basket item with a quantity of 3 teddy bears.

```

Removing a product from a basket:

```php
$product = \JordJD\ExiguousEcommerce\Product::findBySlug("teddy-bear");

$basket = \JordJD\ExiguousEcommerce\Basket::findCurrent();

$basket->removeProduct($product); // Removes all teddy bears from the basket
```

Setting/Offsetting the quantity of a product in the basket:

```php
$product = \JordJD\ExiguousEcommerce\Product::findBySlug("teddy-bear");

$basket = \JordJD\ExiguousEcommerce\Basket::findCurrent();

$basket->addProduct($product); // Add one Teddy Bear

$basket->setProductQuantity($product, 10); // Set the number of Teddy Bears in the basket to ten

$basket->offsetProductQuantity($product, 10); // Add ten more Teddy Bears

$basket->offsetProductQuantity($product, -5); // Remove five of those Teddy Bears
```

Migrating the basket to an order:

```php
$basket = \JordJD\ExiguousEcommerce\Basket::findCurrent();

$basket->convertToOrder();
```

Getting and using settings:

```php

// Retrieves settings from the core.json file within the .settings directory
$coreSettings = \JordJD\ExiguousEcommerce\Settings::find('core');

echo $coreSettings->data->primaryCurrency; // Output the ecommerce's primary currency setting

```
