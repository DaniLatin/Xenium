<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class StaticContent extends Module
{
    protected $table;
    public $fields = array();
    
    public function __construct()
    {
        $this->table = 'www_static_contents';
        
        $this->fields = 
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
                        'class' => 'input-xxlarge rows12', 
                        'type' => 'text')),
            )
        );
        
        $this->load_plugins();
        
        $this->cache_setting = true;
    }
    
    public function static_contents_get_all()
    {
        $sc = new StaticContent;
        $xdb_static_contents = new Xdb;
        $xdb_static_contents_rows = $xdb_static_contents->set_table($sc->table)
                                                        ->where(array('trashed' => 0))
                                                        ->group_by('title')
                                                        ->db_select(true, 0, strtolower(get_class($sc)));
        return $xdb_static_contents_rows;
    }
    
    public function static_contents_project_all($project_id)
    {
        $xdb_static_contents = new Xdb;
        $xdb_static_contents_rows = $xdb_static_contents->set_table($this->table)
                                                        ->where(array('trashed' => 0, 'project_id' => $project_id))
                                                        ->db_select(true, 0, strtolower(get_class($this)));
        
        return $xdb_static_contents_rows;
    }
    
    public function static_contents_get_by_id($id)
    {
        $sc = new StaticContent;
        $xdb_static_contents = new Xdb;
        $xdb_static_contents_rows = $xdb_static_contents->set_table($sc->table)
                                                        ->db_select_by_id($id, strtolower(get_class($sc)));
        return $xdb_static_contents_rows;
    }
    
    public function static_contents_get_by_slug($slug)
    {
        global $selected_language, $project_id;
        $xdb_static_contents = new Xdb;
        $xdb_static_contents_rows = $xdb_static_contents->set_table($this->table)
                                                        ->where(array('slug_' . $selected_language => $slug, 'project_id' => $project_id))
                                                        ->limit(1)
                                                        ->db_select(true, 0, strtolower(get_class($this)));
        foreach($this->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
        {
            $xdb_static_contents_rows[0]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_static_contents_rows[0][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
            unset($xdb_static_contents_rows[0][$dynamic_field . '_' . $selected_language]);
        }
        return $xdb_static_contents_rows[0];
    }
    
    public function static_contents_get_by_title($title)
    {
        global $selected_language, $project_id;
        $xdb_static_contents = new Xdb;
        $xdb_static_contents_rows = $xdb_static_contents->set_table($this->table)
                                                        ->where(array('title' => $title, 'project_id' => $project_id))
                                                        ->limit(1)
                                                        ->db_select(true, 0, strtolower(get_class($this)));
        foreach($this->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
        {
            if (isset($xdb_static_contents_rows[0][$dynamic_field . '_' . $selected_language]))
            {
                $xdb_static_contents_rows[0]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_static_contents_rows[0][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                unset($xdb_static_contents_rows[0][$dynamic_field . '_' . $selected_language]);
            }
        }
        if (is_array($xdb_static_contents_rows) && isset($xdb_static_contents_rows[0]))
        {
            return $xdb_static_contents_rows[0];
        }
        else
        {
            return false;
        }
    }
    
    public function static_contents_insert_new($title, $edit_for_project)
    {
        $sc = new StaticContent;
        $projects = Projects::get_all_projects();
        foreach ($projects as $project)
        {
            $sc->fields['static_fields']['project_id']['value'] = $project['id'];
            $sc->fields['static_fields']['title']['value'] = $title;
            
            $xdb_static_contents_insert = new Xdb;
            $insert_new = $xdb_static_contents_insert->db_insert_content($project['id'], $sc->table, $sc->fields, strtolower(get_class($sc)));
            
            if ($edit_for_project == $project['id'])
            {
                $last_id = $insert_new;
            }
            
            return $last_id;
        }
        
    }
    
    public function static_contents_to_trash($id)
    {
        $sc = new StaticContent;
        $projects = Projects::get_all_projects();
        $trashed_object['static_fields']['trashed'] = $sc->fields['static_fields']['trashed'];
        $trashed_object['static_fields']['trashed']['value'] = 1;
        foreach ($projects as $project)
        {
            $project_id = $project['id'];
            $xdb_static_contents_update = new Xdb;
            $update = $xdb_static_contents_update->set_table($sc->table)
                                                 //->update_fields($project_id, $trashed_object)
                                                 ->simple_update_fields(array('trashed' => 1))
                                                 ->where(array('id' => $id))
                                                 ->db_update(strtolower(get_class($sc)), array('trashed'));
            //$xdb_static_contents_update->update_permanent_cache_single($sc->table, $id);
        }
    }
    
    public function static_contents_save($id, $project_id, $new_values)
    {
        $sc = new StaticContent;
        $xdb_static_contents_update = new Xdb;
        $update = $xdb_static_contents_update->set_table($sc->table)
                                             ->update_fields($project_id, $sc->fields, $new_values, 'update')
                                             ->where(array('id' => $id))
                                             ->db_update(strtolower(get_class($sc)), array('trashed'));
        //$xdb_static_contents_update->update_permanent_cache_single($sc->table, $id);
    }
    
    public function static_contents_rearrange_array($values_array)
    {
        foreach ($values_array as $value)
        {
            $title = $value['title'];
            unset($value['title']);
            $output_array[$title] = $value;
        }
        
        return $output_array;
    }
    
    public function static_contents_generate_url($title, $project_domain = '')
    {
        global $selected_language, $project_id;
        $all_contents = $this->static_contents_project_all($project_id);
        $all_contents_arranged = $this->static_contents_rearrange_array($all_contents);
        if (isset($all_contents_arranged[$title]))
        {
            $get_content = $all_contents_arranged[$title];
            
            if (!$project_domain)
            {
                $generate_url = '<a href="http://' . $_SERVER['HTTP_HOST'] . '/' . $selected_language . '/' . $get_content['slug_' . $selected_language] . '/">' . html_entity_decode($get_content['title_' . $selected_language], ENT_QUOTES, 'UTF-8') . '</a>';
            }
            else
            {
                $generate_url = '<a href="' . $project_domain . '/' . $selected_language . '/' . $get_content['slug_' . $selected_language] . '/">' . html_entity_decode($get_content['title_' . $selected_language], ENT_QUOTES, 'UTF-8') . '</a>';
            }
            return $generate_url;
        }
        else
        {
            return false;
        }
    }
}

?>