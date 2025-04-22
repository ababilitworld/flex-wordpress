<?php 
namespace Ababilithub\FlexWordpress\Package\Route\V1\Contract\Handler;

interface Handle
{
    public function handle(RouteInterface $route): void;
}