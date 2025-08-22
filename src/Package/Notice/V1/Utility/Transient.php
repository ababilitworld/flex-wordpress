<?php
namespace Ababilithub\FlexWordpress\Package\Notice\V1\Utility;

class Transient
{
    protected $key;
    protected $notices = [];
    
    public function __construct(string $key)
    {
        $this->key = $key;
        $this->load_notices();
    }
    
    public function load_notices(): void
    {
        $stored = get_transient($this->key);
        $this->notices = is_array($stored) ? $stored : [];
    }
    
    public function save_notices(): void
    {
        if (!empty($this->notices)) 
        {
            set_transient($this->key, $this->notices, 30);
        } 
        else 
        {
            delete_transient($this->key);
        }
    }
    
    public function add(string $code, string $message, array $data = []): void
    {
        $this->notices[] = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
        $this->save_notices();
    }
    
    public function add_from_array(array $notice): void
    {
        $defaults = [
            'code' => 'general_notice',
            'message' => '',
            'data' => ['class' => 'notice notice-info is-dismissible']
        ];
        
        $notice = wp_parse_args($notice, $defaults);
        $this->add($notice['code'], $notice['message'], $notice['data']);
    }
    
    public function get_notices(): array
    {
        return $this->notices;
    }
    
    public function has_notices(): bool
    {
        return !empty($this->notices);
    }
    
    public function get_notice_codes(): array
    {
        return array_column($this->notices, 'code');
    }
    
    public function get_notice_messages(string $code = ''): array
    {
        if (empty($code)) {
            return array_column($this->notices, 'message');
        }
        
        $messages = [];
        foreach ($this->notices as $notice) {
            if ($notice['code'] === $code) {
                $messages[] = $notice['message'];
            }
        }
        return $messages;
    }
    
    public function get_notice_data(string $code = '')
    {
        if (empty($code)) {
            return array_column($this->notices, 'data');
        }
        
        foreach ($this->notices as $notice) {
            if ($notice['code'] === $code) {
                return $notice['data'];
            }
        }
        return [];
    }
    
    public function clear(): void
    {
        $this->notices = [];
        delete_transient($this->key);
    }
    
    public function __destruct()
    {
        $this->save_notices();
    }
}