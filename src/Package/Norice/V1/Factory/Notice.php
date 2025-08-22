<?php
namespace Ababilithub\FlexWordpress\Package\Notice\V1\Factory;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Notice\V1\Contract\Notice as NoticeContract,
};

class Notice extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return NoticeContract
     */
    protected static function resolve(string $targetClass): NoticeContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof NoticeContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement NoticeContract");
        }

        return $instance;
    }
} 