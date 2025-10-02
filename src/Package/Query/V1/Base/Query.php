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
    
    public function __construct(array $data = [])
    {
        $this->setup_default_args();
        $this->init($data);
    }
    
    abstract public function init(array $data = []): static;
    
    /**
     * Setup default query arguments
     */
    abstract protected function setup_default_args(): void;
    
    /**
     * Set custom arguments
     */
    public function set_custom_args(array $args, string $option = 'merge'): static
    {
        if ($option === 'replace') 
        {
            $this->args = $args;
        } 
        elseif ($option === 'merge')
        {
            $this->custom_args = $args;
            $this->merge_args();
        }
        
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
     * Add meta query
     */
    public function add_meta_query(string $key, $value = null, string $compare = '=', string $type = 'CHAR'): static
    {
        if (!isset($this->args['meta_query'])) {
            $this->args['meta_query'] = [];
        }
        
        $meta_query = ['key' => $key];
        
        if (!is_null($value)) {
            $meta_query['value'] = $value;
        }
        
        if ($compare) {
            $meta_query['compare'] = $compare;
        }
        
        if ($type) {
            $meta_query['type'] = $type;
        }
        
        $this->args['meta_query'][] = $meta_query;
        return $this;
    }
    
    /**
     * Set meta query relation
     */
    public function set_meta_query_relation(string $relation): static
    {
        if (isset($this->args['meta_query'])) {
            $this->args['meta_query']['relation'] = in_array(strtoupper($relation), ['AND', 'OR']) ? $relation : 'AND';
        }
        return $this;
    }
    
    /**
     * Execute the query
     */
    abstract public function run();
    
    /**
     * Check if query has results
     */
    abstract public function has_results(): bool;
    
    /**
     * Get results count
     */
    abstract public function get_count(): int;
}