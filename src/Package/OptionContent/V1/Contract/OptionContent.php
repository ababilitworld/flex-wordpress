<?php 
namespace Ababilithub\FlexWordpress\Package\Option\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface OptionContent
{
    public function init(array $data = []): static;
    public function register(): void;
    public function render(): void;
    public function save(array $data = []): void;
     
}