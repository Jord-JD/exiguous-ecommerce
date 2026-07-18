<?php

namespace JordJD\ExiguousEcommerce;

class Category
{
    public static $directory = 'categories';
    public static $class = 'Category';

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

    public function __construct($id, $data)
    {
        $this->id = $id;
        $this->data = $data;
    }

    public function products()
    {
        $ids = isset($this->data->productIds) && is_array($this->data->productIds) ? $this->data->productIds : array();

        $objs = array();

        foreach ($ids as $id) {
            $obj = Product::find($id);

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
