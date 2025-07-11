<?php
namespace Ababilithub\FlexWordpress\Package\Menu\V1\Concrete;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Menu\Base\Menu as BaseMenu,
};



if (!class_exists(__NAMESPACE__.'\Menu')) 
{

    class Menu extends BaseMenu
    {

        public function init(array $data = []) : static
        {

        }

        public function init_service() : void
        {
            
        }

        public function init_hook() : void
        {
            
        }
        
    }
}
