<?php
namespace Ababilithub\FlexWordpress\Package\Migration\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Migration\V1\Contract\Migration as MigrationContract,
};

class Migration extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return MigrationContract
     */
    protected static function resolve(string $targetClass): MigrationContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof MigrationContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement MigrationContract");
        }

        return $instance;
    }
} 