<?php
namespace Ababilithub\FlexWordpress\Package\Option\V1\Mixin;

trait Option
{
    /**
     * Validate if the current option save request should be processed
     * 
     * @return bool Whether the save should proceed
     */
    private function isValidOptionSave(): bool
    {
        // Skip autosaves (if this is tied to a post somehow)
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return false;
        }

        // Check user capabilities - typically 'manage_options' for site options
        return current_user_can('manage_options');
    }

    /**
     * Save a simple text option
     */
    private function saveTextOption(string $option_name, string $option_value): void
    {
        if (isset($_POST[$option_name])) {
            update_option($option_name, sanitize_text_field($option_value));
        } else {
            delete_option($option_name);
        }
    }

    /**
     * Save an array option with sanitization
     */
    private function saveArrayOption(string $option_name, array $option_value): void
    {
        if (isset($_POST[$option_name]) && is_array($_POST[$option_name])) {
            // Sanitize each array element
            $sanitized_value = array_map('sanitize_text_field', $option_value);
            update_option($option_name, $sanitized_value);
        } else {
            delete_option($option_name);
        }
    }

    /**
     * Save an associative array option with key-value sanitization
     */
    private function saveAssocArrayOption(string $option_name, array $option_value): void
    {
        if (isset($_POST[$option_name]))
        {
            $sanitized_value = [];
            foreach ($option_value as $key => $value)
            {
                $sanitized_key = sanitize_key($key);
                $sanitized_value[$sanitized_key] = is_array($value) 
                    ? array_map('sanitize_text_field', $value)
                    : sanitize_text_field($value);
            }
            update_option($option_name, $sanitized_value);
        } 
        else 
        {
            delete_option($option_name);
        }
    }

    /**
     * Save a serialized array option (for complex data structures)
     */
    private function saveSerializedArrayOption(string $option_name, array $option_value): void
    {
        if (isset($_POST[$option_name])) 
        {
            // Sanitize before serialization
            $sanitized_value = $this->map_recursive('sanitize_text_field', $option_value);
            update_option($option_name, $sanitized_value);
        }
        else 
        {
            delete_option($option_name);
        }
    }

    /**
     * Save a single image attachment option
     */
    private function saveImageOption(string $option_name, int $image_id): void
    {
        if ($this->isValidImageAttachment($image_id)) {
            update_option($option_name, $image_id);
        } else {
            delete_option($option_name);
        }
    }

    /**
     * Save multiple image attachments option
     */
    private function saveMultipleImagesOption(string $option_name, array $image_ids): void
    {
        $valid_images = array_filter(array_map('absint', $image_ids), [$this, 'isValidImageAttachment']);
        
        if (!empty($valid_images)) {
            update_option($option_name, $valid_images);
        } else {
            delete_option($option_name);
        }
    }

    /**
     * Save a single file attachment option (non-image)
     */
    private function saveAttachmentOption(string $option_name, int $attachment_id): void
    {
        if ($this->isValidAttachment($attachment_id)) {
            update_option($option_name, $attachment_id);
        } else {
            delete_option($option_name);
        }
    }

    /**
     * Save multiple file attachments option
     */
    private function saveMultipleAttachmentsOption(string $option_name, array $attachment_ids): void
    {
        $valid_attachments = array_filter(array_map('absint', $attachment_ids), [$this, 'isValidAttachment']);
        
        if (!empty($valid_attachments)) {
            update_option($option_name, $valid_attachments);
        } else {
            delete_option($option_name);
        }
    }

    /**
     * Check if an ID is a valid image attachment
     */
    private function isValidImageAttachment(int $attachment_id): bool
    {
        return $attachment_id && wp_attachment_is_image($attachment_id);
    }

    /**
     * Check if an ID is a valid attachment (any type)
     */
    private function isValidAttachment(int $attachment_id): bool
    {
        $attachment = get_post($attachment_id);
        return $attachment && $attachment->post_type === 'attachment';
    }

    /**
     * Helper function for recursive array sanitization
     */
    private function map_recursive(callable $callback, array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) 
        {
            if (is_array($value)) 
            {
                $result[$key] = $this->map_recursive($callback, $value);
            } 
            else
            {
                $result[$key] = call_user_func($callback, $value);
            }
        }
        return $result;
    }
}