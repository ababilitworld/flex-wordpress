<?php
namespace Ababilithub\FlexWordpress\Package\ColorScheme\V1\Utility;

class ColorScheme 
{
    
    /**
     * Converts a HEX color to RGB array.
     */
    public static function hex_to_rgb(string $hex): array 
    {
        $hex = str_replace('#', '', $hex);
        
        if(strlen($hex) == 3) 
        {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } 
        else
        {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        return ['r' => $r, 'g' => $g, 'b' => $b];
    }
    
    /**
     * Calculates the contrast ratio between two colors for accessibility (WCAG).
     */
    public static function get_contrast_ratio(string $hex1, string $hex2): float 
    {
        $rgb1 = self::hex_to_rgb($hex1);
        $rgb2 = self::hex_to_rgb($hex2);
        
        $l1 = self::get_relative_luminance($rgb1['r'], $rgb1['g'], $rgb1['b']);
        $l2 = self::get_relative_luminance($rgb2['r'], $rgb2['g'], $rgb2['b']);
        
        return (max($l1, $l2) + 0.05) / (min($l1, $l2) + 0.05);
    }

    public static function get_relative_luminance(string $r, string $g, string $b): float
    {
        $r = $r <= 0.03928 ? $r / 12.92 : (($r + 0.055) / 1.055) ** 2.4;
        $g = $g <= 0.03928 ? $g / 12.92 : (($g + 0.055) / 1.055) ** 2.4;
        $b = $b <= 0.03928 ? $b / 12.92 : (($b + 0.055) / 1.055) ** 2.4;

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }
    
    /**
     * Checks if a color is valid (simple hex check).
     */
    public static function is_valid_hex_color(string $color): bool 
    {
        return (bool) preg_match('/^#([a-f0-9]{6}|[a-f0-9]{3})$/i', $color);
    }
    
    
}