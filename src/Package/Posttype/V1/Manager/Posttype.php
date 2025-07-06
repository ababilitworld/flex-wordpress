<?php
namespace Ababilithub\FlexWordpress\Package\Posttype\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Posttype\V1\Contract\Posttype as PosttypeContract, 
    FlexWordpress\Package\Posttype\V1\Factory\Posttype as PosttypeFactory,
    FlexWordpress\Package\Posttype\V1\Concrete\StaticFilter\Posttype as StaticFilterPosttype,
};

class  Posttype extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
                [
                //StaticFilterPosttype::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = PosttypeFactory::get($item);

            if ($item_instance instanceof PosttypeContract) 
            {
                $item_instance->register();
            }
        }
    }
}