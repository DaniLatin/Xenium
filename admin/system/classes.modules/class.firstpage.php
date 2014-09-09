<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class FirstPage extends Module
{
    protected $table;
    public $fields = array();
    public $cache_setting;
    
    public function __construct()
    {
        $this->table = 'www_first_pages';
        
        $this->fields = 
        array(
        'static_fields' => 
            array(
                'project_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'title' => array(
                    'value' => '', 
                    'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false,
                    'html' => array(
                        'type' => 'input', 
                        'label' => 'Title:'), 
                    'html_attributes' => array(
                        'class' => 'input-xxlarge html_edit_simple', 
                        'type' => 'text')),
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
                        'class' => 'input-xxlarge html_edit_simple', 
                        'type' => 'text')),
                'slug' => array(
                    'value' => '', 
                    'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false),
                'description' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => true,
                    'html' => array(
                        'type' => 'textarea', 
                        'label' => 'Description:'), 
                    'html_attributes' => array(
                        'class' => 'span12 html_edit_advanced rows12', 
                        'rows' => 16,
                        'style' => 'height: 600px;',
                        'type' => 'text')),
                'seo_description' => array(
                    'value' => '', 
                    'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => true,
                    'html' => array(
                        'type' => 'textarea', 
                        'label' => 'SEO description:'), 
                    'html_attributes' => array(
                        'class' => 'span12 rows12', 
                        'type' => 'text')),
                'seo_keywords' => array(
                    'value' => '', 
                    'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => true,
                    'html' => array(
                        'type' => 'input', 
                        'label' => 'SEO keywords:'), 
                    'html_attributes' => array(
                        'class' => 'input-xxlarge', 
                        'type' => 'text')),
            )
        );
        
        $this->load_plugins();
        
        $this->cache_setting = true;
    }
    
    public function first_page_insert_new($project_id)
    {
        $fp = new FirstPage;
        
        $fp->fields['static_fields']['project_id']['value'] = $project_id;
        
        $xdb_first_page_insert = new Xdb;
        //$insert_new = $xdb_first_page_insert->db_insert_content($project['id'], $fp->table, $fp->fields, strtolower(get_class($fp)));
        $insert_new = $xdb_first_page_insert->db_insert_content($project_id, $fp->table, $fp->fields, strtolower(get_class($fp)));
        
        return $last_id;
    }
    
    public function first_page_get_by_project_id($project_id)
    {
        $GLOBALS['project_id'] = $project_id;
        
        $fp = new FirstPage;
        $xdb_first_page = new Xdb;
        $xdb_first_page_rows = $xdb_first_page->set_table($fp->table)
                                              ->where(array('project_id' => $project_id))
                                              ->limit(1)
                                              ->db_select(true, 0, strtolower(get_class($fp)));
        if (!is_array($xdb_first_page_rows))
        {
            FirstPage::first_page_insert_new($project_id);
        }
        
        return $xdb_first_page_rows;
    }
    
    public function first_page_www_get_by_project_id($project_id)
    {
        global $selected_language, $current_country, $currency;
        
        $currency = Countries::get_country_currency();
        $fp = new FirstPage;
        $xdb_first_page = new Xdb;
        $xdb_first_page_rows = $xdb_first_page->set_table($fp->table)
                                              ->where(array('project_id' => $project_id))
                                              ->limit(1)
                                              ->db_select(true, 0, strtolower(get_class($fp)));
        //print_r($xdb_first_page_rows[0]['description_en']);
        //echo $xdb_first_page_rows[0]['description_en'];
        foreach($fp->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
        {
            $xdb_first_page_rows[0]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_first_page_rows[0][$dynamic_field . '_' . $selected_language]);
            unset($xdb_first_page_rows[0][$dynamic_field . '_' . $selected_language]);
        }
        
        return $xdb_first_page_rows[0];
    }
    
    public function first_page_save($id, $project_id, $new_values)
    {
        $GLOBALS['project_id'] = $project_id;
        echo $project_id;
        $fp = new FirstPage;
        $xdb_first_page_update = new Xdb;
        $update = $xdb_first_page_update->set_table($fp->table)
                                        ->update_fields($project_id, $fp->fields, $new_values, 'update')
                                        ->where(array('id' => $id))
                                        ->db_update(strtolower(get_class($fp)), array('project_id'));
        $xdb_first_page_update->update_permanent_cache_single($fp->table, $id);
    }
}

?>