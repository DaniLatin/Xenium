<?php

/**
 * @author Danijel
 * @copyright 2014
 * 
 * @moduleCategory dynamic_content
 * @moduleName Blog
 * @moduleTextEditor true
 * @moduleTextEditorModal true
 * @prepProcessContentSaveFunction prep_process_blog
 * @postProcessContentViewFunction post_process_blog
 */

class Blog extends Module
{
    protected $table;
    public $fields = array();
    
    public function __construct()
    {
        $this->table = 'www_blog';
        
        $this->fields = 
        array(
        'static_fields' => 
            array(
                'project_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'title' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'trashed' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'post_date' => array(
                    'value' => '', 
                    'field_type' => 'DATE NULL', 
                    'index' => true, 
                    'admin_editable' => true, 
                    'html' => array('type' => 'input', 'label' => 'Post Date:'),
                    'html_attributes' => array(
                        'class' => 'input-small html_edit_simple', 
                        'type' => 'text')),
                'image' => array(
                    'value' => '[]', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'index' => false, 
                    'admin_editable' => true, 
                    'html' => array('type' => 'image', 'label' => 'Post Image:')),
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
                'intro' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => true,
                    'html' => array(
                        'type' => 'textarea', 
                        'label' => 'Intro:'), 
                    'html_attributes' => array(
                        'class' => 'span12 html_edit_simple rows12', 
                        'rows' => 6,
                        'type' => 'text')),
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
    
    public function prep_process_blog($text)
    {
        $text = str_replace(' aloha-block aloha-block-DefaultBlock', '', $text);
        $text = str_replace(' data-aloha-block-type="DefaultBlock"', '', $text);
        $text = str_replace('data-sortable-item="[object Object]"', '', $text);
        $text = str_replace(' contenteditable="false"', '', $text);
        $text = preg_replace('#\s(id)="[^"]+"#', '', $text);
        return $text;
    }
    
    public function post_process_blog($text, $group_name, $parent_table, $parent_id)
    {
        global $selected_language, $current_country, $currency, $offer_class, $blog, $image_size, $trim, $title_trim, $exclusion_list, $tb_counter, $previous_group_name, $block_size;
        
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
                    $add_filling = 3;
                    $row_type = 'quarters';
                }
                
                if (strpos($tb_class,'col2') !== false)
                {
                    $block_size = 2;
                    $add_filling = 6;
                    $row_type = 'quarters';
                }
                
                if (strpos($tb_class,'col3') !== false)
                {
                    $block_size = 3;
                    $add_filling = 9;
                    $row_type = 'quarters';
                }
                
                if (strpos($tb_class,'col4') !== false)
                {
                    $block_size = 4;
                    $add_filling = 12;
                    $row_type = 'quarters';
                }
                
                if (strpos($tb_class,'col-one-third') !== false)
                {
                    $block_size = 'one-third';
                    $add_filling = 4;
                    $row_type = 'thirds';
                }
                
                if (strpos($tb_class,'col-two-thirds') !== false)
                {
                    $block_size = 'two-thirds';
                    $add_filling = 8;
                    $row_type = 'thirds';
                }
                
                
                
                
                
                if (strpos($tb_class,'blog') !== false)
                {
                    $tb_id = $div->firstChild->nodeValue;
                    
                    /*
                    $replacable = $dom->saveXML($div);
                    echo $replacable;
                    echo '
                    ';
                    echo $text;*/
                    
                    if ($tb_id && is_numeric($tb_id))
                    {
                        $replacable = $dom->saveXML($div);
                        //echo $tb_id . 'here';
                        //echo $replacable;
                        
                        $blog = Blog::www_post_get_by_id($tb_id);
                        
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
                            $write_parent->function_relations['www_blog_' . $tb_id][$parent_table . '_' . $parent_id] = array('parent_group' => $group_name, 'parent_table' => $parent_table, 'parent_id' => $parent_id);
                            $write_parent->update_function_relations();
                        //}
                        
                        if (is_array($blog) && !$blog['trashed'])
                        {
                            
                            //$replacement = $replacement_front .  TextBlock::load_prop('textblock_page_prop') . $replacement_end;
                            
                            //$filling = $filling + $add_filling;
                            
                            
                            $replacement = Blog::load_prop('blog_page_prop');
                            
                            // BEFORE
                            /*
                            if ($filling == 0)
                            {
                                $filling = $filling + $add_filling;// echo $filling . ' : ';
                                $replacement = $replacement_front .  Blog::load_prop('blog_page_prop') . $add_filling . ' - ' . $filling;
                                //echo $replacement;
                                
                                //$text = str_replace($replacable, $replacement, $text);
                            }
                            else
                            {
                                $filling = $filling + $add_filling;// echo $filling . ' : ';
                                
                                if ($filling == 12)
                                { //echo 'here';
                                    $replacement = Blog::load_prop('blog_page_prop') . '</div>';
                                    //echo $replacement;
                                    $filling = 0;
                                    
                                    //$text = str_replace($replacable, $replacement, $text);
                                }
                                else
                                {
                                    $filling = $filling + $add_filling;
                                    $replacement = Blog::load_prop('blog_page_prop') . $add_filling . ' - ' . $filling;
                                    //echo $replacement;
                                    
                                    //$text = str_replace($replacable, $replacement, $text);
                                }
                            }*/
                            
                            /*
                            if ($filling == 0)
                            {
                                $filling = $filling + $add_filling;
                                $replacement = $replacement_front .  Blog::load_prop('blog_page_prop');
                            }
                            elseif(($row_type == 'thirds' && $filling > 0 && $filling < 66) || ($row_type == 'quarters' && $filling > 0 && ($filling) < 76))
                            //elseif(($row_type == 'thirds' && $filling > 0 && $filling < 66))
                            {
                                $filling = $filling + $add_filling; echo $filling . ' : ';
                                //$replacement = Blog::load_prop('blog_page_prop');
                                if ($filling == 100)
                                {
                                    $replacement = Blog::load_prop('blog_page_prop') . $replacement_end;
                                    $filling = 0;
                                }
                                else
                                {
                                    $replacement = Blog::load_prop('blog_page_prop');
                                }
                            }
                            elseif(($row_type == 'thirds' && $filling >= 66) || ($row_type == 'quarters' && ($filling + $add_filling) >= 76))
                            //elseif(($row_type == 'thirds' && $filling >= 66))
                            {
                                $replacement = Blog::load_prop('blog_page_prop') . $replacement_end;
                                $filling = 0;
                            }
                            elseif($row_type == 'quarters' && $filling >= 74)
                            {
                                $replacement = Blog::load_prop('blog_page_prop') . $replacement_end;
                                $filling = 0;
                            }
                            else
                            {
                                $filling = $filling + $add_filling;
                                $replacement = Blog::load_prop('blog_page_prop');
                            }
                            */
                            
                            $tb_counter++;
                        }
                        else
                        {
                            $replacement = '';
                        }
                        
                        
                        $text = str_replace($replacable, $replacement, $text);
                        
                    }
                    //print_r($offer);
                }
                
                
            }
            
            echo $text;
            
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
        /*echo '
        
        ' . $text;*/
        return $text;
    }
    
    public function blog_menu_add()
    {
        return '<li><a href="/admin/interface/contents/blog_default/" class="follow">Blog</a></li>';
    }
    
    public function blog_text_editor_add()
    {
        //return '<a href="#addOfferModal" class="btn" data-toggle="modal" no-follow="true" title="Insert offer"><i class="fam-icon-cart-add"></i></a>';
        return '<a href="#addBlogModal" class="btn" data-toggle="modal" no-follow="true" title="Insert blog post">B</a>';
    }
    
    public function blog_text_editor_modal_add($project_id = 'all')
    {
        //global $text_blocks;
        
        $projects = Projects::get_all_projects();
        $project_data = array();
        
        foreach ($projects as $project)
        {
            $project_id = $project['id'];
            $project_data[$project_id] = $project;
            
            $blog_posts = Blog::get_all($project_id);
            $project_data[$project_id]['data'] = $blog_posts;
        }
        
        return AdminAction::load_modal_prop('text_editor_modal_insert_blog', $project_data);
        
        /*
        if (is_numeric($project_id))
        {
            
            $text_blocks = TextBlock::www_get_all($project_id);
            //echo AdminAction::load_modal_prop('text_editor_modal_insert_offer', $project_id);
            return AdminAction::load_modal_prop('text_editor_modal_insert_textblock', $project_id);
        }*/
    }
    
    public function get_all()
    {
        $blog = new Blog;
        $xdb_blog = new Xdb;
        $xdb_blog_rows = $xdb_blog->set_table($blog->table)
                                  ->where(array('trashed' => 0))
                                  ->group_by('title')
                                  ->db_select(true, 0, strtolower(get_class($blog)));
        return $xdb_blog_rows;
    }
    
    public function get_by_10($page = 0)
    {
        global $project_id, $selected_language;
        
        $blog = new Blog;
        $xdb_blog = new Xdb;
        $xdb_blog_rows = $xdb_blog->select_fields('id, image, title_' . $selected_language . ', slug_' . $selected_language . ', intro_' . $selected_language)
                                  ->set_table($blog->table)
                                  ->where(array('trashed' => 0, 'title_' . $selected_language . '::!=' => '', 'intro_' . $selected_language . '::!=' => ''))
                                  //->where(array('trashed' => 0))
                                  ->order_by('id', 'DESC')
                                  ->limit($page . ', 10')
                                  ->db_select(false);
        
        foreach ($xdb_blog_rows as $row_key => $row_value)
        {
            if (isset($xdb_blog_rows[$row_key]['image']))
            {
                $image = json_decode(str_replace('&quot;', '"', $row_value['image']), true);
                unset($xdb_blog_rows[$row_key]['image']);
                $xdb_blog_rows[$row_key]['main_image'] = $image[0];
            }
            
            foreach($this->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
            {
                if (isset($xdb_blog_rows[$row_key][$dynamic_field . '_' . $selected_language]))
                {
                    $xdb_blog_rows[$row_key]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_blog_rows[$row_key][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                    unset($xdb_blog_rows[$row_key][$dynamic_field . '_' . $selected_language]);
                }
            }
        }
        //print_r($xdb_blog_rows);
        return $xdb_blog_rows;
    }
    
    public function get_by_id($id)
    {
        $blog = new Blog;
        $xdb_blog = new Xdb;
        $xdb_blog_rows = $xdb_blog->set_table($blog->table)
                                  ->db_select_by_id($id, strtolower(get_class($blog)));
        return $xdb_blog_rows;
    }
    
    public function www_post_get_by_id($id)
    {
        global $selected_language, $project_id;
        
        $blog = new Blog;
        
        $xdb_blog = new Xdb;
        $xdb_blog_rows = $xdb_blog->select_fields('title_' . $selected_language . ', intro_' . $selected_language . ', slug_' . $selected_language . ', image, project_id, id, trashed')
                                  ->set_table($blog->table)
                                  ->db_select_by_id($id, strtolower(get_class($blog)));
        
        if (isset($xdb_blog_rows[0]['image']))
        {
            $image = json_decode(str_replace('&quot;', '"', $xdb_blog_rows[0]['image']), true);
            unset($xdb_blog_rows[0]['image']);
            $xdb_blog_rows[0]['main_image'] = $image[0];
        }
        
        foreach($blog->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
        {
            if (isset($xdb_blog_rows[0][$dynamic_field . '_' . $selected_language]))
            {
                $xdb_blog_rows[0]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_blog_rows[0][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                unset($xdb_blog_rows[0][$dynamic_field . '_' . $selected_language]);
            }
        }
        
        return $xdb_blog_rows[0];
    }
    
    public function get_by_slug($slug)
    {
        global $selected_language, $project_id;
        
        $xdb_blog = new Xdb;
        $xdb_blog_rows = $xdb_blog->set_table($this->table)
                                  ->where(array('slug_' . $selected_language => $slug, 'project_id' => $project_id))
                                  ->limit(1)
                                  ->db_select(true, 0, strtolower(get_class($this)));
        
        if (isset($xdb_blog_rows[0]['image']))
        {
            $image = json_decode(str_replace('&quot;', '"', $xdb_blog_rows[0]['image']), true);
            unset($xdb_blog_rows[0]['image']);
            $xdb_blog_rows[0]['main_image'] = $image[0];
        }
        
        foreach($this->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
        {
            $xdb_blog_rows[0]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_blog_rows[0][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
            unset($xdb_blog_rows[0][$dynamic_field . '_' . $selected_language]);
        }
        
        return $xdb_blog_rows[0];
    }
    
    public function insert_new($title, $edit_for_project)
    {
        $blog = new Blog;
        $projects = Projects::get_all_projects();
        
        $current_time = new DateTime();
        $now = $current_time->format('Y-m-d');
        
        foreach ($projects as $project)
        {
            $blog->fields['static_fields']['project_id']['value'] = $project['id'];
            $blog->fields['static_fields']['title']['value'] = $title;
            $blog->fields['static_fields']['post_date']['value'] = $now;
            
            $xdb_blog_insert = new Xdb;
            $insert_new = $xdb_blog_insert->db_insert_content($project['id'], $blog->table, $blog->fields, strtolower(get_class($blog)));
            
            if ($edit_for_project == $project['id'])
            {
                $last_id = $insert_new;
            }
            
            return $last_id;
        }
        
    }
    
    public function to_trash($id)
    {
        $blog = new Blog;
        $projects = Projects::get_all_projects();
        $trashed_object['static_fields']['trashed'] = $blog->fields['static_fields']['trashed'];
        $trashed_object['static_fields']['trashed']['value'] = 1;
        foreach ($projects as $project)
        {
            $project_id = $project['id'];
            $xdb_blog_update = new Xdb;
            $update = $xdb_blog_update->set_table($blog->table)
                                                 //->update_fields($project_id, $trashed_object)
                                                 ->simple_update_fields(array('trashed' => 1))
                                                 ->where(array('id' => $id))
                                                 ->db_update(strtolower(get_class($blog)), array('trashed'));
            //$xdb_static_contents_update->update_permanent_cache_single($blog->table, $id);
        }
    }
    
    public function save($id, $project_id, $new_values)
    {
        $blog = new Blog;
        $xdb_blog_update = new Xdb;
        $update = $xdb_blog_update->set_table($blog->table)
                                             ->update_fields($project_id, $blog->fields, $new_values, 'update')
                                             ->where(array('id' => $id))
                                             ->db_update(strtolower(get_class($blog)), array('trashed'));
        //$xdb_static_contents_update->update_permanent_cache_single($blog->table, $id);
    }
    
    public function load_prop($prop_name)
    {
        global $selected_language, $current_country, $currency, $offer_class, $blog, $image_size, $trim, $title_trim, $offer_counter, $block_size;
        //echo $offer_counter . ' ';
        
        $project_info = Projects::get_project_info_by_id($blog['project_id']);
        
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
}

?>