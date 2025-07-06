<?php
namespace Ababilithub\FlexWordpress\Package\PostMetaBoxContent\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\PostMetaBoxContent\V1\Contract\PostMetaBoxContent as PostMetaBoxContentContract,
};

class PostMetaBoxContent extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return PostMetaBoxContentContract
     */
    protected static function resolve(string $targetClass): PostMetaBoxContentContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof PostMetaBoxContentContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement PostMetaBoxContentContract");
        }

        return $instance;
    }
} 