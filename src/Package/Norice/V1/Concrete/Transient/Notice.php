<?php
namespace Ababilithub\FlexWordpress\Package\Notice\V1\Concrete\Transient;

use Ababilithub\{
    FlexWordpress\Package\Notice\V1\Base\Notice as BaseNotice,
    FlexWordpress\Package\Notice\V1\Utility\Transient as TransientNoticeUility
};

class Notice extends BaseNotice
{
    protected $key;
    
    public function init(array $data = []): static
    {
        $this->key = $data['key'] ?? 'ababilithub_notices';
        $this->notice_board = new TransientNoticeUility($this->key);
        $this->init_service();
        $this->init_hook();
        return $this;
    }

    public function init_service(): void
    {
        // Optional service initialization
    }

    public function init_hook(): void
    {
        add_action('admin_notices', [$this, 'render']);
    }
    
    public function render(): void
    {
        if ($this->notice_board->has_notices()) 
        {
            foreach ($this->notice_board->get_notice_codes() as $code) 
            {
                $messages = $this->notice_board->get_notice_messages($code);
                $error_data = $this->notice_board->get_notice_data($code);

                $class = $error_data['class'] ?? 'notice notice-info is-dismissible';
                
                foreach ($messages as $message) 
                {
                    printf(
                        '<div class="%1$s"><p>%2$s</p></div>',
                        esc_attr($class),
                        wp_kses_post($message)
                    );
                }
            }
            
            // Clear notices after rendering
            $this->notice_board->clear();
        }
    }

    public function add(array $notice = []): static
    {
        $this->notice_board->add_from_array($notice);
        return $this;
    }
    
    // Additional helper methods
    public function success(string $message, string $code = 'success'): static
    {
        return $this->add([
            'code' => $code,
            'message' => $message,
            'data' => ['class' => 'notice notice-success is-dismissible']
        ]);
    }
    
    public function error(string $message, string $code = 'error'): static
    {
        return $this->add([
            'code' => $code,
            'message' => $message,
            'data' => ['class' => 'notice notice-error is-dismissible']
        ]);
    }
    
    public function info(string $message, string $code = 'info'): static
    {
        return $this->add([
            'code' => $code,
            'message' => $message,
            'data' => ['class' => 'notice notice-info is-dismissible']
        ]);
    }
    
    public function warning(string $message, string $code = 'warning'): static
    {
        return $this->add([
            'code' => $code,
            'message' => $message,
            'data' => ['class' => 'notice notice-warning is-dismissible']
        ]);
    }
}