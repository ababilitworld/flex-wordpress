<?php 
namespace Ababilithub\FlexWordpress\Package\Debug\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Debug
{
    public function init(array $data = []): static;     
}