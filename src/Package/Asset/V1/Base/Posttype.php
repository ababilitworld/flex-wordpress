<?php
namespace Ababilithub\FlexWordpress\Package\Posttype\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || die();

use AbabilIthub\{
    FlexWordpress\Package\Posttype\V1\Contract\Posttype as WpPosttypeInterface,
    FlexPhp\Package\Mixin\Standard\V1\V1 as StandardMixin,
    FlexWordpress\Package\Posttype\V1\Mixin\Posttype as WpPosttypeMixin,
};

/**
 * Abstract class Posttype
 * Handles custom post type registration with configurable labels, args, and config.
 */
if (!class_exists(__NAMESPACE__ . '\Posttype')) 
{    
    abstract class Posttype implements WpPosttypeInterface
    {
        use StandardMixin, WpPosttypeMixin;
        public static array $instances = [];
        public static ?self $instance = null;

        protected array $defaultConfig = [];
        protected array $config = [];

        protected array $defaultLabels = [];
        protected array $labels = [];

        protected array $defaultArgs = [];
        protected array $args = [];

        protected $posttype;
        protected $slug;
        protected $singular;
        protected $plural;
        protected $textdomain;

        public function __construct(array $config = []) 
        {
            $this->init($config);
        }

        public function init(array $config = []): void 
        {
            $this->config = array_merge($this->getDefaultConfig(), $config);
            $this->labels = array_merge($this->getDefaultLabels(), $this->config['labels'] ?? []);
            $this->args = array_merge($this->getDefaultArgs(), $this->config['args'] ?? []);

            add_action('init', [$this, 'register']);
        }

        protected function needsUpdate(array $config = []): bool 
        {
            return (!empty($config) && $config !== $this->config);
        }

        /**
         * Get Default config.
         */
        public function getDefaultConfig(): array
        {
            return $this->defaultConfig;
        }

        /**
         * Dynamically Set Default config.
         */
        protected function setDefaultConfig(array $config = []): array
        {
            $this->posttype = 'custom_post';
            $this->slug = 'custom-post';
            $this->singular = 'Custom Post';
            $this->plural = 'Custom Posts';
            $this->textdomain = 'plugin-textdomain';

            return $this->defaultConfig = [
                'post_type'  => $this->posttype,
                'slug'       => $this->slug,
                'singular'   => $this->singular,
                'plural'     => $this->plural,
                'textdomain' => $this->textdomain,
                'labels'     => $this->getDefaultLabels(),
                'args'       => $this->getDefaultArgs(),
            ];

        }

        /**
         * Get Default Labels.
         */
        public function getDefaultLabels(): array
        {
            return $this->defaultLabels;
        }

        /**
         * Dynamically Set Default Labels.
         */
        protected function setDefaultLabels(array $config = []): array
        {
            $singular   = $this->singular ;
            $plural     = $this->plural ;
            $textdomain = $this->textdomain ;

            return $this->defaultLabels = [
                'name'                     => __($plural, $textdomain),
                'singular_name'            => __($singular, $textdomain),
                'menu_name'                => __($plural, $textdomain),
                'name_admin_bar'           => __($plural, $textdomain),
                'archives'                 => sprintf(__('%s List', $textdomain), $singular),
                'attributes'               => sprintf(__('%s List', $textdomain), $singular),
                'parent_item_colon'        => sprintf(__('%s Item : ', $textdomain), $singular),
                'all_items'                => sprintf(__('All %s', $textdomain), $plural),
                'add_new_item'             => sprintf(__('Add New %s', $textdomain), $singular),
                'add_new'                  => sprintf(__('Add New %s', $textdomain), $singular),
                'new_item'                 => sprintf(__('New %s', $textdomain), $singular),
                'edit_item'                => sprintf(__('Edit %s', $textdomain), $singular),
                'update_item'              => sprintf(__('Update %s', $textdomain), $singular),
                'view_item'                => sprintf(__('View %s', $textdomain), $singular),
                'view_items'               => sprintf(__('View %s', $textdomain), $plural),
                'search_items'             => sprintf(__('Search %s', $textdomain), $plural),
                'not_found'                => sprintf(__('%s Not found', $textdomain), $singular),
                'not_found_in_trash'       => sprintf(__('%s Not found in Trash', $textdomain), $singular),
                'featured_image'           => sprintf(__('%s Feature Image', $textdomain), $singular),
                'set_featured_image'       => sprintf(__('Set %s Feature Image', $textdomain), $singular),
                'remove_featured_image'    => __('Remove Feature Image', $textdomain),
                'use_featured_image'       => sprintf(__('Use as %s featured image', $textdomain), $singular),
                'insert_into_item'         => sprintf(__('Insert into %s', $textdomain), $singular),
                'uploaded_to_this_item'    => sprintf(__('Uploaded to this %s', $textdomain), $singular),
                'items_list'               => sprintf(__('%s list', $textdomain), $singular),
                'items_list_navigation'    => sprintf(__('%s list navigation', $textdomain), $singular),
                'filter_items_list'        => sprintf(__('Filter %s List', $textdomain), $singular),
            ];
        }

        /**
         * Get Default Args.
         */
        public function getDefaultArgs(): array
        {
            return $this->defaultArgs;
        }

        /**
         * Dynamically Set Default Args.
         */
        protected function setDefaultArgs(array $args = []): array
        {
            return $this->defaultArgs = [
                'public'            => true,
                'has_archive'       => true,
                'show_ui'           => true,
                'show_in_menu'      => true,
                'menu_icon'         => 'dashicons-admin-post',
                'menu_position'     => 20,
                'show_in_rest'      => true,
                'supports'          => ['title', 'editor', 'thumbnail'],
                'rewrite'           => ['slug' => $this->slug],
                'capability_type'   => 'post',
            ];
        }        

        /**
         * Get Config.
         */
        public function getConfig(): array 
        {
            return $this->config;
        }

        /**
         * Dynamically Set Config.
         */
        protected function setConfig(array $config = []): array
        {
            return $this->config = $config;
        }   

        /**
         * Get Labels.
         */
        public function getLabels(): array 
        {
            return $this->labels;
        }

        /**
         * Dynamically Set Labels.
         */
        protected function setLabels(array $labels = []): array
        {
            return $this->labels = $labels;
        }

        /**
         * Get Args.
         */
        public function getArgs(): array 
        {
            return $this->args;
        }

        /**
         * Dynamically Set Args.
         */
        protected function setArgs(array $args = []): array
        {
            return $this->args = $args;
        }

        /**
         * Get Post Type.
         */
        protected function getPosttype(): string 
        {
            return $this->posttype;
        }

        /**
         * Register post type.
         */
        public function register(): void
        {
            add_action('init', function () {
                if (!post_type_exists($this->getPosttype())) {
                    register_post_type($this->getPosttype(), $this->getArgs());
                }
            });

            $this->registerHooks();
        }

        /**
         * Register the required hooks to posttype.
         */
        protected function registerHooks(): void
        {

        }
        
    }
}
