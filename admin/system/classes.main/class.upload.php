<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class Upload
{
    public $file_info;
    
    public function get_file_info($file_name)
    {
        if(function_exists("finfo_open"))
        {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $this->file_info = finfo_file($finfo, $file_name);
        }
        else
        {
            $this->file_info = mime_content_type($file_name);
        }
        return $this;
    }
    
    public function return_type()
    {
        $file_type = explode('/', $this->file_info);
        return $file_type[0];
    }
    
    public function return_ext()
    {
        $file_ext = explode('/', $this->file_info);
        return $file_type[1];
    }
    
    public function upload_file($file_name)
    {
        $type = $this->get_file_info($file_name)->return_type();
        $ext = $this->get_file_info($file_name)->return_ext();
    }
}

?>