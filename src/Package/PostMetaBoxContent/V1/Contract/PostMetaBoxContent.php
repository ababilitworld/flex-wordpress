<?php 
namespace Ababilithub\FlexWordpress\Package\PostMetaBoxContent\V1\Contract;

interface PostMetaBoxContent
{
    public function init(): void;
    public function register(): void;
    public function render(): void; 
}