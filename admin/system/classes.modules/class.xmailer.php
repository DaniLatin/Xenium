<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class xMailer
{
    protected $settings_table;
    public $settings_fields = array();
    protected $mailer;
    
    public function __construct()
    {
        $this->settings_table = 'settings_email';
        
        $this->settings_fields = 
        array(
        'static_fields' => 
            array(
                'project_id' => array('value' => '', 'field_type' => 'INT( 1 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'smtp' => array('value' => '', 'field_type' => 'INT( 1 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'smtp_auth' => array('value' => '', 'field_type' => 'INT( 1 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'host' => array('value' => '', 'field_type' => 'VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'smtp_port' => array('value' => '', 'field_type' => 'INT( 5 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'smtp_username' => array('value' => '', 'field_type' => 'VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'smtp_password' => array('value' => '', 'field_type' => 'VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'set_from_email' => array('value' => '', 'field_type' => 'VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'set_from_name' => array('value' => '', 'field_type' => 'VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'add_reply_to_email' => array('value' => '', 'field_type' => 'VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'add_reply_to_name' => array('value' => '', 'field_type' => 'VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false)
            )
        );
        
        include ($_SERVER['DOCUMENT_ROOT'] . '/admin/system/phpmailer/class.phpmailer.php');
        $this->mailer = new PHPMailer();
        
        $this->cache_setting = true;
    }
    
    public function get_email_settings($project_id)
    {
        $email_settings_xdb = new Xdb;
        $email_settings_xdb_row = $email_settings_xdb->set_table($this->settings_table)
                                                     ->where(array('project_id' => $project_id))
                                                     ->limit(1)
                                                     ->db_select(true, 0, strtolower(get_class($this)));
        if (isset($email_settings_xdb_row[0]))
        {
            return $email_settings_xdb_row[0];
        }
        else
        {
            $email_settings_output = array('project_id' => $project_id, 'smtp' => 0, 'smtp_auth' => 0, 'host' => '', 'smtp_port' => 0, 'smtp_username' => '', 'smtp_password' => '', 'set_from_email' => 'testmail@gmail.com', 'set_from_name' => 'John Doe', 'add_reply_to_email' => 'testmail@gmail.com', 'add_reply_to_name' => 'John Doe', 'add_address_email' => 'testmail@gmail.com', 'add_address_name' => 'John Doe');
            
            foreach ($this->settings_fields['static_fields'] as $field_name => $field_value)
            {
                $this->settings_fields['static_fields'][$field_name]['value'] = $email_settings_output[$field_name];
            }
            
            $this->save_email_settings($project_id, $email_settings_output, true);
            return $email_settings_output;
        }
    }
    
    public function save_email_settings($project_id, $email_settings, $insert = false)
    {
        if ($insert)
        {
            $xdb_email_setting_insert = new Xdb;
            $insert_new = $xdb_email_setting_insert->db_insert_content($project_id, $this->settings_table, $this->settings_fields);
        }
        else
        {
            $xdb_email_setting_update = new Xdb;
            $update = $xdb_email_setting_update->set_table($this->settings_table)
                                               ->simple_update_fields($email_settings)
                                               ->where(array('project_id' => $project_id))
                                               ->db_update(strtolower(get_class($this)));
            
            $xdb_email_setting_update->update_permanent_cache_single($sc->table, $id);
        }
    }
    
    public function send_mail($email_title, $send_to, $replacables = array())
    {
        global $project_id, $selected_language;
        $email_settings = $this->get_email_settings($project_id);
        
        if (isset($email_settings['smtp']) && $email_settings['smtp'] == 1)
        {
            $this->mailer->IsSMTP();
            $this->mailer->Host = $email_settings['host'];
            
            if (isset($email_settings['smtp_auth']) && $email_settings['smtp_auth'] == 1)
            {
                $this->mailer->SMTPAuth = true;
                //$this->mailer->SMTPSecure = "ssl"; maybe "tls"
                $this->mailer->Port = $email_settings['smtp_port'];
                $this->mailer->Username = $email_settings['smtp_username'];
                $this->mailer->Password = $email_settings['smtp_password'];
            }
        }
        
        $this->mailer->SetFrom($email_settings['set_from_email'], $email_settings['set_from_name']);
        $this->mailer->AddReplyTo($email_settings['add_reply_to_email'], $email_settings['add_reply_to_name']);
        
        if (is_array($send_to))
        {
            foreach ($send_to as $send_to_email)
            {
                $this->mailer->AddAddress($send_to_email);
            }
        }
        else
        {
            $this->mailer->AddAddress($send_to);
        }
        
        $email_contents = Emails::email_get_by_title($email_title);
        
        //$this->mailer->Subject = 'PHPMailer Test Subject via mail(), basic';
        $this->mailer->Subject = str_replace(array('&scaron;', '&Scaron;'), array('š', 'Š'), $email_contents['title_' . $selected_language]);
        
        $this->mailer->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional, comment out and test
        
        if (is_array($replacables) && isset($replacables))
        {
            foreach ($replacables as $replacable_key => $replacable_value)
            {
                $email_contents['description_' . $selected_language] = str_replace($replacable_key, $replacable_value, html_entity_decode($email_contents['description_' . $selected_language], ENT_QUOTES, 'UTF-8'));
            }
        }
        
        //$this->mailer->MsgHTML('<b>Some nice html</b>');
        $this->mailer->MsgHTML(str_replace(array('&scaron;', '&Scaron;'), array('š', 'Š'), $email_contents['description_' . $selected_language]));
        
        if(!$this->mailer->Send()) {
          //echo 'Mailer Error: ' . $this->mailer->ErrorInfo;
          return false;
        } else {
          //echo 'Message sent!';
          return true;
        }
    }
}

?>