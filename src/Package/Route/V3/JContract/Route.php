<?php
namespace Ababilithub\FlexWordpress\Package\Route\V3\JContract;

interface Route 
{
    public function register(): void;
    public function getPath(): string;
    public function getHandler(): \Closure;
    public function getMethods(): array;
}