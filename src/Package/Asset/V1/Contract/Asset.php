<?php 
namespace Ababilithub\FlexWordpress\Package\Asset\V1\Contract;

interface Asset
{
    public function init(array $data = []): static;
    public function register(): void;
}