<?php

namespace Ababilithub\FlexWordpress\Package\Query\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Pagination\V1\Contract\Pagination as PaginationContract;

interface Query
{
    public function init(array $data = []): static;

    public function set_pagination(PaginationContract $pagination): static;

    public function paginate(PaginationContract $pagination): static;

    public function get_pagination(): ?PaginationContract;

    public function prepare_args(): array;

    public function execute(): static;

    public function get_query(): ?\WP_Query;

    public function get_results(): array;

    public function get_current_page(): int;

    public function get_per_page(): int;

    public function get_total_items(): int;

    public function get_total_pages(): int;

    public function get_config(): array;

    public function set_config(array $config): static;

    public function get_default_config(): array;

    public function set_default_config(array $config): static;

    public function get_config_value(
        string $key,
        mixed $default = null
    ): mixed;

    public function set_config_value(
        string $key,
        mixed $value
    ): static;
}
