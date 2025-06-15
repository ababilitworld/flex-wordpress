<?php
namespace Ababilithub\FlexWordpress\Package\Taxonomy\Concrete\Address\Upazila;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Taxonomy\Base\Taxonomy as BaseTaxonomy
};

use const Ababilithub\{
    FlexWordpress\PLUGIN_PRE_UNDS
};

if (!class_exists(__NAMESPACE__.'\Taxonomy')) 
{
    class Taxonomy extends BaseTaxonomy
    {
        use StandardMixin;
        protected function init(): void
        {
            $this->taxonomy = 'upazila';
            $this->taxonomy_slug = 'upazila';

            $this->init_hook();
            $this->init_service();
        }

        protected function init_hook(): void
        {
            add_action('init', [$this, 'init_taxonomy'], 97); 
            parent::init_hook();
            //add_filter(PLUGIN_PRE_UNDS.'_admin_menu', [$this, 'add_menu_items']);
        }

        protected function init_service(): void
        {
            //
        }

        public function add_menu_items($menu_items = [])
        {
            $menu_items[] = [
                'type' => 'submenu',
                'parent_slug' => 'parent-slug',
                'page_title' => __('Upazila', 'flex-aahub-by-ababilitworld'),
                'menu_title' => __('Upazila', 'flex-aahub-by-ababilitworld'),
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy='.$this->taxonomy_slug,
                'callback' => null,
                'position' => 9,
            ];

            return $menu_items;
        }

        public function init_taxonomy()
        {
            
            $this->set_labels([
                'name'              => _x('Upazilas', 'taxonomy general name', 'flex-aahub-by-ababilitworld'),
                'singular_name'     => _x('Upazila', 'taxonomy singular name', 'flex-aahub-by-ababilitworld'),
                'search_items'      => __('Search Upazilas', 'flex-aahub-by-ababilitworld'),
                'all_items'         => __('All Upazilas', 'flex-aahub-by-ababilitworld'),
                'parent_item'       => __('Parent Upazila', 'flex-aahub-by-ababilitworld'),
                'parent_item_colon' => __('Parent Upazila:', 'flex-aahub-by-ababilitworld'),
                'edit_item'         => __('Edit Upazila', 'flex-aahub-by-ababilitworld'),
                'update_item'       => __('Update Upazila', 'flex-aahub-by-ababilitworld'),
                'add_new_item'      => __('Add New Upazila', 'flex-aahub-by-ababilitworld'),
                'new_item_name'     => __('New Upazila Name', 'flex-aahub-by-ababilitworld'),
                'menu_name'         => __('Upazilas', 'flex-aahub-by-ababilitworld'),
            ]);

            $this->set_args([
                'hierarchical' => true,
                'labels' => $this->labels,
                'public' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => ['slug' => $this->taxonomy_slug],
                'show_in_quick_edit' => true,
                'show_in_rest' => true,
                'meta_box_cb' => 'post_categories_meta_box',
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
            ]);
        }
    }
}