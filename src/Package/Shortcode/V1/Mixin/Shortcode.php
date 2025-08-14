<?php
namespace Ababilithub\FlexWordpress\Package\Shortcode\V1\Mixin;

(defined('ABSPATH') && defined('WPINC')) || exit();

trait Shortcode
{
    public function domsin_shortcode_list(array $atts = [] ): array
    {
        $domain = $atts['domain'];
        global $shortcode_tags;

        $shortcodes = $shortcode_tags;
        
        ksort($shortcodes);
        
        $shortcode_output = array();
        
        foreach ($shortcodes as $shortcode => $value) 
        {
            if(substr($shortcode, 0, strlen($domain)) === $domain)
            {
                $shortcode_output[] = $shortcode;
            }
        }
        
        return $shortcode_output;

    }

    public function format_shortcodes_as_string($shortcodes)
    {
        $string = '';
        $custom_shortcodes = array();				
        $count = count($shortcodes);
        if($count)
        {
            foreach($shortcodes as $key=>$shortcodelist)
            {
                if(is_array($shortcodelist['shortcodes']) && count($shortcodelist['shortcodes']))
                {
                    foreach($shortcodelist['shortcodes'] as $key=>$code)
                    {
                        $custom_shortcodes[$key] = $code;
                    }
                }							
            }

        }

        $custom_count = count($custom_shortcodes);

        if($custom_count)
        {
            foreach($custom_shortcodes as $key=>$custom_code)
            {
                if($custom_count > 1)
                {
                    $string.=" {".$key."} ,";
                }
                else
                {
                    $string.=" {".$key."}";
                }
                $custom_count--;
            }
        }
        
        return $string;
        
    }
}