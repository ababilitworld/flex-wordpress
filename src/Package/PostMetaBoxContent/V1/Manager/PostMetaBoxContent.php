<?php
namespace Ababilithub\FlexWordpress\Package\PostMetaBoxContent\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\PostMetaBoxContent\V1\Contract\PostMetaBoxContent as PostMetaBoxContentContract, 
    FlexWordpress\Package\PostMetaBoxContent\V1\Factory\PostMetaBoxContent as PostMetaBoxContentFactory,
    FlexWordpress\Package\PostMetaBoxContent\V1\Concrete\Land\Deed\Posttype\PostMetaBox\PostMetaBoxContent as LandDeedPostMetaBoxContent,
};

class  PostMetaBoxContent extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
                [
                LandDeedPostMetaBoxContent::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = PostMetaBoxContentFactory::get($item);

            if ($item_instance instanceof PostMetaBoxContentContract) 
            {
                $item_instance->register();
            }
        }
    }
}