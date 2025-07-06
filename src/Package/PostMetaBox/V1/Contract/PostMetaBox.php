<?php 
namespace Ababilithub\FlexWordpress\Package\PostMetaBox\V1\Contract;

interface PostMetaBox
{
    public function init(): void;
    public function register(): void;
    public function render(): void; 
}