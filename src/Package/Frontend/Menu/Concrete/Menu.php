<?php
namespace Ababilithub\FlexWordpress\Package\Frontend\Menu\Concrete;

use Ababilithub\{
    FlexPhp\Package\Mixin\Standard\V1\V1 as StandardMixin,
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
        protected string $menu_label = 'Dashboard';
        protected string $capability = 'read';

        public function __construct()
        {
            parent::__construct();

            // Add Submenus
            $this->add_submenu([
                'menu_title' => 'Profile',
                'capability' => 'read',
                'slug'       => home_url('/profile'),
            ]);

            $this->add_submenu([
                'menu_title' => 'Settings',
                'capability' => 'manage_options',
                'slug'       => home_url('/settings'),
            ]);
        }

        protected function get_menu_url(): string
        {
            return home_url('/dashboard');
        }

        protected function get_menu_label(): string
        {
            return $this->menu_label;
        }

        protected function get_menu_capability(): string
        {
            return $this->capability;
        }

        public function add_rewrite_rule(): void
        {
            add_rewrite_rule('^custom-dashboard/?$', 'index.php?custom_dashboard=1', 'top');
        }

        public function add_query_vars($query_vars) 
        {
            $query_vars[] = 'custom_dashboard';
            return $query_vars;
        }

        public function load_template($template) 
        {
            if (get_query_var('custom_dashboard') == 1) 
            {
                return plugin_dir_path(__FILE__) . 'templates/custom-dashboard-template.php';
            }
            return $template;
        }
    }
}
