<?php
namespace Ababilithub\FlexWordpress\Package\Posttype\V1\Mixin;

use Ababilithub\{
    FlexWordpress\Package\Cache\V1\Mixin\Cache as WpCacheMixin,
};

use WP_Error;

trait Posttype
{
    use WpCacheMixin;

    /**
     * Creates a new post of this post type
     * 
     * @param array $post_data Array of post data (must include 'title')
     * @param array $taxonomies Array of taxonomy terms to assign (['taxonomy' => ['term1', 'term2']])
     * @param array $meta_data Array of meta data to add
     * @return int|WP_Error The post ID on success, WP_Error on failure
     */
    public function add_post(array $post_data, array $taxonomies = [], array $meta_data = []): int|WP_Error
    {
        $defaults = [
            'post_title' => '',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => $this->slug,
        ];
        
        $post_args = wp_parse_args($post_data, $defaults);
        
        // Validate required fields
        if (empty($post_args['post_title'])) 
        {
            return new WP_Error('missing_title', __('Post title is required', 'flex-eland'));
        }
        
        $post_id = wp_insert_post($post_args, true);
        
        if (is_wp_error($post_id)) 
        {
            return $post_id;
        }
        
        // Assign taxonomies if provided
        foreach ($taxonomies as $taxonomy => $terms) 
        {
            if (taxonomy_exists($taxonomy)) 
            {
                wp_set_object_terms($post_id, $terms, $taxonomy);
            }
        }
        
        // Add meta data if provided
        if (!empty($meta_data)) 
        {
            $this->add_post_meta($post_id, $meta_data);
        }
        
        return $post_id;
    }

    /**
     * Adds meta data to a post
     * 
     * @param int $post_id Post ID
     * @param array $meta_data Array of meta data (key => value pairs)
     * @param bool $update_existing Whether to update existing meta keys
     * @return array Array of results for each meta operation
     */
    public function update_post_meta(int $post_id, array $meta_data, bool $update_existing = true): array
    {
        $results = [];
        
        foreach ($meta_data as $key => $value) 
        {
            if ($update_existing || !metadata_exists('post', $post_id, $key)) 
            {
                $results[$key] = update_post_meta($post_id, $key, $value);
            } 
            else
            {
                $results[$key] = false; // Skip existing
            }
        }
        
        return $results;
    }

    /**
     * Disable Gutenberg (block editor) for specific post types.
     *
     * @param array $post_types List of post types to disable Gutenberg for.
     * 
     * @return void
     */
    public function disable_gutenberg(array $post_types): void
    {
        add_filter('use_block_editor_for_post_type', function ($use_block_editor, $post_type) use ($post_types) {
            if (in_array($post_type, $post_types, true)) {
                return false;
            }
            return $use_block_editor;
        }, 10, 2);
    }

    /**
     * Set custom placeholder title for post type.
     *
     * @param string $post_type Post type key.
     * @param string $placeholder Placeholder text.
     * 
     * @return void
     */
    public function setTitlePlaceholder(string $post_type, string $placeholder): void
    {
        add_filter('enter_title_here', function ($title, $current_post) use ($post_type, $placeholder) {
            if ($current_post->post_type === $post_type) {
                return $placeholder;
            }
            return $title;
        }, 10, 2);
    }

    /**
     * Change default messages after post update.
     *
     * @param string $post_type Post type key.
     * @param array $messages Custom messages array.
     * 
     * @return void
     */
    public function customizePostUpdatedMessages(string $post_type, array $messages): void
    {
        add_filter('post_updated_messages', function ($default_messages) use ($post_type, $messages) {
            $default_messages[$post_type] = wp_parse_args($messages, $default_messages[$post_type] ?? []);
            return $default_messages;
        });
    }

    /**
     * Display all post meta for a specific post in a table
     * 
     * @param int $post_id The ID of the post to show meta for
     */
    public function render_post_meta_table($post_id) 
    {
        // Verify the post exists
        if (!get_post($post_id)) {
            echo '<div class="notice notice-error"><p>Invalid post ID specified.</p></div>';
            return;
        }

        // Get all post meta
        $all_meta = get_post_meta($post_id);
        
        echo '<div class="wrap">';
        echo '<h1>Post Meta Data for #' . esc_html($post_id) . '</h1>';
        echo '<p><a href="' . get_edit_post_link($post_id) . '">&larr; Back to post editor</a></p>';
        
        if (empty($all_meta)) {
            echo '<div class="notice notice-info"><p>No meta data found for this post.</p></div>';
            echo '</div>';
            return;
        }

        echo '<table class="widefat fixed striped">';
        echo '<thead>
            <tr>
                <th width="25%">Meta Key</th>
                <th width="75%">Meta Value</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($all_meta as $meta_key => $meta_values) {
            echo '<tr>';
            echo '<td><strong>' . esc_html($meta_key) . '</strong></td>';
            echo '<td>';
            
            foreach ($meta_values as $value) {
                $unserialized = maybe_unserialize($value);
                
                if (is_array($unserialized) || is_object($unserialized)) {
                    echo '<pre>' . esc_html(print_r($unserialized, true)) . '</pre>';
                } else {
                    echo esc_html($value);
                }
                
                echo '<hr style="margin: 5px 0; border: 0; border-top: 1px dashed #ccc;">';
            }
            
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        
        // Add styling
        echo '<style>
            .wrap { margin: 20px; }
            table.widefat { border-collapse: collapse; margin-top: 20px; }
            table.widefat th, 
            table.widefat td { padding: 10px; border: 1px solid #e5e5e5; vertical-align: top; }
            table.widefat th { background-color: #f7f7f7; font-weight: 600; }
            table.widefat pre { 
                margin: 0; 
                white-space: pre-wrap; 
                max-width: 100%; 
                overflow-x: auto;
                background: #f5f5f5;
                padding: 5px;
                border-radius: 3px;
            }
            .notice { margin: 20px 0 !important; }
        </style>';
        
        echo '</div>';
    }
}
