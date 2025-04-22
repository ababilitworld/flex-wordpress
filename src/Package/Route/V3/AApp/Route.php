<?php
namespace Ababilithub\FlexWordpress\Package\Route\V3\AApp;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Route\V3\BProvider\Route as RouteServiceProvider;

if (!class_exists(__NAMESPACE__ . '\Route')) 
{
    class Route 
    {
        private array $providers = [];
    
        public function __construct() 
        {
            $this->registerProviders();
        }
    
        protected function registerProviders(): void 
        {
            $this->providers = [
                new RouteServiceProvider(),
            ];
        }
    
        public function boot(): void 
        {
            // Register all providers
            array_walk($this->providers, fn($provider) => 
                method_exists($provider, 'register') && $provider->register()
            );
            
            // Boot all providers
            array_walk($this->providers, fn($provider) => 
                method_exists($provider, 'boot') && $provider->boot()
            );
        }
        
        public static function make(): self
        {
            return new static();
        }
    }
}