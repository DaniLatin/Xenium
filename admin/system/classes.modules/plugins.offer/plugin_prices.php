<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class plugin_Prices
{
    public $fields = array();
    public $add_fields2;
    
    public function __construct()
    {
        $this->fields['static_fields'] = array('prices' => array('value' => '', 'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => false));
        //$this->add_fields2 = 'krneki';
    }
}

?>