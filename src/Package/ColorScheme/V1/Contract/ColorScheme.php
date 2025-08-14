<?php
namespace Ababilithub\FlexPhp\Package\ColorScheme\V1\Contract;

interface ColorScheme
{
    public function init(array $data = []): static;
    public function register(): void;
    public function calculateContrastRatio(): float;
    public function isAccessible(): bool;
    public function toCssVariables(): array;
}