<?php
namespace Ababilithub\FlexWordpress\Package\Query\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Query 
{
    //public function init(array $data = []): static;
    public function set_custom_args(array $args, string $option = 'merge'): static;
    public function get_args(): array;
    public function reset(): static;
    
}