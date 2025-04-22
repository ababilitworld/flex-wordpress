<?php 
namespace Ababilithub\FlexWordpress\Package\Route\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Route\V2\Base\Route as BaseRoute,
    FlexWordpress\Package\Route\V2\Contract\Handle\Handle as RouteHandlerInterface, 
};

if (!class_exists(__NAMESPACE__ . '\Route')) 
{
    class Route extends BaseRoute
    {
        public function __construct() {
            add_action('init', [$this, 'init_router']);
            add_filter('query_vars', [$this, 'filter_query_vars']);
            add_action('template_redirect', [$this, 'dispatch'], 1);
            add_filter('template_include', [$this, 'filter_template'], 99);
            register_activation_hook(__FILE__, [$this, 'activate']);
        }

        public function add_route(string $pattern, string $method, callable $callback): self {
            $this->routes[] = [
                'pattern' => $pattern,
                'method' => strtoupper($method),
                'callback' => $callback,
                'name' => 'route_' . md5($pattern . $method)
            ];
            return $this;
        }

        public function handle(array $params): void {
            call_user_func_array($this->current_route['callback'], $params);
        }

        public function init_router(): void {
            $this->register_rewrite_rules();
        }

        public function filter_query_vars(array $vars): array {
            return $this->register_query_vars($vars);
        }

        public function dispatch(): void {
            $route_name = get_query_var('custom_route');
            
            if (!$route_name) return;
            
            foreach ($this->routes as $route) {
                if ($route['name'] === $route_name && $_SERVER['REQUEST_METHOD'] === $route['method']) {
                    $this->current_route = $route;
                    $this->handle($this->extract_params($_SERVER['REQUEST_URI'], $route['pattern']));
                    exit;
                }
            }
            
            wp_die('Route not found', 404);
        }

        public function filter_template(string $template): string {
            $template_handler = new WordPressTemplateHandler($this->current_route);
            return $template_handler->handle_template($template);
        }

        protected function register_rewrite_rules(): void {
            foreach ($this->routes as $route) {
                add_rewrite_rule(
                    $this->pattern_to_regex($route['pattern']),
                    'index.php?custom_route=' . $route['name'],
                    'top'
                );
            }
        }

        protected function register_query_vars(array $vars): array {
            return array_merge($vars, ['custom_route']);
        }

        public function activate(): void {
            $this->register_rewrite_rules();
            flush_rewrite_rules();
        }
    }
}
