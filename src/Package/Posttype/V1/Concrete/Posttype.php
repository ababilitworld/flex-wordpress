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
        $this->posttype = 'fldocc';
        $this->slug = 'fldocc';
        
        $this->init_hook();
        $this->init_service();
    }

    protected function init_hook(): void
    {
        //add_filter(PLUGIN_PRE_UNDS.'_admin_menu', [$this, 'add_menu_items']);
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
            'page_title' => __('Flex Document', 'flex-aahub-by-ababilitworld'),
            'menu_title' => __('Flex Document', 'flex-aahub-by-ababilitworld'),
            'capability' => 'manage_options',
            'menu_slug' => 'edit.php?post_type='.$this->posttype,
            'callback' => null,
            'position' => 9,
        ];

        return $menu_items;
    }

    public function init_posttype()
    {
        
        $this->set_labels([
            'name' => esc_html__('Land Deeds', 'flex-aahub-by-ababilitworld'),
            'singular_name' => esc_html__('Land Deed', 'flex-aahub-by-ababilitworld'),
            'menu_name' => esc_html__('Land Deeds', 'flex-aahub-by-ababilitworld'),
            'name_admin_bar' => esc_html__('Land Deeds', 'flex-aahub-by-ababilitworld'),
            'archives' => esc_html__('Land Deed List', 'flex-aahub-by-ababilitworld'),
            'attributes' => esc_html__('Land Deed List', 'flex-aahub-by-ababilitworld'),
            'parent_item_colon' => esc_html__('Land Deed Item : ', 'flex-aahub-by-ababilitworld'),
            'all_items' => esc_html__('All Land Deed', 'flex-aahub-by-ababilitworld'),
            'add_new_item' => esc_html__('Add new Land Deed', 'flex-aahub-by-ababilitworld'),
            'add_new' => esc_html__('Add new Land Deed', 'flex-aahub-by-ababilitworld'),
            'new_item' => esc_html__('New Land Deed', 'flex-aahub-by-ababilitworld'),
            'edit_item' => esc_html__('Edit Land Deed', 'flex-aahub-by-ababilitworld'),
            'update_item' => esc_html__('Update Land Deed', 'flex-aahub-by-ababilitworld'),
            'view_item' => esc_html__('View Land Deed', 'flex-aahub-by-ababilitworld'),
            'view_items' => esc_html__('View Land Deeds', 'flex-aahub-by-ababilitworld'),
            'search_items' => esc_html__('Search Land Deeds', 'flex-aahub-by-ababilitworld'),
            'not_found' => esc_html__('Land Deed Not found', 'flex-aahub-by-ababilitworld'),
            'not_found_in_trash' => esc_html__('Land Deed Not found in Trash', 'flex-aahub-by-ababilitworld'),
            'featured_image' => esc_html__('Land Deed Feature Image', 'flex-aahub-by-ababilitworld'),
            'set_featured_image' => esc_html__('Set Land Deed Feature Image', 'flex-aahub-by-ababilitworld'),
            'remove_featured_image' => esc_html__('Remove Feature Image', 'flex-aahub-by-ababilitworld'),
            'use_featured_image' => esc_html__('Use as Land Deed featured image', 'flex-aahub-by-ababilitworld'),
            'insert_into_item' => esc_html__('Insert into Land Deed', 'flex-aahub-by-ababilitworld'),
            'uploaded_to_this_item' => esc_html__('Uploaded to this ', 'flex-aahub-by-ababilitworld'),
            'items_list' => esc_html__('Land Deed list', 'flex-aahub-by-ababilitworld'),
            'items_list_navigation' => esc_html__('Land Deed list navigation', 'flex-aahub-by-ababilitworld'),
            'filter_items_list' => esc_html__('Filter Land Deed List', 'flex-aahub-by-ababilitworld')
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
               [
                    'key' => '__short_description',
                    'type' => 'string',
                    'description' => 'short description of the '.$this->slug,
                    'single' => true,
                    'show_in_rest' => true,
                    'sanitize_callback' => null,
                    'auth_callback' => null,
                ]
            ),
            $this->generate_meta_definition(
                [
                    'key' => '__short_description',
                    'type' => 'string',
                    'description' => 'short description of the '.$this->slug,
                    'single' => true,
                    'show_in_rest' => true,
                    'sanitize_callback' => null,
                    'auth_callback' => null,
                ]
            ),
            $this->generate_meta_definition(
                [
                    'key' => '__short_description',
                    'type' => 'string',
                    'description' => 'short description of the '.$this->slug,
                    'single' => true,
                    'show_in_rest' => true,
                    'sanitize_callback' => null,
                    'auth_callback' => null,
                ]
            ),
        ]);

    }
}