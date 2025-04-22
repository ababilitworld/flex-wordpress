<?php
namespace Ababilithub\FlexWordpress\Package\Route\V3\FRepository;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Route\V3\JContract\Route as RouteInterface,
    FlexWordpress\Package\Route\V3\FRepository\Contract\Route as RouteRepositoryInterface, 
};

if (!class_exists(__NAMESPACE__ . '\Route')) 
{
    class Route implements RouteRepositoryInterface 
    {
        protected array $routes = [];

        public function all(): array 
        {
            return $this->routes;
        }

        public function find(string $path): ?RouteInterface 
        {
            return $this->routes[$path] ?? null;
        }

        public function register(RouteInterface $route): void 
        {
            $this->routes[$route->getPath()] = $route;
        }
        
        public function flush(): void
        {
            $this->routes = [];
        }
    }
}