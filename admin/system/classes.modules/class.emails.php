<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class Emails
{
    protected $emails_table;
    public $emails_fields;
    
    public function __construct()
    {
        $this->emails_table = 'www_emails';
        
        $this->emails_fields = 
        array(
        'static_fields' => 
            array(
                'project_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'title' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'trashed' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false)
            ),
        'dynamic_fields' =>
            array(
                'title' => array(
                    'value' => '', 
                    'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => true,
                    'html' => array(
                        'type' => 'input', 
                        'label' => 'Title:'), 
                    'html_attributes' => array(
                        'class' => 'input-xxlarge', 
                        'type' => 'text')),
                'description' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => true,
                    'html' => array(
                        'type' => 'textarea', 
                        'label' => 'Description:'), 
                    'html_attributes' => array(
                        'class' => 'span12 html_edit_advanced', 
                        'rows' => 16,
                        'type' => 'text'))
            )
        );
        
        $this->cache_setting = true;
    }
    
    public function emails_get_all()
    {
        $xMail = new Emails;
        $xdb_emails = new Xdb;
        $xdb_emails_rows = $xdb_emails->set_table($xMail->emails_table)
                                      ->where(array('trashed' => 0))
                                      ->group_by('title')
                                      //->db_select(true, 0, strtolower(get_class($xMail)));
                                      ->db_select(false);
        return $xdb_emails_rows;
    }
    
    public function email_insert_new($title, $edit_for_project)
    {
        global $project_id;
        
        $xMail = new Emails;
        $projects = Projects::get_all_projects();
        foreach ($projects as $project)
        {
            $xMail->emails_fields['static_fields']['project_id']['value'] = $project['id'];
            $xMail->emails_fields['static_fields']['title']['value'] = $title;
            
            $project_id = $project['id'];
            
            $xdb_email_insert = new Xdb;
            $insert_new = $xdb_email_insert->db_insert_content($project['id'], $xMail->emails_table, $xMail->emails_fields, strtolower(get_class($xMail)));
            
            if ($edit_for_project == $project['id'])
            {
                $last_id = $insert_new;
            }
            
            return $last_id;
        }
    }
    
    public function email_get_by_id($id)
    {
        $xMail = new Emails;
        $xdb_email = new Xdb;
        $xdb_email_rows = $xdb_email->set_table($xMail->emails_table)
                                    ->db_select_by_id($id, strtolower(get_class($xMail)));
        return $xdb_email_rows;
    }
    
    public function email_get_by_title($title)
    {
        global $project_id;
        
        $xMail = new Emails;
        $xdb_email = new Xdb;
        $xdb_email_rows = $xdb_email->set_table($xMail->emails_table)
                                    ->where(array('title' => $title, 'project_id' => $project_id))
                                    ->limit(1)
                                    ->db_select(true, 0, strtolower(get_class($xMail)));
        
        if (is_array($xdb_email_rows) && isset($xdb_email_rows[0]))
        {
            return $xdb_email_rows[0];
        }
        else
        {
            return false;
        }
    }
    
    public function emails_save($id, $project_id, $new_values)
    {
        $email = new Emails;
        $xdb_emails_update = new Xdb;
        $update = $xdb_emails_update->set_table($email->emails_table)
                                    ->update_fields($project_id, $email->emails_fields, $new_values, 'update')
                                    ->where(array('id' => $id))
                                    ->db_update(strtolower(get_class($email)), array('trashed'));
        
        //$xdb_emails_update->update_permanent_cache_single($email->emails_table, $id);
    }
    
    public function emails_to_trash($id)
    {
        $email = new Emails;
        $projects = Projects::get_all_projects();
        $trashed_object['static_fields']['trashed'] = $email->fields['static_fields']['trashed'];
        $trashed_object['static_fields']['trashed']['value'] = 1;
        foreach ($projects as $project)
        {
            $project_id = $project['id'];
            $xdb_emails_update = new Xdb;
            $update = $xdb_emails_update->set_table($email->emails_table)
                                                 ->update_fields($project_id, $trashed_object)
                                                 ->where(array('id' => $id))
                                                 ->db_update(strtolower(get_class($email)), array('trashed'));
            
            $xdb_emails_update->update_permanent_cache_single($email->emails_table, $id);
        }
    }
}

?>