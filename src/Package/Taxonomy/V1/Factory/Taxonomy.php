<?php
namespace Ababilithub\FlexWordpress\Package\Taxonomy\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Taxonomy\V1\Contract\Taxonomy as TaxonomyContract,
};

class Taxonomy extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return TaxonomyContract
     */
    protected static function resolve(string $targetClass): TaxonomyContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof TaxonomyContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement TaxonomyContract");
        }

        return $instance;
    }
}