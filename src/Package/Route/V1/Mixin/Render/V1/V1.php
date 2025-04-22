<?php

namespace Ababilithub\FlexWordpress\Package\Route\V1\Mixin\Render\V1;

trait V1 
{
    public function renderTemplate(string $type, string $template): void 
    {
        switch ($type) 
        {
            case 'file':
                if (file_exists($template)) include $template;
                break;
            case 'html':
                echo $template;
                break;
            case 'filter':
                echo apply_filters($template, '');
                break;
            default:
                echo __('Invalid template type.', 'your-plugin');
        }
    }
}
