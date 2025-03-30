<?php
namespace Ababilithub\FlexWordpress\Package\Asset\V1\Mixin;

if ( ! trait_exists(__NAMESPACE__.'\Asset' ) ) :

    trait Asset
    {
        public function get_url($url_suffix = null)
        {
            // Detect where the package is loaded from
            $reflection = new \ReflectionClass(__CLASS__);
            $package_dir = dirname($reflection->getFileName());
            $package_dir_with_file_name = $reflection->getFileName();
            // Convert to a URL
            //$plugin_dir = plugin_dir_url(dirname($package_dir, 2));
            $plugin_dir_url = plugin_dir_url($package_dir_with_file_name);
        
            // Point to the correct directory url
            //return $plugin_dir . 'vendor/ababilitworld/flex-pagination-by-ababilitworld/src/Package/';
            return $plugin_dir_url . $url_suffix;
        }
    }

endif;
