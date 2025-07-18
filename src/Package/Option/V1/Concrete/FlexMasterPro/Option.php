<?php
namespace Ababilithub\FlexWordpress\Package\Option\V1\Concrete\FlexMasterPro;

use Ababilithub\{
    FlexWordpress\Package\Option\V1\Base\Option as BaseOption
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
};

class Option extends BaseOption
{
    public function init(array $data = []) : static
    {
        $this->id = PLUGIN_PRE_HYPH.'-'.$this->posttype.'-'.'meta-box';
        $this->title = esc_html__(' Attributes : ', 'flex-eland') . get_the_title(get_the_ID());

        return $this;
    }

    public function render(): void
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