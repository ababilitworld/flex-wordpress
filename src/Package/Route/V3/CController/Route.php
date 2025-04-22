<?php
namespace Ababilithub\FlexWordpress\Package\Route\V3\CController;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Route\V3\DManager\Route as RouteManager;

if (!class_exists(__NAMESPACE__ . '\Route')) 
{
    class Route 
    {
        private RouteManager $manager;

        public function __construct(RouteManager $manager) 
        {
            $this->manager = $manager;
        }

        public function register(): void 
        {
            $this->manager->init();
        }

        public function add(string $path, \Closure $handler, array $methods = ['GET']): void 
        {
            $this->manager->addRoute($path, $handler, $methods);
        }
        
        public function group(string $prefix, \Closure $callback): void
        {
            $callback(function($path, $handler, $methods = ['GET']) use ($prefix) {
                $this->add($prefix . $path, $handler, $methods);
            });
        }
    }
}