<?php
namespace Ababilithub\FlexWordpress\Package\Test;

use Ababilithub\FlexWordpress\Package\Test\Template\Template;

use Ababilitworld\{
    FlexTraitByAbabilitworld\Standard\Standard,
};

use Ababilithub\{
    FlexWordpress\Package\Test\Menu\Menu as TestMenu,
    FlexWordpress\Package\Posttype\Contract\Posttype as WpPosttypeInterface,
    FlexWordpress\Package\Posttype\Mixin\Posttype as WpPosttypeMixin,
    FlexWordpress\Package\Test\Posttype\V1\Concrete\Posttype as ConcretePosttype,
};

use const Ababilitworld\{
    FlexWordpress\PLUGIN_NAME,
    FlexWordpress\PLUGIN_DIR,
    FlexWordpress\PLUGIN_URL,
    FlexWordpress\PLUGIN_FILE,
    FlexWordpress\PLUGIN_PRE_UNDS,
    FlexWordpress\PLUGIN_PRE_HYPH,
    FlexWordpress\PLUGIN_VERSION
};

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

if (!class_exists(__NAMESPACE__.'\Test')) 
{
    class Test 
    {
        use Standard;
        private $menu;
        private $posttype;

        public function __construct($data = []) 
        {
            $this->init($data); 
            
        }

        public function init($data) 
        {
            $this->menu = TestMenu::instance(); 
            $this->posttype = ConcretePosttype::getInstance();      
        }
    }
}