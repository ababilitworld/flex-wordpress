<?php
namespace Ababilithub\FlexWordpress\Package\Frontend\Menu\Concrete;

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Frontend\Menu\Base\Menu as BaseMenu,
};

(defined('ABSPATH') && defined('WPINC')) || exit();

if (!class_exists(__NAMESPACE__ . '\Menu')) 
{

    /**
     * Concrete Class Menu
     * Implements the WordPress frontend base menu 
     */
    class Menu extends BaseMenu
    {
        protected string $menu_slug = 'custom-dashboard';
        protected string $menu_label = 'Custom Dashboard';
        protected string $capability = 'read';

        protected string $template_type = 'html';
        protected mixed $template_part = '<div style="color:red;">Bismillah</div>';

        public function __construct()
        {
            
            parent::__construct();

            // Add Submenus
            // $this->add_submenu([
            //     'menu_title' => 'Profile',
            //     'capability' => 'read',
            //     'slug'       => home_url('/profile'),
            // ]);

            // $this->add_submenu([
            //     'menu_title' => 'Settings',
            //     'capability' => 'manage_options',
            //     'slug'       => home_url('/settings'),
            // ]);
        }

        protected function get_menu_url(): string
        {
            return home_url($this->menu_slug);
        }

        protected function get_menu_label(): string
        {
            return $this->menu_label;
        }

        protected function get_menu_capability(): string
        {
            return $this->capability;
        }
    }
}
