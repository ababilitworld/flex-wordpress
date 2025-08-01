<?php
namespace Ababilithub\FlexWordpress\Package\Migration\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Migration\V1\Contract\Migration as MigrationContract, 
    FlexWordpress\Package\Migration\V1\Factory\Migration as MigrationFactory,
    FlexWordpress\Package\Migration\V1\Concrete\PolymorphicRelationship\Migration as PolymorphicRelationshipMigration,
};

class  Migration extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
                [
                PolymorphicRelationshipMigration::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = MigrationFactory::get($item);

            if ($item_instance instanceof MigrationContract) 
            {
                $item_instance->up();
            }
        }
    }
}