<?php

namespace Ababilithub\FlexWordpress\Package\Pagination\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Pagination\V1\Contract\Pagination as PaginationContract
};

abstract class Pagination implements PaginationContract
{
    /**
     * Pagination type.
     */
    protected string $type = 'pagination';

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
     * Pagination context.
     *
     * @var array<string, mixed>
     */
    protected array $context = [];

    /**
     * Calculated pagination data.
     *
     * @var array<string, mixed>
     */
    protected array $pagination = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->config = $this->default_config;
    }

    /**
     * Get pagination type.
     */
    public function get_type(): string
    {
        return $this->type;
    }

    /**
     * Initialize.
     *
     * @param array<string, mixed> $data
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
            isset($data['context']) &&
            is_array($data['context'])
        ) {
            $this->set_context($data['context']);
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
     * Context
     * ---------------------------------------------------------
     */

    public function set_context(array $context = []): static
    {
        $this->context = array_replace(
            $this->context,
            $context
        );

        return $this;
    }

    public function get_context(): array
    {
        return $this->context;
    }

    public function get_context_value(
        string $key,
        mixed $default = null
    ): mixed {
        return $this->context[$key] ?? $default;
    }

    public function set_context_value(
        string $key,
        mixed $value
    ): static {
        $this->context[$key] = $value;

        return $this;
    }

    /*
     * ---------------------------------------------------------
     * Pagination Data
     * ---------------------------------------------------------
     */

    public function paginate(): static
    {
        $total_items = $this->get_total_items();
        $per_page = $this->get_per_page();
        $current_page = $this->get_current_page();

        $total_pages = $per_page > 0
            ? (int) ceil($total_items / $per_page)
            : 0;

        $current_page = $total_pages > 0
            ? min($current_page, $total_pages)
            : 1;

        $this->pagination = [
            'current_page' => $current_page,
            'per_page' => $per_page,
            'total_items' => $total_items,
            'total_pages' => $total_pages,
            'has_previous' => $current_page > 1,
            'has_next' => $current_page < $total_pages,
        ];

        return $this;
    }

    public function get_current_page(): int
    {
        return max(
            1,
            absint(
                $this->get_context_value(
                    'current_page',
                    $this->get_config_value(
                        'current_page',
                        1
                    )
                )
            )
        );
    }

    public function get_per_page(): int
    {
        return max(
            1,
            absint(
                $this->get_context_value(
                    'per_page',
                    $this->get_config_value(
                        'per_page',
                        get_option('posts_per_page')
                    )
                )
            )
        );
    }

    public function get_total_items(): int
    {
        return max(
            0,
            absint(
                $this->get_context_value(
                    'total_items',
                    0
                )
            )
        );
    }

    public function get_total_pages(): int
    {
        if (isset($this->pagination['total_pages'])) {
            return absint(
                $this->pagination['total_pages']
            );
        }

        $per_page = $this->get_per_page();

        if ($per_page <= 0) {
            return 0;
        }

        return (int) ceil(
            $this->get_total_items() / $per_page
        );
    }

    public function has_next(): bool
    {
        return $this->get_current_page()
            < $this->get_total_pages();
    }

    public function has_previous(): bool
    {
        return $this->get_current_page() > 1;
    }

    /**
     * Get calculated pagination data.
     *
     * @return array<string, mixed>
     */
    public function get_pagination(): array
    {
        return $this->pagination;
    }

    /**
     * Get pagination data value.
     */
    public function get_pagination_value(
        string $key,
        mixed $default = null
    ): mixed {
        return $this->pagination[$key] ?? $default;
    }

    /**
     * Generate a URL for a page.
     */
    protected function get_page_url(int $page): string
    {
        $page = max(1, $page);

        return esc_url(
            add_query_arg(
                'paged',
                $page,
                $this->get_current_url()
            )
        );
    }

    /**
     * Get current URL.
     */
    protected function get_current_url(): string
    {
        $url = $this->get_context_value(
            'url',
            ''
        );

        if ($url !== '') {
            return esc_url_raw($url);
        }

        return esc_url_raw(
            get_pagenum_link(
                $this->get_current_page()
            )
        );
    }

    /**
     * Pagination links.
     */
    abstract public function pagination_links(): string;

    /**
     * Render.
     *
     * Concrete implementations provide their own markup.
     */
    public function render(array $data = []): void
    {
        echo $this->pagination_links();
    }

    /**
     * Reset.
     */
    public function reset(): static
    {
        $this->config = $this->default_config;
        $this->context = [];
        $this->pagination = [];

        return $this;
    }
}