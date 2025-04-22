<?php 
namespace Ababilithub\FlexWordpress\Package\Route\V1\Contract\Presentation\Render\V1;

interface V1
{
    public function render(string $type, string $template): void;
}
