<?php
namespace Ababilithub\FlexWordpress\Package\Taxonomy\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Taxonomy
{
    public function get_taxonomy_slug(): string;
    public function add_post_type(string $post_type): self;
    public function register_taxonomy(): void;
}