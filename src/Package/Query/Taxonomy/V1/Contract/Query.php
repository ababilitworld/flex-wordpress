<?php 
namespace Ababilithub\FlexWordpress\Package\Query\Taxonomy\V1\Contract;

interface Query
{
    public function init(array $data=[]): static;
}