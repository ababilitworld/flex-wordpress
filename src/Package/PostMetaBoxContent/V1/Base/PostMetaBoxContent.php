<?php
namespace Ababilithub\FlexWordpress\Package\PostMetaBoxContent\V1\Base;

use Ababilithub\{
    FlexWordpress\Package\PostMetaBoxContent\V1\Contract\PostMetaBoxContent as PostMetaBoxContentContract
};

abstract class PostMetaBoxContent implements PostMetaBoxContentContract
{
    protected $posttype;
    protected $post_id;
    protected $tab_item_id;
    protected $tab_item_label;
    protected $tab_item_icon;
    protected $tab_item_status;    

    public function __construct()
    {
        $this->init();
    }

    abstract public function init() : void;


    public function register() : void
    {
        //
    }

    abstract public function render():void;
    abstract public function save($post_id, $post):void;

    public function tab_item() : void
    {
        ?>
        <li class="tab-item <?php echo esc_attr($this->tab_item_status);?>" data-tab="<?php echo esc_attr($this->tab_item_id);?>">
            <span href="#" class="tab-link">
                <i class="<?php echo esc_attr($this->tab_item_icon);?>"></i>
                <span class="tab-text"><?php echo esc_html($this->tab_item_label);?></span>
            </span>
        </li>
        <?php
    }

    public function tab_content() : void
    {
        ?>
        <div class="tab-content <?php echo esc_attr($this->tab_item_status);?>" id="<?php echo esc_attr($this->tab_item_id);?>">
            <h3><?php echo esc_html($this->tab_item_label);?></h3>
            <?php $this->render(); ?>
        </div>
        <?php
    }
}