<?php
namespace Ababilithub\FlexWordpress\Package\ColorScheme\V1\Contract;

interface ColorScheme
{
    public function init(array $data = []): static;
}