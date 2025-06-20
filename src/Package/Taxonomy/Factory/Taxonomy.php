<?php
namespace Ababilithub\FlexWordpress\Package\Taxonomy\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Taxonomy\Contract\Taxonomy as TaxonomyContract,
};

class Taxonomy
{
    private static $instances = [];
    
    public static function get(string $targetClass): TaxonomyContract
    {
        if (!isset(self::$instances[$targetClass])) 
        {
            self::$instances[$targetClass] = new $targetClass();
        }

        return self::$instances[$targetClass];
    }
}