<?php 
namespace Ababilithub\FlexAuthorization\Package\Plugin\User\Item\V1\Concrete\Taxonomy;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexAuthorization\Package\Plugin\User\Item\V1\Base\Item as BaseItem
};

class Item extends BaseItem 
{
    protected $item_type = 'taxonomy';

    public function get_all_items(): array {
        $terms = get_terms([
            'taxonomy' => $this->item_name,
            'hide_empty' => false
        ]);
        
        return array_map(function($term) {
            return $this->format_item($term->term_id, $term->name);
        }, $terms);
    }
}