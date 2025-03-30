<?php
namespace Ababilithub\FlexWordpress\Package\Mixin\Standard\V1;

use Ababilithub\{
    FlexWordpress\Package\Asset\V1\Mixin\Asset as WpMixinAsset,
};

if ( ! trait_exists(__NAMESPACE__.'\V1' ) ) :

    trait V1
    {
        use WpMixinAsset;
    }

endif;


