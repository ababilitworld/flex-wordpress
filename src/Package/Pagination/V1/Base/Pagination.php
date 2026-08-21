<?php

namespace Ababilithub\FlexWordpress\Package\Pagination\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Query\V1\Contract\Query as QueryContract,
    FlexWordpress\Package\Pagination\V1\Contract\Pagination as PaginationContract
};

abstract class Pagination implements PaginationContract
{
    /**
     * Pagination type.
     *
     * @var string
     */
    protected string $type = 'pagination';

    /**
     * Current configuration.
     *
     * @var array
     */
    protected array $config = [];

    /**
     * Default configuration.
     *
     * @var array
     */
    protected array $default_config = [
        'enabled' => true,

        'per_page' => 10,

        'page' => 1,

        'page_var' => 'paged',

        'mid_size' => 2,

        'end_size' => 1,

        'prev_text' => 'Previous',

        'next_text' => 'Next',

        'class' => '',

        'attribute' => '',
    ];

    /**
     * Query.
     *
     * @var QueryContract|null
     */
    protected ?QueryContract $query = null;

    /**
     * Total records.
     *
     * @var int
     */
    protected int $total_items = 0;

    /**
     * Get type.
     *
     * @return string
     */
    public function get_type(): string
    {
        return $this->type;
    }

    /**
     * Initialize.
     *
     * @param array $data
     *
     * @return static
     */
    public function init(array $data = []): static
    {
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
    public function set_config(
        array $config = []
    ): static {
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
        return array_key_exists(
            $key,
            $this->config
        )
            ? $this->config[$key]
            : $default;
    }

    /**
     * Set query.
     *
     * @param QueryContract $query
     *
     * @return static
     */
    public function set_query(
        QueryContract $query
    ): static {
        $this->query = $query;

        return $this;
    }

    /**
     * Get query.
     *
     * @return QueryContract|null
     */
    public function get_query(): ?QueryContract
    {
        return $this->query;
    }

    /**
     * Set total items.
     *
     * @param int $total
     *
     * @return static
     */
    public function set_total_items(
        int $total
    ): static {
        $this->total_items = max(
            0,
            $total
        );

        return $this;
    }

    /**
     * Get total items.
     *
     * @return int
     */
    public function get_total_items(): int
    {
        return $this->total_items;
    }

    /**
     * Get records per page.
     *
     * @return int
     */
    public function get_per_page(): int
    {
        return max(
            1,
            absint(
                $this->get_config_value(
                    'per_page',
                    10
                )
            )
        );
    }

    /**
     * Get current page.
     *
     * The request value has priority over the
     * configured default page.
     *
     * @return int
     */
    public function get_current_page(): int
    {
        $page_var = sanitize_key(
            (string) $this->get_config_value(
                'page_var',
                'paged'
            )
        );

        if ($page_var === '') {
            $page_var = 'paged';
        }

        $configured_page = max(
            1,
            absint(
                $this->get_config_value(
                    'page',
                    1
                )
            )
        );

        if (
            isset($_GET[$page_var])
            && !is_array($_GET[$page_var])
        ) {
            $request_page = absint(
                wp_unslash(
                    $_GET[$page_var]
                )
            );

            if ($request_page > 0) {
                return $request_page;
            }
        }

        return $configured_page;
    }

    /**
     * Get total pages.
     *
     * @return int
     */
    public function get_total_pages(): int
    {
        if ($this->total_items <= 0) {
            return 0;
        }

        return (int) ceil(
            $this->total_items / $this->get_per_page()
        );
    }

    /**
     * Apply pagination.
     *
     * Concrete query implementations can override
     * this when they need specialized behavior.
     *
     * @return static
     */
    public function paginate(): static
    {
        return $this;
    }

    /**
     * Render pagination.
     *
     * @param array $data
     *
     * @return void
     */
    public function render(
        array $data = []
    ): void {
        echo $this->pagination_links();
    }

    /**
     * Get current URL without pagination parameter.
     *
     * @return string
     */
    protected function get_base_url(): string
    {
        return remove_query_arg(
            $this->get_config_value(
                'page_var',
                'paged'
            )
        );
    }

    /**
     * Build page URL.
     *
     * @param int $page
     *
     * @return string
     */
    protected function get_page_url(
        int $page
    ): string {
        return esc_url(
            add_query_arg(
                $this->get_config_value(
                    'page_var',
                    'paged'
                ),
                max(1, $page),
                $this->get_base_url()
            )
        );
    }

    /**
     * Render pagination links.
     *
     * Concrete pagination implementations must provide
     * the actual HTML.
     *
     * @return string
     */
    abstract public function pagination_links(): string;
}