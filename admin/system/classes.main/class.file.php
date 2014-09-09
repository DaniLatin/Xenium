<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class File
{
    protected $file;
    protected $write_string;
    
    public function __construct($get_file)
    {
        $this->file = $get_file;
    }
    
    public function set_writable()
    {
        if (!file_exists(dirname($this->file)))
        {
            mkdir(dirname($this->file));
        }
        chmod(dirname($this->file), 0777);
        if (file_exists($this->file))
        {
            chmod($this->file, 0777);
        }
        return $this;
    }
    
    public function set_unwritable()
    {
        chmod(dirname($this->file), 0755);
        chmod($this->file, 0755);
        return $this;
    }
    
    public function write_to_file($string)
    {
        $this->write_string = $string;
        
        $cf = fopen($this->file, 'w+') or die("can't open file");
        fwrite($cf, $this->write_string);
        fclose($cf);
        
        return $this;
    }
    
    public function create_dir($dir)
    {
        if (!is_dir($dir))
        {
            mkdir($dir, 0777, true);
        }
    }
}

?>