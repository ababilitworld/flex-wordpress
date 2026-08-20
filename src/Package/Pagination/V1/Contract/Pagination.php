<?php

namespace Ababilithub\FlexWordpress\Package\Pagination\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Pagination
{
    /**
     * Get pagination type.
     */
    public function get_type(): string;

    /**
     * Initialize pagination.
     *
     * @param array<string, mixed> $data
     */
    public function init(array $data = []): static;

    /**
     * Get runtime configuration.
     *
     * @return array<string, mixed>
     */
    public function get_config(): array;

    /**
     * Set runtime configuration.
     *
     * @param array<string, mixed> $config
     */
    public function set_config(array $config = []): static;

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
    public function set_default_config(array $config = []): static;

    /**
     * Get a configuration value.
     */
    public function get_config_value(
        string $key,
        mixed $default = null
    ): mixed;

    /**
     * Set a configuration value.
     */
    public function set_config_value(
        string $key,
        mixed $value
    ): static;

    /**
     * Set pagination context.
     *
     * Context may contain:
     *
     * query
     * total_items
     * total_pages
     * current_page
     * per_page
     */
    public function set_context(array $context = []): static;

    /**
     * Get pagination context.
     *
     * @return array<string, mixed>
     */
    public function get_context(): array;

    /**
     * Get context value.
     */
    public function get_context_value(
        string $key,
        mixed $default = null
    ): mixed;

    /**
     * Set context value.
     */
    public function set_context_value(
        string $key,
        mixed $value
    ): static;

    /**
     * Calculate/prepare pagination.
     */
    public function paginate(): static;

    /**
     * Get current page.
     */
    public function get_current_page(): int;

    /**
     * Get per-page value.
     */
    public function get_per_page(): int;

    /**
     * Get total items.
     */
    public function get_total_items(): int;

    /**
     * Get total pages.
     */
    public function get_total_pages(): int;

    /**
     * Determine whether another page exists.
     */
    public function has_next(): bool;

    /**
     * Determine whether previous page exists.
     */
    public function has_previous(): bool;

    /**
     * Get pagination links.
     *
     * @return string
     */
    public function pagination_links(): string;

    /**
     * Render pagination.
     *
     * @param array<string, mixed> $data
     */
    public function render(array $data = []): void;
}