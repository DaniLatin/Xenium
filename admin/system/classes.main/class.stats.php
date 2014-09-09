<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class Stats
{
    public $table;
    public $fields = array();
    
    public function __construct()
    {
        $this->fields = 
        array(
        'static_fields' => 
            array(
                //'identifier' => array('value' => '', 'field_type' => 'VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'project_id' => array('value' => '', 'field_type' => 'INT( 11 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'date' => array('value' => '', 'field_type' => 'DATE NOT NULL', 'index' => true, 'admin_editable' => false),
                //'counter' => array('value' => '', 'field_type' => 'INT( 11 ) NOT NULL', 'index' => true, 'admin_editable' => false),
            )
        );
    }
    /*
    public function write_stats($class_identifier, $identifier)
    {
        $stats = new Stats;
        
        $stats->table = 'stats_' . strtolower($class_identifier);
        
        $current_time = new DateTime();
        $now = $current_time->format('Y-m-d');
        
        $check_record = new Xdb;
        $check_record_exists = $check_record->select_fields('id, counter')
                                            ->set_table($stats->table)
                                            ->where(array('identifier' => $identifier, 'date' => $now))
                                            ->limit(1)
                                            ->db_select(false);
        
        if (isset($check_record_exists[0]['id']))
        {
            $id = $check_record_exists[0]['id'];
            $curr_count = $check_record_exists[0]['counter'];
            $new_count = $curr_count + 1;
            
            $update_count = new Xdb;
            $update = $update_count->set_table($stats->table)
                                   ->simple_update_fields(array('counter' => $new_count))
                                   ->where(array('id' => $id))
                                   ->db_update();
        }
        else
        {
            $stats->fields['static_fields']['identifier']['value'] = $identifier;
            $stats->fields['static_fields']['date']['value'] = $now;
            $stats->fields['static_fields']['counter']['value'] = 1;
            
            $xdb_stats_insert = new Xdb;
            $last_stats_id = $xdb_stats_insert->db_insert($stats->table, $stats->fields);
        }
    }
    */
    
    public function write_stats($class_identifier, $key_identifiers = array())
    {
        global $project_id;
        
        $stats = new Stats;
        
        $stats->table = 'stats_' . strtolower($class_identifier);
        
        $current_time = new DateTime();
        $now = $current_time->format('Y-m-d');
        
        $check_record = new Xdb;
        $check_record_exists = $check_record->select_fields('*')
                                            ->set_table($stats->table)
                                            ->where(array('project_id' => $project_id, 'date' => $now))
                                            ->limit(1)
                                            ->db_select(false);
        
        if (isset($check_record_exists[0]['id']))
        {
            $id = $check_record_exists[0]['id'];
            
            foreach($key_identifiers as $key => $key_value)
            {
                if (!is_numeric($key))
                {
                    $base_key = $key;
                }
                else
                {
                    $base_key = $key_value;
                }
                
                if (isset($check_record_exists[0]['counter_' . $base_key]))
                {
                    $curr_count = $check_record_exists[0]['counter_' . $base_key];
                    
                    if (!is_numeric($key) && isset($key_value['value']))
                    {
                        if (isset($key_value['json']))
                        {
                            $base_array = json_decode(str_replace('&quot;', '"', $curr_count), true);
                            $new_array = json_decode(str_replace('&quot;', '"', $key_value['value']), true);
                            
                            foreach ($new_array as $new_key => $new_value)
                            {
                                if (isset($base_array[$new_key]))
                                {
                                    foreach ($new_value as $the_key => $the_value)
                                    {
                                        if (is_numeric($the_value))
                                        {
                                            $base_array[$new_key][$the_key] = $base_array[$new_key][$the_key] + $the_value;
                                        }
                                    }
                                }
                                else
                                {
                                    $base_array[$new_key] = $new_value;
                                }
                            }
                            
                            $new_count = json_encode($base_array);
                            $field_type = 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL';
                        }
                        else
                        {
                            $new_count = $curr_count + $key_value['value'];
                            $field_type = 'DECIMAL( 10,2 ) NOT NULL';
                        }
                    }
                    else
                    {
                        $new_count = $curr_count + 1;
                        $field_type = 'INT( 11 ) NOT NULL';
                    }
                    
                    $update_array['counter_' . $base_key] = $new_count; 
                }
                else
                {
                    if (!is_numeric($key) && isset($key_value['value']))
                    {
                        $update_array['counter_' . $base_key] = $key_value['value']; 
                        
                        if (isset($key_value['json']))
                        {
                            $field_type = 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL';
                        }
                        else
                        {
                            $field_type = 'DECIMAL( 10,2 ) NOT NULL';
                        }
                    }
                    else
                    {
                        $update_array['counter_' . $base_key] = 1;
                        $field_type = 'INT( 11 ) NOT NULL'; 
                    }
                }
                $stats->fields['static_fields']['counter_' . $base_key] = array('value' => $update_array['counter_' . $base_key], 'field_type' => $field_type, 'index' => true, 'admin_editable' => false);
            }
            
            $update_query = http_build_query($update_array, '', 'ANDPARAMETER');
            
            $update_count = new Xdb;
            $update = $update_count->set_table($stats->table)
                                   ->update_fields($project_id, $stats->fields, $update_query, 'update')
                                   ->where(array('id' => $id))
                                   ->db_update();
        }
        else
        {
            /*
            $stats->fields['static_fields']['identifier']['value'] = $identifier;
            $stats->fields['static_fields']['date']['value'] = $now;
            $stats->fields['static_fields']['counter']['value'] = 1;
            */
            $stats->fields['static_fields']['project_id'] = array('value' => $project_id, 'field_type' => 'INT( 11 ) NOT NULL', 'index' => true, 'admin_editable' => false);
            $stats->fields['static_fields']['date'] = array('value' => $now, 'field_type' => 'DATE NOT NULL', 'index' => true, 'admin_editable' => false);
            
            foreach($key_identifiers as $key => $key_value)
            {
                if (!is_numeric($key))
                {
                    $base_key = $key;
                }
                else
                {
                    $base_key = $key_value;
                }
                
                if (!is_numeric($key) && isset($key_value['value']))
                {
                    if (isset($key_value['json']))
                    {
                        $stats->fields['static_fields']['counter_' . $base_key] = array('value' => $key_value['value'], 'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false);
                    }
                    else
                    {
                        $stats->fields['static_fields']['counter_' . $base_key] = array('value' => $key_value['value'], 'field_type' => 'DECIMAL( 10,2 ) NOT NULL', 'index' => true, 'admin_editable' => false);
                    }
                }
                else
                {
                    $stats->fields['static_fields']['counter_' . $base_key] = array('value' => 1, 'field_type' => 'INT( 11 ) NOT NULL', 'index' => true, 'admin_editable' => false);
                }
            }
            
            $xdb_stats_insert = new Xdb;
            $last_stats_id = $xdb_stats_insert->db_insert($stats->table, $stats->fields);
        }
    }
}