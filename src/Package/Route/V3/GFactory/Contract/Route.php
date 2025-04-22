<?php
namespace Ababilithub\FlexWordpress\Package\Route\V3\GFactory\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Route\V3\JContract\Route as RouteInterface
};

interface Route
{
    public function create(string $path, \Closure $handler, array $methods = ['GET']): RouteInterface;
}