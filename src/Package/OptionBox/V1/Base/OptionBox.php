<?php
namespace Ababilithub\FlexWordpress\Package\OptionBox\V1\Base;

use Ababilithub\{
    FlexWordpress\Package\OptionBox\V1\Contract\OptionBox as OptionBoxContract
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
};

abstract class OptionBox implements OptionBoxContract
{
    public $id;
    protected $title;

    public function __construct(array $data = [])
    {
        $this->init($data);
    }

    abstract public function init(array $data = []) : static;

    public function register() : void 
    {
        
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