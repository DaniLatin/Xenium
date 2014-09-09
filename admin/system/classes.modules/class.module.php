<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class Module
{
    private $methods = array();
    private $plugins = array();
    public $cache_setting;
    
    public function __construct()
    {
        $this->load_plugins();
        $this->cache_setting = false;
    }
    
    protected function load_plugins()
    {
        $base = $_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.modules/plugins.' . strtolower(get_class($this)) . '/';
        $plugins = glob($base . '*.php');
        foreach($plugins as $plugin)
        {
            include_once $plugin;
            $name = basename($plugin, '.php');
            $className = $name;
            $obj = new $className();
            $this->plugins[$name] = $obj;

            foreach (get_class_methods($obj) as $method )
                 $this->methods[$method] = $name;
            
            foreach (get_object_vars($obj) as $var_name => $var_value )
            {
                if (property_exists(get_class($this), $var_name))
                {
                    if (is_array($this->{$var_name}))
                    {
                        $this->{$var_name} = array_merge_recursive($this->{$var_name}, $var_value);
                    }
                    else
                    {
                        $this->{$var_name} = $this->{$var_name} . $var_value;
                    }
                    
                }
                else
                {
                    $this->{$var_name} = $var_value;
                }
            }
        }
    }
    
    public function __call($method, $args)
    {
        if(! key_exists($method, $this->methods))
           throw new Exception ("Call to undefined method: " . $method);
 
           array_unshift($args, $this);
           return call_user_func_array(array($this->plugins[$this->methods[$method]], $method), $args);
    }
    
    public function load_prop($prop_name)
    {
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.modules/props.' . strtolower(get_called_class()) . '/' . $prop_name . '.php');
        $prop = ob_get_contents();
        ob_end_clean();
        return $prop;
    }
    
    public static function load_cache_setting($class_name)
    {
        $class_copy = new $class_name;
        return $class_copy->cache_setting;
        //return self::cache_setting;
    }
}

?>