<?php
namespace Ababilithub\FlexWordpress\Package\Route\V1\Mixin\Rewrite\V1;

/**
 * Trait FlushRewriteOnceTrait
 * Ensures rewrite rules are flushed only once per class/package after activation.
 */
trait V1
{
    /**
     * Get the unique option key based on the class using the trait.
     *
     * @return string
     */
    protected function get_flush_option_key(): string
    {
        return 'ababilithub_rewrite_flush_' . str_replace('\\', '_', static::class);
    }

    /**
     * Checks and flushes rewrite rules once if not already flushed.
     *
     * @return void
     */
    protected function maybe_flush_rewrite(): void
    {
        $key = $this->get_flush_option_key();
        if (get_option($key) !== 'flushed') 
        {
            flush_rewrite_rules(false);
            update_option($key, 'flushed');
        }
    }

    /**
     * Static method for activation hook to reset flush flag.
     *
     * @return void
     */
    public static function reset_flush_flag_on_activation(): void
    {
        $key = 'ababilithub_rewrite_flush_' . str_replace('\\', '_', static::class);
        delete_option($key);
    }
}
