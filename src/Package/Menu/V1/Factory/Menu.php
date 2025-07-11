<?php
namespace Ababilithub\FlexWordpress\Package\Menu\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Menu\V1\Contract\Menu as MenuContract,
};

class Menu extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return MenuContract
     */
    protected static function resolve(string $targetClass): MenuContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof MenuContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement MenuContract");
        }

        return $instance;
    }
} 