<?php
namespace Ababilithub\FlexWordpress\Package\Repository\V1\Base;

use Ababilithub\{
    FlexWordpress\Package\Repository\V1\Contract\Repository as RepositoryContract
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
};

abstract class Repository implements RepositoryContract
{
    public $repository;
    
    public function __construct(array $data = [])
    {
        $this->init($data);
    }

    abstract public function init(array $data = []) : static;
    abstract public function get() : mixed;
    abstract public function update() : mixed;
    abstract public function delete() : mixed;
    
}