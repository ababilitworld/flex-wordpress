<?php
namespace Ababilithub\FlexWordpress\Package\Repository\V1\Concrete\Transient;

use Ababilithub\{
    FlexWordpress\Package\Repository\V1\Base\Repository as BaseRepository
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
};

class Repository extends BaseRepository
{
    public function init(array $data = []): static
    {
        $this->repository = new \WP_Error();
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
        if ($this->repository->has_errors()) 
        {
            foreach ($this->repository->get_error_codes() as $code) 
            {
                $messages = $this->repository->get_error_messages($code);

                $error_data = $this->repository->get_error_data($code);

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

    public function get(array $error = []) : static
    {
        $this->repository->add($error['code'], $error['message'],$error['data']);
        return $this;
    }

    public function update(array $error = []) : static
    {
        $this->repository->add($error['code'], $error['message'],$error['data']);
        return $this;
    }

    public function delete(array $error = []) : void
    {
        $this->repository->add($error['code'], $error['message'],$error['data']);
        return $this;
    }

    
}