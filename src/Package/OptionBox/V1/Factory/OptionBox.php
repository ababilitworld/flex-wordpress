<?php
namespace Ababilithub\FlexWordpress\Package\OptionBox\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\OptionBox\V1\Contract\OptionBox as OptionBoxContract,
};

class OptionBox extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return OptionBoxContract
     */
    protected static function resolve(string $targetClass): OptionBoxContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof OptionBoxContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement OptionBoxContract");
        }

        return $instance;
    }
} 