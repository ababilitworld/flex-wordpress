<?php
namespace Ababilithub\FlexWordpress\Package\Template\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Template\V1\Contract\Template as TemplateContract,
};

class Template extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return TemplateContract
     */
    protected static function resolve(string $targetClass): TemplateContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof TemplateContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement TemplateContract");
        }

        return $instance;
    }
} 