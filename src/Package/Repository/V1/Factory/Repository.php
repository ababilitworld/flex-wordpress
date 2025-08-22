<?php
namespace Ababilithub\FlexWordpress\Package\Repository\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Repository\V1\Contract\Repository as RepositoryContract,
};

class Repository extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return RepositoryContract
     */
    protected static function resolve(string $targetClass): RepositoryContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof RepositoryContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement RepositoryContract");
        }

        return $instance;
    }
} 