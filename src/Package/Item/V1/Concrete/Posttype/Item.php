<?php 
namespace Ababilithub\FlexAuthorization\Package\Plugin\User\Item\V1\Concrete\Posttype;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexAuthorization\Package\Plugin\User\Item\V1\Base\Item as BaseItem
};

class Item extends BaseItem 
{
    protected $item_type = 'post_type';

    public function get_all_items(): array 
    {
        $posts = get_posts([
            'post_type' => $this->item_name,
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ]);
        
        return array_map(function($post): array {
            return $this->format_item($post->ID, $post->post_title);
        }, $posts);
    }
}