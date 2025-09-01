<?php
namespace Ababilithub\FlexWordpress\Package\Template\V1\Concrete\List\PremiumCard;

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Mixin\V1\Standard\Mixin as StandardWpMixin,
    FlexWordpress\Package\Template\V1\Base\Template as BaseTemplate,
};

class Template extends BaseTemplate
{
    use StandardMixin, StandardWpMixin;

    public function init(array $data =[]) : static
    {
        $this->asset_base_url = $this->get_url('Asset/');
        $this->asset_base_prefix = 'ababilithub-template-list-premiumcard';
        $this->init_service();
        $this->init_hook();
        return $this;
    }

    public function init_service():void
    {

    }

    public function init_hook() : void
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-slider');

        wp_enqueue_style(
            $this->asset_base_prefix.'-style', 
            $this->asset_base_url.'Css/Style.css',
            array(), 
            time()
        );

        wp_enqueue_script(
            $this->asset_base_prefix.'-script', 
            $this->asset_base_url.'Js/Script.js',
            array('jquery', 'jquery-ui-slider'), 
            time(), 
            true
        );
        
        wp_localize_script(
            $this->asset_base_prefix.'-script', 
            $this->asset_base_prefix.'_template_localize', 
            array(
                'adminAjaxUrl' => admin_url('admin-ajax.php'),
                'ajaxNonce' => wp_create_nonce($this->asset_base_prefix.'_nonce'),
            )
        );
    }

    public function render($items = null) : bool|string
    {        
        if (empty($items)) 
        {
            return '<p>No items found to render. !!!</p>';
        }
        
        ob_start();
        ?>
        <div class="fa-deed-app">
            <!-- Search Panel -->
            <div class="fa-search-panel">
                <input type="text" class="fa-search-input" placeholder="Search deeds by location, type, or ID...">
                <button class="fa-search-btn">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>

            <div class="fa-deed-container">

                <!-- Main Content -->
                <main class="fa-deed-list-container">
                    <div class="fa-deed-list">
                        <?php 
                        // Default Thumbnail Image Url
                        $default_thumbnail_image_url = home_url('/wp-content/uploads/flex-image/flex-image-placeholder.png');
                        echo "<pre>";print_r($default_thumbnail_image_url);echo "</pre>";
                        foreach ($items as $item) 
                        {
                            unset($thumbnail_image_url);
                            $thumbnail_image_url = $item->thumbnail_id ? get_the_post_thumbnail_url($item->thumbnail_id, 'large') : $default_thumbnail_image_url;
                        ?>
                            <article class="fa-deed-card">
                                <div class="fa-deed-image" style="background-image: url('<?php echo esc_url($thumbnail_image_url); ?>')"></div>
                                <div class="fa-deed-content">
                                    <h3><?php echo esc_html($item->name); ?></h3>
                                    <div class="fa-deed-meta">
                                        <span><i class="fas fa-file-alt"></i> <?php echo esc_html('1'); ?></span>
                                    </div>
                                    <div class="fa-deed-footer">
                                        <a href="<?php the_permalink(); ?>" class="fa-view-btn">View Details</a>
                                    </div>
                                </div>
                            </article>
                        <?php } ?>
                    </div>
                </main>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}