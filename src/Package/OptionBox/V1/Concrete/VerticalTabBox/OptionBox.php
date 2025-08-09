<?php
namespace Ababilithub\FlexWordpress\Package\OptionBox\V1\Concrete\VerticalTabBox;

use Ababilithub\{
    FlexWordpress\Package\OptionBox\V1\Base\OptionBox as BaseOptionBox
};

use const Ababilithub\{
    VerticalTab\PLUGIN_PRE_HYPH,
    VerticalTab\PLUGIN_PRE_UNDS,
};

class OptionBox extends BaseOptionBox 
{
    public function init(array $data = []) : static
    {
        $this->id = PLUGIN_PRE_HYPH.'-'.'vertical-tab-options';
        $this->title = $data['title']??'Attributes';

        return $this;
    }

    public function render(): void
    {
        ?>
        <div class="fpba">
            <div class="meta-box">
                <div class="app-container">
                    <form method="post" action="">
                        <?php wp_nonce_field($this->id.'_nonce_action'); ?>
                        <input type="hidden" name="option_page" value="<?php echo esc_attr($this->id); ?>">
                        
                        <div class="vertical-tabs">
                            <div class="tabs-header">
                                <button class="toggle-tabs" id="toggleTabs">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="tabs-title"><?php echo $this->title;?></span>
                            </div>
                            <ul class="tab-items">
                                <?php do_action($this->id.'_'.'tab_item'); ?>
                            </ul>
                        </div>
                        <main class="content-area">
                            <?php do_action($this->id.'_'.'tab_content'); ?>
                        </main>
                        <?php submit_button(__('Save Settings', 'text-domain')); ?>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
}