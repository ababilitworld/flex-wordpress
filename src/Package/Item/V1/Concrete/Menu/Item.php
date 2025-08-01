<?php 
namespace Ababilithub\FlexAuthorization\Package\Plugin\User\Item\V1\Concrete\Menu;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexAuthorization\Package\Plugin\User\Item\V1\Base\Item as BaseItem
};

class Item extends BaseItem 
{
    protected $item_type = 'menu';

    public function get_all_items(): array 
    {
        $menu_items = wp_get_nav_menu_items($this->item_name);
        
        return array_map(function($item) {
            return $this->format_item($item->ID, $item->title);
        }, $menu_items ?: []);
    }

    public function filterItem(array $menu): array 
    {
        $userId = get_current_user_id();
        
        return array_filter($menu, function($item) use ($userId) {
            $menuSlug = $item[2] ?? '';
            return $this->isItemAllowed($userId, $menuSlug);
        });
    }

    private function isItemAllowed(int $userId, string $menuSlug): bool 
    {
        $requiredCap = $this->getItemCapability($menuSlug);
        return $requiredCap ? $this->auth->userCan($userId, $requiredCap) : true;
    }

    private function getItemCapability(string $menuSlug): ?string 
    {
        // Implementation from previous example
    }
}