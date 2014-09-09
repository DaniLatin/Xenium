<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class plugin_Subscription
{
    public $fields = array();
    public $session_fields;
    
    public function __construct()
    {
        $this->session_fields = ', subscription_start_date, subscription_end_date, subscription_saved_total';
        
        $this->fields['static_fields'] = array(
            'subscription_start_date' => array('value' => '', 'field_type' => 'DATE NOT NULL', 'index' => true, 'admin_editable' => false),
            'subscription_end_date' => array('value' => '', 'field_type' => 'DATE NOT NULL', 'index' => true, 'admin_editable' => false),
            'subscription_saved_total' => array('value' => '', 'field_type' => 'DECIMAL( 10, 2 ) NOT NULL', 'index' => true, 'admin_editable' => false),
            'subscription_offers_total' => array('value' => '', 'field_type' => 'INT ( 11 ) NOT NULL', 'index' => true, 'admin_editable' => false),
        );
    }
    
    public function update_user_actions()
    {
        global $new_saved_total, $new_offers_total;
        $user_id = $_SESSION['logged_in_user']['id'];
        $update_user_actions = new Xdb;
        $update = $update_user_actions->set_table('www_users')
                                      ->simple_update_fields(array('subscription_saved_total' => $new_saved_total, 'subscription_offers_total' => $new_offers_total))
                                      ->where(array('id' => $user_id))
                                      ->db_update();
    }
}