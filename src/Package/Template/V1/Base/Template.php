<?php
namespace Ababilithub\FlexWordpress\Package\Template\V1\Base;

use Ababilithub\{
    FlexWordpress\Package\Template\V1\Contract\Template as TemplateContract
};

abstract class Template implements TemplateContract
{   
    protected $asset_base_url;
    protected $asset_base_prefix;

    public function __construct(array $data = [])
    {
        $this->init($data);
    }

    abstract public function init(array $data = []) : static;
    
    abstract public function render() : bool|string;

}