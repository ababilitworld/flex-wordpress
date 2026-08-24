<?php

namespace Ababilithub\FlexWordpress\Package\DTO\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface DTO
{
    public function init(array $data = [], array $config = []): static;

    /*
     * Data
     */
    public function get_data(): array;

    public function set_data(array $data): static;

    public function get(string $key, mixed $default = null): mixed;

    public function set(string $key, mixed $value): static;

    public function has(string $key): bool;

    public function all(): array;

    public function merge_data(array $data): static;

    /*
     * Default Data
     */
    public function get_default_data(): array;

    public function set_default_data(array $data): static;

    public function reset_data(): static;

    /*
     * Config
     */
    public function get_config(): array;

    public function set_config(array $config): static;

    public function config(string $key, mixed $default = null): mixed;

    public function set_config_value(
        string $key,
        mixed $value
    ): static;

    public function has_config(string $key): bool;

    public function merge_config(array $config): static;

    /*
     * Default Config
     */
    public function get_default_config(): array;

    public function set_default_config(array $config): static;

    public function reset_config(): static;

    /*
     * Conversion
     */
    public function to_array(): array;
}