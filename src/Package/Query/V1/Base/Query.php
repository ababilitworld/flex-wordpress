<?php

namespace Ababilithub\FlexWordpress\Package\Query\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Query\V1\Contract\Query as QueryContract,
    FlexWordpress\Package\Pagination\V1\Contract\Pagination as PaginationContract,
};

abstract class Query implements QueryContract
{
    protected array $config = [];

    protected array $default_config = [];

    protected ?\WP_Query $query = null;

    protected array $results = [];

    protected ?PaginationContract $pagination = null;

    protected int $current_page = 1;

    protected int $per_page = 10;

    public function init(array $data = []): static
    {
        if (isset($data['config']) && is_array($data['config'])) {
            $this->set_config($data['config']);
        }

        if (
            isset($data['pagination'])
            && $data['pagination'] instanceof PaginationContract
        ) {
            $this->set_pagination($data['pagination']);
        }

        return $this;
    }

    public function set_pagination(
        PaginationContract $pagination
    ): static {
        $this->pagination = $pagination;

        return $this;
    }

    public function paginate(
        PaginationContract $pagination
    ): static {
        return $this->set_pagination($pagination);
    }

    public function get_pagination(): ?PaginationContract
    {
        return $this->pagination;
    }

    public function get_current_page(): int
    {
        if ($this->pagination) {
            $page = (int) $this->pagination->get_config_value(
                'current_page',
                max(1, (int) get_query_var('paged', 1))
            );

            return max(1, $page);
        }

        return max(1, (int) get_query_var('paged', 1));
    }

    public function get_per_page(): int
    {
        if ($this->pagination) {
            return max(
                1,
                (int) $this->pagination->get_config_value(
                    'per_page',
                    $this->get_config_value('per_page', 10)
                )
            );
        }

        return max(1, (int) $this->get_config_value('per_page', 10));
    }

    public function prepare_args(): array
    {
        $this->current_page = $this->get_current_page();
        $this->per_page = $this->get_per_page();

        $args = [
            'posts_per_page' => $this->per_page,
            'paged' => $this->current_page,
        ];

        if ($this->pagination) {
            $this->pagination->prepare();
        }

        return $args;
    }

    public function execute(): static
    {
        $args = $this->prepare_args();

        $args = $this->prepare_query_args($args);

        $this->query = new \WP_Query($args);

        $this->results = $this->query->posts;

        if ($this->pagination) {
            $this->pagination
                ->set_query($this->query)
                ->paginate();
        }

        return $this;
    }

    /**
     * Concrete queries override this method to build their WP_Query args.
     */
    protected function prepare_query_args(array $args): array
    {
        return $args;
    }

    public function get_query(): ?\WP_Query
    {
        return $this->query;
    }

    public function get_results(): array
    {
        return $this->results;
    }

    public function get_total_items(): int
    {
        return $this->query
            ? (int) $this->query->found_posts
            : 0;
    }

    public function get_total_pages(): int
    {
        return $this->query
            ? (int) $this->query->max_num_pages
            : 0;
    }

    public function get_config(): array
    {
        return $this->config;
    }

    public function set_config(array $config): static
    {
        $this->config = array_replace_recursive(
            $this->default_config,
            $config
        );

        return $this;
    }

    public function get_default_config(): array
    {
        return $this->default_config;
    }

    public function set_default_config(array $config): static
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
}
