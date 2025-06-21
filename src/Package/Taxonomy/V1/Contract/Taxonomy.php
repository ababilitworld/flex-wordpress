<?php
namespace Ababilithub\FlexWordpress\Package\Taxonomy\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Taxonomy
{
    public function register(): void;
}