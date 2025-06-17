<?php
namespace Ababilithub\FlexWordpress\Package\Posttype\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Posttype\V1\Contract\Posttype as PosttypeContract,
};

class Posttype
{
    private static $instances = [];
    
    public static function get(string $taxonomyClass): PosttypeContract
    {
        if (!isset(self::$instances[$taxonomyClass])) 
        {
            self::$instances[$taxonomyClass] = new $taxonomyClass();
        }

        return self::$instances[$taxonomyClass];
    }
}