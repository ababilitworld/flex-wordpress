<?php
namespace Ababilithub\FlexWordpress\Package\PostMetaBox\V1\Base;

use Ababilithub\{
    FlexWordpress\Package\PostMetaBox\V1\Contract\PostMetaBox as PostMetaBoxContract
};

use const Ababilithub\{
    FlexELand\PLUGIN_PRE_HYPH,
    FlexELand\PLUGIN_PRE_UNDS,
};

abstract class PostMetaBox implements PostMetaBoxContract
{
    protected $posttype;
    protected $id;
    protected $title;

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
            $this->posttype // Specify the post type
        );
    }
    abstract public function render() : void;

    public function renderDefault(): void
    {
        $post_id = get_the_ID();
        ?>
        <div class="fpba">
            <div class="meta-box">
                <div class="app-container">
                    <div class="vertical-tabs">
                        <div class="tabs-header">
                            <button class="toggle-tabs" id="toggleTabs">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span class="tabs-title">Attributes</span>
                        </div>
                        <ul class="tab-items">
                            <?php do_action(PLUGIN_PRE_UNDS.'_'.$this->posttype.'_'.'meta_box_tab_item'); ?>
                        </ul>
                    </div>
                    <main class="content-area">
                        <?php do_action(PLUGIN_PRE_UNDS.'_'.$this->posttype.'_'.'meta_box_tab_content', $post_id); ?>
                    </main>
                </div>
            </div>
        </div>
        <?php
    }
}