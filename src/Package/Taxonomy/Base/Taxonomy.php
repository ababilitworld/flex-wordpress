<?php
namespace Ababilithub\FlexWordpress\Package\Taxonomy\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Taxonomy\Contract\Taxonomy as TaxonomyContract,
};

abstract class Taxonomy implements TaxonomyContract
{    
    protected $taxonomy;
    protected $slug;
    protected $post_types = [];
    protected $labels = [];
    protected $args = [];
    protected array $terms = [];
    
    public function __construct()
    {
        $this->init();
    }

    abstract protected function init(): void;
    
    protected function init_hook(): void
    {
        add_action('init', [$this, 'register_taxonomy'], 21);
        add_action('init', [$this, 'process_terms'], 22);
    }

    protected function init_service(): void
    {
        // Can be overridden by child classes
    }

    protected function set_labels(array $labels): void
    {
        $this->labels = $labels;
    }

    protected function set_args(array $args): void
    {
        $this->args = $args;
    }

    protected function set_terms(array $terms): void
    {
        $this->terms = $terms;
    }

    public function get_slug(): string
    {
        return $this->slug;
    }

    public function add_post_type(string $post_type): self
    {
        if (!in_array($post_type, $this->post_types, true)) 
        {
            $this->post_types[] = $post_type;

            if (taxonomy_exists($this->taxonomy)) 
            {
                $object_taxonomies = get_object_taxonomies($post_type, 'names');
                if (!in_array($this->taxonomy, $object_taxonomies, true)) 
                {
                    register_taxonomy_for_object_type($this->taxonomy, $post_type);
                }
            }
        }

        return $this;
    }

    public function register_taxonomy(): void
    {
        register_taxonomy($this->slug, $this->post_types, $this->args);
    }

    public function process_terms(): void
    {
        if (empty($this->terms)) return;
        
        foreach ($this->terms as $term) 
        {
            $this->upsert_term($term);
        }
    }

    protected function upsert_term(array $data): void
    {
        $term = term_exists($data['slug'], $this->slug);
        
        if (!$term) 
        {
            $term = wp_insert_term($data['name'], $this->slug, [
                'slug' => $data['slug'],
                'description' => $data['description'] ?? ''
            ]);
        }

        if (!is_wp_error($term) && isset($data['meta'])) 
        {
            foreach ($data['meta'] as $key => $value) 
            {
                update_term_meta($term['term_id'], $key, $value);
            }
        }
    }

    protected function generate_term_data(
        string $slug,
        string $name,
        string $description = '',
        array $meta = []
    ): array 
    {
        return [
            'slug' => $slug,
            'name' => $name,
            'description' => $description,
            'meta' => $meta
        ];
    }
}