<?php
namespace Ababilithub\FlexWordpress\Package\Frontend\Menu\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\Standard\V1\V1 as StandardMixin,
};

if (!class_exists(__NAMESPACE__ . '\Menu')) 
{

    /**
     * Abstract Class BaseMenu
     * Defines the structure for WordPress frontend menu classes
     */
    abstract class Menu
    {
        use StandardMixin;

        /**
         * Holds the menu slug
         * @var string
         */
        protected string $menu_slug;

        /**
         * Holds the menu label
         * @var string
         */
        protected string $menu_label;

        /**
         * Holds the menu capability
         * @var string
         */
        protected string $capability;

        /**
         * Holds the submenu items
         * @var array
         */
        protected array $submenus = [];

        /**
         * Constructor to hook menu initialization
         */
        public function __construct()
        {
            add_action('init', [$this, 'add_rewrite_rule']);
            add_filter('query_vars', [$this, 'add_query_vars']);
            add_filter('template_include', [$this, 'load_template']);
            add_filter('wp_nav_menu_items', [$this, 'add_to_menu'], 10, 2);
        }

        /**
         * Adds the menu item and submenus to the WordPress frontend menu
         * 
         * @param string $items The HTML list content for the menu items.
         * @param object $args An object containing wp_nav_menu() arguments.
         * @return string Modified menu items.
         */
        public function add_to_menu(string $items, $args): string
        {
            if ($this->can_user_access()) {
                $menu_html = '<li class="menu-item menu-has-children">';
                $menu_html .= sprintf(
                    '<a href="%s">%s</a>',
                    esc_url($this->get_menu_url()),
                    esc_html($this->get_menu_label())
                );

                if (!empty($this->submenus)) {
                    $menu_html .= '<ul class="sub-menu">';
                    foreach ($this->submenus as $submenu) {
                        if (current_user_can($submenu['capability'])) {
                            $menu_html .= sprintf(
                                '<li class="menu-item"><a href="%s">%s</a></li>',
                                esc_url($submenu['slug']),
                                esc_html($submenu['menu_title'])
                            );
                        }
                    }
                    $menu_html .= '</ul>';
                }

                $menu_html .= '</li>';
                $items .= $menu_html;
            }
            return $items;
        }

        /**
         * Adds a submenu
         * 
         * @param array $data Submenu configuration.
         */
        public function add_submenu(array $data): void
        {
            if (!isset($data['menu_title'], $data['capability'], $data['slug'])) {
                throw new \InvalidArgumentException("Missing submenu parameters.");
            }

            $this->submenus[] = [
                'menu_title' => sanitize_text_field($data['menu_title']),
                'capability' => sanitize_text_field($data['capability']),
                'slug'       => esc_url($data['slug']),
            ];
        }

        /**
         * Determines if the current user has permission to see the menu
         */
        protected function can_user_access(): bool
        {
            return current_user_can($this->get_menu_capability());
        }

        /**
         * Add custom rewrite rules
         */
        public function add_rewrite_rule(): void
        {
            //
        }

        /**
         * Add custom query vars
         */
        public function add_query_vars($query_vars) 
        {
            return $query_vars;
        }

        /**
         * Load a custom template
         */
        public function load_template($template) 
        {
            return $template;
        }

        /**
         * Get the menu URL
         */
        abstract protected function get_menu_url(): string;

        /**
         * Get the menu label
         */
        abstract protected function get_menu_label(): string;

        /**
         * Get the menu capability
         */
        abstract protected function get_menu_capability(): string;
    }
}
