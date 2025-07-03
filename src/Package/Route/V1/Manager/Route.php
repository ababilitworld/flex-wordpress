<?php
namespace Ababilithub\FlexWordpress\Package\Route\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Route\V1\Factory\Route as RouteFactory,
    FlexWordpress\Package\Route\V1\Concrete\StaticFilter\Route as StaticFilterRoute,
};

class Route extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
                [
                //StaticFilterRoute::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $route = RouteFactory::get($item);

            if ($route instanceof RouteContract) 
            {
                $route->register();
            }
        }
    }
}