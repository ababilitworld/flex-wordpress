<?php
namespace Ababilithub\FlexWordpress\Package\TermMeta\V1\Mixin;

trait TermMeta
{
    private function is_valid_save($term_id, $taxonomy) 
    {
        // // Verify nonce
        // if (!isset($_POST['deed_deeds_nonce']) || 
        //     !wp_verify_nonce($_POST['deed_deeds_nonce'], 'deed_deeds_nonce')) {
        //     return false;
        // }

        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
        {
            return false;
        }

        // Check user permissions
        if (!current_user_can('edit_term', $term_id)) 
        {
            return false;
        }

        // Only save for our taxonomy
        return taxonomy_exists($taxonomy) && $taxonomy === $this->taxonomy;
    }

    private function save_text_field($term_id, $meta_key, $meta_value) 
    {
        if (isset($_POST[$meta_key])) 
        {
            update_term_meta($term_id, $meta_key, $meta_value);
        } 
        else 
        {
            delete_term_meta($term_id, $meta_key);
        }
    }

    private function save_thumbnail_image($term_id, $meta_key, $meta_value) 
    {
        if (isset($_POST[$meta_key]) && !empty($_POST[$meta_key]) && !empty($meta_value)) 
        {
            $image_id = absint($meta_value);
            
            if ($image_id && wp_attachment_is_image($image_id)) 
            {
                update_term_meta($term_id, $meta_key, $image_id);
            } 
            else 
            {
                delete_term_meta($term_id, $meta_key);
            }
        } 
        else 
        {
            delete_term_meta($term_id, $meta_key);
        }
    }

    private function save_single_image($term_id, $meta_key, $meta_value): void
    {
        if (isset($_POST[$meta_key]) && !empty($_POST[$meta_key]) && !empty($meta_value)) 
        {
            $image_id = absint($meta_value);
            
            if ($image_id && wp_attachment_is_image($image_id)) 
            {
                update_term_meta($term_id, $meta_key, $image_id);
            } 
            else 
            {
                delete_term_meta($term_id, $meta_key);
            }
        } 
        else 
        {
            delete_term_meta($term_id, $meta_key);
        }
    }

    private function save_multiple_images($term_id, $meta_key, $meta_value) 
    {
        if (isset($_POST[$meta_key]) && is_array($_POST[$meta_key]) && is_array($meta_value)) 
        {
            $images = array_map('absint', $_POST[$meta_key]);
            $valid_images = array_filter($images, 'wp_attachment_is_image');
            update_term_meta($term_id, $meta_key, $valid_images);
        } 
        else 
        {
            delete_term_meta($term_id, $meta_key);
        }
    }

    private function save_single_attachment($term_id, $meta_key, $meta_value) 
    {
        if (isset($_POST[$meta_key]) && !empty($_POST[$meta_key]) && !empty($meta_value)) 
        {
            $attachment_id = absint($meta_value);
            $attachment = get_post($attachment_id);
            if($attachment && $attachment->post_type === 'attachment')
            {
                update_term_meta($term_id, $meta_key, $meta_value);
            }
            else 
            {
                delete_term_meta($term_id, $meta_key);
            }
        }
        else 
        {
            delete_term_meta($term_id, $meta_key);
        }
    }

    private function save_multiple_attachments($term_id, $meta_key, $meta_value) 
    {
        if (isset($_POST[$meta_key]) && is_array($_POST[$meta_key]) && is_array($meta_value)) 
        {
            $attachments = array_map('absint', $_POST[$meta_key]);
            $valid_attachments = array_filter($attachments, function($attachment_id) {
                $attachment = get_post($attachment_id);
                return $attachment && $attachment->post_type === 'attachment';
            });

            if(is_array($valid_attachments) && count($valid_attachments))
            {
                update_term_meta($term_id, $meta_key, $valid_attachments);
            }
            else 
            {
                delete_term_meta($term_id, $meta_key);
            }
        }
        else 
        {
            delete_term_meta($term_id, $meta_key);
        }
    }

    ////

    public function prepare_map_location(string $map_location = null): string|null
    {
        if (!empty($map_location)) {
            // Only encode if it's not already encoded
            if (base64_decode($map_location, true) === false) {
                return $map_location = base64_encode($map_location);
            }
        }
        return null;
    }

    public function get_map_location($map_value): string|null
    {
        if (!empty($map_value)) 
        {
            $decoded = wp_unslash(base64_decode($map_value, true));
            return $map_value = ($decoded !== false) ? $decoded : $map_value;
        }
        return null;
    }
    
    // Additional term-specific helper methods
    
    private function get_term_meta_value($term_id, $meta_key, $single = true)
    {
        return get_term_meta($term_id, $meta_key, $single);
    }
    
    private function update_term_meta_value($term_id, $meta_key, $meta_value)
    {
        return update_term_meta($term_id, $meta_key, $meta_value);
    }
    
    private function delete_term_meta_value($term_id, $meta_key)
    {
        return delete_term_meta($term_id, $meta_key);
    }
}