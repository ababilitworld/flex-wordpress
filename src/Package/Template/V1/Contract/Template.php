<?php
namespace Ababilithub\FlexWordpress\Package\Template\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Template
{
    public function init(array $data = []): static;
}