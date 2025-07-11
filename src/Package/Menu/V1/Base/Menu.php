<?php
namespace Ababilithub\FlexWordpress\Package\Menu\V1\Base;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
};

if ( ! class_exists( __NAMESPACE__.'\Menu' ) ) 
{
    /**
     * Abstract Class BaseMenu
     * Defines the structure for WordPress menu classes
     */
    abstract class Menu
    {
        protected $menu_items = [];

        /**
         * BaseMenu constructor
         */
        public function __construct()
        {
            $this->init();
            $this->init_service();
            $this->init_hook();
        }

        public function init(array $data = []) : static
        {

            return $this;
        }

        protected function init_service() : void
        {

        }
        protected function init_hook() : void
        {

        }

        public function register(): void
        {
            add_action('admin_menu', [$this, 'register_menus']);
        }

        /**
         * Register all admin menus and submenus
         */
        public function register_menus()
        {
            foreach ($this->menu_items as $item) 
            {
                if ($item['type'] === 'menu') 
                {
                    add_menu_page(
                        $item['page_title'],
                        $item['menu_title'],
                        $item['capability'],
                        $item['menu_slug'],
                        $item['callback'],
                        $item['icon'],
                        $item['position']
                    );
                } 
                elseif ($item['type'] === 'submenu') 
                {
                    add_submenu_page(
                        $item['parent_slug'],
                        $item['page_title'],
                        $item['menu_title'],
                        $item['capability'],
                        $item['menu_slug'],
                        $item['callback'],
                        $item['position']
                    );
                }
            }
        }
    }

}
	