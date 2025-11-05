<?php
namespace Ababilithub\FlexWordpress\Package\Asset\V1\Base;

use Ababilithub\{
    FlexWordpress\Package\Asset\V1\Contract\Asset as AssetContract
};

abstract class Asset implements AssetContract
{    
    public function __construct(array $data = [])
    {
        $this->init($data);
    }

    abstract public function init(array $data = []) : static;
    abstract public function register(): void;    
}