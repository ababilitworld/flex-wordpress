<?php

namespace Ababilithub\FlexWordpress\Package\Repository\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Repository
{
    public function find(int $id): mixed;

    public function find_many(array $items = []): array;
}