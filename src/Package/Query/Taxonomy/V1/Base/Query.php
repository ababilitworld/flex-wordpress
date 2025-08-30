<?php
namespace Ababilithub\FlexWordpress\Package\Query\Taxonomy\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Query\Taxonomy\V1\Contract\Query as QueryContract
};

abstract class Query implements QueryContract
{
    protected $args = [];
    protected $default_args = [];
    protected $custom_args = [];
    
    public function __construct(array $data = [])
    {
        $this->setup_default_args();
        $this->init($data);
    }
    
    abstract public function init(array $data = []): static;
    
    /**
     * Setup default query arguments for taxonomy terms
     */
    protected function setup_default_args(): void
    {
        $this->default_args = [
            'taxonomy' => 'category',
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC',
            'number' => 0, // 0 means no limit
            'offset' => 0,
            'fields' => 'all',
            'name' => '',
            'slug' => '',
            'hierarchical' => true,
            'search' => '',
            'name__like' => '',
            'description__like' => '',
            'pad_counts' => false,
            'get' => '',
            'child_of' => 0,
            'parent' => 0,
            'childless' => false,
            'cache_domain' => 'core',
            'update_term_meta_cache' => true,
            'meta_query' => [],
        ];
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
     * Set taxonomy name
     */
    public function set_taxonomy(string $taxonomy): static
    {
        $this->args['taxonomy'] = $taxonomy;
        return $this;
    }
    
    /**
     * Set whether to hide empty terms
     */
    public function set_hide_empty(bool $hide_empty): static
    {
        $this->args['hide_empty'] = $hide_empty;
        return $this;
    }
    
    /**
     * Set order parameters
     */
    public function set_order_by(string $orderby, string $order = 'ASC'): static
    {
        $this->args['orderby'] = $orderby;
        $this->args['order'] = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        return $this;
    }
    
    /**
     * Set number of terms to return
     */
    public function set_number(int $number): static
    {
        $this->args['number'] = max(0, $number);
        return $this;
    }
    
    /**
     * Set offset for pagination
     */
    public function set_offset(int $offset): static
    {
        $this->args['offset'] = max(0, $offset);
        return $this;
    }
    
    /**
     * Set fields to return
     */
    public function set_fields(string $fields): static
    {
        $valid_fields = ['all', 'ids', 'tt_ids', 'names', 'slugs', 'count', 'id=>parent', 'id=>name', 'id=>slug'];
        $this->args['fields'] = in_array($fields, $valid_fields) ? $fields : 'all';
        return $this;
    }
    
    /**
     * Filter by term name
     */
    public function set_name(string $name): static
    {
        $this->args['name'] = $name;
        return $this;
    }
    
    /**
     * Filter by term slug
     */
    public function set_slug($slug): static
    {
        $this->args['slug'] = is_array($slug) ? $slug : [$slug];
        return $this;
    }
    
    /**
     * Set hierarchical retrieval
     */
    public function set_hierarchical(bool $hierarchical): static
    {
        $this->args['hierarchical'] = $hierarchical;
        return $this;
    }
    
    /**
     * Set search term
     */
    public function set_search(string $search): static
    {
        $this->args['search'] = $search;
        return $this;
    }
    
    /**
     * Set name like search
     */
    public function set_name_like(string $name_like): static
    {
        $this->args['name__like'] = $name_like;
        return $this;
    }
    
    /**
     * Set description like search
     */
    public function set_description_like(string $description_like): static
    {
        $this->args['description__like'] = $description_like;
        return $this;
    }
    
    /**
     * Set whether to pad counts
     */
    public function set_pad_counts(bool $pad_counts): static
    {
        $this->args['pad_counts'] = $pad_counts;
        return $this;
    }
    
    /**
     * Set child of parameter
     */
    public function set_child_of(int $child_of): static
    {
        $this->args['child_of'] = max(0, $child_of);
        return $this;
    }
    
    /**
     * Set parent parameter
     */
    public function set_parent(int $parent): static
    {
        $this->args['parent'] = $parent;
        return $this;
    }
    
    /**
     * Set childless parameter
     */
    public function set_childless(bool $childless): static
    {
        $this->args['childless'] = $childless;
        return $this;
    }
    
    /**
     * Add meta query for term meta
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
     * Include specific term IDs
     */
    public function include_term_ids($term_ids): static
    {
        if (!empty($term_ids)) {
            $this->args['include'] = is_array($term_ids) ? $term_ids : [$term_ids];
        }
        return $this;
    }
    
    /**
     * Exclude specific term IDs
     */
    public function exclude_term_ids($term_ids): static
    {
        if (!empty($term_ids)) {
            $this->args['exclude'] = is_array($term_ids) ? $term_ids : [$term_ids];
        }
        return $this;
    }
    
    /**
     * Exclude tree (parent and children)
     */
    public function exclude_tree($term_ids): static
    {
        if (!empty($term_ids)) {
            $this->args['exclude_tree'] = is_array($term_ids) ? $term_ids : [$term_ids];
        }
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
        return $this;
    }
    
    /**
     * Execute the query and get terms
     */
    public function get_terms(): array
    {
        // Apply relation parameters if we have multiple meta queries
        if (isset($this->args['meta_query']) && count($this->args['meta_query']) > 1 && !isset($this->args['meta_query']['relation'])) {
            $this->args['meta_query']['relation'] = 'AND';
        }
        
        $terms = get_terms($this->args);
        
        if (is_wp_error($terms)) {
            return [];
        }
        
        return $terms;
    }
    
    /**
     * Get term count
     */
    public function get_count(): int
    {
        $args = $this->args;
        $args['fields'] = 'count';
        
        $count = get_terms($args);
        
        return is_wp_error($count) ? 0 : (int) $count;
    }
    
    /**
     * Check if terms exist
     */
    public function has_terms(): bool
    {
        return $this->get_count() > 0;
    }
    
    /**
     * Get terms with their meta data
     */
    public function get_terms_with_meta(): array
    {
        $terms = $this->get_terms();
        
        if (empty($terms)) {
            return [];
        }
        
        $terms_with_meta = [];
        
        foreach ($terms as $term) {
            if (is_object($term)) {
                $term->meta = get_term_meta($term->term_id);
                $terms_with_meta[] = $term;
            }
        }
        
        return $terms_with_meta;
    }
    
    /**
     * Get terms as ID => Name array
     */
    public function get_terms_as_id_name(): array
    {
        $args = $this->args;
        $args['fields'] = 'id=>name';
        
        $terms = get_terms($args);
        
        return is_wp_error($terms) ? [] : $terms;
    }
    
    /**
     * Get terms as ID => Slug array
     */
    public function get_terms_as_id_slug(): array
    {
        $args = $this->args;
        $args['fields'] = 'id=>slug';
        
        $terms = get_terms($args);
        
        return is_wp_error($terms) ? [] : $terms;
    }
    
    /**
     * Get hierarchical terms tree
     */
    public function get_hierarchical_terms(): array
    {
        $args = $this->args;
        $args['hierarchical'] = true;
        
        $terms = get_terms($args);
        
        if (is_wp_error($terms) || empty($terms)) {
            return [];
        }
        
        return $this->build_term_tree($terms);
    }
    
    /**
     * Build hierarchical term tree
     */
    protected function build_term_tree(array $terms, int $parent_id = 0): array
    {
        $tree = [];
        
        foreach ($terms as $term) {
            if ($term->parent === $parent_id) {
                $term->children = $this->build_term_tree($terms, $term->term_id);
                $tree[] = $term;
            }
        }
        
        return $tree;
    }
}