<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class History extends Module
{
    protected $table;
    public $module;
    public $fields = array();
    
    public function __construct($module_table)
    {
        $this->table = 'history_' . $module_table;
        
        $this->module = $module_table;
        
        $this->fields = 
        array(
        'static_fields' => 
            array(
                'module' => array('value' => '', 'field_type' => 'VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'project_id' => array('value' => '', 'field_type' => 'INT( 11 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'project_name' => array('value' => '', 'field_type' => 'VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'content_id' => array('value' => '', 'field_type' => 'INT( 11 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'content_title' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'user_id' => array('value' => '', 'field_type' => 'INT( 11 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'user_name' => array('value' => '', 'field_type' => 'VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'action' => array('value' => '', 'field_type' => 'VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'datetime' => array('value' => '', 'field_type' => 'DATETIME NOT NULL', 'index' => true, 'admin_editable' => false),
                'system' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                )
            )
        );
        
        $this->load_plugins();
    }
    
    public function write_history($project_id, $content_id, $content_title, $action)
    {
        $this->fields['static_fields']['module']['value'] = $this->module;
        $this->fields['static_fields']['project_id']['value'] = $project_id;
        
        $project_info = Projects::get_project_info_by_id($project_id);
        $this->fields['static_fields']['project_name']['value'] = $project_info['project_name'];
        
        $this->fields['static_fields']['content_id']['value'] = $content_id;
        $this->fields['static_fields']['content_title']['value'] = $content_title;
        
        $this->fields['static_fields']['user_id']['value'] = $_SESSION['admin_user'][0]['id'];
        $this->fields['static_fields']['user_name']['value'] = $_SESSION['admin_user'][0]['username'];
        
        $this->fields['static_fields']['action']['value'] = $action;
        
        $date_time = new DateTime();
        $save_date_time = $date_time->format('Y-m-d H:i:s');
        
        $this->fields['static_fields']['datetime']['value'] = $save_date_time;
                    
        $bc = new Browscap($_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.third.party/browscap.cache/');
        $current_browser = $bc->getBrowser(null, true);
        $this->fields['static_fields']['system']['value'] = json_encode($current_browser);
        
        $xdb_history_insert = new Xdb;
        $last_history_id = $xdb_history_insert->db_insert($this->table, $this->fields);
    }
}