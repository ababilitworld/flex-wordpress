<?php 
namespace Ababilithub\FlexWordpress\Package\Query\V1\Cascade\Taxonomy\V1\Contract;

interface Query
{
    public function init(array $data=[]): static;
}