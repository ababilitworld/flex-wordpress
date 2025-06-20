<?php

namespace Ababilithub\FlexWordpress\Package\Shortcode\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Shortcode\V1\Contract\Shortcode as ShortcodeContract;

abstract class Shortcode implements ShortcodeContract
{
    protected string $tag;
    protected array $defaultAttributes = [];
    protected array $attributes = [];

    public function __construct()
    {
        $this->set_tag();
        $this->init();
    }

    abstract protected function set_tag(): void;

    abstract public function render(array $attributes): string;

    public function register(): void
    {
        add_shortcode($this->get_tag(), [$this, 'render']);
    }

    public function get_tag(): string
    {
        return $this->tag;
    }

    public function get_default_attributes(): array
    {
        return $this->defaultAttributes;
    }

    public function set_attributes(array $attributes): void
    {
        $this->attributes = shortcode_atts($this->defaultAttributes, $attributes);
    }

    public function get_attributes(): array
    {
        return $this->attributes;
    }

    protected function init(): void
    {
        // Optional for concrete to override
    }
}
