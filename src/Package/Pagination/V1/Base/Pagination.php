<?php

namespace Ababilithub\FlexWordpress\Package\Pagination\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Pagination\V1\Contract\Pagination as PaginationContract;

abstract class Pagination implements PaginationContract
{
    protected string $type = '';

    protected array $config = [];

    protected array $default_config = [];

    protected ?\WP_Query $query = null;

    public function init(array $data = []): static
    {
        if (isset($data['config']) && is_array($data['config'])) {
            $this->set_config($data['config']);
        }

        return $this;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function prepare(): static
    {
        return $this;
    }

    public function set_query(\WP_Query $query): static
    {
        $this->query = $query;

        return $this;
    }

    public function get_query(): ?\WP_Query
    {
        return $this->query;
    }

    public function paginate(): static
    {
        return $this;
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

    public function pagination_links(): string
    {
        return '';
    }

    public function render(array $data = []): void
    {
        echo $this->pagination_links();
    }

    public function html(): string
    {
        ob_start();
        $this->render();
        return (string) ob_get_clean();
    }
}
