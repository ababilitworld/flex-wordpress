<?php

namespace Ababilithub\FlexWordpress\Package\Query\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();


use Ababilithub\{
    FlexWordpress\Package\Query\V1\Contract\Query as QueryContract,
    FlexWordpress\Package\Pagination\V1\Contract\Pagination as PaginationContract
};

abstract class Query implements QueryContract
{
    /**
     * Query type.
     */
    protected string $type = 'query';

    /**
     * Runtime configuration.
     *
     * @var array<string, mixed>
     */
    protected array $config = [];

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $default_config = [];

    /**
     * Runtime query arguments.
     *
     * @var array<string, mixed>
     */
    protected array $args = [];

    /**
     * Default query arguments.
     *
     * @var array<string, mixed>
     */
    protected array $default_args = [];

    /**
     * Pagination object.
     */
    protected ?PaginationContract $pagination = null;

    /**
     * Results.
     *
     * @var array<int, mixed>
     */
    protected array $results = [];

    /**
     * Total matching items.
     */
    protected int $found_items = 0;

    /**
     * Maximum pages.
     */
    protected int $max_num_pages = 0;

    public function __construct()
    {
        $this->config = $this->default_config;
        $this->args = $this->default_args;
    }

    /**
     * Get query type.
     */
    public function get_type(): string
    {
        return $this->type;
    }

    /**
     * Initialize query.
     */
    public function init(array $data = []): static
    {
        if (
            isset($data['config']) &&
            is_array($data['config'])
        ) {
            $this->set_config($data['config']);
        }

        if (
            isset($data['args']) &&
            is_array($data['args'])
        ) {
            $this->set_args($data['args']);
        }

        return $this;
    }

    /*
     * ---------------------------------------------------------
     * Configuration
     * ---------------------------------------------------------
     */

    public function get_config(): array
    {
        return $this->config;
    }

    public function set_config(array $config = []): static
    {
        $this->config = array_replace(
            $this->config,
            $config
        );

        return $this;
    }

    public function get_default_config(): array
    {
        return $this->default_config;
    }

    public function set_default_config(array $config = []): static
    {
        $this->default_config = $config;

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
     * Arguments
     * ---------------------------------------------------------
     */

    public function get_args(): array
    {
        return $this->args;
    }

    public function set_args(array $args = []): static
    {
        $this->args = array_replace(
            $this->args,
            $args
        );

        return $this;
    }

    public function get_arg(
        string $key,
        mixed $default = null
    ): mixed {
        return $this->args[$key] ?? $default;
    }

    public function set_arg(
        string $key,
        mixed $value
    ): static {
        $this->args[$key] = $value;

        return $this;
    }

    /*
     * ---------------------------------------------------------
     * Pagination
     * ---------------------------------------------------------
     */

    public function paginate(
        PaginationContract $pagination
    ): static {
        $this->pagination = $pagination;

        return $this;
    }

    public function get_pagination(): ?PaginationContract
    {
        return $this->pagination;
    }

    /**
     * Get requested page from pagination.
     */
    protected function get_current_page(): int
    {
        if (!$this->pagination) {
            return max(
                1,
                absint(
                    $this->get_arg('paged', 1)
                )
            );
        }

        return max(
            1,
            absint(
                $this->pagination->get_config_value(
                    'current_page',
                    $this->get_arg('paged', 1)
                )
            )
        );
    }

    /**
     * Get posts per page.
     */
    protected function get_per_page(): int
    {
        return max(
            1,
            absint(
                $this->get_arg(
                    'posts_per_page',
                    get_option('posts_per_page')
                )
            )
        );
    }

    /*
     * ---------------------------------------------------------
     * Logical Query Helpers
     * ---------------------------------------------------------
     */

    /**
     * Normalize a logical meta query.
     *
     * Example:
     *
     * [
     *     'relation' => 'OR',
     *     [
     *         'key' => 'price',
     *         'value' => 100,
     *         'compare' => '>=',
     *         'type' => 'NUMERIC',
     *     ],
     *     [
     *         'key' => 'featured',
     *         'value' => 'yes',
     *     ],
     * ]
     */
    protected function normalize_meta_query(
        array $query
    ): array {
        if ($query === []) {
            return [];
        }

        $normalized = [];

        if (
            isset($query['relation']) &&
            in_array(
                strtoupper((string) $query['relation']),
                ['AND', 'OR'],
                true
            )
        ) {
            $normalized['relation'] = strtoupper(
                (string) $query['relation']
            );
        }

        foreach ($query as $key => $value) {
            if ($key === 'relation') {
                continue;
            }

            if (!is_array($value)) {
                continue;
            }

            if (
                isset($value['relation']) ||
                $this->is_meta_clause($value)
            ) {
                $normalized[] = $value;
            }
        }

        return $normalized;
    }

    /**
     * Normalize a logical taxonomy query.
     */
    protected function normalize_tax_query(
        array $query
    ): array {
        if ($query === []) {
            return [];
        }

        $normalized = [];

        if (
            isset($query['relation']) &&
            in_array(
                strtoupper((string) $query['relation']),
                ['AND', 'OR'],
                true
            )
        ) {
            $normalized['relation'] = strtoupper(
                (string) $query['relation']
            );
        }

        foreach ($query as $key => $value) {
            if ($key === 'relation') {
                continue;
            }

            if (!is_array($value)) {
                continue;
            }

            if (
                isset($value['relation']) ||
                $this->is_taxonomy_clause($value)
            ) {
                $normalized[] = $value;
            }
        }

        return $normalized;
    }

    /**
     * Determine whether an array is a meta clause.
     */
    protected function is_meta_clause(array $clause): bool
    {
        return isset($clause['key'])
            || isset($clause['compare_key']);
    }

    /**
     * Determine whether an array is a taxonomy clause.
     */
    protected function is_taxonomy_clause(array $clause): bool
    {
        return isset($clause['taxonomy']);
    }

    /**
     * Add a meta query clause.
     */
    public function add_meta_query(
        array $clause,
        string $relation = 'AND'
    ): static {
        $relation = strtoupper($relation);

        if (!in_array($relation, ['AND', 'OR'], true)) {
            $relation = 'AND';
        }

        $meta_query = $this->get_arg(
            'meta_query',
            []
        );

        if (!is_array($meta_query)) {
            $meta_query = [];
        }

        if (!isset($meta_query['relation'])) {
            $meta_query['relation'] = $relation;
        }

        $meta_query[] = $clause;

        $this->set_arg(
            'meta_query',
            $meta_query
        );

        return $this;
    }

    /**
     * Add taxonomy query clause.
     */
    public function add_tax_query(
        array $clause,
        string $relation = 'AND'
    ): static {
        $relation = strtoupper($relation);

        if (!in_array($relation, ['AND', 'OR'], true)) {
            $relation = 'AND';
        }

        $tax_query = $this->get_arg(
            'tax_query',
            []
        );

        if (!is_array($tax_query)) {
            $tax_query = [];
        }

        if (!isset($tax_query['relation'])) {
            $tax_query['relation'] = $relation;
        }

        $tax_query[] = $clause;

        $this->set_arg(
            'tax_query',
            $tax_query
        );

        return $this;
    }

    /*
     * ---------------------------------------------------------
     * Lifecycle
     * ---------------------------------------------------------
     */

    public function execute(): static
    {
        $this->prepare_args();

        $this->run_query();

        $this->initialize_pagination();

        return $this;
    }

    /**
     * Concrete implementation executes actual query.
     */
    abstract protected function run_query(): void;

    /**
     * Prepare arguments before execution.
     */
    protected function prepare_args(): void
    {
        if ($this->pagination) {
            $this->args['paged'] =
                $this->get_current_page();

            $this->args['posts_per_page'] =
                $this->get_per_page();
        }
    }

    /**
     * Initialize pagination.
     */
    protected function initialize_pagination(): void
    {
        if (!$this->pagination) {
            return;
        }

        $this->pagination
            ->set_query(
                $this->get_pagination_query()
            )
            ->paginate();
    }

    /**
     * Concrete classes can override this.
     */
    protected function get_pagination_query(): mixed
    {
        return null;
    }

    /*
     * ---------------------------------------------------------
     * Results
     * ---------------------------------------------------------
     */

    public function get_results(): array
    {
        return $this->results;
    }

    public function has_results(): bool
    {
        return $this->results !== [];
    }

    public function get_found_items(): int
    {
        return $this->found_items;
    }

    public function get_max_num_pages(): int
    {
        return $this->max_num_pages;
    }

    /*
     * ---------------------------------------------------------
     * Reset
     * ---------------------------------------------------------
     */

    public function reset(): static
    {
        $this->config = $this->default_config;
        $this->args = $this->default_args;

        $this->pagination = null;
        $this->results = [];
        $this->found_items = 0;
        $this->max_num_pages = 0;

        return $this;
    }
}