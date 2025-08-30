<?php
namespace Ababilithub\FlexWordpress\Package\Query\Taxonomy\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Query\Taxonomy\V1\Contract\Query as QueryContract, 
    FlexWordpress\Package\Query\Taxonomy\V1\Factory\Query as QueryFactory,
    FlexWordpress\Package\Query\Taxonomy\V1\Concrete\Recent\Post\Query as RecentPostQuery,
};

class  Query extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
            [
                RecentPostQuery::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = QueryFactory::get($item);

            if ($item_instance instanceof QueryContract) 
            {
                $item_instance->setup_default_args();
            }
        }
    }
}