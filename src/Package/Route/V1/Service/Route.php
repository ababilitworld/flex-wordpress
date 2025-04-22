<?php
namespace Ababilithub\FlexWordpress\Package\Route\V1\Service;

use Ababilithub\FlexWordpress\Package\Route\V1\Contract\Route\V1\V1 as RouteInterface;
use Ababilithub\FlexWordpress\Package\Route\V1\Mixin\Render\V1\V1 as Renderable;


class Route 
{
    protected array $routes = [];

    public function addRoute(RouteInterface $route): void 
    {
        $this->routes[] = $route;
    }

    public function registerRoutes(): void 
    {
        foreach ($this->routes as $route) 
        {
            add_rewrite_rule(
                '^' . trim($route->getUrl(), '/') . '/?$',
                'index.php?custom_route=' . sanitize_title($route->getLabel()),
                'top'
            );

            add_filter('query_vars', function ($vars) {
                $vars[] = 'custom_route';
                return $vars;
            });

            add_action('template_redirect', function () use ($route) {
                if (get_query_var('custom_route') === sanitize_title($route->getLabel())) {
                    if (!current_user_can($route->getCapability())) {
                        wp_die(__('Unauthorized access.', 'your-plugin'));
                    }

                    call_user_func($route->getCallback());
                    exit;
                }
            });
        }
    }

    public function flushRewrite(): void 
    {
        flush_rewrite_rules();
    }
}
