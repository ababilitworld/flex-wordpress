<?php
namespace Ababilithub\FlexWordpress\Package\Mixin\V1\Standard;

use Ababilithub\{
    FlexWordpress\Package\Asset\V1\Mixin\Asset as WpMixinAsset,
};

if ( ! trait_exists(__NAMESPACE__.'\Mixin' ) ) :

    trait Mixin
    {
        use WpMixinAsset;
    }

endif;


