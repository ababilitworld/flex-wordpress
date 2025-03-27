<?php 
namespace Ababilithub\FlexWordpress\Package\Posttype\Contract;

interface Posttype 
{
    public function init(array $data): static;
    public function register(): void;
}