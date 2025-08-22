<?php
namespace Ababilithub\FlexWordpress\Package\Repository\V1\Mixin;

trait Repository
{
    private function is_valid_save($post_id, $post) 
    {
        // Verify nonce
        if (!isset($_POST['deed_deeds_nonce']) || 
            !wp_verify_nonce($_POST['deed_deeds_nonce'], 'deed_deeds_nonce')) {
            return false;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
        {
            return false;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) 
        {
            return false;
        }

        // Only save for our post type
        return get_post_type($post_id) === $this->posttype;
    }

    private function save_text_field($post_id, $meta_key, $meta_value) 
    {

        if (isset($_POST[$meta_key])) 
        {
            update_post_meta($post_id, $meta_key, $meta_value);
        } 
        else 
        {
            delete_post_meta($post_id, $meta_key);
        }
    }

    private function save_thumbnail_image($post_id, $meta_key, $meta_value) 
    {
        if (isset($_POST[$meta_key]) && !empty($_POST[$meta_key]) && !empty($meta_value)) 
        {
            $image_id = absint($meta_value);
            
            if ($image_id && wp_attachment_is_image($image_id)) 
            {
                set_post_thumbnail($post_id, $image_id);
            } 
            else 
            {
                delete_post_thumbnail($post_id);
            }
        } 
        else 
        {
            delete_post_thumbnail($post_id);
        }
    }

    private function save_single_image($post_id,$meta_key,$meta_value):void
    {
        if (isset($_POST[$meta_key]) && !empty($_POST[$meta_key]) && !empty($meta_value)) 
        {
            $image_id = absint($meta_value);
            
            if ($image_id && wp_attachment_is_image($image_id)) 
            {
                update_post_meta($post_id, $meta_key, $image_id);
            } 
            else 
            {
                delete_post_meta($post_id, $meta_key);
            }
        } 
        else 
        {
            delete_post_meta($post_id, $meta_key);
        }

    }

    private function save_multiple_images($post_id,$meta_key,$meta_value) 
    {
        if (isset($_POST[$meta_key]) && is_array($_POST[$meta_key]) && is_array($meta_value)) 
        {
            $images = array_map('absint', $_POST[$meta_key]);
            $valid_images = array_filter($images, 'wp_attachment_is_image');
            update_post_meta($post_id, $meta_key, $valid_images);
        } 
        else 
        {
            delete_post_meta($post_id, $meta_key);
        }
    }

    private function save_single_attachment($post_id,$meta_key,$meta_value) 
    {
        if (isset($_POST[$meta_key]) && !empty($_POST[$meta_key]) && !empty($meta_value)) 
        {
            $attachment_id = absint($meta_value);
            $attachment = get_post($attachment_id);
            if($attachment && $attachment->post_type === 'attachment')
            {
                update_post_meta($post_id, $meta_key, $meta_value);
            }
            else 
            {
                delete_post_meta($post_id, $meta_key);
            }
            
        }
        else 
        {
            delete_post_meta($post_id, $meta_key);
        }
    }

    private function save_multiple_attachments($post_id,$meta_key,$meta_value) 
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
                update_post_meta($post_id, $meta_key, $valid_attachments);
            }
            else 
            {
                delete_post_meta($post_id, $meta_key);
            }
            
        }
        else 
        {
            delete_post_meta($post_id, $meta_key);
        }
    }

    ////
    
}