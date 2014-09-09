<?php

/**
 * @author Danijel
 * @copyright 2013
 * 
 * @moduleCategory dynamic_content
 * @moduleName Slideshow
 */

class Slideshow extends Module
{
    protected $table;
    public $fields = array();
    
    public function __construct()
    {
        $this->table = 'www_slideshow';
        
        $this->fields = 
        array(
        'static_fields' => 
            array(
                'project_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'title' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'position' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'trashed' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false)
            ),
        'dynamic_fields' =>
            array(
                'image' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => true,
                    'html' => array(
                        'type' => 'textarea', 
                        'label' => 'Description:'), 
                    'html_attributes' => array(
                        'class' => 'span12 html_edit_advanced', 
                        'rows' => 8,
                        'type' => 'text')),
                'url' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'url_opening' => array('value' => '', 'field_type' => 'VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
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
        
        $this->load_plugins();
        
        $this->cache_setting = true;
    }
    
    public function slideshow_menu_add()
    {
        return '<li><a href="/admin/interface/contents/slideshow_default/" class="follow">Slideshow</a></li>';
    }
    
    public function slideshow_get_all()
    {
        $slide = new Slideshow;
        $xdb_slideshows = new Xdb;
        $xdb_slideshows_rows = $xdb_slideshows->set_table($slide->table)
                                              ->where(array('trashed' => 0))
                                              ->group_by('title')
                                              ->order_by('position')
                                              //->db_select(true, 0, strtolower(get_class($slide)));
                                              ->db_select(false);
        return $xdb_slideshows_rows;
    }
    
    public function slideshow_get_by_id($id)
    {
        $slide = new Slideshow;
        $xdb_slideshow = new Xdb;
        $xdb_slideshow_rows = $xdb_slideshow->set_table($slide->table)
                                            ->db_select_by_id($id, strtolower(get_class($slide)));
        return $xdb_slideshow_rows;
    }
    
    public function slideshow_insert_new($title, $edit_for_project)
    {
        $slide = new Slideshow;
        $projects = Projects::get_all_projects();
        
        $xdb_slideshows = new Xdb;
        $xdb_slideshows_min = $xdb_slideshows->select_fields('MIN(position)')
                                             ->set_table($slide->table)
                                             ->db_select(false);
        
        $min_position = $xdb_slideshows_min[0]['MIN(position)'];
        $new_position = $min_position -1;
        
        foreach ($projects as $project)
        {
            $slide->fields['static_fields']['project_id']['value'] = $project['id'];
            $slide->fields['static_fields']['title']['value'] = $title;
            $slide->fields['static_fields']['position']['value'] = $new_position;
            
            $xdb_slideshow_insert = new Xdb;
            $insert_new = $xdb_slideshow_insert->db_insert_content($project['id'], $slide->table, $slide->fields, strtolower(get_class($slide)));
            
            if ($edit_for_project == $project['id'])
            {
                $last_id = $insert_new;
            }
            
            return $last_id;
        }
        
    }
    
    public function slideshow_save($id, $project_id, $new_values)
    {
        $slide = new Slideshow;
        $xdb_slideshow_update = new Xdb;
        $update = $xdb_slideshow_update->set_table($slide->table)
                                       ->update_fields($project_id, $slide->fields, $new_values, 'update')
                                       ->where(array('id' => $id))
                                       ->db_update(strtolower(get_class($slide)), array('trashed'));
        //$xdb_slideshow_update->update_permanent_cache_single($slide->table, $id);
    }
    
    public function slideshow_to_trash($id)
    {
        $slide = new Slideshow;
        $projects = Projects::get_all_projects();
        $trashed_object['static_fields']['trashed'] = $slide->fields['static_fields']['trashed'];
        $trashed_object['static_fields']['trashed']['value'] = 1;
        foreach ($projects as $project)
        {
            $project_id = $project['id'];
            $xdb_slideshow_update = new Xdb;
            $update = $xdb_slideshow_update->set_table($slide->table)
                                           //->update_fields($project_id, $trashed_object)
                                           ->simple_update_fields(array('trashed' => 1))
                                           ->where(array('id' => $id))
                                           ->db_update(strtolower(get_class($slide)), array('trashed'));
            $xdb_slideshow_update->update_permanent_cache_single($slide->table, $id);
        }
    }
    
    public function slideshow_www_get_all()
    {
        global $selected_language, $project_id;
        
        $slide = new Slideshow;
        $xdb_slideshows = new Xdb;
        $xdb_slideshows_rows = $xdb_slideshows->set_table($slide->table)
                                              ->where(array('trashed' => 0, 'project_id' => $project_id))
                                              ->order_by('position')
                                              ->db_select(true, 0, strtolower(get_class($slide)));
        
        $row_counter = 0;
        foreach($xdb_slideshows_rows as $key => $row)
        {
            foreach($slide->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
            {
                if (isset($row['image_' . $selected_language]) && $row['image_' . $selected_language] && $row['image_' . $selected_language] != '')
                {
                    $image = $row['image_' . $selected_language];
                    $image_decoded = json_decode(str_replace('&quot;', '"', $image), true);
                    $xdb_slideshows_rows[$key]['dynamic_image'] = $image_decoded[0];
                    
                    //if ($row_counter == 0 && file_exists('http://' . $_SERVER['HTTP_HOST'] . '/images/1920x350/' . $image_decoded[0]['fileYear'] . '/' . $image_decoded[0]['fileMonth'] . '/' . $image_decoded[0]['fileName']))
                    /*
                    if ($row_counter == 0)
                    {
                        $image_filename = '/images/1920x350/' . $image_decoded[0]['fileYear'] . '/' . $image_decoded[0]['fileMonth'] . '/' . $image_decoded[0]['fileName'];
                        $image_memcache = sha1($image_filename);
                        
                        $get_image = new xMemcache;
                        $get_image_set = $get_image->get_memcache($image_memcache);
                        if ($get_image_set)
                        {
                            $image_contents = gzdecode($get_image_set); //echo 'loaded from memcache';
                        }
                        else
                        {
                            $image_contents = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/images/1920x350/' . $image_decoded[0]['fileYear'] . '/' . $image_decoded[0]['fileMonth'] . '/' . $image_decoded[0]['fileName']);
                            $get_image->set_memcache($image_memcache, gzencode($image_contents));
                        }
                        
                        $xdb_slideshows_rows[$key]['dynamic_image_64'] = 'data:image/jpeg;base64,' . base64_encode($image_contents);
                    }*/
                    
                    $xdb_slideshows_rows[$key]['dynamic_description'] = html_entity_decode($row['description_' . $selected_language], ENT_QUOTES, 'UTF-8');
                    $xdb_slideshows_rows[$key]['dynamic_url'] = html_entity_decode($row['url_' . $selected_language], ENT_QUOTES, 'UTF-8');
                    $xdb_slideshows_rows[$key]['dynamic_url_opening'] = html_entity_decode($row['url_opening_' . $selected_language], ENT_QUOTES, 'UTF-8');
                    unset($xdb_slideshows_rows[$key]['image_' . $selected_language]);
                    unset($xdb_slideshows_rows[$key]['description_' . $selected_language]);
                }
                else
                {
                   unset($xdb_slideshows_rows[$key]);
                }
            }
            
            $row_counter++;
        }
        
        return $xdb_slideshows_rows;
    }
    
    public function slideshow_set_order($order)
    {
        $slide = new Slideshow;
        //echo $order;
        $order = str_replace('ANDPARAMETER', '&', $order);
        parse_str($order, $new_order);
        $projects = Projects::get_all_projects();
        /*
        foreach ($new_order['content'] as $position => $item)
        {
            //$sql[] = "UPDATE `table` SET `position` = $position WHERE `id` = $item";
            $order_object['static_fields']['position'] = $slide->fields['static_fields']['position'];
            $order_object['static_fields']['position']['value'] = $position;
        }
        */
        //print_r($new_order);
        foreach ($projects as $project)
        {
            foreach ($new_order['content'] as $position => $item)
            {
                //echo $position;
                //$sql[] = "UPDATE `table` SET `position` = $position WHERE `id` = $item";
                $order_object['static_fields']['position'] = $slide->fields['static_fields']['position'];
                $order_object['static_fields']['position']['value'] = $position;
            
                $project_id = $project['id'];
                $xdb_slideshow_update = new Xdb;
                $update = $xdb_slideshow_update->set_table($slide->table)
                                               //->update_fields($project_id, $order_object)
                                               //->update_fields($project_id, $slide->fields, $new_values, 'update')
                                               ->simple_update_fields(array('position' => $position))
                                               ->where(array('id' => $item))
                                               ->db_update(strtolower(get_class($slide)), array('trashed'));
                //$xdb_slideshow_update->update_permanent_cache_single($slide->table, $item);
            }
        }
    }
}

 ?>