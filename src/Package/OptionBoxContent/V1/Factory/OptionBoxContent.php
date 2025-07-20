<?php
namespace Ababilithub\FlexWordpress\Package\OptionBoxContent\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\OptionBoxContent\V1\Contract\OptionBoxContent as OptionBoxContentContract,
};

class OptionBoxContent extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return OptionBoxContentContract
     */
    protected static function resolve(string $targetClass): OptionBoxContentContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof OptionBoxContentContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement OptionBoxContentContract");
        }

        return $instance;
    }
} 