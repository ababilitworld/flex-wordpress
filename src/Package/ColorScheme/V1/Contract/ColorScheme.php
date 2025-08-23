<?php
namespace Ababilithub\FlexPhp\Package\ColorScheme\V1\Contract;

interface ColorScheme
{
    public function init(array $data = []): static;
}