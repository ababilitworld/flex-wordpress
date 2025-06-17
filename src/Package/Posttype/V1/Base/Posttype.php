<?php
namespace Ababilithub\FlexWordpress\Package\Posttype\V1\Base;

use finfo;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Utility\ArrayUtility\Utility as ArrayUtility,
    FlexWordpress\Package\Posttype\V1\Contract\Posttype as PosttypeContract,
};

use WP_Error;
abstract class Posttype implements PosttypeContract
{    
    protected $posttype;
    protected $slug;
    protected $taxonomies = [];
    protected $available_theme_supports = [];
    protected $theme_supports = [];
    protected $available_posttype_supports = [];
    protected $posttype_supports = [];
    protected $labels = [];
    protected $args = [];
    protected $metas = [];
    protected $use_block_editor = true;  
    
    public function __construct()
    {
        $this->init();
    }

    abstract protected function init(): void;
    
    protected function init_hook(): void
    {
        
        // add_action('init', [$this, 'register_taxonomies'], 31);
        // add_action('init', [$this, 'register_supports'], 32);
        // add_action('init', [$this, 'register_metas'], 34);
        
    }

    protected function init_service(): void
    {
        // Can be overridden by child classes
    }

    protected function available_posttype_supports():array
    {
        return $this->available_posttype_supports = array(
            
            // Basic content
            'title',
            'editor',
            'excerpt',
            'thumbnail',
            
            // Post functionality
            'comments',
            'trackbacks',
            'revisions',
            'custom-fields',
            
            // Layout
            'page-attributes',
            'post-formats',
            
            // Editor enhancements
            'author',
            'wp-block-styles',
            'align-wide',
            'responsive-embeds',

            //Template Features
            'template',
            'template-lock',

            //Experimental Features
            'custom-line-height',
            'experimental-border',
            'experimental-duotone',
            'experimental-font-size',
            'experimental-link-color'    
        );
    }

    protected function set_posttype_supports(array $posttype_supports): void
    {
        $this->posttype_supports = $posttype_supports;
    }

    protected function set_taxonomies(array $taxonomies): void
    {
        $this->taxonomies = $taxonomies;
    }

    protected function set_labels(array $labels): void
    {
        $this->labels = $labels;
    }

    protected function set_args(array $args): void
    {
        $this->args = $args;
    }

    protected function set_metas(array $metas): void
    {
        $this->metas = $metas;
    }

    public function get_slug(): string
    {
        return $this->slug;
    }

    public function register_post_type(): void
    {
        register_post_type($this->slug, $this->args);
    }

    public function add_taxonomy(string $taxonomy_slug): void
    {
        if (!in_array($taxonomy_slug, $this->taxonomies, true)) 
        {
            $this->taxonomies[] = $taxonomy_slug;

            if (post_type_exists($this->slug) && taxonomy_exists($taxonomy_slug)) 
            {
                $object_taxonomies = get_object_taxonomies($this->slug, 'names');
                if (!in_array($taxonomy_slug, $object_taxonomies, true)) 
                {
                    register_taxonomy_for_object_type($taxonomy_slug, $this->slug);
                }
            }
        }
    }

    public function process_metas(): void
    {
        if (empty($this->metas)) return;
        
        foreach ($this->metas as $meta) 
        {
            $this->register_meta($meta);
        }
    }

    protected function generate_meta_definition(array $meta): array 
    {
        return [
            'key' => $meta['key'],
            'type' => $meta['type'] ?? 'string',
            'description' => $meta['description'] ?? '',
            'single' => $meta['single'] ?? true,
            'show_in_rest' => $meta['show_in_rest'] ?? true,
            'sanitize_callback' => $meta['sanitize_callback'] ?? null,
            'auth_callback' => $meta['auth_callback'] ?? null,
        ];
    }

    public function add_meta(array $meta): void
    {
        if (!ArrayUtility::search_array($meta, $this->metas, 'key')) 
        {
            $this->metas[] = $meta;
        }
    }

    public function register_meta(array $meta): void
    {
        register_post_meta(
            $this->slug, 
            $meta['key'], 
            [
                'type' => $meta['type'] ?? 'string',
                'description' => $meta['description'] ?? '',
                'single' => $meta['single'] ?? true,
                'show_in_rest' => $meta['show_in_rest'] ?? true,
                'sanitize_callback' => $meta['sanitize_callback'] ?? null,
                'auth_callback' => $meta['auth_callback'] ?? null,
            ]
        );
    }

    public function register_metas(): void
    {
        foreach ($this->metas as $meta) 
        {
            register_post_meta(
                $this->slug,
                $meta['key'],
                [
                    'type' => $meta['type'] ?? 'string',
                    'description' => $meta['description'] ?? '',
                    'single' => $meta['single'] ?? true,
                    'show_in_rest' => $meta['show_in_rest'] ?? true,
                    'sanitize_callback' => $meta['sanitize_callback'] ?? null,
                    'auth_callback' => $meta['auth_callback'] ?? null,
                ]
            );
        }
    }

    /**
     * Creates a new post of this post type
     * 
     * @param array $post_data Array of post data (must include 'title')
     * @param array $taxonomies Array of taxonomy terms to assign (['taxonomy' => ['term1', 'term2']])
     * @param array $meta_data Array of meta data to add
     * @return int|WP_Error The post ID on success, WP_Error on failure
     */
    public function add_post(array $post_data, array $taxonomies = [], array $meta_data = []): int|WP_Error
    {
        $defaults = [
            'post_title' => '',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => $this->slug,
        ];
        
        $post_args = wp_parse_args($post_data, $defaults);
        
        // Validate required fields
        if (empty($post_args['post_title'])) 
        {
            return new WP_Error('missing_title', __('Post title is required', 'flex-eland'));
        }
        
        $post_id = wp_insert_post($post_args, true);
        
        if (is_wp_error($post_id)) 
        {
            return $post_id;
        }
        
        // Assign taxonomies if provided
        foreach ($taxonomies as $taxonomy => $terms) 
        {
            if (taxonomy_exists($taxonomy)) 
            {
                wp_set_object_terms($post_id, $terms, $taxonomy);
            }
        }
        
        // Add meta data if provided
        if (!empty($meta_data)) 
        {
            $this->add_post_meta($post_id, $meta_data);
        }
        
        return $post_id;
    }

    /**
     * Adds meta data to a post
     * 
     * @param int $post_id Post ID
     * @param array $meta_data Array of meta data (key => value pairs)
     * @param bool $update_existing Whether to update existing meta keys
     * @return array Array of results for each meta operation
     */
    public function add_post_meta(int $post_id, array $meta_data, bool $update_existing = true): array
    {
        $results = [];
        
        foreach ($meta_data as $key => $value) 
        {
            if ($update_existing || !metadata_exists('post', $post_id, $key)) 
            {
                $results[$key] = update_post_meta($post_id, $key, $value);
            } 
            else
            {
                $results[$key] = false; // Skip existing
            }
        }
        
        return $results;
    }

    /**
     * Helper method to generate meta data array
     */
    protected function generate_meta_data( array $meta ): array 
    {
        return [
            'key' => $meta['key'],
            'type' => $meta['type'] ?? 'string',
            'description' => $meta['description'] ?? '',
            'single' => $meta['single'] ?? true,
            'show_in_rest' => $meta['show_in_rest'] ?? true,
            'sanitize_callback' => $meta['sanitize_callback'] ?? null,
            'auth_callback' => $meta['auth_callback'] ?? null,
        ];
    }
}