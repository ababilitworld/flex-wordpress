<?php

namespace Ababilithub\FlexWordpress\Package\Pagination\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Pagination\V1\Contract\Pagination as PaginationContract,
};

class Pagination extends BaseFactory
{
    protected static function resolve(string $targetClass): PaginationContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof PaginationContract) {
            throw new \InvalidArgumentException(
                "{$targetClass} must implement PaginationContract"
            );
        }

        return $instance;
    }
}
