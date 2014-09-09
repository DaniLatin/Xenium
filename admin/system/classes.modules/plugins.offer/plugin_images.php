<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class plugin_Images
{
    public $fields = array();
    public $add_fields2;
    
    public function __construct()
    {
        $this->fields['static_fields'] = array('images' => array('value' => '', 'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => false));
        //$this->add_fields2 = 'krneki';
    }
}

?>