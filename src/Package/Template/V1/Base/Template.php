<?php

namespace Ababilithub\FlexWordpress\Package\Template\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Template\V1\Contract\Template as TemplateContract,
};

abstract class Template implements TemplateContract
{
    /**
     * Template type.
     */
    protected string $type = 'template';

    /**
     * Default data.
     *
     * @var array<string, mixed>
     */
    protected array $default_data = [];

    /**
     * Current data.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $default_config = [];

    /**
     * Current configuration.
     *
     * @var array<string, mixed>
     */
    protected array $config = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->data = $this->default_data;
        $this->config = $this->default_config;
    }

    /**
     * Get type.
     */
    public function get_type(): string
    {
        return $this->type;
    }

    /**
     * Initialize template.
     *
     * Supported:
     *
     * [
     *     'data' => [],
     *     'config' => [],
     * ]
     */
    public function init(array $data = []): static
    {
        if (
            isset($data['data']) &&
            is_array($data['data'])
        ) {
            $this->set_data($data['data']);
        }

        if (
            isset($data['config']) &&
            is_array($data['config'])
        ) {
            $this->set_config($data['config']);
        }

        return $this;
    }

    /*
     * ---------------------------------------------------------
     * Data
     * ---------------------------------------------------------
     */

    public function get_default_data(): array
    {
        return $this->default_data;
    }

    public function set_default_data(
        array $data = []
    ): static {
        $this->default_data = $data;

        /*
         * If no runtime data exists, also update
         * the active data.
         */
        if ($this->data === []) {
            $this->data = $data;
        }

        return $this;
    }

    public function get_data(): array
    {
        return $this->data;
    }

    public function set_data(
        array $data = []
    ): static {
        $this->data = array_replace(
            $this->data,
            $data
        );

        return $this;
    }

    public function get_data_value(
        string $key,
        mixed $default = null
    ): mixed {
        return $this->data[$key] ?? $default;
    }

    public function set_data_value(
        string $key,
        mixed $value
    ): static {
        $this->data[$key] = $value;

        return $this;
    }

    /*
     * ---------------------------------------------------------
     * Configuration
     * ---------------------------------------------------------
     */

    public function get_default_config(): array
    {
        return $this->default_config;
    }

    public function set_default_config(
        array $config = []
    ): static {
        $this->default_config = $config;

        if ($this->config === []) {
            $this->config = $config;
        }

        return $this;
    }

    public function get_config(): array
    {
        return $this->config;
    }

    public function set_config(
        array $config = []
    ): static {
        $this->config = array_replace(
            $this->config,
            $config
        );

        return $this;
    }

    public function get_config_value(
        string $key,
        mixed $default = null
    ): mixed {
        return $this->config[$key] ?? $default;
    }

    public function set_config_value(
        string $key,
        mixed $value
    ): static {
        $this->config[$key] = $value;

        return $this;
    }

    /*
     * ---------------------------------------------------------
     * Rendering
     * ---------------------------------------------------------
     */

    /**
     * Render HTML.
     *
     * If data is supplied at render time it is merged with
     * the initialized data.
     */
    public function html(array $data = []): string
    {
        $render_data = array_replace(
            $this->data,
            $data
        );

        return $this->render_html(
            $render_data
        );
    }

    /**
     * Echo rendered HTML.
     */
    public function render(array $data = []): void
    {
        echo $this->html($data);
    }

    /**
     * Concrete template generates HTML.
     */
    abstract protected function render_html(
        array $data
    ): string;

    /*
     * ---------------------------------------------------------
     * Reset
     * ---------------------------------------------------------
     */

    public function reset(): static
    {
        $this->data = $this->default_data;
        $this->config = $this->default_config;

        return $this;
    }

    /*
     * ---------------------------------------------------------
     * Helpers
     * ---------------------------------------------------------
     */

    /**
     * Escape HTML attribute.
     */
    protected function attribute(
        string $value
    ): string {
        return esc_attr($value);
    }

    /**
     * Escape HTML text.
     */
    protected function text(
        mixed $value
    ): string {
        return esc_html(
            (string) $value
        );
    }

    /**
     * Escape URL.
     */
    protected function url(
        string $value
    ): string {
        return esc_url($value);
    }

    /**
     * Convert class array/string to HTML class string.
     */
    protected function classes(
        array|string $classes
    ): string {
        if (is_string($classes)) {
            return esc_attr($classes);
        }

        $classes = array_filter(
            array_map(
                'sanitize_html_class',
                $classes
            )
        );

        return esc_attr(
            implode(' ', $classes)
        );
    }
}