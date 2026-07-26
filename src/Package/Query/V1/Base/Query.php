<?php
namespace Ababilithub\FlexWordpress\Package\Query\V1\Base;

use Ababilithub\{
    FlexWordpress\Package\Query\V1\Contract\Query as QueryContract
};
use WP_Query;

abstract class Query implements QueryContract
{
    /**
     * Current resolved query configuration.
     *
     * @var array<string, mixed>
     */
    protected array $config = [];

    /**
     * Default query configuration.
     *
     * @var array<string, mixed>
     */
    protected array $default_config = [];

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data = [])
    {
        $this->init($data);
    }

    /**
     * Initialize the query instance.
     *
     * @param array<string, mixed> $data
     */
    abstract public function init(array $data = []): static;

    /**
     * Execute the post-type query.
     *
     * @param array<string, mixed> $data
     */
    abstract public function query(array $data = []): WP_Query;

    /**
     * Set and merge the current configuration.
     *
     * @param array<string, mixed> $config
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
     * Merge values into the existing resolved configuration.
     *
     * @param array<string, mixed> $config
     */
    public function merge_config(array $config = []): static
    {
        $this->config = array_replace_recursive(
            $this->config,
            $config
        );

        return $this;
    }

    /**
     * Get the resolved query configuration.
     *
     * @return array<string, mixed>
     */
    public function get_config(): array
    {
        return $this->config;
    }

    /**
     * Set the default query configuration.
     *
     * @param array<string, mixed> $default_config
     */
    public function set_default_config(array $default_config = []): static
    {
        $this->default_config = $default_config;

        return $this;
    }

    /**
     * Get the default query configuration.
     *
     * @return array<string, mixed>
     */
    public function get_default_config(): array
    {
        return $this->default_config;
    }

    /**
     * Get a configuration value using dot notation.
     *
     * Examples:
     *
     * get_config_value('pagination.per_page', 10)
     * get_config_value('post.status', 'publish')
     */
    protected function get_config_value(
        string $key,
        mixed $default = null
    ): mixed {
        if ($key === '') {
            return $default;
        }

        $value = $this->config;

        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }
}