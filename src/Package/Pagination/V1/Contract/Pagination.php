<?php

namespace Ababilithub\FlexWordpress\Package\Pagination\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Query\V1\Contract\Query as QueryContract
};

interface Pagination
{
    /**
     * Get pagination type.
     *
     * @return string
     */
    public function get_type(): string;

    /**
     * Initialize pagination.
     *
     * @param array $data
     * @return static
     */
    public function init(array $data = []): static;

    /**
     * Set query.
     *
     * @param QueryContract $query
     * @return static
     */
    public function set_query(
        QueryContract $query
    ): static;

    /**
     * Get query.
     *
     * @return QueryContract|null
     */
    public function get_query(): ?QueryContract;

    /**
     * Set total records.
     *
     * @param int $total
     * @return static
     */
    public function set_total_items(
        int $total
    ): static;

    /**
     * Get total records.
     *
     * @return int
     */
    public function get_total_items(): int;

    /**
     * Get current page.
     *
     * @return int
     */
    public function get_current_page(): int;

    /**
     * Get records per page.
     *
     * @return int
     */
    public function get_per_page(): int;

    /**
     * Get total pages.
     *
     * @return int
     */
    public function get_total_pages(): int;

    /**
     * Apply pagination.
     *
     * @return static
     */
    public function paginate(): static;

    /**
     * Render pagination links.
     *
     * @return string
     */
    public function pagination_links(): string;

    /**
     * Render pagination.
     *
     * @param array $data
     * @return void
     */
    public function render(array $data = []): void;
}