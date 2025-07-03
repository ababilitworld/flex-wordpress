<?php
namespace Ababilithub\FlexWordpress\Package\Route\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();

interface Route
{
    public function init(): void;
    public function register(): void;
}