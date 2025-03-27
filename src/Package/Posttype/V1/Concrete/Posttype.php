<?php
namespace Ababilitworld\FlexWordpress\Package\Posttype\V1\Concrete;

(defined('ABSPATH') && defined('WPINC')) || die();

use Ababilitworld\{
    FlexWordpress\Package\Posttype\V1\Base\Posttype as BasePosttype
};

if (!class_exists(__NAMESPACE__ . '\\Posttype')) 
{
    class Posttype extends BasePosttype
    {
        private static ?self $instance = null;

        public function __construct(array $config = [])
        {
            parent::__construct($config);
            $this->setDefaultConfig();
            $this->setDefaultLabels();
            $this->setDefaultArgs();
        }

        public static function getInstance(array $config = []): self
        {
            if (is_null(self::$instance)) {
                self::$instance = new self($config);
            }
            return self::$instance;
        }

        /**
         * Register the required hooks for post type.
         */
        protected function registerHooks(): void
        {
            
        }
    }
}
