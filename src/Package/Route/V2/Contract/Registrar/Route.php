<?php

namespace Ababilithub\FlexWordpress\Package\Route\V2\Contract\Registrar;

interface Route 
{
    public function add_route(string $pattern, string $method, callable $callback): self;
}