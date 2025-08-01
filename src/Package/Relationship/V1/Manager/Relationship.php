<?php
namespace Ababilithub\FlexWordpress\Package\Relationship\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Relationship\V1\Contract\Relationship as RelationshipContract, 
    FlexWordpress\Package\Relationship\V1\Factory\Relationship as RelationshipFactory,
    FlexWordpress\Package\Relationship\V1\Concrete\PolymorphicRelationship\Relationship as PolymorphicRelationshipRelationship,
};

class  Relationship extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
                [
                PolymorphicRelationshipRelationship::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = RelationshipFactory::get($item);

            if ($item_instance instanceof RelationshipContract) 
            {
                $item_instance->up();
            }
        }
    }
}