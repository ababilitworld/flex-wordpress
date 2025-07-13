<?php

namespace Ababilithub\FlexWordpress\Package\Posttype\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Posttype\V1\Contract\Posttype as PosttypeContract
};

abstract class Posttype implements PosttypeContract
{
    protected string $posttype;
    protected string $slug;
    protected array $taxonomies = [];
    protected array $available_theme_supports = [];
    protected array $theme_supports = [];
    protected array $available_posttype_supports = [];
    protected array $posttype_supports = [];
    protected array $labels = [];
    protected array $args = [];
    protected array $metas = [];
    protected bool $use_block_editor = true;

    public function __construct()
    {
        $this->init();
    }

    abstract public function init(): void;
    public function register(): void
    {
        register_post_type($this->slug, $this->args);
    }

    public function get_posttype(): string
    {
        return $this->posttype;
    }

    protected function set_posttype(string $posttype): void
    {
        $this->posttype = $posttype;
    }

    public function get_slug(): string
    {
        return $this->slug;
    }

    protected function set_slug(string $slug): void
    {
        $this->slug = $slug;
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

    public function add_action_view_details($actions, \WP_Post $post) 
    {
        // Check if this is our taxonomy
        if ($post->post_type !== $this->posttype) 
        {
            return $actions;
        }

        $actions['view_details'] = sprintf(
            '<a href="%s">%s</a>',
            esc_url(admin_url(
                'admin.php?page=flex-supervisor-audit-post&object_id=' . $post->ID . '&action_id=view_details'
            )),
            esc_html__('View Details', 'flex-eland')
        );
        
        return $actions;
    }

    public function get_default_args()
    {
        return array(
            '_builtin' =>  'bool',
            '_edit_link' =>   'string',
            'autosave_rest_controller_class' =>   'bool|string',
            'can_export' =>   'bool',
            'capabilities' =>   'string[]',
            'capability_type' =>   'array|string',
            'delete_with_user' =>   'bool',
            'description' =>   'string',
            'exclude_from_search' =>   'bool',
            'has_archive' =>   'bool|string',
            'hierarchical' =>   'bool',
            'label' =>   'string',
            'labels' =>   'string[]',
            'late_route_registration' =>   'bool',
            'map_meta_cap' =>   'bool',
            'menu_icon' =>   'string',
            'menu_position' =>   'int',
            'public' =>   'bool',
            'publicly_queryable' =>   'bool',
            'query_var' =>   'bool|string',
            'register_meta_box_cb' =>   'callable',
            'rest_base' =>   'string',
            'rest_controller_class' =>   'string',
            'rest_namespace' =>   'string',
            'revisions_rest_controller_class' =>   'bool|string',
            'rewrite' =>   array('ep_mask' =>   'int', 'feeds' =>   'bool', 'pages' =>   'bool', 'slug' =>   'string', 'with_front' =>   'bool'),
            'show_in_admin_bar' =>   'bool',
            'show_in_menu' =>   'bool|string',
            'show_in_nav_menus' =>   'bool',
            'show_in_rest' =>   'bool',
            'show_ui' =>   'bool',
            'supports' =>   'array|bool',
            'taxonomies' =>   'string[]',
            'template' =>   'array',
            'template_lock' =>   'bool|string'
        );
    }

    public function available_posttype_supports():array
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
}
