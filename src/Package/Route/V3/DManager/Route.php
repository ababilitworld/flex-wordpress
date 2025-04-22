<?php
namespace Ababilithub\FlexWordpress\Package\Route\V3\DManager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Route\V3\EService\Contract\Route as RouteServiceInterface, 
    FlexWordpress\Package\Route\V3\HConcrete\Web\Route as WebRoute,
    FlexWordpress\Package\Route\V3\HConcrete\Api\Route as ApiRoute,
};

if (!class_exists(__NAMESPACE__ . '\Route')) 
{
    class Route 
    {
        private RouteServiceInterface $service;

        public function __construct(RouteServiceInterface $service) 
        {
            $this->service = $service;
        }

        public function init(): void 
        {
            $this->service->registerRoutes();
            flush_rewrite_rules(false);
        }

        public function addRoute(string $path, \Closure $handler, array $methods = ['GET'], string $type = 'web'): void 
        {
            if (!class_exists(ApiRoute::class) && $type === 'api') {
                throw new \RuntimeException('API Route implementation not available');
            }
            
            $route = $type === 'api' 
                ? new ApiRoute($path, $handler, $methods)
                : new WebRoute($path, $handler, $methods);
                
            $this->service->register($route);
        }

        public function getRoutes(): array 
        {
            return $this->service->getRegisteredRoutes();
        }
    }
}