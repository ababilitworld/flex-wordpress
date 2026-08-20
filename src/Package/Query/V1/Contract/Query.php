<?php
namespace Ababilithub\FlexWordpress\Package\Query\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Pagination\V1\Contract\Pagination as PaginationContract
};

interface Query
{
    public function get_type(): string;

    public function init(array $data = []): static;

    public function get_config(): array;

    public function set_config(array $config = []): static;

    public function get_default_config(): array;

    public function set_default_config(array $config = []): static;

    public function get_config_value(
        string $key,
        mixed $default = null
    ): mixed;

    public function set_config_value(
        string $key,
        mixed $value
    ): static;

    public function get_args(): array;

    public function set_args(array $args = []): static;

    public function get_arg(
        string $key,
        mixed $default = null
    ): mixed;

    public function set_arg(
        string $key,
        mixed $value
    ): static;

    public function paginate(
        PaginationContract $pagination
    ): static;

    public function get_pagination(): ?PaginationContract;

    public function execute(): static;

    public function get_results(): array;

    public function has_results(): bool;

    public function get_found_items(): int;

    public function get_max_num_pages(): int;

    public function reset(): static;
}