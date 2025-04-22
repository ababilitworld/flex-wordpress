<?php 
namespace Ababilithub\FlexWordpress\Package\Route\V1\Contract\Route\V1;

interface V1
{
    public function getLabel(): string;
    public function getUrl(): string;
    public function getCapability(): string;
    public function getCallback(): callable;
}
