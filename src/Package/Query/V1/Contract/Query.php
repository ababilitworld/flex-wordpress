<?php
namespace Ababilithub\FlexWordpress\Package\Query\V1\Contract;

use WP_Query;

interface Query
{
    public function init(array $data = []): static;
    public function query() : WP_Query;
}