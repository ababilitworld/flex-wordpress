<?php 
namespace Ababilithub\FlexWordpress\Package\OptionBox\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface OptionBox
{
    public function init(array $data = []): static;
    public function register(): void;
    public function render(): void;
    public function save(array $data = []):void;
     
}