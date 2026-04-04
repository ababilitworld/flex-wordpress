<?php
namespace Ababilithub\FlexWordpress\Package\MenuFront\V1\Base;

defined('ABSPATH') && defined('WPINC') || exit();

use Ababilithub\FlexWordpress\Package\Menu\V1\Contract\Menu as MenuContract;

if (!class_exists(__NAMESPACE__ . '\Menu')) {
    abstract class Menu implements MenuContract
    {
        protected $menu_location_filter_name = 'flex_ababilithub_front_menu_locations';
        protected $menu_locations = [];
        protected $menu_item_filter_name = 'flex_ababilithub_front_menu_items';
        protected $menu_items = [];
        
        public function __construct(array $data = [])
        {
            $this->init($data);
        }

        abstract public function init(array $data = []): static;

        public function register(): void
        {
            
            add_action('after_setup_theme', [$this, 'register_nav_menus'], 20);
            add_action('after_theme_switch', [$this, 'register_nav_menus'], 20);
        }

        public function register_nav_menu_locations(): void
        {
            $this->menu_locations = apply_filters($this->menu_location_filter_name, $this->menu_locations);

            if (empty($this->menu_locations)) 
            {
                return;
            }

            $menu_locations = [];
            foreach ($this->menu_locations as $menu_location) 
            {
                if (!empty($menu_location['location']) && !empty($menu_location['description'])) 
                {
                    $menu_locations[$menu_location['location']] = __($menu_location['description'], 'flex-theme-by-ababilithub');
                }
            }

            if (!empty($menu_locations)) 
            {
                register_nav_menus($menu_locations);
            }

        }

        public function register_nav_menus(): void
        {
            
        }

        protected function menu_location_has_menu(string $location): bool
        {
            $locations = get_nav_menu_locations();
            return isset($locations[$location]) && $locations[$location] > 0;
        }

        /**
         * Create a default menu and pages on theme activation (optional).
         * This is often called from a theme activation hook.
         */
        public function theme_settings(): void
        {
            $locations = get_nav_menu_locations();
            $menu_id   = $locations['flex_ababilithub_front_menu'] ?? 0;

            if ( ! $menu_id ) {
                $menu_id = wp_create_nav_menu( 'Flex Theme Menu' );
                $locations['flex_ababilithub_front_menu'] = $menu_id;
                set_theme_mod( 'nav_menu_locations', $locations );
            }

            $default_items = [
                [ 'title' => 'Home',        'description' => 'Homepage',      'url' => home_url('/'),                 'content' => '[home_content]',        'status' => 'publish', 'position' => 1 ],
                [ 'title' => 'About',       'description' => 'About',         'url' => home_url('/about'),            'content' => '[about_content]',       'status' => 'publish', 'position' => 2 ],
                [ 'title' => 'Services',    'description' => 'Services',      'url' => home_url('/services'),         'content' => '[services_content]',    'status' => 'publish', 'position' => 3 ],
                [ 'title' => 'Portfolios',  'description' => 'Portfolios',    'url' => home_url('/portfolios'),       'content' => '[portfolios_content]',  'status' => 'publish', 'position' => 4 ],
                [ 'title' => 'Appointment', 'description' => 'Appointment',   'url' => home_url('/appointment'),      'content' => '[appointment_content]', 'status' => 'publish', 'position' => 5 ],
            ];

            // Get existing menu items and remove duplicates by title
            $existing_items   = wp_get_nav_menu_items( $menu_id );
            $existing_titles  = [];
            $title_to_id      = [];

            if ( $existing_items ) {
                foreach ( $existing_items as $item ) {
                    if ( in_array( $item->title, $existing_titles ) ) {
                        wp_delete_post( $item->ID, true );
                    } else {
                        $existing_titles[] = $item->title;
                        $title_to_id[ $item->title ] = $item->ID;
                    }
                }
            }

            // Add or update menu items with the correct order (based on position)
            foreach ( $default_items as $item ) {
                $args = [
                    'menu-item-title'       => $item['title'],
                    'menu-item-description' => $item['description'],
                    'menu-item-url'         => $item['url'],
                    'menu-item-status'      => $item['status'],
                    'menu-item-type'        => 'custom',
                    'menu-item-position'    => $item['position'], // sets menu_order
                ];

                // If item exists, update it; otherwise create a new one
                if ( isset( $title_to_id[ $item['title'] ] ) ) 
                {
                    wp_update_nav_menu_item( $menu_id, $title_to_id[ $item['title'] ], $args );
                } 
                else 
                {
                    wp_update_nav_menu_item( $menu_id, 0, $args );
                    $this->create_page_if_not_exists( $item );
                }
            }
        }

        private function create_page_if_not_exists(array $data): void
        {
            $page = get_page_by_path(sanitize_title($data['title']));
            if (!$page) {
                wp_insert_post([
                    'post_title'   => $data['title'],
                    'post_content' => $data['content'],
                    'post_status'  => $data['status'],
                    'post_type'    => 'page',
                ]);
            }
        }
    }
}