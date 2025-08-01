<?php
namespace Ababilithub\FlexWordpress\Package\Relationship\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Relationship\V1\Contract\Relationship as RelationshipContract,
};

class Relationship extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return RelationshipContract
     */
    protected static function resolve(string $targetClass): RelationshipContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof RelationshipContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement RelationshipContract");
        }

        return $instance;
    }
} 