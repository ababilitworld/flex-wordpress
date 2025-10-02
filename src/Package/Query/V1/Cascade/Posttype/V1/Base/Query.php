<?php
namespace Ababilithub\FlexWordpress\Package\Query\V1\Cascade\Posttype\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Query\V1\Base\Query as BaseQuery,
    FlexWordpress\Package\Query\V1\Cascade\Posttype\V1\Contract\Query as QueryPosttypeContract
};

abstract class Query extends BaseQuery implements QueryPosttypeContract
{
    protected $pagination = false;
    protected $wp_query = null;
    
    /**
     * Setup default query arguments for post types
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
     * Add taxonomy query
     */
    public function add_tax_query($taxonomy, $terms, $field = 'term_id', $operator = 'IN', $include_children = true): static
    {
        if (!isset($this->args['tax_query'])) {
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
     * Set tax query relation
     */
    public function set_tax_query_relation($relation): static
    {
        if (isset($this->args['tax_query'])) {
            $this->args['tax_query']['relation'] = in_array(strtoupper($relation), ['AND', 'OR']) ? $relation : 'AND';
        }
        return $this;
    }

    /**
     * Get page number
     */
    public function get_page_number(): int
    {
        if (get_query_var('paged')) {
            $paged = get_query_var('paged');
        } elseif (get_query_var('page')) {
            $paged = get_query_var('page');
        } else {
            $paged = 1;
        }

        return $paged;
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
    public function set_pagination($paged = null): static
    {
        $this->pagination = true;
        $this->args['paged'] = max(1, $paged ?? (int) $this->get_page_number());        
        return $this;
    }
    
    /**
     * Implement pagination
     */
    public function implement_pagination(): static
    {
        $this->pagination = true;
        $this->args['nopaging'] = false;
        
        if (!isset($this->args['posts_per_page'])) {
            $this->args['posts_per_page'] = -1;
        }

        if (!isset($this->args['paged'])) {
            $this->args['paged'] = 1;
        }
        
        return $this;
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
        
        $this->wp_query = new \WP_Query($this->args);
        return $this->wp_query;
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
    public function get_count(): int
    {
        $query = $this->run();
        return $query->found_posts;
    }
    
    /**
     * Check if query has posts
     */
    public function has_results(): bool
    {
        $query = $this->run();
        return $query->have_posts();
    }
    
    /**
     * Get WP_Query object
     */
    public function get_wp_query(): ?\WP_Query
    {
        return $this->wp_query;
    }
    
    /**
     * Get posts with meta data
     */
    public function get_posts_with_meta(): array
    {
        $posts = $this->get_results();
        
        if (empty($posts)) {
            return [];
        }
        
        $posts_with_meta = [];
        
        foreach ($posts as $post) {
            if (is_object($post)) {
                $post->meta = get_post_meta($post->ID);
                $posts_with_meta[] = $post;
            }
        }
        
        return $posts_with_meta;
    }
    
    /**
     * Get pagination data
     */
    public function get_pagination_data(): array
    {
        if (!$this->wp_query) {
            $this->run();
        }
        
        return [
            'current_page' => $this->args['paged'] ?? 1,
            'posts_per_page' => $this->args['posts_per_page'] ?? -1,
            'found_posts' => $this->wp_query->found_posts,
            'max_num_pages' => $this->wp_query->max_num_pages,
            'has_next_page' => $this->wp_query->max_num_pages > ($this->args['paged'] ?? 1),
            'has_previous_page' => ($this->args['paged'] ?? 1) > 1,
        ];
    }
}