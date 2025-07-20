<?php 
namespace Ababilithub\FlexWordpress\Package\OptionBoxContent\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface OptionBoxContent
{
    public function init(array $data = []): static;
    public function register(): void;
    public function render(): void;
    public function save(array $data = []): void;
     
}