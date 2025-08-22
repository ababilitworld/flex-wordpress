<?php
namespace Ababilithub\FlexWordpress\Package\Notice\V1\Concrete\WpError;

use Ababilithub\{
    FlexWordpress\Package\Notice\V1\Base\Notice as BaseNotice
};

class Notice extends BaseNotice
{
    public function init(array $data = []): static
    {
        $this->notice_board = new \WP_Error();
        $this->init_service();
        $this->init_hook();
        return $this;
    }

    public function init_service():void
    {

    }

    public function init_hook():void
    {
        add_action('admin_notices',[$this,'render']);
    }
    
    public function render(): void
    {
        if ($this->notice_board->has_errors()) 
        {
            foreach ($this->notice_board->get_error_codes() as $code) 
            {
                $messages = $this->notice_board->get_error_messages($code);

                $error_data = $this->notice_board->get_error_data($code);

                $class = $error_data['class'] ?? 'notice notice-error is-dismissible';
                
                foreach ($messages as $message) 
                {
                    printf(
                        '<div class="%1$s"><p>%2$s</p></div>',
                        esc_attr($class),
                        wp_kses_post($message)
                    );
                }
            }
        }
    }

    public function add(array $error = []) : static
    {
        $this->notice_board->add($error['code'], $error['message'],$error['data']);
        return $this;
    }
    
}