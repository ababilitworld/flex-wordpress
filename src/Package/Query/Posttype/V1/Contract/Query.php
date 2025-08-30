<?php 
namespace Ababilithub\FlexWordpress\Package\Query\Posttype\V1\Contract;

interface Query
{
    public function init(array $data=[]): static;
}