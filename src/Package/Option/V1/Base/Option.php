<?php
namespace Ababilithub\FlexWordpress\Package\Option\V1\Base;

use Ababilithub\{
    FlexWordpress\Package\Option\V1\Contract\Option as OptionContract
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
};

abstract class Option implements OptionContract
{
    public string $option_key;
    public array $option_values = [];
    
    public function __construct(array $data = [])
    {
        $this->init($data);
    }

    abstract public function init(array $data = []) : static;
    abstract public function validate(array $values): bool;

    private function load_option_values(): void 
    {
        $this->option_values = get_option($this->option_key, []);
    }
    
    public function get_option_values(array $default = []): mixed 
    {
        return $this->option_values ?? $default;
    }
    
    public function get_option($key, $default = null): mixed 
    {
        return $this->option_values[$key] ?? $default;
    }

    public function set_option(string $key, $value): bool
    {
        $this->option_values[$key] = $value;
        return update_option($this->option_key, $this->option_values);
    }
    
    public function refresh_option_values(): void 
    {
        $this->load_option_values();
    }

    public function get_option($option,$default)
    {
        $return = '';

        $keys = self::extract_array_keys_from_string($option);

        $count_keys = count($keys);

        if($count_keys)
        {
            $value = get_option($keys[0]);

            if(empty($value))
            {
                $return = $default;
            }
            else if(is_array($value))
            {
                $return = self::get_value_from_nested_array($value,array_slice($keys, 1),$default);
            }
            else
            {
                $return = $value;
            }
            
        }
        else
        {
            $return = $default;
        }

        if(is_array($return))
        {
            return array_map(array('MP_SMS_Function','sanitize_array'), $return);
        }
        
        return self::senitize($return);
    }

    public static function extract_array_keys_from_string($string) : array 
    {
        $parent_key = array(explode('[', $string, 2)[0]);
        $child_keys = self::contents($string,'[',']');
        $keys = array_merge($parent_key,$child_keys);
        return $keys;
    }

    public static function contents($string, $start, $end)
    {
        $result = array();
        foreach (explode($start, $string) as $key => $value) 
        {
            if(strpos($value, $end) !== FALSE)
            {
                $result[] = substr($value, 0, strpos($value, $end));
            }
        }
        return $result;
    }

    public function get_value_from_nested_array($multidimensionalArray, $keys, $defaultValue = null)
    {
        foreach ($keys as $key) 
        {
            if (!array_key_exists($key, $multidimensionalArray))
            {
                return $defaultValue;
            }
            
            $multidimensionalArray = $multidimensionalArray[$key];
        }
        
        return $multidimensionalArray;
    }

    public function array_key_exist_like($array, $search_key): mixed
    {
        foreach($array as $key => $v)
        {
            if (strpos($key, $search_key) !== false)
            {
                return $key;
            }
        }

        return false;

    }

    public function mp_error_notice($error)
    {				
        if($error->has_errors())
        {
            foreach($error->get_error_messages() as $error)
            {
                $class = 'notice notice-error';
                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $error ) );
            }
            
        }
    }
}