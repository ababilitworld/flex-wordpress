<?php
namespace Ababilithub\FlexWordpress\Package\Notification\V1;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

use Ababilithub\{
    FlexPhp\Package\Mixin\Standard\V1\V1 as PhpStandardMixin
};

if ( ! class_exists( __NAMESPACE__.'\Notification' ) ) 
{
    /**
     * Class Notification
     *
     * @package Ababilithub\FlexWordpress
     */
    class Notification 
    {	
        use PhpStandardMixin;

        public $wp_error;

        public function __construct()
        {
            $this->wp_error = new \WP_Error();
            add_action('admin_notices', array($this, 'show_notice' ) );
        }

        public function add_notice($handle, $message, $data = [], $type = 'error'): void
        {
            $data['type'] = $type;
            $this->wp_error->add($handle, $message, $data);
        }

        public function show_notice() : void
        {				
            if($this->wp_error->has_errors())
            {
                foreach ( $this->wp_error->get_error_codes() as $code ) 
                {
                    $messages = $this->wp_error->get_error_messages( $code );
                    $data = $this->wp_error->get_error_data( $code );
                    $type = isset($data['type']) ? sanitize_html_class($data['type']) : 'error';
                    $class = 'notice notice-' . $type;
                
                    foreach ( $messages as $message ) 
                    {
                        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ) );
                    }
                }
                                    
            }
        }	
    }

}