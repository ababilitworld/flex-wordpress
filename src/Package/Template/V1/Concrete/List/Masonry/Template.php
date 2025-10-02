<?php
namespace Ababilithub\FlexWordpress\Package\Template\V1\Concrete\List\Masonry;

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
        $this->asset_base_prefix = 'ababilithub-template-list-masonry';
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
            $this->asset_base_prefix.'-animation-style', 
            $this->asset_base_url.'Appearence/Animation/Css/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'-theme-forest-green-style', 
            $this->asset_base_url.'Appearence/Theme/Css/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'-layout-masonry-style', 
            $this->asset_base_url.'Appearence/Layout/Css/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'-component-header-style', 
            $this->asset_base_url.'Appearence/Component/Header/Css/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'-component-content-style', 
            $this->asset_base_url.'Appearence/Component/Content/Css/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'-component-screen-style', 
            $this->asset_base_url.'Appearence/Component/Screen/Css/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'-component-h3-style', 
            $this->asset_base_url.'Appearence/Component/H3/Css/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'-component-footer-style', 
            $this->asset_base_url.'Appearence/Component/Footer/Css/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'-component-button-style', 
            $this->asset_base_url.'Appearence/Component/Button/Css/Style.css',
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

        <div class="ababilithub">
            <div class="theme forest-green">
                <div class="layout masonry">
                    <?php 
                        // Default Thumbnail Image Url
                        $default_thumbnail_image_url = home_url('/wp-content/uploads/flex-image/flex-image-placeholder.png');
                    
                        foreach ($items as $item) 
                        {
                            $thumbnail_image_url = '';
                            if (!empty($item->thumbnail_id) && wp_attachment_is_image($item->thumbnail_id)) 
                            {
                                $thumbnail_image_url = wp_get_attachment_url($item->thumbnail_id);
                            }
                            else
                            {
                                $thumbnail_image_url = $default_thumbnail_image_url;
                            }
                            
                    ?>
                        <div class="layout-item" data-layout-item="<?php echo esc_attr($item->data);?>">
                            <div class="header">
                                <h3><?php //echo esc_html($item->name);?></h3>
                            </div>
                            <div class="content">
                                <h3><?php echo esc_html($item->name);?></h3>
                                <img src="<?php echo esc_url($thumbnail_image_url);?>" alt="<?php echo esc_attr($item->name);?>">
                            </div>
                            <div class="footer">
                                <a href="<?php echo esc_attr($item->url); ?>" class="fa-view-btn">View Details</a>
                            </div>
                        </div>
                
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}