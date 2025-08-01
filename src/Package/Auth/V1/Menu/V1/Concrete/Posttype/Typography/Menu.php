<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\Menu\V1\Concrete\Posttype\Typography;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Menu\V1\Base\Menu as BaseMenu,
    FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\Typography\Posttype as TypographyPosttype
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_UNDS,
    FlexMasterPro\PLUGIN_DIR,
};

if (!class_exists(__NAMESPACE__.'\Menu')) 
{

    class Menu extends BaseMenu
    {

        public function init(array $data = []) : static
        {
            $this->menu_filter_name = PLUGIN_PRE_UNDS.'_admin_menu';
            $this->init_service();
            $this->init_hook();
            return $this;
        }

        public function init_service() : void
        {
            
        }

        public function init_hook() : void
        {
            // Add filter to collect menu items
            add_filter($this->menu_filter_name, [$this, 'add_menu_items']);
            
        }

        /**
         * Add default menu items
         */
        public function add_menu_items($menu_items = [])
        {
            $menu_items[] = [
                'type' => 'submenu',
                'parent_slug' => 'flex-master-pro',
                'page_title' => 'Typography',
                'menu_title' => 'Typography',
                'capability' => 'manage_options',
                'menu_slug' => 'edit.php?post_type='.TypographyPosttype::POSTTYPE,
                'callback' => '',
                'position' => 4,
            ];

            return $menu_items;
        }

        /**
         * Custom main page render
         */
        public function render_main_page()
        {
            echo '<div class="wrap">';
            echo '<h1>Main Menu Dashboard</h1>';
            echo '<p>Welcome to Flex Bangla Land administration panel.</p>';
            echo '</div>';
        }

        /**
         * Custom main page render
         */
        public function render_submenu()
        {
            echo '<div class="wrap">';
            echo '<h1>Sub Menu Dashboard</h1>';
            echo '<p>Welcome to Flex Bangla Land administration panel.</p>';
            echo '</div>';
        }
        
    }
}
