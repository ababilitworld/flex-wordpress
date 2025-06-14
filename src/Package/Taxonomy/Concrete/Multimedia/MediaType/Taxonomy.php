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
                'name'              => _x('Media Types', 'taxonomy general name', 'flex-aahub-by-ababilitworld'),
                'singular_name'     => _x('Media Type', 'taxonomy singular name', 'flex-aahub-by-ababilitworld'),
                'search_items'      => __('Search Media Types', 'flex-aahub-by-ababilitworld'),
                'all_items'         => __('All Media Types', 'flex-aahub-by-ababilitworld'),
                'parent_item'       => __('Parent Media Type', 'flex-aahub-by-ababilitworld'),
                'parent_item_colon' => __('Parent Media Type:', 'flex-aahub-by-ababilitworld'),
                'edit_item'         => __('Edit Media Type', 'flex-aahub-by-ababilitworld'),
                'update_item'       => __('Update Media Type', 'flex-aahub-by-ababilitworld'),
                'add_new_item'      => __('Add New Media Type', 'flex-aahub-by-ababilitworld'),
                'new_item_name'     => __('New Media Type Name', 'flex-aahub-by-ababilitworld'),
                'menu_name'         => __('Media Types', 'flex-aahub-by-ababilitworld'),
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
                'page_title' => __('Media Type', 'flex-aahub-by-ababilitworld'),
                'menu_title' => __('Media Type', 'flex-aahub-by-ababilitworld'),
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy='.$this->taxonomy_slug,
                'callback' => null,
                'position' => 9,
            ];

            return $menu_items;
        }
    }
}