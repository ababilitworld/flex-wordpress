<?php

namespace Ababilithub\FlexWordpress\Package\DTO\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface DTO
{
    public function init(array $data = []): static;

    public function get(string $key, mixed $default = null): mixed;

    public function set(string $key, mixed $value): static;

    public function has(string $key): bool;

    public function all(): array;

    public function get_data(): array;

    public function set_data(array $data): static;

    public function merge_data(array $data): static;

    public function get_default_data(): array;

    public function set_default_data(array $data): static;

    public function reset_data(): static;

    public function to_array(): array;
}