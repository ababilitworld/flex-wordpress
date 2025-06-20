<?php

namespace Ababilithub\FlexWordpress\Package\Shortcode\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Shortcode\V1\Contract\Shortcode as ShortcodeContract,
};

abstract class Shortcode implements ShortcodeContract
{
    public function default_attribute(): array 
    {
        return [];
    }

    abstract protected function get_tag(): string;

    abstract public function render(array $attributes): string;

    public function register(): void 
    {
        add_shortcode($this->get_tag(), [$this, 'render']);
    }

    
}