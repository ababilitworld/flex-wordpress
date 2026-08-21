<?php

namespace Ababilithub\FlexWordpress\Package\Pagination\V1\Contract;

use Ababilithub\{
    FlexWordpress\Package\Query\V1\Contract\Query as QueryContract,
};

interface Pagination
{
    /**
     * Get pagination type.
     */
    public function get_type(): string;

    /**
     * Initialize pagination.
     *
     * @param array $data
     *
     * @return static
     */
    public function init(array $data = []): static;

    /**
     * Set query instance.
     *
     * @param QueryContract $query
     *
     * @return static
     */
    public function set_query(QueryContract $query): static;

    /**
     * Get query instance.
     *
     * @return QueryContract|null
     */
    public function get_query(): ?QueryContract;

    /**
     * Generate pagination data.
     *
     * @return mixed
     */
    public function paginate();

    /**
     * Generate pagination links.
     *
     * @return string
     */
    public function pagination_links();

    /**
     * Render pagination.
     *
     * @param array $data
     *
     * @return void
     */
    public function render(array $data = []): void;

    /**
     * Generate HTML.
     *
     * @return string
     */
    public function html(): string;
}