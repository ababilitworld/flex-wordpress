<?php

namespace Ababilithub\FlexWordpress\Package\Template\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Template\V1\Contract\Template as TemplateContract
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
     * Template asset base prefix.
     */
    protected string $asset_base_prefix = '';

    /**
     * Template asset base URL.
     */
    protected string $asset_base_url = '';

    /**
     * Constructor.
     */
    public function __construct(array $data = [])
    {
        $this->data = $this->default_data;
        $this->config = $this->default_config;

        $this->init($data);
    }

    /**
     * Get template type.
     */
    public function get_type(): string
    {
        return $this->type;
    }

    /**
     * Initialize template.
     *
     * Supported structure:
     *
     * [
     *     'data' => [],
     *     'config' => [],
     * ]
     */
    public function init(array $data = []): static
    {
        if (
            isset($data['data'])
            && is_array($data['data'])
        ) {
            $this->set_data($data['data']);
        }

        if (
            isset($data['config'])
            && is_array($data['config'])
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
        return array_key_exists(
            $key,
            $this->data
        )
            ? $this->data[$key]
            : $default;
    }

    public function set_data_value(
        string $key,
        mixed $value
    ): static {
        $this->data[$key] = $value;

        return $this;
    }

    public function has_data_value(
        string $key
    ): bool {
        return array_key_exists(
            $key,
            $this->data
        );
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
        return array_key_exists(
            $key,
            $this->config
        )
            ? $this->config[$key]
            : $default;
    }

    public function set_config_value(
        string $key,
        mixed $value
    ): static {
        $this->config[$key] = $value;

        return $this;
    }

    public function has_config_value(
        string $key
    ): bool {
        return array_key_exists(
            $key,
            $this->config
        );
    }

    /*
     * ---------------------------------------------------------
     * Rendering
     * ---------------------------------------------------------
     */

    /**
     * Render HTML.
     *
     * Runtime data is merged with initialized data.
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
     * Generate HTML.
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
     * Asset
     * ---------------------------------------------------------
     */

    public function get_asset_base_prefix(): string
    {
        return $this->asset_base_prefix;
    }

    public function set_asset_base_prefix(
        string $prefix
    ): static {
        $this->asset_base_prefix = $prefix;

        return $this;
    }

    public function get_asset_base_url(): string
    {
        return $this->asset_base_url;
    }

    public function set_asset_base_url(
        string $url
    ): static {
        $this->asset_base_url = $url;

        return $this;
    }

    /*
     * ---------------------------------------------------------
     * Escaping Helpers
     * ---------------------------------------------------------
     */

    protected function attribute(
        string $value
    ): string {
        return esc_attr($value);
    }

    protected function text(
        mixed $value
    ): string {
        return esc_html(
            (string) $value
        );
    }

    protected function url(
        string $value
    ): string {
        return esc_url($value);
    }

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