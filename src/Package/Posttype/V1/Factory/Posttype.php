<?php
namespace Ababilithub\FlexWordpress\Package\Posttype\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Posttype\V1\Contract\Posttype as PosttypeContract,
};

class Posttype
{
private static $instances = [];
    
    public static function get(string $targetClass): PosttypeContract
    {
        if (!isset(self::$instances[$targetClass])) 
        {
            self::$instances[$targetClass] = new $targetClass();
        }

        return self::$instances[$targetClass];
    }
}