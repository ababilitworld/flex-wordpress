<?php
namespace Ababilithub\FlexWordpress\Package\Taxonomy\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Taxonomy\Contract\Taxonomy as TaxonomyContract,
};

abstract class Taxonomy implements TaxonomyContract
{    
    protected $taxonomy;
    protected $taxonomy_slug;
    protected $post_types = [];
    protected $labels = [];
    protected $args = [];
    
    public function __construct()
    {
        $this->init();
    }

    abstract protected function init(): void;
    
    protected function init_hook(): void
    {
        add_action('init', [$this, 'register_taxonomy'], 0);
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

    public function get_taxonomy_slug(): string
    {
        return $this->taxonomy_slug;
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
        register_taxonomy($this->taxonomy_slug, $this->post_types, $this->args);
    }
}