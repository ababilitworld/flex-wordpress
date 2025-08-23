<?php
namespace Ababilithub\FlexWordpress\Package\ColorScheme\V1\Mixin;

use Ababilithub\{
    FlexWordpress\Package\ColorScheme\V1\Utility\ColorScheme as ColorSchemeUtility
};

trait ColorScheme 
{
    /**
     * Validates an array of colors.
     */
    protected function validate_color_array(array $colors): array 
    {
        $validated = [];
        foreach ($colors as $key => $value) 
        {
            if ($this->is_valid_hex_color($key,$value)) 
            {
                $validated[sanitize_key($key)] = sanitize_hex_color($value);
            }
        }
        return $validated;
    }
    
    /**
     * Checks if a string is a valid HEX color.
     */
    protected function is_valid_hex_color(string $key, string $color): bool 
    {
        $state = (bool) preg_match('/^#([a-f0-9]{6}|[a-f0-9]{3})$/i', $color);
        if (!$state)
        {
            throw new \InvalidArgumentException(
                sprintf('%s must be a valid hex color, "%s" given', $key, $color)
            );
        }
        return $state;
    }
    
    /**
     * Ensures a contrast ratio meets WCAG AA standards.
     */
    protected function has_sufficient_contrast_ratio(string $color1, string $color2, float $minRatio = 4.5): bool 
    {
        // You could use the static method from our Utility Class here!
        $ratio = ColorSchemeUtility::get_contrast_ratio($color1, $color2);
        return $ratio >= $minRatio;
    }    
}