<?php 
namespace Ababilithub\FlexWordpress\Package\Posttype\V1\Contract;

interface Posttype 
{
    public function init(array $data): void;
    public function register(): void;
}