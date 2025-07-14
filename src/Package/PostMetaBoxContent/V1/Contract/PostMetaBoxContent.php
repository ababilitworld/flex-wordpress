<?php 
namespace Ababilithub\FlexWordpress\Package\PostMetaBoxContent\V1\Contract;

interface PostMetaBoxContent
{
    public function init(array $data = []) : static;
    public function register(): void;
    public function render(): void; 
}