<?php

namespace Ababilithub\FlexWordpress\Package\Template\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Template
{
    /**
     * Get template type.
     */
    public function get_type(): string;

    /*
     * ---------------------------------------------------------
     * Initialization
     * ---------------------------------------------------------
     */

    /**
     * Initialize template.
     *
     * Supported:
     *
     * [
     *     'data'   => [],
     *     'config' => [],
     * ]
     *
     * @param array<string, mixed> $data
     */
    public function init(
        array $data = []
    ): static;

    /*
     * ---------------------------------------------------------
     * Data
     * ---------------------------------------------------------
     */

    /**
     * Get default data.
     *
     * @return array<string, mixed>
     */
    public function get_default_data(): array;

    /**
     * Set default data.
     *
     * @param array<string, mixed> $data
     */
    public function set_default_data(
        array $data = []
    ): static;

    /**
     * Get current data.
     *
     * @return array<string, mixed>
     */
    public function get_data(): array;

    /**
     * Set current data.
     *
     * @param array<string, mixed> $data
     */
    public function set_data(
        array $data = []
    ): static;

    /**
     * Get data value.
     */
    public function get_data_value(
        string $key,
        mixed $default = null
    ): mixed;

    /**
     * Set data value.
     */
    public function set_data_value(
        string $key,
        mixed $value
    ): static;

    /**
     * Determine whether data key exists.
     */
    public function has_data_value(
        string $key
    ): bool;

    /*
     * ---------------------------------------------------------
     * Configuration
     * ---------------------------------------------------------
     */

    /**
     * Get default configuration.
     *
     * @return array<string, mixed>
     */
    public function get_default_config(): array;

    /**
     * Set default configuration.
     *
     * @param array<string, mixed> $config
     */
    public function set_default_config(
        array $config = []
    ): static;

    /**
     * Get current configuration.
     *
     * @return array<string, mixed>
     */
    public function get_config(): array;

    /**
     * Set current configuration.
     *
     * @param array<string, mixed> $config
     */
    public function set_config(
        array $config = []
    ): static;

    /**
     * Get configuration value.
     */
    public function get_config_value(
        string $key,
        mixed $default = null
    ): mixed;

    /**
     * Set configuration value.
     */
    public function set_config_value(
        string $key,
        mixed $value
    ): static;

    /**
     * Determine whether configuration key exists.
     */
    public function has_config_value(
        string $key
    ): bool;

    /*
     * ---------------------------------------------------------
     * Rendering
     * ---------------------------------------------------------
     */

    /**
     * Render HTML.
     *
     * @param array<string, mixed> $data
     */
    public function html(
        array $data = []
    ): string;

    /**
     * Echo rendered HTML.
     *
     * @param array<string, mixed> $data
     */
    public function render(
        array $data = []
    ): void;

    /*
     * ---------------------------------------------------------
     * Reset
     * ---------------------------------------------------------
     */

    /**
     * Reset template state.
     */
    public function reset(): static;

    /*
     * ---------------------------------------------------------
     * Assets
     * ---------------------------------------------------------
     */

    /**
     * Get asset base prefix.
     */
    public function get_asset_base_prefix(): string;

    /**
     * Set asset base prefix.
     */
    public function set_asset_base_prefix(
        string $prefix
    ): static;

    /**
     * Get asset base URL.
     */
    public function get_asset_base_url(): string;

    /**
     * Set asset base URL.
     */
    public function set_asset_base_url(
        string $url
    ): static;
} 
