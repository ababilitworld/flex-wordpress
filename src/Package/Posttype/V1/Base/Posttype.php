<?php
namespace Ababilithub\FlexWordpress\Package\Posttype\V1\Base;

use finfo;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Posttype\V1\Mixin\Posttype as WpPosttypeMixin,
    FlexPhp\Package\Utility\ArrayUtility\Utility as ArrayUtility,
    FlexWordpress\Package\Posttype\V1\Contract\Posttype as PosttypeContract,
};

use WP_Error;

abstract class Posttype implements PosttypeContract
{
    use WpPosttypeMixin;    
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
        // parent init hook   
    }

    protected function init_service(): void
    {
        // parent init services
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

    public function register_taxonomy(string $taxonomy_slug): void
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

    public function register_metas(): void
    {
        if (empty($this->metas)) {
            return;
        }

        foreach ($this->metas as $meta) {
            $this->register_meta($meta);
        }
    }

    // Add these methods to your base class
    public function register_meta(array $meta): bool
    {
        if (!post_type_exists($this->slug)) {
            return false;
        }

        return register_post_meta(
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