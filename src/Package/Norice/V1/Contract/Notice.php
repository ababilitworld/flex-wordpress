<?php 
namespace Ababilithub\FlexWordpress\Package\Notice\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Notice
{
    public function init(array $data = []): static;     
}