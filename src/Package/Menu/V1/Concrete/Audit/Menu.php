<?php
namespace Ababilithub\FlexWordpress\Package\Menu\V1\Concrete\Audit;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Menu\V1\Base\Menu as BaseMenu,
};

if (!class_exists(__NAMESPACE__.'\Menu')) 
{

    class Menu extends BaseMenu
    {
        public function init(array $data = []) : static
        {
            $this->menu_items[] = [
                'type' => 'submenu',
                'parent_slug' => 'flex-supervisor',
                'page_title' => 'Audit',
                'menu_title' => 'Audit',
                'capability' => 'manage_options',
                'menu_slug' => 'flex-supervisor-audit',
                'callback' => [$this, 'render'],
                'position' => 1,
            ];
            return $this;
        }

        public function init_service() : void
        {
            
        }

        public function init_hook() : void
        {
            
        }
        
    }
}
