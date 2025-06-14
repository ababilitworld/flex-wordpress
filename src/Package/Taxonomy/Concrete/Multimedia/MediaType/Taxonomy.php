<?php
namespace Ababilithub\FlexWordpress\Package\Taxonomy\Concrete\Multimedia\MediaType;

use Ababilithub\{
    FlexWordpress\Package\Taxonomy\Base\Taxonomy as BaseTaxonomy
};

use const Ababilithub\FlexELand\PLUGIN_PRE_UNDS;

(defined('ABSPATH') && defined('WPINC')) || exit();

if (!class_exists(__NAMESPACE__.'\Taxonomy')) 
{
    class Taxonomy extends BaseTaxonomy
    {
        protected function init(): void
        {
            $this->taxonomy = 'media-type';
            $this->taxonomy_slug = 'media-type';
            
            $this->set_labels([
                'name'              => _x('Media Types', 'taxonomy general name', 'flex-eland'),
                'singular_name'     => _x('Media Type', 'taxonomy singular name', 'flex-eland'),
                'search_items'      => __('Search Media Types', 'flex-eland'),
                'all_items'         => __('All Media Types', 'flex-eland'),
                'parent_item'       => __('Parent Media Type', 'flex-eland'),
                'parent_item_colon' => __('Parent Media Type:', 'flex-eland'),
                'edit_item'         => __('Edit Media Type', 'flex-eland'),
                'update_item'       => __('Update Media Type', 'flex-eland'),
                'add_new_item'      => __('Add New Media Type', 'flex-eland'),
                'new_item_name'     => __('New Media Type Name', 'flex-eland'),
                'menu_name'         => __('Media Types', 'flex-eland'),
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

            $this->init_hook();
            $this->init_service();
        }

        protected function init_hook(): void
        {
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
                'page_title' => 'Media Type',
                'menu_title' => 'Media Type',
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy='.$this->taxonomy_slug,
                'callback' => null,
                'position' => 9,
            ];

            return $menu_items;
        }
    }
}