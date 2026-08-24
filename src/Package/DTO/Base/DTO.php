<?php

namespace Ababilithub\FlexWordpress\Package\DTO\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\DTO\V1\Contract\DTO as DTOContract;

abstract class DTO implements DTOContract
{
    /**
     * Runtime data.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Default data/schema.
     *
     * @var array<string, mixed>
     */
    protected array $default_data = [];

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
     * Initialize DTO.
     */
    public function init( array $data = [], array $config = [] ): static 
    {

        $this->data = $this->get_default_data();

        $this->config = $this->get_default_config();

        if ($data) 
        {
            $this->merge_data($data);
        }

        if ($config) 
        {
            $this->merge_config($config);
        }

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

    public function get_data(): array
    {
        return $this->data;
    }

    public function set_data(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function get(
        string $key,
        mixed $default = null
    ): mixed {

        return array_key_exists($key, $this->data)
            ? $this->data[$key]
            : $default;
    }

    public function set(
        string $key,
        mixed $value
    ): static {

        $this->data[$key] = $value;

        return $this;
    }

    public function has(string $key): bool
    {
        return array_key_exists(
            $key,
            $this->data
        );
    }

    public function all(): array
    {
        return $this->data;
    }

    public function merge_data(array $data): static
    {
        $this->data = array_replace(
            $this->data,
            $data
        );

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Default Data
    |--------------------------------------------------------------------------
    */

    public function get_default_data(): array
    {
        return $this->default_data;
    }

    public function set_default_data(array $data): static
    {
        $this->default_data = $data;

        return $this;
    }

    public function reset_data(): static
    {
        $this->data = $this->get_default_data();

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Config
    |--------------------------------------------------------------------------
    */

    public function get_config(): array
    {
        return $this->config;
    }

    public function set_config(array $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function config(
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

    public function set_config_value(
        string $key,
        mixed $value
    ): static {

        $this->config[$key] = $value;

        return $this;
    }

    public function has_config(string $key): bool
    {
        return array_key_exists(
            $key,
            $this->config
        );
    }

    public function merge_config(
        array $config
    ): static {

        $this->config = array_replace(
            $this->config,
            $config
        );

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Default Config
    |--------------------------------------------------------------------------
    */

    public function get_default_config(): array
    {
        return $this->default_config;
    }

    public function set_default_config(
        array $config
    ): static {

        $this->default_config = $config;

        return $this;
    }

    public function reset_config(): static
    {
        $this->config = $this->get_default_config();

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Conversion
    |--------------------------------------------------------------------------
    */

    public function to_array(): array
    {
        return $this->data;
    }
}