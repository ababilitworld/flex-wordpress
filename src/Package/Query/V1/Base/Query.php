<?php

namespace Ababilithub\FlexWordpress\Package\Query\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Pagination\V1\Contract\Pagination as PaginationContract,
    FlexWordpress\Package\Query\V1\Contract\Query as QueryContract,
};

abstract class Query implements QueryContract
{
    /**
     * Query data.
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Query configuration.
     *
     * @var array
     */
    protected array $config = [];

    /**
     * Default configuration.
     *
     * @var array
     */
    protected array $default_config = [];

    /**
     * Query results.
     *
     * @var array
     */
    protected array $results = [];

    /**
     * Underlying WordPress query.
     *
     * @var mixed
     */
    protected mixed $query = null;

    /**
     * Pagination object.
     *
     * @var PaginationContract|null
     */
    protected ?PaginationContract $pagination = null;

    /**
     * Initialize query.
     *
     * @param array $data
     *
     * @return static
     */
    public function init(array $data = []): static
    {
        $this->data = is_array($data['data'] ?? null)
            ? $data['data']
            : [];

        $this->config = array_replace_recursive(
            $this->default_config,
            is_array($data['config'] ?? null)
                ? $data['config']
                : []
        );

        return $this;
    }

    /**
     * Set configuration.
     *
     * @param array $config
     *
     * @return static
     */
    public function set_config(array $config = []): static
    {
        $this->config = array_replace_recursive(
            $this->default_config,
            $config
        );

        return $this;
    }

    /**
     * Get configuration.
     *
     * @return array
     */
    public function get_config(): array
    {
        return $this->config;
    }

    /**
     * Set configuration value.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return static
     */
    public function set_config_value(
        string $key,
        mixed $value
    ): static {
        $this->config[$key] = $value;

        return $this;
    }

    /**
     * Get configuration value.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get_config_value(
        string $key,
        mixed $default = null
    ): mixed {
        return array_key_exists($key, $this->config)
            ? $this->config[$key]
            : $default;
    }

    /**
     * Attach pagination to the query.
     *
     * The pagination object determines the current page
     * from the request. That page is then synchronized
     * with the query configuration before execution.
     *
     * This keeps pagination concerns inside the reusable
     * query infrastructure rather than inside individual
     * plugin templates.
     *
     * @param PaginationContract|null $pagination
     *
     * @return static
     */
    public function paginate(
        ?PaginationContract $pagination = null
    ): static {
        $this->pagination = $pagination;

        if (!$pagination) {
            return $this;
        }

        /*
         * Synchronize records per page.
         */
        $this->set_config_value(
            'posts_per_page',
            $pagination->get_per_page()
        );

        /*
         * Synchronize current page.
         *
         * Example:
         *
         * ?paged=1 → paged = 1
         * ?paged=2 → paged = 2
         * ?paged=3 → paged = 3
         */
        $this->set_config_value(
            'paged',
            max(
                1,
                $pagination->get_current_page()
            )
        );

        return $this;
    }

    /**
     * Execute query.
     *
     * @return static
     */
    public function execute(): static
    {
        /*
         * Prepare WordPress query arguments.
         */
        $args = $this->prepare_args();

        /*
         * Create the concrete query.
         */
        $this->query = $this->create_query(
            $args
        );

        /*
         * Extract raw results.
         */
        $this->results = $this->extract_results(
            $this->query
        );

        /*
         * Connect the application-level query
         * to pagination.
         *
         * Pagination receives this Query object,
         * not WP_Query.
         */
        if ($this->pagination) {
            $this->pagination
                ->set_query($this)
                ->set_total_items(
                    $this->get_found_rows()
                );
        }

        return $this;
    }

    /**
     * Get results.
     *
     * @return array
     */
    public function get_results(): array
    {
        return $this->results;
    }

    /**
     * Get underlying query.
     *
     * @return ?\WP_Query
     */
    public function get_query(): ?\WP_Query
    {
        return $this->query;
    }

    /**
     * Get found rows.
     *
     * @return int
     */
    public function get_found_rows(): int
    {
        if ($this->query instanceof \WP_Query) {
            return (int) $this->query->found_posts;
        }

        return count($this->results);
    }

    /**
     * Get pagination.
     *
     * @return PaginationContract|null
     */
    public function get_pagination(): ?PaginationContract
    {
        return $this->pagination;
    }

    /**
     * Extract results from underlying query.
     *
     * @param mixed $query
     *
     * @return array
     */
    protected function extract_results(
        mixed $query
    ): array {
        if ($query instanceof \WP_Query) {
            return $query->posts;
        }

        return is_array($query)
            ? $query
            : [];
    }

    /**
     * Prepare concrete query arguments.
     *
     * @return array
     */
    abstract public function prepare_args(): array;

    /**
     * Create concrete query.
     *
     * @param array $args
     *
     * @return mixed
     */
    abstract protected function create_query(
        array $args
    ): mixed;
}