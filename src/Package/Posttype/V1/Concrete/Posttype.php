<?php
namespace Ababilithub\FlexWordpress\Package\Posttype\V1\Concrete;

(defined('ABSPATH') && defined('WPINC')) || die();

use AbabilIthub\{
    FlexPhp\Package\Mixin\Standard\V1\V1 as StandardMixin,
    FlexWordpress\Package\Posttype\V1\Base\Posttype as BasePosttype,
};

if (!class_exists(__NAMESPACE__ . '\\Posttype')) 
{
    class Posttype extends BasePosttype
    {
        use StandardMixin;

        public function __construct(array $config = [])
        {
            parent::__construct($config);
            $this->setDefaultConfig();
            $this->setDefaultLabels();
            $this->setDefaultArgs();
        }

        /**
         * Register the required hooks for post type.
         */
        protected function registerHooks(): void
        {
            $this->disableGutenberg([$this->posttype]);

            $this->addPostTypeSupport($this->posttype, ['thumbnail']);

            $this->setTitlePlaceholder($this->posttype, __('Enter Posttype Title', $this->textdomain));

            $this->customizePostUpdatedMessages($this->posttype, [
                1 => __('Posttype updated.', $this->textdomain),
                6 => __('Posttype published.', $this->textdomain),
            ]);

            add_action('init', function () {
                $this->registerTaxonomies();
                $this->registerMetaFields();
            });
        }

        /**
         * register custom taxonomies
         */
        public function registerTaxonomies(): void
        {
            add_action('init', function () {
                register_taxonomy('posttype_category', [$this->posttype], [
                    'label'        => __('Posttype Categories', $this->textdomain),
                    'rewrite'      => ['slug' => 'posttype-category'],
                    'hierarchical' => true,
                    'show_in_rest' => true,
                ]);

                register_taxonomy('posttype_tag', [$this->posttype], [
                    'label'        => __('Posttype Tags', $this->textdomain),
                    'rewrite'      => ['slug' => 'posttype-tag'],
                    'hierarchical' => false,
                    'show_in_rest' => true,
                ]);
            });
        }

        /**
         * Register Custom meta fields
         */
        public function registerMetaFields(): void
        {
            add_action('add_meta_boxes', function () {
                add_meta_box(
                    'posttype_post_meta',
                    __('Posttype Details', $this->textdomain),
                    [$this, 'renderMetaBox'],
                    $this->posttype,
                    'side'
                );
            });

            add_action('save_post', [$this, 'saveMetaFields']);
        }

        /**
         * Render meta box
         */
        public function renderMetaBox($post): void
        {
            $subtitle = get_post_meta($post->ID, '_posttype_post_subtitle', true);
            ?>
            <label for="posttype_post_subtitle"><?php _e('Subtitle', $this->textdomain); ?></label>
            <input type="text" id="posttype_post_subtitle" name="posttype_post_subtitle" value="<?php echo esc_attr($subtitle); ?>" class="widefat">
            <?php
        }

        /**
         * Save meta fields
         */
        public function saveMetaFields($post_id): void
        {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
            if (!isset($_POST['posttype_post_subtitle'])) return;

            update_post_meta($post_id, '_posttype_post_subtitle', sanitize_text_field($_POST['posttype_post_subtitle']));
        }
    
    }
}
