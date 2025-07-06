<?php
namespace Ababilithub\FlexWordpress\Package\Taxonomy\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Taxonomy\V1\Contract\Taxonomy as TaxonomyContract, 
    FlexWordpress\Package\Taxonomy\V1\Factory\Taxonomy as TaxonomyFactory,
    FlexWordpress\Package\Taxonomy\V1\Concrete\StaticFilter\Taxonomy as StaticFilterTaxonomy,
};

class Taxonomy extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
                [
                //StaticFilterTaxonomy::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = TaxonomyFactory::get($item);

            if ($item_instance instanceof TaxonomyContract) 
            {
                $item_instance->register();
            }
        }
    }
}