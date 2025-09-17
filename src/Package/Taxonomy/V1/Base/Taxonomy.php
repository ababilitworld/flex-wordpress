<?php
namespace Ababilithub\FlexWordpress\Package\Taxonomy\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Taxonomy\V1\Contract\Taxonomy as TaxonomyContract,
};

abstract class Taxonomy implements TaxonomyContract
{    
    protected $taxonomy;
    protected $slug;
    protected $post_types = [];
    protected $labels = [];
    protected $args = [];
    protected array $terms = [];
    
    public function __construct()
    {
        $this->init();
    }

    abstract public function init(): void;
    public function register(): void
    {
        register_taxonomy($this->slug, $this->post_types, $this->args);
    }

    public function enqueue_common_admin_scripts($hook): void
    {
         // Only load on taxonomy pages
        if ($hook == 'term.php' || $hook == 'edit-tags.php') 
        {
            wp_enqueue_media();
            wp_enqueue_script(
                "taxonomy-{$this->slug}-media-uploader", 
                get_template_directory_uri() . '/js/taxonomy-media-uploader.js', 
                array('jquery'), 
                time(), 
                true
            );
            wp_enqueue_style(
                "taxonomy-{$this->slug}-media-uploader",
                get_template_directory_uri() . '/css/taxonomy-media-uploader.css'
            );
        }
    }
    

    protected function set_post_types(array $post_types): void
    {
        $this->post_types = $post_types;
    }

    protected function set_labels(array $labels): void
    {
        $this->labels = $labels;
    }

    protected function set_args(array $args): void
    {
        $this->args = $args;
    }

    protected function set_terms(array $terms): void
    {
        $this->terms = $terms;
    }

    public function get_slug(): string
    {
        return $this->slug;
    }

    public function process_terms(): void
    {
        if (empty($this->terms)) return;
        
        foreach ($this->terms as $data) 
        {
            $term = term_exists($data['slug'], $this->slug);
        
            if (!$term) 
            {
                $term = wp_insert_term($data['name'], $this->slug, [
                    'slug' => $data['slug'],
                    'description' => $data['description'] ?? ''
                ]);
            }

            if (!is_wp_error($term) && isset($data['meta'])) 
            {
                foreach ($data['meta'] as $key => $value) 
                {
                    update_term_meta($term['term_id'], $key, $value);
                }
            }
        }
    }

    protected function generate_term_data(
        string $slug,
        string $name,
        string $description = '',
        array $meta = []
    ): array 
    {
        return [
            'slug' => $slug,
            'name' => $name,
            'description' => $description,
            'meta' => $meta
        ];
    }

    /**
     * Add "View Metas" action link to term list rows
     */
    public function row_action_view_details(array $actions, \WP_Term $term): array
    {
        // Check if this is our taxonomy
        if ($term->taxonomy !== $this->taxonomy) 
        {
            return $actions;
        }

        // Always show the link (remove meta check for now)
        $actions['view_details'] = sprintf(
            '<a href="%s" aria-label="%s">%s</a>',
            esc_url(admin_url(sprintf(
                'admin.php?page=flex-supervisor-audit-term&object_id=%d&action_id=view_details',
                $term->term_id
            ))),
            esc_attr(sprintf(__('View meta for "%s"', 'flex-eland'), $term->name)),
            esc_html__('View Details', 'flex-eland')
        );

        return $actions;
    }

    /**
     * Add image field to add new term form
     */
    public function add_term_image_field($taxonomy): void
    {
        ?>
        <div class="form-field term-image-wrap">
            <label for="term-image"><?php _e('Term Image', 'flex-eland'); ?></label>
            <input type="hidden" id="term-image" name="term_image" value="">
            <div id="term-image-preview" style="min-height: 100px; border: 1px dashed #c3c4c7; margin: 10px 0; display: flex; align-items: center; justify-content: center;">
                <p><?php _e('No image selected', 'flex-eland'); ?></p>
            </div>
            <button type="button" class="button" id="upload-term-image"><?php _e('Select Image', 'flex-eland'); ?></button>
            <button type="button" class="button hidden" id="remove-term-image"><?php _e('Remove Image', 'flex-eland'); ?></button>
            <p class="description"><?php _e('Upload an image or icon for this term.', 'flex-eland'); ?></p>
        </div>
        
        <div class="form-field term-icon-wrap">
            <label for="term-icon"><?php _e('Term Icon (Font Awesome)', 'flex-eland'); ?></label>
            <input type="text" id="term-icon" name="term_icon" value="" placeholder="fas fa-camera">
            <p class="description"><?php _e('Enter a Font Awesome icon class (alternative to image).', 'flex-eland'); ?></p>
        </div>
        <?php
    }
    
    /**
     * Add image field to edit term form
     */
    public function edit_term_image_field($term, $taxonomy): void
    {
        $image_id = get_term_meta($term->term_id, 'term_image', true);
        $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
        $icon = get_term_meta($term->term_id, 'term_icon', true);
        ?>
        <tr class="form-field term-image-wrap">
            <th scope="row">
                <label for="term-image"><?php _e('Term Image', 'flex-eland'); ?></label>
            </th>
            <td>
                <input type="hidden" id="term-image" name="term_image" value="<?php echo esc_attr($image_id); ?>">
                <div id="term-image-preview" style="min-height: 100px; border: 1px dashed #c3c4c7; margin: 10px 0; display: flex; align-items: center; justify-content: center;">
                    <?php if ($image_url) : ?>
                        <img src="<?php echo esc_url($image_url); ?>" style="max-width: 100%; max-height: 200px;">
                    <?php else : ?>
                        <p><?php _e('No image selected', 'flex-eland'); ?></p>
                    <?php endif; ?>
                </div>
                <button type="button" class="button" id="upload-term-image"><?php _e('Select Image', 'flex-eland'); ?></button>
                <button type="button" class="button <?php echo !$image_url ? 'hidden' : ''; ?>" id="remove-term-image"><?php _e('Remove Image', 'flex-eland'); ?></button>
                <p class="description"><?php _e('Upload an image or icon for this term.', 'flex-eland'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field term-icon-wrap">
            <th scope="row">
                <label for="term-icon"><?php _e('Term Icon (Font Awesome)', 'flex-eland'); ?></label>
            </th>
            <td>
                <input type="text" id="term-icon" name="term_icon" value="<?php echo esc_attr($icon); ?>" placeholder="fas fa-camera">
                <p class="description"><?php _e('Enter a Font Awesome icon class (alternative to image).', 'flex-eland'); ?></p>
            </td>
        </tr>
        <?php
    }
    
    /**
     * Save term image/icon data
     */
    public function save_term_image($term_id): void
    {
        if (isset($_POST['term_image'])) {
            update_term_meta($term_id, 'term_image', sanitize_text_field($_POST['term_image']));
        }
        
        if (isset($_POST['term_icon'])) {
            update_term_meta($term_id, 'term_icon', sanitize_text_field($_POST['term_icon']));
        }
    }
    
    /**
     * Add image column to term list
     */
    public function add_image_column($columns): array
    {
        $new_columns = [];
        
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key === 'name') {
                $new_columns['term_image'] = __('Image/Icon', 'flex-eland');
            }
        }
        
        return $new_columns;
    }
    
    /**
     * Add content to image column
     */
    public function add_image_column_content($content, $column_name, $term_id): string
    {
        if ($column_name !== 'term_image') {
            return $content;
        }
        
        $image_id = get_term_meta($term_id, 'term_image', true);
        $icon = get_term_meta($term_id, 'term_icon', true);
        
        if ($image_id) {
            $image_url = wp_get_attachment_url($image_id);
            if ($image_url) {
                return '<img src="' . esc_url($image_url) . '" style="max-width: 50px; max-height: 50px;">';
            }
        }
        
        if ($icon) {
            return '<i class="' . esc_attr($icon) . '" style="font-size: 24px;"></i>';
        }
        
        return '—';
    }
}
