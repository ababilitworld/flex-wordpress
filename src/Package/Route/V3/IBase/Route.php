<?php
namespace Ababilithub\FlexWordpress\Package\Route\V3\IBase;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Route\V3\JContract\Route as RouteContract;

if (!class_exists(__NAMESPACE__ . '\Route')) 
{
    abstract class Route implements RouteContract
    {
        protected string $path;
        protected \Closure $handler;
        protected array $methods;

        public function __construct(string $path, \Closure $handler, array $methods = ['GET']) 
        {
            $this->path = $path;
            $this->handler = $handler;
            $this->methods = $methods;
        }

        public function getPath(): string 
        {
            return $this->path;
        }

        public function getHandler(): \Closure 
        {
            return $this->handler;
        }

        public function getMethods(): array 
        {
            return $this->methods;
        }

        abstract public function register(): void;
    }
}