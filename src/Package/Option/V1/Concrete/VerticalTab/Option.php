<?php
namespace Ababilithub\FlexWordpress\Package\Option\V1\Concrete\VerticalTab;

use Ababilithub\{
    FlexWordpress\Package\Option\V1\Base\Option as BaseOption
};

use const Ababilithub\{
    VerticalTab\PLUGIN_PRE_HYPH,
    VerticalTab\PLUGIN_PRE_UNDS,
};

class Option extends BaseOption 
{
    public function init(array $data = []) : static
    {
        $this->id = $data['tab_id']??'-'.'vertical-tab-options';
        $this->title = $data['title']??'Attributes';

        return $this;
    }

    public function render(): void
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