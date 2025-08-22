<?php
namespace Ababilithub\FlexWordpress\Package\Notice\V1\Base;

use Ababilithub\{
    FlexWordpress\Package\Notice\V1\Contract\Notice as NoticeContract
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
};

abstract class Notice implements NoticeContract
{
    public $notice_board;
    
    public function __construct(array $data = [])
    {
        $this->init($data);
    }

    abstract public function init(array $data = []) : static;
    abstract public function render() : void;
    
}