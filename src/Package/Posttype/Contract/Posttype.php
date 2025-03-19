<?php 
namespace Ababilitworld\FlexWordpress\Package\Posttype\Contract;

interface Posttype 
{
    public function init(array $data): void;
}