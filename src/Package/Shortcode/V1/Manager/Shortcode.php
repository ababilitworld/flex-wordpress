<?php
namespace Ababilithub\FlexWordpress\Package\Shortcode\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Shortcode\V1\Contract\Shortcode as ShortcodeContract, 
    FlexWordpress\Package\Shortcode\V1\Factory\Shortcode as ShortcodeFactory,
    FlexWordpress\Package\Shortcode\V1\Concrete\StaticFilter\Shortcode as StaticFilterShortcode,
};

class  Shortcode extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
                [
                //StaticFilterShortcode::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = ShortcodeFactory::get($item);

            if ($item_instance instanceof ShortcodeContract) 
            {
                $item_instance->register();
            }
        }
    }
}