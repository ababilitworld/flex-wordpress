<?php

namespace Ababilithub\FlexWordpress\Package\Route\V2\Contract\Template;

interface Template 
{
    public function handle_template(string $default_template): string;
}