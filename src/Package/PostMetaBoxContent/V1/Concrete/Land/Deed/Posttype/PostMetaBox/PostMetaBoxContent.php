<?php
namespace Ababilithub\FlexWordpress\Package\PostMetaBoxContent\V1\Concrete\Land\Deed\Posttype\PostMetaBox;

use Ababilithub\{
    FlexWordpress\Package\PostMeta\V1\Mixin\PostMeta as PostMetaMixin,
    FlexWordpress\Package\PostMetaBoxContent\V1\Base\PostMetaBoxContent as BasePostMetaBoxContent
};

use const Ababilithub\{
    FlexELand\PLUGIN_PRE_HYPH,
    FlexELand\PLUGIN_PRE_UNDS,
    FlexELand\Package\Plugin\Posttype\Land\Deed\POSTTYPE,
};

class PostMetaBoxContent extends BasePostMetaBoxContent
{
    use PostMetaMixin;
    public function init():void
    {
        $this->posttype = POSTTYPE;
        $this->post_id = get_the_ID();
        $this->tab_item_id = $this->posttype.'-'.'general-settings';
        $this->tab_item_label = esc_html__('General Settings');
        $this->tab_item_icon = 'fas fa-home';
        $this->tab_item_status = 'active';

        $this->init_service();
        $this->init_hook();
    }

    public function init_service():void
    {

    }

    public function init_hook() : void
    {
        add_action(PLUGIN_PRE_UNDS.'_'.$this->posttype.'_'.'meta_box_tab_item',[$this,'tab_item']);
        add_action(PLUGIN_PRE_UNDS.'_'.$this->posttype.'_'.'meta_box_tab_content', [$this,'tab_content']);
        add_action('save_post', [$this, 'save'], 10, 2);
    }

    public function render() : void
    {

    }

    private function get_meta_values($post_id) 
    {
        return [
            'deed_date' => get_post_meta($post_id, 'deed-date', true) ?: '',
            'deed_number' => get_post_meta($post_id, 'deed-number', true) ?: '',
            'plot_number' => get_post_meta($post_id, 'plot-number', true) ?: '',
            'land_quantity' => get_post_meta($post_id, 'land-quantity', true) ?: '',
            'deed_thumbnail_image' => get_post_meta($post_id, 'deed-thumbnail-image', true) ?: '',
            'images' => get_post_meta($post_id, 'deed-images', true) ?: [],
            'docs' => get_post_meta($post_id, 'deed-attachments', true) ?: []
        ];
    }

    public function save($post_id, $post) : void 
    {
        if (!$this->is_valid_save($post_id, $post)) 
        {
            return;
        }

        $this->save_text_field($post_id,'deed-date',sanitize_text_field($_POST['deed-date']));
        $this->save_text_field($post_id,'deed-number',sanitize_text_field($_POST['deed-number']));
        $this->save_text_field($post_id,'plot-number',sanitize_text_field($_POST['plot-number']));
        $this->save_text_field($post_id,'land-quantity',sanitize_text_field($_POST['land-quantity']));
        $this->save_thumbnail_image($post_id,'deed-thumbnail-image',absint($_POST['deed-thumbnail-image']));
        $this->save_multiple_images($post_id,'deed-images',array_map('sanitize_text_field',$_POST['deed-images']));
        $this->save_multiple_attachments($post_id,'deed-attachments',array_map('sanitize_text_field',$_POST['deed-attachments']));
    }
}