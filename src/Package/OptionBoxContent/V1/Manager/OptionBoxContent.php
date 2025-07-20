<?php
namespace Ababilithub\FlexWordpress\Package\OptionBoxContent\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\OptionBoxContent\V1\Contract\OptionBoxContent as OptionBoxContentContract, 
    FlexWordpress\Package\OptionBoxContent\V1\Factory\OptionBoxContent as OptionBoxContentFactory,
    FlexWordpress\Package\OptionBoxContent\V1\Concrete\FlexMasterPro\OptionBoxContent as FlexMasterProOptionBoxContent,
};

class  OptionBoxContent extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
                [
                FlexMasterProOptionBoxContent::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = OptionBoxContentFactory::get($item);

            if ($item_instance instanceof OptionBoxContentContract) 
            {
                $item_instance->register();
            }
        }
    }
}