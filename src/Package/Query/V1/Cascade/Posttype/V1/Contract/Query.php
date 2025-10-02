<?php 
namespace Ababilithub\FlexWordpress\Package\Query\V1\Cascade\Posttype\V1\Contract;

interface Query
{
    public function init(array $data=[]): static;
}