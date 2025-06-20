<?php 
namespace Ababilithub\FlexWordpress\Package\Shortcode\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Shortcode
{
    public function register(): void;
    public function render(array $attributes): string;
     
}