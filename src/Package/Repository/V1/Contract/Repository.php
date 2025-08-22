<?php 
namespace Ababilithub\FlexWordpress\Package\Repository\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Repository
{
    public function init(array $data = []): static;     
}