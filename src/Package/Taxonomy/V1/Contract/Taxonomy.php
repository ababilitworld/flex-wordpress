<?php
namespace Ababilithub\FlexWordpress\Package\Taxonomy\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Taxonomy
{
    public function get_slug(): string;
    public function add_post_type(string $post_type): self;
    public function register_taxonomy(): void;
}