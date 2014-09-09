<?php

/**
 * @author Danijel
 * @copyright 2013
 * 
 * @moduleCategory dynamic_content
 * @moduleName TextBlock
 * @moduleTextEditor true
 * @moduleTextEditorModal true
 * @prepProcessContentSaveFunction prep_process_textblock
 * @postProcessContentViewFunction post_process_textblock
 */

class TextBlock extends Module
{
    protected $table;
    public $fields = array();
    
    public function __construct()
    {
        $this->table = 'www_text_blocks';
        
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
            )
        );
        
        $this->load_plugins();
        
        $this->cache_setting = true;
    }
    
    public function prep_process_textblock($text)
    {
        $text = str_replace(' aloha-block aloha-block-DefaultBlock', '', $text);
        $text = str_replace(' data-aloha-block-type="DefaultBlock"', '', $text);
        $text = str_replace('data-sortable-item="[object Object]"', '', $text);
        $text = str_replace(' contenteditable="false"', '', $text);
        $text = preg_replace('#\s(id)="[^"]+"#', '', $text);
        return $text;
    }
    
    public function post_process_textblock($text, $group_name, $parent_table, $parent_id)
    {
        global $selected_language, $current_country, $currency, $offer_class, $textblock, $image_size, $trim, $title_trim, $exclusion_list, $tb_counter, $previous_group_name, $block_size;
        
        if (!isset($previous_group_name))
        {
            $previous_group_name = '';
        }
        
        if (!isset($tb_counter) || $previous_group_name != $group_name);
        {
            $tb_counter = 0;
        }
        
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        if ($text)
        {
            $dom = new DOMDocument('1.0', 'UTF-8');
            @$dom->loadHTML('<?xml encoding="UTF-8">' . $text); 
            $dom->encoding = 'utf-8';
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = false;
            $divs = $dom->getElementsByTagName('div');
            
            $text = $dom->saveXML();
            
            $filling = 0;
            
            $replacement_front = '<div class="row">';
            $replacement_end = '</div>';
            
            //$blocks_num = count($divs); echo 'blocks ' . $blocks_num;
            //$current_block_num = 0;
            
            foreach($divs as $div)
            {
                $tb_class = $div->getAttribute('class');
                
                if (strpos($tb_class,'col1') !== false)
                {
                    $block_size = 1;
                    $add_filling = 25;
                    $row_type = 'quarters';
                }
                
                if (strpos($tb_class,'col2') !== false)
                {
                    $block_size = 2;
                    $add_filling = 50;
                    $row_type = 'quarters';
                }
                
                if (strpos($tb_class,'col3') !== false)
                {
                    $block_size = 3;
                    $add_filling = 75;
                    $row_type = 'quarters';
                }
                
                if (strpos($tb_class,'col4') !== false)
                {
                    $block_size = 4;
                    $add_filling = 100;
                    $row_type = 'quarters';
                }
                
                if (strpos($tb_class,'col-one-third') !== false)
                {
                    $block_size = 'one-third';
                    $add_filling = 33;
                    $row_type = 'thirds';
                }
                
                if (strpos($tb_class,'col-two-thirds') !== false)
                {
                    $block_size = 'two-thirds';
                    $add_filling = 66;
                    $row_type = 'thirds';
                }
                
                
                
                
                
                if (strpos($tb_class,'textblock') !== false)
                {
                    $tb_id = $div->firstChild->nodeValue;
                    
                    
                    //echo $replacable;
                    //echo $text;
                    
                    if ($tb_id && is_numeric($tb_id))
                    {
                        $replacable = $dom->saveXML($div);
                        
                        $textblock = TextBlock::www_post_get_by_id($tb_id);
                        
                        //$exclusion_list[] = $offer_id;
                        
                        /*
                        $query_memcache = new Xdb;
                        $query_memcache_key = $query_memcache->set_table('www_offers')
                                                                 ->where(array('id' => $offer_id, 'trashed' => 0))
                                                                 ->limit(1)
                                                                 ->db_select_get_memcache_key();
                        $query_memcache->function_relations[$query_memcache_key][$parent_table . '_' . $parent_id] = array('parent_table' => $parent_table, 'parent_id' => $parent_id);
                        $query_memcache->update_function_relations();
                        */
                        
                        //$admin_request = strpos($_SERVER['REQUEST_URI'], '/admin/');
                        //if ($admin_request === false)
                        //{
                            $write_parent = new Xdb;
                            $write_parent->function_relations['www_text_blocks_' . $tb_id][$parent_table . '_' . $parent_id] = array('parent_group' => $group_name, 'parent_table' => $parent_table, 'parent_id' => $parent_id);
                            $write_parent->update_function_relations();
                        //}
                        
                        if (is_array($textblock) && !$textblock['trashed'])
                        {
                            //$replacement = $replacement_front .  TextBlock::load_prop('textblock_page_prop') . $replacement_end;
                            
                            $replacement = TextBlock::load_prop('textblock_page_prop');
                            
                            /*
                            if ($filling == 0)
                            {
                                $filling = $filling + $add_filling;
                                $replacement = $replacement_front .  TextBlock::load_prop('textblock_page_prop');
                            }
                            elseif(($row_type == 'thirds' && $filling > 0 && $filling < 66) || ($row_type == 'quarters' && $filling > 0 && ($filling) < 50))
                            //elseif(($row_type == 'thirds' && $filling > 0 && $filling < 66))
                            {
                                $filling = $filling + $add_filling;
                                $replacement = TextBlock::load_prop('textblock_page_prop');
                            }
                            elseif(($row_type == 'thirds' && $filling >= 66) || ($row_type == 'quarters' && ($filling + $add_filling) >= 76))
                            //elseif(($row_type == 'thirds' && $filling >= 66))
                            {
                                $replacement = TextBlock::load_prop('textblock_page_prop') . $replacement_end;
                                $filling = 0;
                            }
                            else
                            {
                                $filling = $filling + $add_filling;
                                $replacement = TextBlock::load_prop('textblock_page_prop');
                            }*/
                            
                            $tb_counter++;
                        }
                        else
                        {
                            $replacement = '';
                        }
                        //echo $replacable;
                        $text = str_replace($replacable, $replacement, $text);
                        
                    }
                    //print_r($offer);
                }
                
                
            }
            
            $text = str_replace('</body></html>', '', $text);
            $text = str_replace('<html><body>', '', $text);
            $text = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">', '', $text);
            $text = str_replace('<?xml version="1.0" standalone="yes"?>', '', $text);
            $text = str_replace('<?xml version="1.0" encoding="utf-8" standalone="yes"?>', '', $text);
            $text = str_replace('<?xml encoding="UTF-8"?>', '', $text);
        } 
        
        //$offer_counter++;
        //echo $offer_counter;
        $previous_group_name = $group_name;
        
        return $text;
    }
    
    public function textblock_menu_add()
    {
        return '<li><a href="/admin/interface/contents/textblock_default/" class="follow">Text blocks</a></li>';
    }
    
    public function textblock_text_editor_add()
    {
        //return '<a href="#addOfferModal" class="btn" data-toggle="modal" no-follow="true" title="Insert offer"><i class="fam-icon-cart-add"></i></a>';
        return '<a href="#addTextBlockModal" class="btn" data-toggle="modal" no-follow="true" title="Insert text block"><i class="icon-file-text-alt"></i></a>';
    }
    
    public function textblock_text_editor_modal_add($project_id = 'all')
    {
        //global $text_blocks;
        
        $projects = Projects::get_all_projects();
        $project_data = array();
        
        foreach ($projects as $project)
        {
            $project_id = $project['id'];
            $project_data[$project_id] = $project;
            
            $text_blocks = TextBlock::www_get_all($project_id);
            $project_data[$project_id]['data'] = $text_blocks;
        }
        
        return AdminAction::load_modal_prop('text_editor_modal_insert_textblock', $project_data);
        
        /*
        if (is_numeric($project_id))
        {
            
            $text_blocks = TextBlock::www_get_all($project_id);
            //echo AdminAction::load_modal_prop('text_editor_modal_insert_offer', $project_id);
            return AdminAction::load_modal_prop('text_editor_modal_insert_textblock', $project_id);
        }*/
    }
    
    public function www_post_get_by_id($id)
    {
        global $selected_language, $current_country, $project_id;
        
        $selected_country = strtolower($current_country);
        $tb = new TextBlock;
        $xdb_tb = new Xdb;
        $xdb_tb_rows = $xdb_tb->select_fields('description_' . $selected_language . ', id, project_id, trashed')
                                    ->set_table($tb->table)
                                    ->db_select_by_id_not_trashed($id, strtolower(get_class($tb)), true, 0, false);
        
        if (isset($xdb_tb_rows[0]))
        {
            foreach($tb->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
            {
                if (isset($xdb_tb_rows[0][$dynamic_field . '_' . $selected_language]))
                {
                    $xdb_tb_rows[0]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_tb_rows[0][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                    unset($xdb_tb_rows[0][$dynamic_field . '_' . $selected_language]);
                }
            }
            
            return $xdb_tb_rows[0];
        }
        else
        {
            return false;
        }
    }
    
    public function load_prop($prop_name)
    {
        global $selected_language, $current_country, $currency, $offer_class, $textblock, $image_size, $trim, $title_trim, $offer_counter, $block_size;
        //echo $offer_counter . ' ';
        
        $project_info = Projects::get_project_info_by_id($textblock['project_id']);
        
        $filename = $_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_info['project_slug'] . '/templates/' . $project_info['template_slug'] . '/props/' . $prop_name . '.php';
        
        if (file_exists($filename))
        {
            ob_start();
            include($filename);
            $prop = ob_get_contents();
            ob_end_clean();
        }
        else
        {
            ob_start();
            include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.modules/props.' . strtolower(get_called_class()) . '/' . $prop_name . '.php');
            $prop = ob_get_contents();
            ob_end_clean();
        }
        
        return $prop;
    }
    
    public function www_get_all($project_id)
    {
        $tb = new TextBlock;
        $xdb_text_blocks = new Xdb;
        $xdb_text_blocks_rows = $xdb_text_blocks->set_table($tb->table)
                                                ->where(array('trashed' => 0, 'project_id' => $project_id))
                                                ->db_select(true, 0, strtolower(get_class($tb)));
        return $xdb_text_blocks_rows;
    }
    
    public function get_all()
    {
        $tb = new TextBlock;
        $xdb_static_contents = new Xdb;
        $xdb_static_contents_rows = $xdb_static_contents->set_table($tb->table)
                                                        ->where(array('trashed' => 0))
                                                        ->group_by('title')
                                                        ->db_select(true, 0, strtolower(get_class($tb)));
        return $xdb_static_contents_rows;
    }
    
    public function get_by_id($id)
    {
        $tb = new TextBlock;
        $xdb_static_contents = new Xdb;
        $xdb_static_contents_rows = $xdb_static_contents->set_table($tb->table)
                                                        ->db_select_by_id($id, strtolower(get_class($tb)));
        return $xdb_static_contents_rows;
    }
    
    public function insert_new($title, $edit_for_project)
    {
        $tb = new TextBlock;
        $projects = Projects::get_all_projects();
        foreach ($projects as $project)
        {
            $tb->fields['static_fields']['project_id']['value'] = $project['id'];
            $tb->fields['static_fields']['title']['value'] = $title;
            
            $xdb_static_contents_insert = new Xdb;
            $insert_new = $xdb_static_contents_insert->db_insert_content($project['id'], $tb->table, $tb->fields, strtolower(get_class($tb)));
            
            if ($edit_for_project == $project['id'])
            {
                $last_id = $insert_new;
            }
            
            return $last_id;
        }
        
    }
    
    public function to_trash($id)
    {
        $tb = new TextBlock;
        $projects = Projects::get_all_projects();
        $trashed_object['static_fields']['trashed'] = $tb->fields['static_fields']['trashed'];
        $trashed_object['static_fields']['trashed']['value'] = 1;
        foreach ($projects as $project)
        {
            $project_id = $project['id'];
            $xdb_static_contents_update = new Xdb;
            $update = $xdb_static_contents_update->set_table($tb->table)
                                                 //->update_fields($project_id, $trashed_object)
                                                 ->simple_update_fields(array('trashed' => 1))
                                                 ->where(array('id' => $id))
                                                 ->db_update(strtolower(get_class($tb)), array('trashed'));
            //$xdb_static_contents_update->update_permanent_cache_single($sc->table, $id);
        }
    }
    
    public function save($id, $project_id, $new_values)
    {
        $tb = new TextBlock;
        $xdb_static_contents_update = new Xdb;
        $update = $xdb_static_contents_update->set_table($tb->table)
                                             ->update_fields($project_id, $tb->fields, $new_values, 'update')
                                             ->where(array('id' => $id))
                                             ->db_update(strtolower(get_class($tb)), array('trashed'));
        //$xdb_static_contents_update->update_permanent_cache_single($sc->table, $id);
    }
}

?>