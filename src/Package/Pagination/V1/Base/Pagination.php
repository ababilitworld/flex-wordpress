<?php

namespace Ababilithub\FlexWordpress\Package\Pagination\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Pagination\V1\Contract\Pagination as PaginationContract,
    FlexWordpress\Package\Query\V1\Contract\Query as QueryContract
};

abstract class Pagination implements PaginationContract
{
    /**
     * Pagination type.
     *
     * @var string
     */
    protected string $type = '';

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
     * Query instance.
     *
     * @var QueryContract|null
     */
    protected ?QueryContract $query = null;

    /**
     * Initialize pagination.
     *
     * @param array $data
     *
     * @return static
     */
    public function init(array $data = []): static
    {
        if (isset($data['config']) && is_array($data['config'])) {
            $this->set_config(
                $data['config']
            );
        }

        return $this;
    }

    /**
     * Get pagination type.
     *
     * @return string
     */
    public function get_type(): string
    {
        return $this->type;
    }

    /**
     * Get configuration.
     *
     * @return array<string, mixed>
     */
    public function get_config(): array
    {
        return $this->config;
    }

    /**
     * Set configuration.
     *
     * Configuration is merged over defaults.
     *
     * @param array<string, mixed> $config
     *
     * @return static
     */
    public function set_config(array $config): static
    {
        $this->config = array_replace_recursive(
            $this->get_default_config(),
            $config
        );

        return $this;
    }

    /**
     * Get default configuration.
     *
     * @return array<string, mixed>
     */
    public function get_default_config(): array
    {
        return $this->default_config;
    }

    /**
     * Set default configuration.
     *
     * @param array<string, mixed> $config
     *
     * @return static
     */
    public function set_default_config(array $config): static
    {
        $this->default_config = $config;

        return $this;
    }

    /**
     * Set query.
     *
     * This is called by the Query abstraction when pagination
     * is attached to the query.
     *
     * @param QueryContract $query
     *
     * @return static
     */
    public function set_query(QueryContract $query): static
    {
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
     * Generate pagination.
     *
     * Concrete pagination classes may override this.
     *
     * @return mixed
     */
    public function paginate()
    {
        return null;
    }

    /**
     * Generate pagination links.
     *
     * @return string
     */
    public function pagination_links(): string
    {
        return '';
    }

    /**
     * Render pagination.
     *
     * @param array $data
     *
     * @return void
     */
    public function render(array $data = []): void
    {
        echo $this->html();
    }

    /**
     * Generate pagination HTML.
     *
     * @return string
     */
    public function html(): string
    {
        ob_start();

        $this->render();

        return (string) ob_get_clean();
    }

    /**
     * Get a configuration value.
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
        return $this->config[$key] ?? $default;
    }

    /**
     * Set a configuration value.
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

}