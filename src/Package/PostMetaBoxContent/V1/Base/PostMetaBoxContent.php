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
    protected $tab_item_badge;

    public function __construct()
    {
        $this->init();
    }

    abstract public function init(array $data = []) : static;


    public function register() : void
    {
        //
    }

    abstract public function render():void;
    abstract public function save($post_id, $post, $update):void;

    public function tab_item() : void
    {
        ?>
        <li class="faih-tab__item <?php echo esc_attr($this->tab_item_status);?>" role="presentation">
            <button type="button" class="faih-tab__link" role="tab" data-tab="<?php echo esc_attr($this->tab_item_id);?>" aria-controls="<?php echo esc_attr($this->tab_item_id);?>" aria-selected="<?php echo $this->tab_item_status === 'active' ? 'true' : 'false'; ?>">
                <i class="faih-tab__icon <?php echo esc_attr($this->tab_item_icon);?>"></i>
                <span class="faih-tab__text"><?php echo esc_html($this->tab_item_label);?></span>
                <?php  if(!empty($this->tab_item_badge)) :?>
                <span class="faih-tab__badge"><?php echo esc_html($this->tab_item_badge);?></span>
                <?php  endif ?>
            </button>
        </li>
        <?php
    }

    public function tab_content() : void
    {
        ?>
        
        <div class="faih-tab__content <?php echo esc_attr($this->tab_item_status);?>" id="<?php echo esc_attr($this->tab_item_id);?>" role="tabpanel">
            <h3><?php echo esc_html($this->tab_item_label);?></h3>
            <?php $this->render(); ?>
        </div>
       
        <?php
    }
}
