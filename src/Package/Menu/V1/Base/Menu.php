<?php
namespace Ababilithub\FlexWordpress\Package\Menu\V1\Base;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Menu\V1\Contract\Menu as MenuContract
};

if ( ! class_exists( __NAMESPACE__.'\Menu' ) ) 
{
    /**
     * Abstract Class BaseMenu
     * Defines the structure for WordPress menu classes
     */
    abstract class Menu implements MenuContract
    {
        protected $menu_items = [];
        protected $menu_filter_name = '';

        /**
         * BaseMenu constructor
         */
        public function __construct()
        {
            $this->init();
        }

        abstract public function init(array $data = []): static;

        public function register(): void
        {
            $this->menu_items = apply_filters($this->menu_filter_name, []);
            add_action('admin_menu', [$this, 'register_menus'],20);
        }

        public function register_menus(): void
        {
            if(count($this->menu_items))
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

        /**
         * Check if a WordPress admin menu exists by slug.
         */
        protected function menu_exists(string $menu_slug): bool
        {
            global $menu, $submenu;

            // Check parent menus
            foreach ($menu as $item) 
            {
                if ($item[2] === $menu_slug) 
                {
                    return true;
                }
            }

            // Check submenus
            foreach ($submenu as $parent_slug => $items) 
            {
                foreach ($items as $item) 
                {
                    if ($item[2] === $menu_slug) 
                    {
                        return true;
                    }
                }
            }

            return false;
        }
    }

}
	