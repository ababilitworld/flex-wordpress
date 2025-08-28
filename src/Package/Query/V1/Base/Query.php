<?php
namespace Ababilithub\FlexWordpress\Package\Query\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Query\V1\Contract\Query as QueryContract
};

abstract class Query implements QueryContract
{
    protected $args = [];
    protected $default_args = [];
    protected $custom_args = [];
    protected $pagination = false;

    public $posts_per_page = -1;
    public $paged = 1;
    
    public function __construct(array $data = [])
    {
        $this->setup_default_args();
        $this->init($data);
    }
    
    abstract public function init(array $data = []): static;
    
    /**
     * Setup default query arguments
     */
    protected function setup_default_args(): void
    {
        $this->default_args = [
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'paged' => 1,
            'ignore_sticky_posts' => true,
            'orderby' => 'date',
            'order' => 'DESC',
        ];
        
        //$this->args = $this->default_args;
    }
    
    /**
     * Set custom arguments
     */
    public function set_custom_args(array $args): static
    {
        $this->custom_args = $args;
        $this->merge_args();
        return $this;
    }
    
    /**
     * Merge default, custom, and instance arguments
     */
    protected function merge_args(): void
    {
        $this->args = array_merge(
            $this->default_args,
            $this->custom_args,
            $this->get_instance_args()
        );
    }
    
    /**
     * Get instance-specific arguments
     */
    protected function get_instance_args(): array
    {
        return [];
    }
    
    /**
     * Set post type(s)
     */
    public function set_post_type($post_type): static
    {
        $this->args['post_type'] = $post_type;
        return $this;
    }
    
    /**
     * Set post status
     */
    public function set_post_status($status): static
    {
        $this->args['post_status'] = $status;
        return $this;
    }
    
    /**
     * Set order parameters
     */
    public function set_order_by($order_by, $order = 'DESC'): static
    {
        $this->args['orderby'] = $order_by;
        $this->args['order'] = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
        return $this;
    }
    
    /**
     * Set post IDs to include
     */
    public function set_post_ids($ids): static
    {
        if (!empty($ids)) {
            $this->args['post__in'] = is_array($ids) ? $ids : [$ids];
        }
        return $this;
    }
    
    /**
     * Set post IDs to exclude
     */
    public function set_exclude_post_ids($ids): static
    {
        if (!empty($ids)) {
            $this->args['post__not_in'] = is_array($ids) ? $ids : [$ids];
        }
        return $this;
    }
    
    /**
     * Add meta query
     */
    public function add_meta_query($key, $value = null, $compare = '=', $type = 'CHAR'): static
    {
        if (!isset($this->args['meta_query'])) 
        {
            $this->args['meta_query'] = [];
        }
        
        $meta_query = ['key' => $key];
        
        if (!is_null($value)) 
        {
            $meta_query['value'] = $value;
        }
        
        if ($compare) 
        {
            $meta_query['compare'] = $compare;
        }
        
        if ($type)
        {
            $meta_query['type'] = $type;
        }
        
        $this->args['meta_query'][] = $meta_query;
        return $this;
    }
    
    /**
     * Add taxonomy query
     */
    public function add_tax_query($taxonomy, $terms, $field = 'term_id', $operator = 'IN', $include_children = true): static
    {
        if (!isset($this->args['tax_query'])) 
        {
            $this->args['tax_query'] = [];
        }
        
        $this->args['tax_query'][] = [
            'taxonomy' => $taxonomy,
            'terms' => is_array($terms) ? $terms : [$terms],
            'field' => $field,
            'operator' => $operator,
            'include_children' => $include_children,
        ];
        
        return $this;
    }
    
    /**
     * Set meta query relation
     */
    public function set_meta_query_relation($relation): static
    {
        if (isset($this->args['meta_query'])) 
        {
            $this->args['meta_query']['relation'] = in_array(strtoupper($relation), ['AND', 'OR']) ? $relation : 'AND';
        }
        return $this;
    }
    
    /**
     * Set tax query relation
     */
    public function set_tax_query_relation($relation): static
    {
        if (isset($this->args['tax_query'])) 
        {
            $this->args['tax_query']['relation'] = in_array(strtoupper($relation), ['AND', 'OR']) ? $relation : 'AND';
        }
        return $this;
    }

    /**
     * Set posts per page
     */
    public function set_posts_per_page($number): static
    {
        $this->args['posts_per_page'] = (int) $number;
        return $this;
    }
    
    /**
     * Set pagination
     */
    public function set_pagination($paged = 1): static
    {
        $this->pagination = true;
        $this->args['paged'] = max(1, (int) $paged);
        return $this;
    }
    
    /**
     * Implement pagination
     */
    public function implement_pagination(): void
    {
        $this->pagination = true;
        $this->args['nopaging'] = false;
        if(!isset($this->args['posts_per_page']))
        {
            $this->args['posts_per_page'] = -1;
        }            
        
        if (get_query_var('paged')) 
        {
            $paged = get_query_var('paged');
        } 
        elseif (get_query_var('page')) 
        {
            $paged = get_query_var('page');
        } 
        else
        {
            $paged = 1;
        }

        if(!isset($this->args['paged']))
        {
            $this->args['paged'] = max(1, $paged);
        }
        
    }
    
    /**
     * Disable pagination
     */
    public function disable_pagination(): static
    {
        $this->pagination = false;
        $this->args['nopaging'] = true;
        $this->args['posts_per_page'] = -1;
        unset($this->args['paged']);
        return $this;
    }
    
    /**
     * Get current arguments
     */
    public function get_args(): array
    {
        return $this->args;
    }
    
    /**
     * Reset to default arguments
     */
    public function reset(): static
    {
        $this->args = $this->default_args;
        $this->custom_args = [];
        $this->pagination = false;
        return $this;
    }
    
    /**
     * Execute the query
     */
    public function run(): \WP_Query
    {
        // Apply relation parameters if we have multiple queries
        if (isset($this->args['meta_query']) && count($this->args['meta_query']) > 1 && !isset($this->args['meta_query']['relation'])) {
            $this->args['meta_query']['relation'] = 'AND';
        }
        
        if (isset($this->args['tax_query']) && count($this->args['tax_query']) > 1 && !isset($this->args['tax_query']['relation'])) {
            $this->args['tax_query']['relation'] = 'AND';
        }
        
        return new \WP_Query($this->args);
    }
    
    /**
     * Get results as array
     */
    public function get_results(): array
    {
        $query = $this->run();
        return $query->posts;
    }
    
    /**
     * Get found posts count
     */
    public function get_found_posts(): int
    {
        $query = $this->run();
        return $query->found_posts;
    }
    
    /**
     * Check if query has posts
     */
    public function has_posts(): bool
    {
        $query = $this->run();
        return $query->have_posts();
    }
}