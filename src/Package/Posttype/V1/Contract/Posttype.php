<?php 
namespace Ababilithub\FlexWordpress\Package\Posttype\V1\Contract;

interface Posttype
{
    public function get_slug(): string;
    public function register_post_type(): void;
    public function add_taxonomy(string $taxonomy_slug): void;
    public function add_post_meta(int $post_id, array $meta_data, bool $update_existing = true): array; 
}