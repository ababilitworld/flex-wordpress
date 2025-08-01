<?php 
namespace Ababilithub\FlexAuthorization\Package\Plugin\User\Item\V1\Base;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexAuthorization\Package\Plugin\User\Item\V1\Contract\Item as ItemContract
};
abstract class Item implements ItemContract 
{
    protected $item_name;
    protected $item_type;
    protected $meta_prefix = 'user_assigned_items';

    public function __construct(string $item_name) 
    {
        $this->item_name = $item_name;
    }

    public function get_user_items(int $user_id): array 
    {
        $items = get_user_meta($user_id, $this->get_meta_key(), true);
        return is_array($items) ? $items : [];
    }

    public function get_item_type(): string 
    {
        return $this->item_type;
    }

    public function get_item_name(): string 
    {
        return $this->item_name;
    }

    protected function get_meta_key(): string 
    {
        return $this->meta_prefix . '_' . $this->item_type . '_' . $this->item_name;
    }

    protected function format_item($id, $label): array 
    {
        return [
            'value' => $id,
            'label' => $label
        ];
    }
}