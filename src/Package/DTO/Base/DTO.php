<?php

namespace Ababilithub\FlexWordpress\Package\DTO\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\DTO\V1\Contract\DTO as DTOContract
};

abstract class DTO implements DTOContract
{
    protected array $data = [];

    protected array $default_data = [];

    public function init(array $data = []): static
    {
        $this->data = $this->get_default_data();

        if ($data) {
            $this->merge_data($data);
        }

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

    public function get_data(): array
    {
        return $this->data;
    }

    public function set_data(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function merge_data(array $data): static
    {
        $this->data = array_replace(
            $this->data,
            $data
        );

        return $this;
    }

    public function get_default_data(): array
    {
        return $this->default_data;
    }

    public function set_default_data(
        array $data
    ): static {
        $this->default_data = $data;

        return $this;
    }

    public function reset_data(): static
    {
        $this->data = $this->get_default_data();

        return $this;
    }

    public function to_array(): array
    {
        return $this->data;
    }
}