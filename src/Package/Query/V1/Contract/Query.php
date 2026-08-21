<?php

namespace Ababilithub\FlexWordpress\Package\Query\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Pagination\V1\Contract\Pagination as PaginationContract;

interface Query
{
    /**
     * Initialize query.
     *
     * @param array $data
     * @return static
     */
    public function init(array $data = []): static;

    /**
     * Set configuration.
     *
     * @param array $config
     * @return static
     */
    public function set_config(array $config = []): static;

    /**
     * Get configuration.
     *
     * @return array
     */
    public function get_config(): array;

    /**
     * Set a configuration value.
     *
     * @param string $key
     * @param mixed $value
     * @return static
     */
    public function set_config_value(
        string $key,
        mixed $value
    ): static;

    /**
     * Get a configuration value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get_config_value(
        string $key,
        mixed $default = null
    ): mixed;

    /**
     * Attach pagination.
     *
     * @param PaginationContract|null $pagination
     * @return static
     */
    public function paginate(
        ?PaginationContract $pagination = null
    ): static;

    /**
     * Execute query.
     *
     * @return static
     */
    public function execute(): static;

    /**
     * Get query results.
     *
     * @return array
     */
    public function get_results(): array;

    /**
     * Get underlying query.
     *
     * @return mixed
     */
    public function get_query(): mixed;

    /**
     * Get total number of matching records.
     *
     * @return int
     */
    public function get_found_rows(): int;

    /**
     * Get attached pagination.
     *
     * @return PaginationContract|null
     */
    public function get_pagination(): ?PaginationContract;
}