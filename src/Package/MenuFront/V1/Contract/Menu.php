<?php 
namespace Ababilithub\FlexWordpress\Package\MenuFront\V1\Contract;

interface Menu
{
    public function init(array $data =[]):static;
    public function register(): void;
    public function callback():void;
}