<?php
namespace Ababilithub\FlexWordpress\Package\Route\V3\GFactory\Concrete;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Route\V3\GFactory\Contract\Route as RouteFactoryInterface,
    FlexWordpress\Package\Route\V3\HConcrete\Web\Route as WebRoute,
    FlexWordpress\Package\Route\V3\HConcrete\Api\Route as ApiRoute,
    FlexWordpress\Package\Route\V3\JContract\Route as RouteInterface
};

if (!class_exists(__NAMESPACE__ . '\Route')) 
{
    class Route implements RouteFactoryInterface
    {
        public function create(string $path, \Closure $handler, array $methods = ['GET'], string $type = 'web'): RouteInterface
        {
            return match($type) {
                'api' => new ApiRoute($path, $handler, $methods),
                default => new WebRoute($path, $handler, $methods)
            };
        }
    }
}