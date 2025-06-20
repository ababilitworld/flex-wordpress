<?php

namespace Ababilithub\FlexWordpress\Package\Shortcode\V1\Concrete\StaticFilter;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Shortcode\V1\Base\Shortcode as BaseShortcode,
};

class Shortcode extends BaseShortcode
{
    protected function get_tag(): string 
    {
        return 'ttbm_top_filter_static';
    }

    public function render(array $attributes): string 
    {
        $params = shortcode_atts($this->default_attribute(), $attributes);
        ob_start();
        do_action('ttbm_top_filter_static', $params);
        return ob_get_clean();
    }
}