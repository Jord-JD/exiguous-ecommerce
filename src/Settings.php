<?php

namespace JordJD\ExiguousEcommerce;

class Settings
{
    public static $directory = 'settings';

    public static function find($name)
    {
        $file = ExiguousEcommerceConfig::getDataDirectory().self::$directory.'/'.basename($name).'.json';

        if (!file_exists($file)) {
            throw new \Exception('File does not exist: '.$file);
        }

        if (!$data = json_decode(file_get_contents($file))) {
            throw new \Exception('Error exists in file (JSON error '.json_last_error().'): '.$file);
        }

        return new self($name, $data);
    }

    /** @var string */
    public $name;
    /** @var object */
    public $data;

    public function __construct($name, $data)
    {
        $this->name = $name;
        $this->data = $data;
    }

    public function save()
    {
        ExiguousEcommerceItem::save(self::$directory, $this);
    }
}
