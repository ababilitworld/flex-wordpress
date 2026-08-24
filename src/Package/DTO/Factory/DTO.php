<?php
namespace Ababilithub\FlexWordpress\Package\DTO\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\DTO\V1\Contract\DTO as DTOContract,
};

class DTO extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return DTOContract
     */
    protected static function resolve(string $targetClass): DTOContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof DTOContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement DTOContract");
        }

        return $instance;
    }
} 