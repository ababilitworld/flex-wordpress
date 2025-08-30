<?php
namespace Ababilithub\FlexWordpress\Package\Query\Posttype\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Query\Posttype\V1\Contract\Query as QueryContract,
};

class Query extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return QueryContract
     */
    protected static function resolve(string $targetClass): QueryContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof QueryContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement QueryContract");
        }

        return $instance;
    }
} 