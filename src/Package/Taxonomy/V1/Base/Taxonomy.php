<?php
namespace Ababilithub\FlexWordpress\Package\Taxonomy\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Taxonomy\V1\Contract\Taxonomy as TaxonomyContract,
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

    abstract public function init(): void;
    public function register(): void
    {
        register_taxonomy($this->slug, $this->post_types, $this->args);
    }

    protected function set_post_types(array $post_types): void
    {
        $this->post_types = $post_types;
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

    public function process_terms(): void
    {
        if (empty($this->terms)) return;
        
        foreach ($this->terms as $data) 
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

    /**
     * Add "View Metas" action link to term list rows
     */
    public function row_action_view_details(array $actions, \WP_Term $term): array
    {
        // Check if this is our taxonomy
        if ($term->taxonomy !== $this->taxonomy) 
        {
            return $actions;
        }

        // Always show the link (remove meta check for now)
        $actions['view_details'] = sprintf(
            '<a href="%s" aria-label="%s">%s</a>',
            esc_url(admin_url(sprintf(
                'admin.php?page=flex-supervisor-audit-term&object_id=%d&action_id=view_details',
                $term->term_id
            ))),
            esc_attr(sprintf(__('View meta for "%s"', 'flex-eland'), $term->name)),
            esc_html__('View Details', 'flex-eland')
        );

        return $actions;
    }
}
