<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class plugin_SubscriptionTime
{
    public $fields = array();
    
    public function __construct()
    {
        $this->fields['static_fields'] = array(
            'subscription_start_date' => array('value' => '', 'field_type' => 'DATE NOT NULL', 'index' => true, 'admin_editable' => false),
            'subscription_end_date' => array('value' => '', 'field_type' => 'DATE NOT NULL', 'index' => true, 'admin_editable' => false),
            'subscription_used' => array('value' => '', 'field_type' => 'INT ( 1 ) NOT NULL', 'index' => true, 'admin_editable' => false),
        );
    }
}