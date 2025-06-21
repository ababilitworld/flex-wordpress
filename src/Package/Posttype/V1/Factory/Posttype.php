<?php
namespace Ababilithub\FlexWordpress\Package\Posttype\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Posttype\V1\Contract\Posttype as PosttypeContract,
};

class Posttype extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return PosttypeContract
     */
    protected static function resolve(string $targetClass): PosttypeContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof PosttypeContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement PosttypeContract");
        }

        return $instance;
    }
} 