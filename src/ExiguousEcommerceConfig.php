<?php

namespace JordJD\ExiguousEcommerce;

abstract class ExiguousEcommerceConfig
{
    public static function getDataDirectory()
    {
        $dataDirectory = getenv('EXIGUOUS_ECOMMERCE_DATA_DIRECTORY');

        if (!$dataDirectory) {
            throw new \Exception('The EXIGUOUS_ECOMMERCE_DATA_DIRECTORY parameter is not specified in your environment or is empty.');
        }

        if (!file_exists($dataDirectory)) {
            throw new \Exception('The specified EXIGUOUS_ECOMMERCE_DATA_DIRECTORY does not seem to exist. '.$dataDirectory);
        }

        if (!is_dir($dataDirectory)) {
            throw new \Exception('The specified EXIGUOUS_ECOMMERCE_DATA_DIRECTORY does not seem to be a directory. '.$dataDirectory);
        }

        return $dataDirectory;
    }
}
