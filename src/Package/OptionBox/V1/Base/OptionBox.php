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
    protected $id;
    protected $title;

    public function __construct()
    {
        $this->init();
    }

    abstract public function init(array $data = []) : static;

    public function register() : void 
    {
        
    }
    abstract public function render() : void;

    /**
     * Generate consistent hook names
     */
    protected function getTabHookName(string $suffix = ''): string
    {
        return PLUGIN_PRE_UNDS . '_options_' . $this->id . '_' . $suffix;
    }

    public function renderDefault(): void
    {
        ?>
        <div class="fpba">
            <div class="meta-box">
                <div class="app-container">
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
                </div>
            </div>
        </div>
        <?php
    }
}