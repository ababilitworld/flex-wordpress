<?php
namespace Ababilithub\FlexWordpress\Package\Repository\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Repository\V1\Contract\Repository as RepositoryContract, 
    FlexWordpress\Package\Repository\V1\Factory\Repository as RepositoryFactory,
    FlexWordpress\Package\Repository\V1\Concrete\WpError\Repository as WpErrorRepository, 
};

class  Repository extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
                [
                WpErrorRepository::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = RepositoryFactory::get($item);

            if ($item_instance instanceof RepositoryContract) 
            {
                $item_instance->init();
            }
        }
    }
}