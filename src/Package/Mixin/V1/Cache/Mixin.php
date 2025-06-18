<?php
namespace Ababilithub\FlexWordpress\Package\Mixin\V1\Cache;

use Ababilithub\{
    FlexWordpress\Package\Asset\V1\Mixin\Asset as WpMixinAsset,
};

trait Mixin
{
    function clear_template_cache() 
    {
        // WordPress cache
        wp_cache_flush();
        
        // Transients
        global $wpdb;
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%'");
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_site_transient_%'");
        
        // OPcache
        if (function_exists('opcache_reset')) 
        {
            opcache_reset();
        }
        
        // Caching plugins
        if (function_exists('rocket_clean_domain')) 
        {
            rocket_clean_domain();
        }

        if (function_exists('w3tc_flush_all')) 
        {
            w3tc_flush_all();
        }
        
        error_log('Template caches cleared',300);
    }
}
