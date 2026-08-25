<?php

namespace Ababilithub\FlexWordpress\Package\Repository\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Repository\V1\Contract\Repository as RepositoryContract;

abstract class Repository implements RepositoryContract
{
    protected array $config = [];

    protected array $default_config = [];

    public function init(array $config = []): static
    {
        $this->config = $this->get_default_config();

        if ($config) {
            $this->merge_config($config);
        }

        return $this;
    }

    public function get_config(): array
    {
        return $this->config;
    }

    public function set_config(
        array $config
    ): static {
        $this->config = $config;

        return $this;
    }

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

    public function set_config_value(
        string $key,
        mixed $value
    ): static {
        $this->config[$key] = $value;

        return $this;
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
}