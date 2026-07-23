<?php
namespace Ababilithub\FlexWordpress\Package\PostMetaBox\V1\Base;

use Ababilithub\{
    FlexWordpress\Package\PostMetaBox\V1\Contract\PostMetaBox as PostMetaBoxContract
};

abstract class PostMetaBox implements PostMetaBoxContract
{
    protected $posttype;
    protected $id;
    protected $title;
    protected $hook_prefix = '';
    protected $content_hook_suffix = 'meta_box_tab_item_content';
    protected $tab_title = 'Attributes';
    protected $tab_type = 'vertical';
    protected $tab_size = 'medium';
    protected $tab_color = 'aurora';

    public function __construct()
    {
        $this->init();
    }

    abstract public function init() : void;

    public function register() : void 
    {
        add_meta_box(
            $this->id, 
            $this->title,
            [$this, 'render'],
            $this->posttype, // Post type
            'normal',       // Context
            'high'          // Priority
        );
    }
    abstract public function render() : void;

    protected function set_tab_style(array $style = []): void
    {
        $types = ['horizontal', 'vertical'];
        $sizes = ['small', 'medium', 'large', 'xl', 'xxl', 'xxxl'];
        $colors = ['aurora', 'ocean', 'sunset', 'amethyst', 'emerald', 'midnight'];

        $type = $style['type'] ?? $this->tab_type;
        $size = $style['size'] ?? $this->tab_size;
        $color = $style['color'] ?? $this->tab_color;

        $this->tab_type = in_array($type, $types, true) ? $type : 'vertical';
        $this->tab_size = in_array($size, $sizes, true) ? $size : 'medium';
        $this->tab_color = in_array($color, $colors, true) ? $color : 'aurora';
        $this->tab_title = sanitize_text_field($style['title'] ?? $this->tab_title);
    }

    public function renderDefault(): void
    {
        $post_id = get_the_ID();
        $component_classes = sprintf(
            'faih-tab faih-tab--%s faih-tab--%s faih-tab--%s',
            esc_attr($this->tab_type),
            esc_attr($this->tab_size),
            esc_attr($this->tab_color)
        );
        $hook_prefix = $this->hook_prefix ?: str_replace('-', '_', sanitize_key($this->id));
        ?>
        <div class="<?php echo esc_attr($component_classes); ?>" data-faih-tab>
            <div class="faih-tab__navigation">
                <div class="faih-tab__header">
                    <span class="faih-tab__title"><?php echo esc_html($this->tab_title); ?></span>
                </div>
                <ul class="faih-tab__items" role="tablist">
                    <?php do_action($hook_prefix.'_'.$this->posttype.'_meta_box_tab_item'); ?>
                </ul>
            </div>
            <main class="faih-tab__content-area">
                <?php do_action($hook_prefix.'_'.$this->posttype.'_'.$this->content_hook_suffix, $post_id); ?>
            </main>
        </div>
        <?php
    }
}
