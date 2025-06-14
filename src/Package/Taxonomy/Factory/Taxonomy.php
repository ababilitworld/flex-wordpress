<?php
namespace Ababilithub\FlexWordpress\Package\Taxonomy\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Taxonomy\Contract\Taxonomy as TaxonomyContract,
};

class Taxonomy
{
    private static $instances = [];
    
    public static function get(string $taxonomyClass): TaxonomyContract
    {
        if (!isset(self::$instances[$taxonomyClass])) 
        {
            self::$instances[$taxonomyClass] = new $taxonomyClass();
        }

        return self::$instances[$taxonomyClass];
    }
}