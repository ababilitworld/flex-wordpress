<?php 
namespace Ababilithub\FlexWordpress\Package\Postmeta\V1\Contract;

interface Postmeta
{
    public function init(): void;
    public function register(): void;
    public function save($post_id);
     
}