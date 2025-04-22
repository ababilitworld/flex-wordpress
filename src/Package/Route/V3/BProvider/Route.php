<?php
namespace Ababilithub\FlexWordpress\Package\Route\V3\BProvider;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Route\V3\FRepository\Route as RouteRepository, 
    FlexWordpress\Package\Route\V3\EService\Route as RouteService, 
    FlexWordpress\Package\Route\V3\DManager\Route as RouteManager, 
    FlexWordpress\Package\Route\V3\CController\Route as RouteController, 
};

if (!class_exists(__NAMESPACE__ . '\Route')) 
{
    class Route 
    {
        public function register(): void 
        {
            $repository = new RouteRepository();
            $service = new RouteService($repository);
            $manager = new RouteManager($service);
            $controller = new RouteController($manager);
    
            // Store in container pattern
            $GLOBALS['ababil_route'] = [
                'controller' => $controller,
                'manager' => $manager,
                'service' => $service,
                'repository' => $repository
            ];
        }
    
        public function boot(): void 
        {
            $GLOBALS['ababil_route']['controller']->register();
        }
    }
}