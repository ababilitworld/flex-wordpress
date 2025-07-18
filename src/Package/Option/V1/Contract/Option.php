<?php 
namespace Ababilithub\FlexWordpress\Package\Option\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Option
{
    public function init(array $data = []): static;
    public function register(): void;
    public function render(): void;
     
}