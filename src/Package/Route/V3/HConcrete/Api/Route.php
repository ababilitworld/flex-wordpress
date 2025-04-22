<?php
namespace Ababilithub\FlexWordpress\Package\Route\V3\HConcrete\Api;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Route\V3\IBase\Route as BaseRoute
};

if (!class_exists(__NAMESPACE__ . '\Route')) 
{
    class Route extends BaseRoute
    {
        public function register(): void 
        {
            add_action('rest_api_init', function() {
                foreach ($this->methods as $method) {
                    register_rest_route('ababil/v1', $this->path, [
                        'methods' => $method,
                        'callback' => $this->handler,
                        'permission_callback' => '__return_true'
                    ]);
                }
            });
        }
    }
}