<?php
namespace Ababilithub\FlexWordpress\Package\Debug\V1\Base;

use Ababilithub\{
    FlexWordpress\Package\Debug\V1\Contract\Debug as DebugContract
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
};

abstract class Debug implements DebugContract
{
    public $debugger;
    
    public function __construct(array $data = [])
    {
        $this->init($data);
    }

    abstract public function init(array $data = []) : static;
    abstract public function render() : void;
    
}