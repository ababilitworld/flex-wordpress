<?php
namespace Ababilithub\FlexWordpress\Package\Debug\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Debug\V1\Contract\Debug as DebugContract,
};

class Debug extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return DebugContract
     */
    protected static function resolve(string $targetClass): DebugContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof DebugContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement DebugContract");
        }

        return $instance;
    }
} 