<?php
namespace Ababilithub\FlexWordpress\Package\PostMetaBox\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\PostMetaBox\V1\Contract\PostMetaBox as PostMetaBoxContract,
};

class PostMetaBox extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return PostMetaBoxContract
     */
    protected static function resolve(string $targetClass): PostMetaBoxContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof PostMetaBoxContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement PostMetaBoxContract");
        }

        return $instance;
    }
} 