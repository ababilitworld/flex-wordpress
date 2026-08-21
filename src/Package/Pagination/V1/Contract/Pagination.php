<?php

namespace Ababilithub\FlexWordpress\Package\Pagination\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Pagination
{
    public function get_type(): string;

    public function init(array $data = []): static;

    /**
     * Prepare pagination before WP_Query executes.
     */
    public function prepare(): static;

    /**
     * Attach the executed WordPress query.
     */
    public function set_query(\WP_Query $query): static;

    public function get_query(): ?\WP_Query;

    /**
     * Calculate/prepare pagination state after WP_Query executes.
     */
    public function paginate(): static;

    public function pagination_links(): string;

    public function render(array $data = []): void;

    public function html(): string;

    public function get_config(): array;

    public function set_config(array $config): static;

    public function get_default_config(): array;

    public function set_default_config(array $config): static;

    public function get_config_value(
        string $key,
        mixed $default = null
    ): mixed;

    public function set_config_value(
        string $key,
        mixed $value
    ): static;
}
