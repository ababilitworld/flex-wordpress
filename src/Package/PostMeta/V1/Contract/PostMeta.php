<?php 
namespace Ababilithub\FlexWordpress\Package\PostMeta\V1\Contract;

interface PostMeta
{ 
    public function init(): void;
    public function register(): void;
    public function save($post_id);
     
}