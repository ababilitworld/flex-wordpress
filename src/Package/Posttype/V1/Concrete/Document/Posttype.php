<?php
namespace Ababilithub\FlexELand\Package\Plugin\Posttype\V1\Concrete\Document;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Posttype\V1\Base\Posttype as BasePosttype
};

use const Ababilithub\{
    FlexWordpress\PLUGIN_PRE_UNDS
};

class Posttype extends BasePosttype
{
    use StandardMixin;
    protected function init(): void
    {
        $this->posttype = 'flexdoc';
        $this->slug = 'flexdoc';
        
        $this->init_hook();
        $this->init_service();
    }

    protected function init_hook(): void
    {
        add_filter(PLUGIN_PRE_UNDS.'_admin_menu', [$this, 'add_menu_items']);
        add_filter('use_block_editor_for_post_type', [$this, 'disable_gutenberg'], 10, 2);
        //add_filter(PLUGIN_PRE_UNDS.'_admin_menu', [$this, 'add_menu_items']);
        add_action('init', [$this, 'init_posttype'], 30); 
        parent::init_hook();           
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
            'page_title' => __('Land Type', 'flex-eland'),
            'menu_title' => __('Land Type', 'flex-eland'),
            'capability' => 'manage_options',
            'menu_slug' => 'edit-tags.php?posttype='.$this->slug,
            'callback' => null,
            'position' => 9,
        ];

        return $menu_items;
    }

    public function init_posttype()
    {
        
        $this->set_labels([
            'name' => esc_html__('Land Deeds', 'flex-eland'),
            'singular_name' => esc_html__('Land Deed', 'flex-eland'),
            'menu_name' => esc_html__('Land Deeds', 'flex-eland'),
            'name_admin_bar' => esc_html__('Land Deeds', 'flex-eland'),
            'archives' => esc_html__('Land Deed List', 'flex-eland'),
            'attributes' => esc_html__('Land Deed List', 'flex-eland'),
            'parent_item_colon' => esc_html__('Land Deed Item : ', 'flex-eland'),
            'all_items' => esc_html__('All Land Deed', 'flex-eland'),
            'add_new_item' => esc_html__('Add new Land Deed', 'flex-eland'),
            'add_new' => esc_html__('Add new Land Deed', 'flex-eland'),
            'new_item' => esc_html__('New Land Deed', 'flex-eland'),
            'edit_item' => esc_html__('Edit Land Deed', 'flex-eland'),
            'update_item' => esc_html__('Update Land Deed', 'flex-eland'),
            'view_item' => esc_html__('View Land Deed', 'flex-eland'),
            'view_items' => esc_html__('View Land Deeds', 'flex-eland'),
            'search_items' => esc_html__('Search Land Deeds', 'flex-eland'),
            'not_found' => esc_html__('Land Deed Not found', 'flex-eland'),
            'not_found_in_trash' => esc_html__('Land Deed Not found in Trash', 'flex-eland'),
            'featured_image' => esc_html__('Land Deed Feature Image', 'flex-eland'),
            'set_featured_image' => esc_html__('Set Land Deed Feature Image', 'flex-eland'),
            'remove_featured_image' => esc_html__('Remove Feature Image', 'flex-eland'),
            'use_featured_image' => esc_html__('Use as Land Deed featured image', 'flex-eland'),
            'insert_into_item' => esc_html__('Insert into Land Deed', 'flex-eland'),
            'uploaded_to_this_item' => esc_html__('Uploaded to this ', 'flex-eland'),
            'items_list' => esc_html__('Land Deed list', 'flex-eland'),
            'items_list_navigation' => esc_html__('Land Deed list navigation', 'flex-eland'),
            'filter_items_list' => esc_html__('Filter Land Deed List', 'flex-eland')
        ]);

        $this->set_supports(
            array('title', 'thumbnail', 'editor', 'custom-fields')
        );

        $this->set_args([
            'public' => true, // Changed to true
            'show_ui' => true,
            'show_in_menu' => false, // Don't show in menu by default
            'labels' => $this->labels,
            'menu_icon' => "dashicons-admin-post",
            'rewrite' => array('slug' => $this->slug),
            'supports' => $this->supports,
            'taxonomies' => array('media-type','extension-type'),
        ]);

        $this->set_metas([
            $this->generate_meta_definition(
                'agricultural-land',
                'Agricultural Land',
                'This is the most prevalent type of land in Bangladesh, used for cultivation and crop production',
                [
                    'max_duration' => 99,
                    'renewable' => true
                ]
            ),
            $this->generate_meta_definition(
                'homestead-land',
                'Homestead Land',
                'This refers to the land where residential dwellings (houses, structures) are located',
                [
                    'max_duration' => 99,
                    'renewable' => true
                ]
            ),
            $this->generate_meta_definition(
                'khas-land',
                'Khas Land',
                'This is government-owned land under the control of the Ministry of Land. It\'s managed by the Collector or Deputy Commissioner on behalf of the government',
                [
                    'max_duration' => 99,
                    'renewable' => true
                ]
            ),
        ]);

    }
}