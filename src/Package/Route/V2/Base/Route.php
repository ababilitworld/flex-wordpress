<?php 
namespace Ababilithub\FlexWordpress\Package\Route\V2\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Route\V2\Contract\Registrar\Route as RouteRegistryInterface,
    FlexWordpress\Package\Route\V2\Contract\Handle\Handle as RouteHandlerInterface, 
};

if (!class_exists(__NAMESPACE__ . '\Route')) 
{
    abstract class Route implements RouteRegistryInterface, RouteHandlerInterface 
    {
        protected array $routes = [];
        protected array $current_route = [];
        
        abstract protected function register_rewrite_rules(): void;
        abstract protected function register_query_vars(array $vars): array;
        
        protected function pattern_to_regex(string $pattern): string 
        {
            $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
            return '^' . str_replace('/', '\/', $regex) . '$';
        }
    
        protected function extract_params(string $url, string $pattern): array 
        {
            $regex = $this->pattern_to_regex($pattern);
            preg_match("#{$regex}#", $url, $matches);
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }
    }

}