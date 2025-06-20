<?php
namespace Ababilithub\FlexWordpress\Package\Shortcode\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Shortcode\V1\Contract\Shortcode as ShortcodeContract,
};

class Shortcode extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return ShortcodeContract
     */
    protected static function resolve(string $targetClass): ShortcodeContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof ShortcodeContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement ShortcodeContract");
        }

        return $instance;
    }
}