<?php
namespace Ababilithub\FlexWordpress\Package\Route\V1\Controller\V1\Concrete;

use Ababilithub\{
    FlexWordpress\Package\Route\V1\Controller\V1\Base\Controller as BaseController
};
class Controller extends BaseController 
{
    public function show(): void 
    {
        do_action('example_route_start');
        $this->renderer->render('file', plugin_dir_path(__FILE__) . '/../templates/example-template.php');
    }
}