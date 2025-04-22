<?php
namespace Ababilithub\FlexWordpress\Package\Route\V3\HConcrete\Web;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Route\V3\IBase\Route as BaseRoute;

if (!class_exists(__NAMESPACE__ . '\Route')) 
{
    class Route extends BaseRoute
    {
        public function register(): void 
        {
            add_action('init', function() {
                add_rewrite_rule(
                    '^' . ltrim($this->path, '/') . '/?$',
                    'index.php?ababil_route=' . urlencode($this->path),
                    'top'
                );
            });
    
            add_filter('query_vars', function($vars) {
                $vars[] = 'ababil_route';
                return $vars;
            });
    
            add_action('template_redirect', function() {
                global $wp_query;
                $route = $wp_query->get('ababil_route');
                
                if ($route && urldecode($route) === $this->path) {
                    call_user_func($this->handler);
                    exit;
                }
            });
        }
    }
}