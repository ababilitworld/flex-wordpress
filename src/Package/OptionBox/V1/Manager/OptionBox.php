<?php
namespace Ababilithub\FlexWordpress\Package\OptionBox\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\OptionBox\V1\Contract\OptionBox as OptionBoxContract, 
    FlexWordpress\Package\OptionBox\V1\Factory\OptionBox as OptionBoxFactory,
    FlexWordpress\Package\OptionBox\V1\Concrete\VerticalTabBox\OptionBox as VerticalTabOptionBox,
};

class  OptionBox extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
                [
                VerticalTabOptionBox::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = OptionBoxFactory::get($item);

            if ($item_instance instanceof OptionBoxContract) 
            {
                $item_instance->register();
            }
        }
    }
}