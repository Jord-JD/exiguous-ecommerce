<?php

namespace JordJD\ExiguousEcommerce;

class Product
{
    public static $directory = 'products';
    public static $class = 'Product';

    public static function find($id)
    {
        return ExiguousEcommerceItem::find(self::$directory, self::$class, $id);
    }

    public static function all()
    {
        return ExiguousEcommerceItem::all(self::$directory, self::$class);
    }

    public static function findBySlug($slug)
    {
        return ExiguousEcommerceItem::findBySlug(self::$directory, self::$class, $slug);
    }

    /** @var int|string|null */
    public $id = null;
    /** @var object|null */
    public $data = null;

    public function __construct($id = null, $data = null)
    {
        if ($id === null && $data === null) {
            $id = ExiguousEcommerceItem::getUsusedId(self::$directory);
            $data = new \stdClass();
        }

        $this->id = $id;
        $this->data = $data;
    }

    public function categories()
    {
        $ids = isset($this->data->categoryIds) && is_array($this->data->categoryIds) ? $this->data->categoryIds : array();

        $objs = array();

        foreach ($ids as $id) {
            $obj = Category::find($id);

            if ($obj) {
                $objs[] = $obj;
            }
        }

        return $objs;
    }

    public function save()
    {
        ExiguousEcommerceItem::save(self::$directory, $this);
    }
}
