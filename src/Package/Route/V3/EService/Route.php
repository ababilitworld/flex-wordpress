<?php
namespace Ababilithub\FlexWordpress\Package\Route\V3\EService;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Route\V3\FRepository\Contract\Route as RouteRepositoryInterface,
    FlexWordpress\Package\Route\V3\EService\Contract\Route as RouteServiceInterface,
    FlexWordpress\Package\Route\V3\JContract\Route as RouteInterface
};

if (!class_exists(__NAMESPACE__ . '\Route')) 
{
    class Route implements RouteServiceInterface
    {
        private RouteRepositoryInterface $repository;

        public function __construct(RouteRepositoryInterface $repository) 
        {
            $this->repository = $repository;
        }

        public function registerRoutes(): void 
        {
            foreach ($this->repository->all() as $route) 
            {
                $route->register();
            }
        }

        public function getRegisteredRoutes(): array 
        {
            return $this->repository->all();
        }

        public function dispatch(string $path): void 
        {
            $route = $this->repository->find($path);
            
            if ($route) {
                call_user_func($route->getHandler());
            } else {
                wp_die('Route not found', 404);
            }
        }
        
        public function register(RouteInterface $route): void
        {
            $this->repository->register($route);
        }
    }
}