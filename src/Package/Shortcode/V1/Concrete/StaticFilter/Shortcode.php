<?php

namespace Ababilithub\FlexWordpress\Package\Shortcode\V1\Concrete\StaticFilter;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Shortcode\V1\Base\Shortcode as BaseShortcode
};

use const Ababilithub\{
    FlexELand\PLUGIN_PRE_UNDS,
};

class Shortcode extends BaseShortcode
{
    protected function set_tag(): void
    {
        $this->tag = PLUGIN_PRE_UNDS.'_top_filter';
    }

    protected function init(): void
    {
        $this->defaultAttributes = [
            'style' => 'grid',
            'column' => '3',
            'pagination' => 'yes',
            'show' => '10',
            'sort' => 'ASC',
            'sort_by' => 'id',
            'status' => 'published',
            'pagination-style' => 'load_more',
            'search-filter' => 'yes',
            'sidebar-filter' => 'yes',
            'shuffle' => 'no',
        ];
    }

    public function render(array $attributes): string
    {
        $this->set_attributes($attributes);
        $params = $this->get_attributes();

        ob_start();
        do_action(PLUGIN_PRE_UNDS.'_top_filter', $params);
        ?>
        <div>Bismillah</div>
        <?php
        return ob_get_clean();
    }
}
