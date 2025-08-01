<?php 
namespace Ababilithub\FlexAuthorization\Package\Plugin\User\Item\V1\Contract;
interface Item 
{
    public function get_all_items(): array;
    public function get_user_items(int $user_id): array;
    public function get_item_type(): string;
    public function get_item_name(): string;
}