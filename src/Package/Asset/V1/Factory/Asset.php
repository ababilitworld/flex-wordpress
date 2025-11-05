<?php
namespace Ababilithub\FlexWordpress\Package\Asset\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Asset\V1\Contract\Asset as AssetContract,
};

class Asset extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return AssetContract
     */
    protected static function resolve(string $targetClass): AssetContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof AssetContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement AssetContract");
        }

        return $instance;
    }
} 