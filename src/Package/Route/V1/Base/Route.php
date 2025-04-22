<?php
namespace Ababilithub\FlexWordpress\Package\Route\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Route\V1\Contract\Route\V1\V1 as RouteInterface, 
};

if (!class_exists(__NAMESPACE__ . '\Route')) 
{
    abstract class Route implements RouteInterface 
    {
        protected string $label;
        protected string $url;
        protected string $capability;
        protected $callback;

        public function __construct(
            string $label,
            string $url,
            string $capability,
            callable $callback
        ) 
        {
            $this->label = $label;
            $this->url = $url;
            $this->capability = $capability;
            $this->callback = $callback;
        }

        public function getLabel(): string 
        {
            return $this->label;
        }

        public function getUrl(): string 
        {
            return $this->url;
        }

        public function getCapability(): string 
        {
            return $this->capability;
        }

        public function getCallback(): callable 
        {
            return $this->callback;
        }
    }
}
