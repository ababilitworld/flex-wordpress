<?php
namespace Ababilithub\FlexWordpress\Package\Route\V1\Controller\V1\Base;

use Ababilithub\FlexWordpress\Package\Route\V1\Contract\Presentation\Render\V1\V1 as RenderableInterface;
abstract class Controller 
{
    protected RenderableInterface $renderer;

    public function __construct(RenderableInterface $renderer) 
    {
        $this->renderer = $renderer;
    }
}