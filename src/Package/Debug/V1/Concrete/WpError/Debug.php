<?php
namespace Ababilithub\FlexWordpress\Package\Debug\V1\Concrete\WpError;

use Ababilithub\{
    FlexWordpress\Package\Debug\V1\Base\Debug as BaseDebug
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
};

class Debug extends BaseDebug
{
    public function init(array $data = []): static
    {
        return $this;
    }
    
    public function render() : void
    {

    }
    
}