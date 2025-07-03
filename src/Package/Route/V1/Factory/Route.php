<?php
namespace Ababilithub\FlexWordpress\Package\Route\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Route\V1\Contract\Route as RouteContract,
};

class Route extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return RouteContract
     */
    protected static function resolve(string $targetClass): RouteContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof RouteContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement RouteContract");
        }

        return $instance;
    }
}