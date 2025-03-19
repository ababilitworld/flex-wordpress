<?php
namespace Ababilitworld\FlexWordpress\Package\Test;

use Ababilitworld\FlexWordpress\Package\Test\Template\Template;

use Ababilitworld\{
    FlexTraitByAbabilitworld\Standard\Standard,
    FlexWordpress\Package\Test\Menu\Menu as TestMenu,
    FlexWordpress\Package\Test\Setting\Setting as Setting,
    FlexWordpress\Package\Test\Service\Service as TestService,
    FlexWordpress\Package\Test\Presentation\Template\Template as TestTemplate
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

        public function __construct($data = []) 
        {
            $this->init($data); 
            
        }

        public function init($data) 
        {
            $this->menu = TestMenu::instance();       
        }
    }
}