<?php
namespace Ababilithub\FlexWordpress\Package\Debug\V1\Concrete\WpError;

use Ababilithub\{
    FlexWordpress\Package\Debug\V1\Base\Debug as BaseDebug
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
};

class Debug extends BaseDebug
{
    public function init(array $data = []): static
    {
        $this->debugger = new \WP_Error();
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
        if ($this->debugger->has_errors()) 
        {
            foreach ($this->debugger->get_error_codes() as $code) 
            {
                $messages = $this->debugger->get_error_messages($code);

                $error_data = $this->debugger->get_error_data($code);

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

    public function add_error(array $error = []) : static
    {
        $this->debugger->add($error['code'], $error['message'],$error['data']);
        return $this;
    }
    
}