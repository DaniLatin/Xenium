<?php

/**
 * @author Danijel
 * @copyright 2013
 * 
 * @moduleCategory dynamic_content
 * @moduleName Offer
 * @moduleTextEditor true
 * @moduleTextEditorModal true
 * @prepProcessContentSaveFunction prep_process_offer
 * @postProcessContentViewFunction post_process_offer
 */

class Offer extends Module
{
    protected $table;
    public $fields = array();
    
    protected $category_table;
    public $category_fields = array();
    
    protected $history_table;
    public $history_fields = array();
    
    public function __construct()
    {
        $this->table = 'www_offers';
        
        $this->fields = 
        array(
        'static_fields' => 
            array(
                'project_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'title' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'category' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'seller_name' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'seller_brandname' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'seller_address' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'seller_city' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'seller_phone' => array('value' => '', 'field_type' => 'VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'seller_email' => array('value' => '', 'field_type' => 'VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'seller_website' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'seller_lat' => array('value' => '', 'field_type' => 'DECIMAL( 20,16 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'seller_lng' => array('value' => '', 'field_type' => 'DECIMAL( 20,16 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'seller_logo' => array(
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
                'voucher_validity_from' => array('value' => '', 'field_type' => 'DATE NULL', 'index' => true, 'admin_editable' => false),
                'voucher_validity_to' => array('value' => '', 'field_type' => 'DATE NULL', 'index' => true, 'admin_editable' => false),
                'voucher_persons' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'published' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'trashed' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'voucher_used' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'voucher_print_limit' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false)
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
                        'label' => 'Description:'), 
                    'html_attributes' => array(
                        'class' => 'span12 html_edit_advanced', 
                        'rows' => 8,
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
                        'type' => 'text')),
                'offer_includes' => array(
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
                'notes' => array(
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
                'seo_description' => array(
                    'value' => '', 
                    'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => true,
                    'html' => array(
                        'type' => 'textarea', 
                        'label' => 'SEO description:'), 
                    'html_attributes' => array(
                        'class' => 'span12', 
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
        
        $this->category_table = 'www_offers_categories';
        
        $this->category_fields = 
        array(
        'static_fields' => 
            array(
                'project_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'parent_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'position' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'title' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'relation' => array('value' => '', 'field_type' => 'VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'offer_count' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false)
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
                        'class' => 'span12 html_edit_advanced', 
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
                        'class' => 'span12', 
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
        
        $this->history_table = 'history_offers';
        
        $this->history_fields = 
        array(
        'static_fields' => 
            array(
                'project_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'offer_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                //'offer_title' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'time_changed' => array('value' => '', 'field_type' => 'DATETIME NULL', 'index' => true, 'admin_editable' => false),
                'user' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false)
            ),
        );
        
        $this->load_plugins();
        
        $this->cache_setting = true;
    }
    
    public function prep_process_offer($text)
    {
        $text = str_replace(' aloha-block aloha-block-DefaultBlock', '', $text);
        $text = str_replace(' data-aloha-block-type="DefaultBlock"', '', $text);
        $text = str_replace('data-sortable-item="[object Object]"', '', $text);
        $text = str_replace(' contenteditable="false"', '', $text);
        $text = preg_replace('#\s(id)="[^"]+"#', '', $text);
        return $text;
    }
    
    public function post_process_offer($text, $group_name, $parent_table, $parent_id)
    {
        global $selected_language, $current_country, $currency, $offer_class, $offer, $image_size, $trim, $title_trim, $exclusion_list, $offer_counter, $previous_group_name;
        
        //echo $group_name . '::' . $parent_table . '--';
        //echo $previous_group_name;
        if (!isset($previous_group_name))
        {
            $previous_group_name = '';
        }
        
        if (!isset($offer_counter) || $previous_group_name != $group_name);
        {
            $offer_counter = 0;
        }
        
        $project_domain = 'http://' . $_SERVER['HTTP_HOST'];
        
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
            
            foreach($divs as $div)
            {
                $offer_class = $div->getAttribute('class');
                
                if (strpos($offer_class,'col1') !== false)
                {
                    $image_size = '260x250';
                    $title_trim = 35;
                    $trim = 60;
                }
                
                if (strpos($offer_class,'col2') !== false)
                {
                    $image_size = '540x250';
                    $title_trim = false;
                    $trim = 150;
                }
                
                if (strpos($offer_class,'col3') !== false)
                {
                    $image_size = '820x250';
                    $title_trim = false;
                    $trim = 225;
                }
                
                if (strpos($offer_class,'col4') !== false)
                {
                    $image_size = '1100x360';
                    $title_trim = false;
                    $trim = 225;
                }
                
                if (strpos($offer_class,'offer') !== false)
                {
                    $offer_id = $div->firstChild->nodeValue;
                    
                    
                    //echo $replacable;
                    //echo $text;
                    
                    if ($offer_id && is_numeric($offer_id))
                    {
                        $replacable = $dom->saveXML($div);
                        
                        $offer = Offer::offer_www_post_get_by_id($offer_id);
                        
                        $exclusion_list[] = $offer_id;
                        
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
                            $write_parent->function_relations['www_offers_' . $offer_id][$parent_table . '_' . $parent_id] = array('parent_group' => $group_name, 'parent_table' => $parent_table, 'parent_id' => $parent_id);
                            $write_parent->update_function_relations();
                        //}
                        
                        if (is_array($offer) && !$offer['trashed'])
                        {
                            $replacement = Offer::load_prop('offer_first_page_prop');
                            $offer_counter++;
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
    
    public function offer_menu_add()
    {
        return '<li><a href="/admin/interface/contents/offers_default/" class="follow">Offers</a></li>';
    }
    
    public function offer_text_editor_add()
    {
        //return '<a href="#addOfferModal" class="btn" data-toggle="modal" no-follow="true" title="Insert offer"><i class="fam-icon-cart-add"></i></a>';
        return '<a href="#addOfferModal" class="btn" data-toggle="modal" no-follow="true" title="Insert offer"><i class="icon-shopping-cart"></i></a>';
    }
    
    public function offer_text_editor_modal_add($project_id = 'all')
    {
        if (is_numeric($project_id))
        {
            //echo AdminAction::load_modal_prop('text_editor_modal_insert_offer', $project_id);
            return AdminAction::load_modal_prop('text_editor_modal_insert_offer', $project_id);
        }
    }
    
    public function get_first_page_offers()
    {
        global $selected_language, $current_country, $currency, $offer_class, $offer, $image_size, $trim, $title_trim, $exclusion_list;
        
        $offer_ids = array();
        $_SERVER['REQUEST_URI'] = '/admin/';
        $project_domain = 'http://' . $_SERVER['HTTP_HOST'];
        $first_page = FirstPage::first_page_get_by_project_id(1);
        $text = $first_page[0]['description_' . $selected_language];
        
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
            
            foreach($divs as $div)
            {
                $offer_class = $div->getAttribute('class');
                
                if (strpos($offer_class,'offer') !== false)
                {
                    $offer_id = $div->firstChild->nodeValue;
                    
                    
                    //echo $replacable;
                    //echo $text;
                    
                    if ($offer_id && is_numeric($offer_id))
                    {
                        $offer_ids[] = $offer_id;
                    }
                    //print_r($offer);
                }
            }
        }
        return Offer::offers_www_get_by_ids_ios($offer_ids);
    }
    
    public function offers_get_all()
    {
        $offer = new Offer;
        $xdb_offers = new Xdb;
        $xdb_offers_rows = $xdb_offers->set_table($offer->table)
                                      ->where(array('trashed' => 0))
                                      ->group_by('title')
                                      ->db_select(true, 0, strtolower(get_class($offer)));
        return $xdb_offers_rows;
    }
    
    public function offers_get_all_nocache()
    {
        $offer = new Offer;
        $xdb_offers = new Xdb;
        $xdb_offers_rows = $xdb_offers->set_table($offer->table)
                                      ->where(array('seller_lat' => 0, 'seller_lng' => 0))
                                      //->group_by('title')
                                      ->limit(90)
                                      ->db_select(false);
        return $xdb_offers_rows;
    }
    
    public function offers_get_count()
    {
        $offer = new Offer;
        $xdb_offers = new Xdb;
        $xdb_offers_rows = $xdb_offers->select_fields('COUNT(*)')
                                      ->set_table($offer->table)
                                      ->where(array('trashed' => 0, 'project_id' => 1))
                                      //->group_by('title')
                                      ->db_select(true, 0, strtolower(get_class($offer)));
        
        return $xdb_offers_rows[0]['COUNT(*)'];
    }
    
    public function offers_get_count_trashed()
    {
        $offer = new Offer;
        $xdb_offers = new Xdb;
        $xdb_offers_rows = $xdb_offers->select_fields('COUNT(*)')
                                      ->set_table($offer->table)
                                      ->where(array('trashed' => 1, 'project_id' => 1))
                                      //->group_by('title')
                                      ->db_select(true, 0, strtolower(get_class($offer)));
        
        return $xdb_offers_rows[0]['COUNT(*)'];
    }
    
    public function offers_www_get_count($project_id, $selected_language)
    {
        $offer = new Offer;
        $xdb_offers = new Xdb;
        $xdb_offers_rows = $xdb_offers->select_fields('COUNT(*)')
                                      ->set_table($offer->table)
                                      ->where(array('trashed' => 0, 'project_id' => $project_id, 'title_' . $selected_language . '::!=' => ''))
                                      //->group_by('title')
                                      //->db_select(true, 0, strtolower(get_class($offer)));
                                      ->db_select(false);
        
        return $xdb_offers_rows[0]['COUNT(*)'];
    }
    
    public function offers_get_voucher_persons_options()
    {
        $offer = new Offer;
        $xdb_offers = new Xdb;
        $xdb_offers_rows = $xdb_offers->select_fields('voucher_persons')
                                      ->set_table($offer->table)
                                      //->where(array('trashed' => 0, 'project_id' => $project_id, 'title_' . $selected_language . '::!=' => ''))
                                      ->group_by('voucher_persons')
                                      ->db_select(true, 0, strtolower(get_class($offer)));
        
        $voucher_persons_array = array();
        
        if (is_array($xdb_offers_rows))
        {
            foreach ($xdb_offers_rows as $row)
            {
                $voucher_persons_array[] = array('id' => $row['voucher_persons'], 'text' => $row['voucher_persons']);
            }
        }
        
        return json_encode($voucher_persons_array);
    }
    
    public function offer_title_exists($title)
    {
        $offer = new Offer;
        $xdb_exists = new Xdb;
        $xdb_exists_rows = $xdb_exists->select_fields('COUNT(*)')
                                      ->set_table($offer->table)
                                      ->where(array('title' => $title))
                                      ->db_select(false);
        //print_r($xdb_exists_rows);
        if (isset($xdb_exists_rows[0]['COUNT(*)']))
        {
            if ($xdb_exists_rows[0]['COUNT(*)'])
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
    public function offers_get_simple_search_count($search_term)
    {
        $offer = new Offer;
        $xdb_offers = new Xdb;
        $search_term_decoded = base64_decode($search_term);
        
        $all_languages = Languages::get_all_www_languages_list();
        $search_array = array('title::LIKE' => '%' . $search_term_decoded . '%', 'OR::prices::LIKE' => '%' . $search_term_decoded . '%', 'OR::seller_address::LIKE' => '%' . $search_term_decoded . '%');
        
        foreach ($offer->fields['dynamic_fields'] as $field => $field_value)
        {
            foreach ($all_languages as $language)
            {
                $search_array['OR::' . $field . '_' . $language['code'] . '::LIKE'] = '%' . $search_term_decoded . '%';
            }
        }
        
        $xdb_offers_rows = $xdb_offers->select_fields('COUNT(*)')
                                      ->set_table($offer->table)
                                      ->where(array('trashed' => 0, 'project_id' => 1))
                                      ->and_where_group($search_array)
                                      //->group_by('title')
                                      ->db_select(false);
        
        return $xdb_offers_rows[0]['COUNT(*)'];
    }
    
    public function offers_get_simple_search_count_trashed($search_term)
    {
        $offer = new Offer;
        $xdb_offers = new Xdb;
        $search_term_decoded = base64_decode($search_term);
        
        $all_languages = Languages::get_all_www_languages_list();
        $search_array = array('title::LIKE' => '%' . $search_term_decoded . '%', 'OR::prices::LIKE' => '%' . $search_term_decoded . '%', 'OR::seller_address::LIKE' => '%' . $search_term_decoded . '%');
        
        foreach ($offer->fields['dynamic_fields'] as $field => $field_value)
        {
            foreach ($all_languages as $language)
            {
                $search_array['OR::' . $field . '_' . $language['code'] . '::LIKE'] = '%' . $search_term_decoded . '%';
            }
        }
        
        $xdb_offers_rows = $xdb_offers->select_fields('COUNT(*)')
                                      ->set_table($offer->table)
                                      ->where(array('trashed' => 1, 'project_id' => 1))
                                      ->and_where_group($search_array)
                                      //->group_by('title')
                                      ->db_select(false);
        
        return $xdb_offers_rows[0]['COUNT(*)'];
    }
    
    public function offers_get_all_limit_search($limit_start, $limit, $search_type, $search_term)
    {
        $offer = new Offer;
        $xdb_offers = new Xdb;
        if ($search_type == 'undefined')
        {
            $xdb_offers_rows = $xdb_offers->select_fields('id, project_id, title')
                                          ->set_table($offer->table)
                                          ->where(array('trashed' => 0))
                                          ->group_by('title')
                                          ->order_by('id', 'DESC')
                                          ->limit($limit_start . ', ' . $limit)
                                          ->db_select(false);
        }
        elseif ($search_type == 'search-simple')
        {
            $search_term_decoded = base64_decode($search_term);
            
            $all_languages = Languages::get_all_www_languages_list();
            $search_array = array('title::LIKE' => '%' . $search_term_decoded . '%', 'OR::prices::LIKE' => '%' . $search_term_decoded . '%', 'OR::seller_address::LIKE' => '%' . $search_term_decoded . '%');
            
            foreach ($offer->fields['dynamic_fields'] as $field => $field_value)
            {
                foreach ($all_languages as $language)
                {
                    $search_array['OR::' . $field . '_' . $language['code'] . '::LIKE'] = '%' . $search_term_decoded . '%';
                }
            }
            
            //print_r($search_array);
            
            $xdb_offers_rows = $xdb_offers->select_fields('id, project_id, title')
                                          ->set_table($offer->table)
                                          ->where(array('trashed' => 0))
                                          ->and_where_group($search_array)
                                          ->group_by('title')
                                          ->order_by('id', 'DESC')
                                          ->limit($limit_start . ', ' . $limit)
                                          ->db_select(false);
        }
        
        if (is_array($xdb_offers_rows))
        {
            return $xdb_offers_rows;
        }
        else
        {
            return false;
        }
    }
    
    public function offers_get_all_limit_search_trashed($limit_start, $limit, $search_type, $search_term)
    {
        $offer = new Offer;
        $xdb_offers = new Xdb;
        if ($search_type == 'undefined')
        {
            $xdb_offers_rows = $xdb_offers->select_fields('id, project_id, title')
                                          ->set_table($offer->table)
                                          ->where(array('trashed' => 1))
                                          ->group_by('title')
                                          ->order_by('id', 'DESC')
                                          ->limit($limit_start . ', ' . $limit)
                                          ->db_select(false);
        }
        elseif ($search_type == 'search-simple')
        {
            $search_term_decoded = base64_decode($search_term);
            
            $all_languages = Languages::get_all_www_languages_list();
            $search_array = array('title::LIKE' => '%' . $search_term_decoded . '%', 'OR::prices::LIKE' => '%' . $search_term_decoded . '%', 'OR::seller_address::LIKE' => '%' . $search_term_decoded . '%');
            
            foreach ($offer->fields['dynamic_fields'] as $field => $field_value)
            {
                foreach ($all_languages as $language)
                {
                    $search_array['OR::' . $field . '_' . $language['code'] . '::LIKE'] = '%' . $search_term_decoded . '%';
                }
            }
            
            //print_r($search_array);
            
            $xdb_offers_rows = $xdb_offers->select_fields('id, project_id, title')
                                          ->set_table($offer->table)
                                          ->where(array('trashed' => 1))
                                          ->and_where_group($search_array)
                                          ->group_by('title')
                                          ->order_by('id', 'DESC')
                                          ->limit($limit_start . ', ' . $limit)
                                          ->db_select(false);
        }
        
        if (is_array($xdb_offers_rows))
        {
            return $xdb_offers_rows;
        }
        else
        {
            return false;
        }
    }
    
    public function offers_get_by_project($project_id)
    {
        $offer = new Offer;
        $xdb_offers = new Xdb;
        $xdb_offers_rows = $xdb_offers->set_table($offer->table)
                                      ->where(array('trashed' => 0, 'project_id' => $project_id))
                                      ->db_select(true, 0, strtolower(get_class($offer)));
        return $xdb_offers_rows;
    }
    
    public function offers_get_cities($project_id)
    {
        $offer = new Offer;
        $xdb_offers = new Xdb;
        $xdb_offers_rows = $xdb_offers->select_fields('seller_city')
                                      ->set_table($offer->table)
                                      ->where(array('trashed' => 0, 'project_id' => $project_id, 'seller_city::!=' => ''))
                                      ->group_by('seller_city')
                                      //->db_select(true, 0, strtolower(get_class($offer)));
                                      ->db_select(false);
        //return $xdb_offers_rows;
        $cities_array = array();
        $to_replace = array('&scaron;', '&Scaron;');
        $replacements = array('š', 'Š');
        if (is_array($xdb_offers_rows))
        {
            foreach ($xdb_offers_rows as $row)
            {
                $city_exploded = explode('|', $row['seller_city']);
                if (isset($city_exploded[2]))
                {
                    $cities_array[$city_exploded[2]][] = str_replace($to_replace, $replacements, $city_exploded[0]);
                }
            }
        }
        
        foreach ($cities_array as $country => $city_array)
        {
            sort($city_array, SORT_STRING);
            $cities_array[$country] = $city_array;
        }
        ksort($cities_array);
        
        return $cities_array;
    }
    
    public function offer_duplicate($title, $new_title, $edit_for_project)
    {
        $offer = new Offer;
        $projects = Projects::get_all_projects();
        foreach ($projects as $project)
        {
            $current_offer = Offer::offer_get_by_title($title, $project['id']);
            unset($current_offer['id']);
            unset($current_offer['title']);
            
            $offer->fields['static_fields']['project_id']['value'] = $project['id'];
            $offer->fields['static_fields']['title']['value'] = $new_title;
            
            $xdb_offer_insert = new Xdb;
            $insert_new = $xdb_offer_insert->db_insert_content($project['id'], $offer->table, $offer->fields, strtolower(get_class($offer)));
            
            $update_duplicate = new Xdb;
            $update = $update_duplicate->set_table($offer->table)
                                       ->simple_update_fields($current_offer)
                                       ->where(array('title' => $new_title, 'project_id' => $project['id']))
                                       ->db_update(strtolower(get_class($offer)));
            
            $update_duplicate->update_permanent_cache_single($offer->table, $insert_new);
            
            if ($edit_for_project == $project['id'])
            {
                $last_id = $insert_new;
            }
            
            return $last_id;
        }
        
    }
    
    public function offer_insert_new($title, $edit_for_project)
    {
        $offer = new Offer;
        $projects = Projects::get_all_projects();
        foreach ($projects as $project)
        {
            $offer->fields['static_fields']['project_id']['value'] = $project['id'];
            $offer->fields['static_fields']['title']['value'] = $title;
            
            $xdb_offer_insert = new Xdb;
            $insert_new = $xdb_offer_insert->db_insert_content($project['id'], $offer->table, $offer->fields, strtolower(get_class($offer)));
            
            if ($edit_for_project == $project['id'])
            {
                $last_id = $insert_new;
            }
            
            return $last_id;
        }
        
    }
    
    public function offer_get_by_title($title, $project_id)
    {
        $offer = new Offer;
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->set_table($offer->table)
                                    ->where(array('title' => $title, 'project_id' => $project_id))
                                    ->db_select(false);
        
        return $xdb_offer_rows[0];
    }
    
    public function offer_get_by_id($id)
    {
        global $project_id;
        
        $offer = new Offer;
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->set_table($offer->table)
                                    ->db_select_by_id($id, strtolower(get_class($offer)));
        //$xdb_offer_rows[0][theprices] = 'neki';
        //$xdb_offer_rows[0][theprices] = html_entity_decode(json_decode($xdb_offer_rows[0]['prices']));
        //print_r($xdb_offer_rows);
        
        if (isset($xdb_offer_rows[0]['voucher_validity_from']) && $xdb_offer_rows[0]['voucher_validity_from'] != null && $xdb_offer_rows[0]['voucher_validity_from'] != '0000-00-00')
        {
            $voucher_validity_from = $xdb_offer_rows[0]['voucher_validity_from'];
            $voucher_validity_to = $xdb_offer_rows[0]['voucher_validity_to'];
            unset($xdb_offer_rows[0]['voucher_validity_from']);
            unset($xdb_offer_rows[0]['voucher_validity_to']);
            $xdb_offer_rows[0]['voucher_validity'] = $voucher_validity_from . ' - ' . $voucher_validity_to;
        }
        else
        {
            $xdb_offer_rows[0]['voucher_validity'] = '';
        }
        
        
        $prices_decode = str_replace('&quot;', '"', $xdb_offer_rows[0]['prices']);
        //print_r(json_decode($prices_decode, true));
        unset($xdb_offer_rows[0]['prices']);
        $xdb_offer_rows[0]['prices'] = json_decode($prices_decode, true);
        return $xdb_offer_rows;
    }
    
    public function offers_www_search($search_string)
    {
        global $selected_language, $current_country;
        
        $selected_country = strtolower($current_country);
        
        $search_select_fields = 'id, project_id, voucher_persons, title_' . $selected_language . ', slug_' . $selected_language . ', intro_' . $selected_language . ', description_' . $selected_language . ', offer_includes_' . $selected_language . ', notes_' . $selected_language . ', seo_description_' . $selected_language . ', seo_keywords_' . $selected_language . ', seller_address, seller_name, seller_brandname, seller_city, prices, images, ((CASE WHEN `title_' . $selected_language . '` LIKE "%' . $search_string . '%" THEN 20 ELSE 0 END) + (CASE WHEN `intro_' . $selected_language . '` LIKE "%' . $search_string . '%" THEN 8 ELSE 0 END) + (CASE WHEN `seo_keywords_' . $selected_language . '` LIKE "%' . $search_string . '%" THEN 9 ELSE 0 END) + (CASE WHEN `description_' . $selected_language . '` LIKE "%' . $search_string . '%" THEN 7 ELSE 0 END)) AS relevance';
        
        
        $search_array = array(
            'title_' . $selected_language . '::LIKE' => '%' . $search_string . '%',
            'OR::intro_' . $selected_language . '::LIKE' => '%' . $search_string . '%',
            'OR::description_' . $selected_language . '::LIKE' => '%' . $search_string . '%',
            'OR::offer_includes_' . $selected_language . '::LIKE' => '%' . $search_string . '%',
            'OR::notes_' . $selected_language . '::LIKE' => '%' . $search_string . '%',
            'OR::seo_description_' . $selected_language . '::LIKE' => '%' . $search_string . '%',
            'OR::seo_keywords_' . $selected_language . '::LIKE' => '%' . $search_string . '%',
            'OR::seller_address::LIKE' => '%' . $search_string . '%',
            'OR::seller_name::LIKE' => '%' . $search_string . '%',
            'OR::seller_brandname::LIKE' => '%' . $search_string . '%',
            'OR::seller_city::LIKE' => '%' . $search_string . '%'
        );
        
        $offer = new Offer;
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->select_fields($search_select_fields)
                                    ->set_table($offer->table)
                                    ->where(array('trashed' => 0))
                                    ->and_where_group($search_array)
                                    ->order_by('relevance', 'DESC')
                                    ->limit(80)
                                    ->db_select(false);
        
        if (is_array($xdb_offer_rows) && !empty($xdb_offer_rows))
        {
            $translation = new Translations;
            $counter = 0;
            if (is_array($xdb_offer_rows)){
                foreach ($xdb_offer_rows as $offer_row)
                {
                    $seller_city_info = explode('|', $offer_row['seller_city']);
                
                    if (isset($seller_city_info[0]))
                    {
                        $xdb_offer_rows[$counter]['city_info']['city_name'] = $translation->get_translation($seller_city_info[0], $selected_language);
                    }
                    else
                    {
                        $xdb_offer_rows[$counter]['city_info']['city_name'] = '';
                    }
                    
                    if (isset($seller_city_info[2]))
                    {
                        $xdb_offer_rows[$counter]['city_info']['country_name'] = $translation->get_translation($seller_city_info[2], $selected_language);
                    }
                    else
                    {
                        $xdb_offer_rows[$counter]['city_info']['country_name'] = '';
                    }
                    
                    // images
                    $images = json_decode(str_replace('&quot;', '"', $offer_row['images']), true);
                    unset($xdb_offer_rows[$counter]['images']);
                    $xdb_offer_rows[$counter]['images'] = $images;
                    $xdb_offer_rows[$counter]['main_image'] = $images[0];
                    
                    //logo
                    if (isset($xdb_offer_rows[$counter]['seller_logo']))
                    {
                        $logo = json_decode(str_replace('&quot;', '"', $offer_row['seller_logo']), true);
                        unset($xdb_offer_rows[$counter]['seller_logo']);
                        $xdb_offer_rows[$counter]['logo_image'] = $logo[0];
                    }
                    
                    
                    // prices
                    $prices = json_decode(str_replace('&quot;', '"', $offer_row['prices']), true);
                    unset($xdb_offer_rows[$counter]['prices']);
                    $xdb_offer_rows[$counter]['prices'] = $prices;
                    if (isset($xdb_offer_rows[$counter]['prices']['original_price_' . $selected_country])) $xdb_offer_rows[$counter]['view_original_price'] = $xdb_offer_rows[$counter]['prices']['original_price_' . $selected_country]; else $xdb_offer_rows[$counter]['view_original_price'] = $xdb_offer_rows[$counter]['prices']['original_price'];
                    if (isset($xdb_offer_rows[$counter]['prices']['discount_price_' . $selected_country])) $xdb_offer_rows[$counter]['view_discount_price'] = $xdb_offer_rows[$counter]['prices']['discount_price_' . $selected_country]; else $xdb_offer_rows[$counter]['view_discount_price'] = $xdb_offer_rows[$counter]['prices']['discount_price'];
                    if (isset($xdb_offer_rows[$counter]['prices']['discount_' . $selected_country])) $xdb_offer_rows[$counter]['view_discount'] = $xdb_offer_rows[$counter]['prices']['discount_' . $selected_country]; else $xdb_offer_rows[$counter]['view_discount'] = $xdb_offer_rows[$counter]['prices']['discount'];
                    
                    foreach($offer->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
                    // foreach($offer->fields['dynamic_fields'] as $dynamic_field)
                    {
                        $xdb_offer_rows[$counter]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_offer_rows[$counter][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                        unset($xdb_offer_rows[$counter][$dynamic_field . '_' . $selected_language]);
                    }
                    /*
                    $start_date = $xdb_offer_rows[$counter]['voucher_validity_from'];
                    $end_date = $xdb_offer_rows[$counter]['voucher_validity_to'];
                
                    unset($xdb_offer_rows[$counter]['voucher_validity_from']);
                    unset($xdb_offer_rows[$counter]['voucher_validity_to']);
                    
                    $xdb_offer_rows[$counter]['voucher_validity_from'] = date("d.m.Y", strtotime($start_date));
                    $xdb_offer_rows[$counter]['voucher_validity_to'] = date("d.m.Y", strtotime($end_date));
                    */
                    $counter++;
                }
            }
            
            return $xdb_offer_rows;
        }
        else
        {
            return false;
        }
    }
    
    public function offers_www_get_by_ids($offer_ids = array())
    {
        global $selected_language, $current_country;
        
        $selected_country = strtolower($current_country);
        
        $offer = new Offer;
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->set_table($offer->table)
                                    ->where(array('id::IN' => $offer_ids, 'trashed' => 0))
                                    ->db_select(false);
        
        $translation = new Translations;
        $counter = 0;
        if (is_array($xdb_offer_rows)){
            foreach ($xdb_offer_rows as $offer_row)
            {
                $seller_city_info = explode('|', $offer_row['seller_city']);
            
                if (isset($seller_city_info[0]))
                {
                    $xdb_offer_rows[$counter]['city_info']['city_name'] = $translation->get_translation($seller_city_info[0], $selected_language);
                }
                else
                {
                    $xdb_offer_rows[$counter]['city_info']['city_name'] = '';
                }
                
                if (isset($seller_city_info[2]))
                {
                    $xdb_offer_rows[$counter]['city_info']['country_name'] = $translation->get_translation($seller_city_info[2], $selected_language);
                }
                else
                {
                    $xdb_offer_rows[$counter]['city_info']['country_name'] = '';
                }
                
                // images
                /*
                $images = json_decode(str_replace('&quot;', '"', $offer_row['images']), true);
                unset($xdb_offer_rows[$counter]['images']);
                $xdb_offer_rows[$counter]['images'] = $images;
                $xdb_offer_rows[$counter]['main_image'] = $images[0];
                */
                
                // prices
                $prices = json_decode(str_replace('&quot;', '"', $offer_row['prices']), true);
                unset($xdb_offer_rows[$counter]['prices']);
                $xdb_offer_rows[$counter]['prices'] = $prices;
                if (isset($xdb_offer_rows[$counter]['prices']['original_price_' . $selected_country])) $xdb_offer_rows[$counter]['view_original_price'] = $xdb_offer_rows[$counter]['prices']['original_price_' . $selected_country]; else $xdb_offer_rows[$counter]['view_original_price'] = $xdb_offer_rows[$counter]['prices']['original_price'];
                if (isset($xdb_offer_rows[$counter]['prices']['discount_price_' . $selected_country])) $xdb_offer_rows[$counter]['view_discount_price'] = $xdb_offer_rows[$counter]['prices']['discount_price_' . $selected_country]; else $xdb_offer_rows[$counter]['view_discount_price'] = $xdb_offer_rows[$counter]['prices']['discount_price'];
                if (isset($xdb_offer_rows[$counter]['prices']['discount_' . $selected_country])) $xdb_offer_rows[$counter]['view_discount'] = $xdb_offer_rows[$counter]['prices']['discount_' . $selected_country]; else $xdb_offer_rows[$counter]['view_discount'] = $xdb_offer_rows[$counter]['prices']['discount'];
                
                foreach($offer->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
                // foreach($offer->fields['dynamic_fields'] as $dynamic_field)
                {
                    $xdb_offer_rows[$counter]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_offer_rows[$counter][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                    unset($xdb_offer_rows[$counter][$dynamic_field . '_' . $selected_language]);
                }
                
                $start_date = $xdb_offer_rows[$counter]['voucher_validity_from'];
                $end_date = $xdb_offer_rows[$counter]['voucher_validity_to'];
            
                unset($xdb_offer_rows[$counter]['voucher_validity_from']);
                unset($xdb_offer_rows[$counter]['voucher_validity_to']);
            
                $xdb_offer_rows[$counter]['voucher_validity_from'] = date("d.m.Y", strtotime($start_date));
                $xdb_offer_rows[$counter]['voucher_validity_to'] = date("d.m.Y", strtotime($end_date));
                
                $xdb_offer_rows[$counter]['voucher_validity_from_string'] = $start_date;
                $xdb_offer_rows[$counter]['voucher_validity_to_string'] = $end_date;
                
                $counter++;
            }
        }
        
        return $xdb_offer_rows;
    }
    
    public function offers_www_get_by_ids_ios($offer_ids = array())
    {
        global $selected_language, $current_country;
        
        $selected_country = strtolower($current_country);
        
        $offer = new Offer;
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->select_fields('title_' . $selected_language . ', intro_' . $selected_language . ', voucher_persons, prices, images, seller_logo, seller_brandname, seller_address, seller_city, id, seller_lat, seller_lng')
                                    ->set_table($offer->table)
                                    ->where(array('id::IN' => $offer_ids, 'trashed' => 0))
                                    ->db_select(false);
        
        $translation = new Translations;
        $counter = 0;
        if (is_array($xdb_offer_rows)){
            foreach ($xdb_offer_rows as $offer_row)
            {
                $seller_city_info = explode('|', $offer_row['seller_city']);
            
                if (isset($seller_city_info[0]))
                {
                    $xdb_offer_rows[$counter]['city_info']['city_name'] = $translation->get_translation($seller_city_info[0], $selected_language);
                }
                else
                {
                    $xdb_offer_rows[$counter]['city_info']['city_name'] = '';
                }
                
                if (isset($seller_city_info[2]))
                {
                    $xdb_offer_rows[$counter]['city_info']['country_name'] = $translation->get_translation($seller_city_info[2], $selected_language);
                }
                else
                {
                    $xdb_offer_rows[$counter]['city_info']['country_name'] = '';
                }
                
                // images
                $images = json_decode(str_replace('&quot;', '"', $offer_row['images']), true);
                unset($xdb_offer_rows[$counter]['images']);
                $xdb_offer_rows[$counter]['images'] = $images;
                $xdb_offer_rows[$counter]['main_image'] = $images[0];
                
                
                // prices
                $prices = json_decode(str_replace('&quot;', '"', $offer_row['prices']), true);
                unset($xdb_offer_rows[$counter]['prices']);
                $xdb_offer_rows[$counter]['prices'] = $prices;
                if (isset($xdb_offer_rows[$counter]['prices']['original_price_' . $selected_country])) $xdb_offer_rows[$counter]['view_original_price'] = $xdb_offer_rows[$counter]['prices']['original_price_' . $selected_country]; else $xdb_offer_rows[$counter]['view_original_price'] = $xdb_offer_rows[$counter]['prices']['original_price'];
                if (isset($xdb_offer_rows[$counter]['prices']['discount_price_' . $selected_country])) $xdb_offer_rows[$counter]['view_discount_price'] = $xdb_offer_rows[$counter]['prices']['discount_price_' . $selected_country]; else $xdb_offer_rows[$counter]['view_discount_price'] = $xdb_offer_rows[$counter]['prices']['discount_price'];
                if (isset($xdb_offer_rows[$counter]['prices']['discount_' . $selected_country])) $xdb_offer_rows[$counter]['view_discount'] = $xdb_offer_rows[$counter]['prices']['discount_' . $selected_country]; else $xdb_offer_rows[$counter]['view_discount'] = $xdb_offer_rows[$counter]['prices']['discount'];
                
                foreach($offer->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
                // foreach($offer->fields['dynamic_fields'] as $dynamic_field)
                {
                    if (isset($xdb_offer_rows[$counter][$dynamic_field . '_' . $selected_language]))
                    {
                        $xdb_offer_rows[$counter]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_offer_rows[$counter][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                        unset($xdb_offer_rows[$counter][$dynamic_field . '_' . $selected_language]);
                    }
                }
                /*
                $start_date = $xdb_offer_rows[$counter]['voucher_validity_from'];
                $end_date = $xdb_offer_rows[$counter]['voucher_validity_to'];
            
                unset($xdb_offer_rows[$counter]['voucher_validity_from']);
                unset($xdb_offer_rows[$counter]['voucher_validity_to']);
            
                $xdb_offer_rows[$counter]['voucher_validity_from'] = date("d.m.Y", strtotime($start_date));
                $xdb_offer_rows[$counter]['voucher_validity_to'] = date("d.m.Y", strtotime($end_date));
                
                $xdb_offer_rows[$counter]['voucher_validity_from_string'] = $start_date;
                $xdb_offer_rows[$counter]['voucher_validity_to_string'] = $end_date;
                */
                $counter++;
            }
        }
        
        return $xdb_offer_rows;
    }
    
    public function offer_www_get_by_id($id)
    {
        global $selected_language, $current_country, $project_id;
        $selected_country = strtolower($current_country);
        $offer = new Offer;
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->set_table($offer->table)
                                    ->db_select_by_id_not_trashed($id, strtolower(get_class($offer)));
        //$xdb_offer_rows[0][theprices] = 'neki';
        //$xdb_offer_rows[0][theprices] = html_entity_decode(json_decode($xdb_offer_rows[0]['prices']));
        
        
        //print_r($xdb_offer_rows[0]);
        
        $translation = new Translations;
        
        if (isset($xdb_offer_rows[0]))
        {
            $seller_city_info = explode('|', $xdb_offer_rows[0]['seller_city']);
            
            if (isset($seller_city_info[0]))
            {
                $xdb_offer_rows[0]['city_info']['city_name'] = $translation->get_translation($seller_city_info[0], $selected_language);
            }
            else
            {
                $xdb_offer_rows[0]['city_info']['city_name'] = '';
            }
            
            if (isset($seller_city_info[2]))
            {
                $xdb_offer_rows[0]['city_info']['country_name'] = $translation->get_translation($seller_city_info[2], $selected_language);
            }
            else
            {
                $xdb_offer_rows[0]['city_info']['country_name'] = '';
            }
            
            
            
            // set images
            $images = json_decode(str_replace('&quot;', '"', $xdb_offer_rows[0]['images']), true);
            $xdb_offer_rows[0]['view_images'] = $images;
            
            //logo
            if (isset($xdb_offer_rows[0]['seller_logo']))
            {
                $logo = json_decode(str_replace('&quot;', '"', $xdb_offer_rows[0]['seller_logo']), true);
                unset($xdb_offer_rows[0]['seller_logo']);
                $xdb_offer_rows[0]['logo_image'] = $logo[0];
            }
            
            $prices_decode = str_replace('&quot;', '"', $xdb_offer_rows[0]['prices']);
            //print_r(json_decode($prices_decode, true));
            unset($xdb_offer_rows[0]['prices']);
            $xdb_offer_rows[0]['prices'] = json_decode($prices_decode, true);
            if (isset($xdb_offer_rows[0]['prices']['original_price_' . $selected_country])) $xdb_offer_rows[0]['view_original_price'] = $xdb_offer_rows[0]['prices']['original_price_' . $selected_country]; else $xdb_offer_rows[0]['view_original_price'] = $xdb_offer_rows[0]['prices']['original_price'];
            if (isset($xdb_offer_rows[0]['prices']['discount_price_' . $selected_country])) $xdb_offer_rows[0]['view_discount_price'] = $xdb_offer_rows[0]['prices']['discount_price_' . $selected_country]; else $xdb_offer_rows[0]['view_discount_price'] = $xdb_offer_rows[0]['prices']['discount_price'];
            if (isset($xdb_offer_rows[0]['prices']['discount_' . $selected_country])) $xdb_offer_rows[0]['view_discount'] = $xdb_offer_rows[0]['prices']['discount_' . $selected_country]; else $xdb_offer_rows[0]['view_discount'] = $xdb_offer_rows[0]['prices']['discount'];
            
            // set money saved
            if (isset($xdb_offer_rows[0]['view_original_price']) && isset($xdb_offer_rows[0]['view_discount_price']))
            {
                $xdb_offer_rows[0]['view_save_money'] = number_format($xdb_offer_rows[0]['view_original_price'] - $xdb_offer_rows[0]['view_discount_price'], 2, ',', ' ');
            }
            
            foreach($offer->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
            {
                $xdb_offer_rows[0]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_offer_rows[0][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                unset($xdb_offer_rows[0][$dynamic_field . '_' . $selected_language]);
            }
            
            $start_date = $xdb_offer_rows[0]['voucher_validity_from'];
            $end_date = $xdb_offer_rows[0]['voucher_validity_to'];
            
            unset($xdb_offer_rows[0]['voucher_validity_from']);
            unset($xdb_offer_rows[0]['voucher_validity_to']);
            
            $xdb_offer_rows[0]['voucher_validity_from'] = date("d.m.Y", strtotime($start_date));
            $xdb_offer_rows[0]['voucher_validity_to'] = date("d.m.Y", strtotime($end_date));
            
            return $xdb_offer_rows[0];
        }
        else
        {
            return false;
        }
    }
    
    public function get_project_by_offer_id($id)
    {
        $project_id_xdb = new Xdb;
        $get_project_id = $project_id_xdb->select_fields('project_id')
                                         ->set_table('www_offers')
                                         ->where(array('id' => $id))
                                         ->db_select(false);
        
        return $get_project_id[0]['project_id'];
    }
    
    public function offer_www_post_get_by_id($id)
    {
        global $selected_language, $current_country, $project_id;
        
        $selected_country = strtolower($current_country);
        $offer = new Offer;
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->select_fields('title_' . $selected_language . ', intro_' . $selected_language . ', voucher_persons, prices, images, seller_logo, seller_brandname, seller_address, seller_city, id, trashed')
                                    ->set_table($offer->table)
                                    ->db_select_by_id_not_trashed($id, strtolower(get_class($offer)), true, 0, false);
        
        $translation = new Translations;
        
        if (isset($xdb_offer_rows[0]))
        {
            $seller_city_info = explode('|', $xdb_offer_rows[0]['seller_city']);
            
            if (isset($seller_city_info[0]))
            {
                $xdb_offer_rows[0]['city_info']['city_name'] = $translation->get_translation($seller_city_info[0], $selected_language);
            }
            else
            {
                $xdb_offer_rows[0]['city_info']['city_name'] = '';
            }
            
            if (isset($seller_city_info[2]))
            {
                $xdb_offer_rows[0]['city_info']['country_name'] = $translation->get_translation($seller_city_info[2], $selected_language);
            }
            else
            {
                $xdb_offer_rows[0]['city_info']['country_name'] = '';
            }
            
            $images = json_decode(str_replace('&quot;', '"', $xdb_offer_rows[0]['images']), true);
            $xdb_offer_rows[0]['view_images'] = $images;
            
            //logo
            if (isset($xdb_offer_rows[0]['seller_logo']))
            {
                $logo = json_decode(str_replace('&quot;', '"', $xdb_offer_rows[0]['seller_logo']), true);
                unset($xdb_offer_rows[0]['seller_logo']);
                $xdb_offer_rows[0]['logo_image'] = $logo[0];
            }
            
            $prices_decode = str_replace('&quot;', '"', $xdb_offer_rows[0]['prices']);
            //print_r(json_decode($prices_decode, true));
            unset($xdb_offer_rows[0]['prices']);
            $xdb_offer_rows[0]['prices'] = json_decode($prices_decode, true);
            if (isset($xdb_offer_rows[0]['prices']['original_price_' . $selected_country])) $xdb_offer_rows[0]['view_original_price'] = $xdb_offer_rows[0]['prices']['original_price_' . $selected_country]; else $xdb_offer_rows[0]['view_original_price'] = $xdb_offer_rows[0]['prices']['original_price'];
            if (isset($xdb_offer_rows[0]['prices']['discount_price_' . $selected_country])) $xdb_offer_rows[0]['view_discount_price'] = $xdb_offer_rows[0]['prices']['discount_price_' . $selected_country]; else $xdb_offer_rows[0]['view_discount_price'] = $xdb_offer_rows[0]['prices']['discount_price'];
            if (isset($xdb_offer_rows[0]['prices']['discount_' . $selected_country])) $xdb_offer_rows[0]['view_discount'] = $xdb_offer_rows[0]['prices']['discount_' . $selected_country]; else $xdb_offer_rows[0]['view_discount'] = $xdb_offer_rows[0]['prices']['discount'];
            
            foreach($offer->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
            {
                if (isset($xdb_offer_rows[0][$dynamic_field . '_' . $selected_language]))
                {
                    $xdb_offer_rows[0]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_offer_rows[0][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                    unset($xdb_offer_rows[0][$dynamic_field . '_' . $selected_language]);
                }
            }
            
            return $xdb_offer_rows[0];
        }
        else
        {
            return false;
        }
    }
    
    public function offer_get_by_category($cat_id)
    {
        global $selected_language, $current_country, $project_id, $exclusion_list, $offer_counter;
        $selected_country = strtolower($current_country);
        $offer = new Offer;
        
        if (isset($offer_counter)) $offer_counter = 0;
        
        // get subcategory ids
        $xdb_subcategory = new Xdb;
        $xdb_subcategory_rows = $xdb_subcategory->set_table($offer->category_table)
                                                ->where(array('parent_id' => $cat_id, 'relation' => 'default'))
                                                ->db_select(true, 0, strtolower(get_class($offer)));
        //print_r($xdb_subcategory_rows);
        $subcategory_ids = array();
        $subcategory_offer_key = 0;
        foreach ($xdb_subcategory_rows as $subcategory)
        {
            //echo $subcategory['id'];
            //$subcategory_ids = array($subcategory['id']);
            array_push($subcategory_ids, $subcategory['id']);
            $subcategory_offer_key++;
        }
        //print_r($subcategory_ids);
        
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->set_table($offer->table)
                                    ->where(array('category::IN' => $subcategory_ids, 'project_id' => $project_id, 'trashed' => 0))
                                    ->order_by('id', 'DESC')
                                    ->db_select(true, 0, strtolower(get_class($offer)));
        //print_r($xdb_offer_rows);
        
        $translation = new Translations;
        //$offer_key = 0;
        if (is_array($xdb_offer_rows)){
            foreach ($xdb_offer_rows as $offer_key => $offer_row)
            {
                if (is_array($exclusion_list) && in_array($offer_row['id'], $exclusion_list))
                {
                    unset($xdb_offer_rows[$offer_key]);
                    $offer_key++;
                    continue;
                }
                
                $seller_city_info = explode('|', $offer_row['seller_city']);
            
                if (isset($seller_city_info[0]))
                {
                    $xdb_offer_rows[$offer_key]['city_info']['city_name'] = $translation->get_translation($seller_city_info[0], $selected_language);
                }
                else
                {
                    $xdb_offer_rows[$offer_key]['city_info']['city_name'] = '';
                }
                
                if (isset($seller_city_info[2]))
                {
                    $xdb_offer_rows[$offer_key]['city_info']['country_name'] = $translation->get_translation($seller_city_info[2], $selected_language);
                }
                else
                {
                    $xdb_offer_rows[$offer_key]['city_info']['country_name'] = '';
                }
                
                // images
                $images = json_decode(str_replace('&quot;', '"', $offer_row['images']), true);
                unset($xdb_offer_rows[$offer_key]['images']);
                $xdb_offer_rows[$offer_key]['images'] = $images;
                $xdb_offer_rows[$offer_key]['main_image'] = $images[0];
                
                //logo
                if (isset($xdb_offer_rows[$offer_key]['seller_logo']))
                {
                    $logo = json_decode(str_replace('&quot;', '"', $offer_row['seller_logo']), true);
                    unset($xdb_offer_rows[$offer_key]['seller_logo']);
                    if (isset($logo[0]))
                    {
                        $xdb_offer_rows[$offer_key]['logo_image'] = $logo[0];
                    }
                }
                
                // prices
                $prices = json_decode(str_replace('&quot;', '"', $offer_row['prices']), true);
                unset($xdb_offer_rows[$offer_key]['prices']);
                $xdb_offer_rows[$offer_key]['prices'] = $prices;
                if (isset($xdb_offer_rows[$offer_key]['prices']['original_price_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_original_price'] = $xdb_offer_rows[$offer_key]['prices']['original_price_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_original_price'] = $xdb_offer_rows[$offer_key]['prices']['original_price'];
                if (isset($xdb_offer_rows[$offer_key]['prices']['discount_price_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_discount_price'] = $xdb_offer_rows[$offer_key]['prices']['discount_price_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_discount_price'] = $xdb_offer_rows[$offer_key]['prices']['discount_price'];
                if (isset($xdb_offer_rows[$offer_key]['prices']['discount_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_discount'] = $xdb_offer_rows[$offer_key]['prices']['discount_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_discount'] = $xdb_offer_rows[$offer_key]['prices']['discount'];
                
                foreach($offer->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
                // foreach($offer->fields['dynamic_fields'] as $dynamic_field)
                {
                    if (isset($xdb_offer_rows[$offer_key][$dynamic_field . '_' . $selected_language]))
                    {
                        $xdb_offer_rows[$offer_key]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_offer_rows[$offer_key][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                        unset($xdb_offer_rows[$offer_key][$dynamic_field . '_' . $selected_language]);
                    }
                }
                
                //$offer_key++;
                $xdb_offer_rows[$offer_key]['offer_counter'] = $offer_counter;
                $offer_counter++;
            }
        }
        
        return $xdb_offer_rows;
    }
    
    public function offer_get_by_category_ios($cat_id)
    {
        global $selected_language, $current_country, $project_id, $exclusion_list;
        $selected_country = strtolower($current_country);
        $offer = new Offer;
        
        // get subcategory ids
        $xdb_subcategory = new Xdb;
        $xdb_subcategory_rows = $xdb_subcategory->set_table($offer->category_table)
                                                ->where(array('parent_id' => $cat_id, 'relation' => 'default'))
                                                ->db_select(true, 0, strtolower(get_class($offer)));
        //print_r($xdb_subcategory_rows);
        $subcategory_ids = array();
        $subcategory_offer_key = 0;
        foreach ($xdb_subcategory_rows as $subcategory)
        {
            //echo $subcategory['id'];
            //$subcategory_ids = array($subcategory['id']);
            array_push($subcategory_ids, $subcategory['id']);
            $subcategory_offer_key++;
        }
        //print_r($subcategory_ids);
        
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->select_fields('title_' . $selected_language . ', intro_' . $selected_language . ', voucher_persons, prices, images, seller_logo, seller_brandname, seller_address, seller_city, id, seller_lat, seller_lng')
                                    ->set_table($offer->table)
                                    ->where(array('category::IN' => $subcategory_ids, 'project_id' => $project_id, 'trashed' => 0))
                                    ->order_by('id', 'DESC')
                                    ->db_select(true, 0, strtolower(get_class($offer)));
        //print_r($xdb_offer_rows);
        
        $translation = new Translations;
        //$offer_key = 0;
        if (is_array($xdb_offer_rows)){
            foreach ($xdb_offer_rows as $offer_key => $offer_row)
            {
                /*
                if (is_array($exclusion_list) && in_array($offer_row['id'], $exclusion_list))
                {
                    unset($xdb_offer_rows[$offer_key]);
                    $offer_key++;
                    continue;
                }
                */
                
                $seller_city_info = explode('|', $offer_row['seller_city']);
            
                if (isset($seller_city_info[0]))
                {
                    $xdb_offer_rows[$offer_key]['city_info']['city_name'] = $translation->get_translation($seller_city_info[0], $selected_language);
                }
                else
                {
                    $xdb_offer_rows[$offer_key]['city_info']['city_name'] = '';
                }
                
                if (isset($seller_city_info[2]))
                {
                    $xdb_offer_rows[$offer_key]['city_info']['country_name'] = $translation->get_translation($seller_city_info[2], $selected_language);
                }
                else
                {
                    $xdb_offer_rows[$offer_key]['city_info']['country_name'] = '';
                }
                
                // images
                $images = json_decode(str_replace('&quot;', '"', $offer_row['images']), true);
                unset($xdb_offer_rows[$offer_key]['images']);
                $xdb_offer_rows[$offer_key]['images'] = $images;
                $xdb_offer_rows[$offer_key]['main_image'] = $images[0];
                
                //logo
                if (isset($xdb_offer_rows[$offer_key]['seller_logo']))
                {
                    $logo = json_decode(str_replace('&quot;', '"', $offer_row['seller_logo']), true);
                    unset($xdb_offer_rows[$offer_key]['seller_logo']);
                    if (isset($logo[0]))
                    {
                        $xdb_offer_rows[$offer_key]['logo_image'] = $logo[0];
                    }
                }
                
                // prices
                $prices = json_decode(str_replace('&quot;', '"', $offer_row['prices']), true);
                unset($xdb_offer_rows[$offer_key]['prices']);
                $xdb_offer_rows[$offer_key]['prices'] = $prices;
                if (isset($xdb_offer_rows[$offer_key]['prices']['original_price_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_original_price'] = $xdb_offer_rows[$offer_key]['prices']['original_price_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_original_price'] = $xdb_offer_rows[$offer_key]['prices']['original_price'];
                if (isset($xdb_offer_rows[$offer_key]['prices']['discount_price_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_discount_price'] = $xdb_offer_rows[$offer_key]['prices']['discount_price_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_discount_price'] = $xdb_offer_rows[$offer_key]['prices']['discount_price'];
                if (isset($xdb_offer_rows[$offer_key]['prices']['discount_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_discount'] = $xdb_offer_rows[$offer_key]['prices']['discount_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_discount'] = $xdb_offer_rows[$offer_key]['prices']['discount'];
                
                foreach($offer->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
                // foreach($offer->fields['dynamic_fields'] as $dynamic_field)
                {
                    if (isset($xdb_offer_rows[$offer_key][$dynamic_field . '_' . $selected_language]))
                    {
                        $xdb_offer_rows[$offer_key]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_offer_rows[$offer_key][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                        unset($xdb_offer_rows[$offer_key][$dynamic_field . '_' . $selected_language]);
                    }
                }
                
                //$offer_key++;
            }
        }
        
        return $xdb_offer_rows;
    }
    
    public function offer_get_by_subcategory($cat_id)
    {
        global $selected_language, $current_country, $project_id, $exclusion_list, $offer_counter;
        $selected_country = strtolower($current_country);
        
        if (isset($offer_counter)) $offer_counter = 0;
        
        $offer = new Offer;
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->set_table($offer->table)
                                    ->where(array('category' => $cat_id, 'project_id' => $project_id, 'trashed' => 0))
                                    ->order_by('id', 'DESC')
                                    ->db_select(true, 0, strtolower(get_class($offer)));
        
        $translation = new Translations;
        //$offer_key = 0;
        foreach ($xdb_offer_rows as $offer_key => $offer_row)
        {
            if (is_array($exclusion_list) && in_array($offer_row['id'], $exclusion_list))
            {
                unset($xdb_offer_rows[$offer_key]);
                $offer_key++;
                continue;
            }
            
            $seller_city_info = explode('|', $offer_row['seller_city']);
            
            if (isset($seller_city_info[0]))
            {
                $xdb_offer_rows[$offer_key]['city_info']['city_name'] = $translation->get_translation($seller_city_info[0], $selected_language);
            }
            else
            {
                $xdb_offer_rows[$offer_key]['city_info']['city_name'] = '';
            }
            
            if (isset($seller_city_info[2]))
            {
                $xdb_offer_rows[$offer_key]['city_info']['country_name'] = $translation->get_translation($seller_city_info[2], $selected_language);
            }
            else
            {
                $xdb_offer_rows[$offer_key]['city_info']['country_name'] = '';
            }
            
            // images
            $images = json_decode(str_replace('&quot;', '"', $offer_row['images']), true);
            unset($xdb_offer_rows[$offer_key]['images']);
            $xdb_offer_rows[$offer_key]['images'] = $images;
            $xdb_offer_rows[$offer_key]['main_image'] = $images[0];
            
            //logo
            if (isset($xdb_offer_rows[$offer_key]['seller_logo']))
            {
                $logo = json_decode(str_replace('&quot;', '"', $offer_row['seller_logo']), true);
                unset($xdb_offer_rows[$offer_key]['seller_logo']);
                if (isset($logo[0]))
                {
                    $xdb_offer_rows[$offer_key]['logo_image'] = $logo[0];
                }
            }
            
            // prices
            $prices = json_decode(str_replace('&quot;', '"', $offer_row['prices']), true);
            unset($xdb_offer_rows[$offer_key]['prices']);
            $xdb_offer_rows[$offer_key]['prices'] = $prices;
            if (isset($xdb_offer_rows[$offer_key]['prices']['original_price_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_original_price'] = $xdb_offer_rows[$offer_key]['prices']['original_price_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_original_price'] = $xdb_offer_rows[$offer_key]['prices']['original_price'];
            if (isset($xdb_offer_rows[$offer_key]['prices']['discount_price_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_discount_price'] = $xdb_offer_rows[$offer_key]['prices']['discount_price_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_discount_price'] = $xdb_offer_rows[$offer_key]['prices']['discount_price'];
            if (isset($xdb_offer_rows[$offer_key]['prices']['discount_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_discount'] = $xdb_offer_rows[$offer_key]['prices']['discount_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_discount'] = $xdb_offer_rows[$offer_key]['prices']['discount'];
            
            foreach($offer->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
            // foreach($offer->fields['dynamic_fields'] as $dynamic_field)
            {
                if (isset($xdb_offer_rows[$offer_key][$dynamic_field . '_' . $selected_language]))
                {
                    $xdb_offer_rows[$offer_key]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_offer_rows[$offer_key][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                    unset($xdb_offer_rows[$offer_key][$dynamic_field . '_' . $selected_language]);
                }
            }
            
            //$offer_key++;
            $xdb_offer_rows[$offer_key]['offer_counter'] = $offer_counter;
            $offer_counter++;
        }
        
        return $xdb_offer_rows;
    }
    
    public function offer_get_by_city($city)
    {
        global $selected_language, $current_country, $project_id, $offer_counter;
        $selected_country = strtolower($current_country);
        
        if (isset($offer_counter)) $offer_counter = 0;
        
        $offer = new Offer;
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->set_table($offer->table)
                                    ->where(array('seller_city::LIKE' => '%' . $city . '%', 'project_id' => $project_id, 'trashed' => 0))
                                    ->db_select(true, 0, strtolower(get_class($offer)));
        
        $translation = new Translations;
        //$offer_key = 0;
        foreach ($xdb_offer_rows as $offer_key => $offer_row)
        {
            $seller_city_info = explode('|', $offer_row['seller_city']);
            
            if (isset($seller_city_info[0]))
            {
                $xdb_offer_rows[$offer_key]['city_info']['city_name'] = $translation->get_translation($seller_city_info[0], $selected_language);
            }
            else
            {
                $xdb_offer_rows[$offer_key]['city_info']['city_name'] = '';
            }
            
            if (isset($seller_city_info[2]))
            {
                $xdb_offer_rows[$offer_key]['city_info']['country_name'] = $translation->get_translation($seller_city_info[2], $selected_language);
            }
            else
            {
                $xdb_offer_rows[$offer_key]['city_info']['country_name'] = '';
            }
            
            // images
            $images = json_decode(str_replace('&quot;', '"', $offer_row['images']), true);
            unset($xdb_offer_rows[$offer_key]['images']);
            $xdb_offer_rows[$offer_key]['images'] = $images;
            $xdb_offer_rows[$offer_key]['main_image'] = $images[0];
            
            //logo
            if (isset($xdb_offer_rows[$offer_key]['seller_logo']))
            {
                $logo = json_decode(str_replace('&quot;', '"', $offer_row['seller_logo']), true);
                unset($xdb_offer_rows[$offer_key]['seller_logo']);
                $xdb_offer_rows[$offer_key]['logo_image'] = $logo[0];
            }
            
            // prices
            $prices = json_decode(str_replace('&quot;', '"', $offer_row['prices']), true);
            unset($xdb_offer_rows[$offer_key]['prices']);
            $xdb_offer_rows[$offer_key]['prices'] = $prices;
            if (isset($xdb_offer_rows[$offer_key]['prices']['original_price_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_original_price'] = $xdb_offer_rows[$offer_key]['prices']['original_price_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_original_price'] = $xdb_offer_rows[$offer_key]['prices']['original_price'];
            if (isset($xdb_offer_rows[$offer_key]['prices']['discount_price_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_discount_price'] = $xdb_offer_rows[$offer_key]['prices']['discount_price_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_discount_price'] = $xdb_offer_rows[$offer_key]['prices']['discount_price'];
            if (isset($xdb_offer_rows[$offer_key]['prices']['discount_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_discount'] = $xdb_offer_rows[$offer_key]['prices']['discount_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_discount'] = $xdb_offer_rows[$offer_key]['prices']['discount'];
            
            foreach($offer->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
            // foreach($offer->fields['dynamic_fields'] as $dynamic_field)
            {
                $xdb_offer_rows[$offer_key]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_offer_rows[$offer_key][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                unset($xdb_offer_rows[$offer_key][$dynamic_field . '_' . $selected_language]);
            }
            
            //$offer_key++;
            $xdb_offer_rows[$offer_key]['offer_counter'] = $offer_counter;
            $offer_counter++;
        }
        
        return $xdb_offer_rows;
    }
    
    public function offer_get_count_by_category($cat_id, $project_id)
    {
        //global $selected_language, $current_country;
        //$selected_country = strtolower($current_country);
        
        $offer = new Offer;
        
        // get subcategory ids
        $xdb_subcategory = new Xdb;
        $xdb_subcategory_rows = $xdb_subcategory->select_fields('id')
                                                ->set_table($offer->category_table)
                                                ->where(array('parent_id' => $cat_id, 'relation' => 'default'))
                                                //->db_select(true, 0, strtolower(get_class($offer)));
                                                ->db_select(false);
        //print_r($xdb_subcategory_rows);
        $subcategory_ids = array();
        $subcategory_counter = 0;
        foreach ($xdb_subcategory_rows as $subcategory)
        {
            //echo $subcategory['id'];
            //$subcategory_ids = array($subcategory['id']);
            array_push($subcategory_ids, $subcategory['id']);
            $subcategory_counter++;
        }
        //print_r($subcategory_ids);
        
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->select_fields('COUNT(id)')
                                    ->set_table($offer->table)
                                    ->where(array('category::IN' => $subcategory_ids, 'project_id' => $project_id, 'trashed' => 0))
                                    //->db_select(true, 0, strtolower(get_class($offer)));
                                    ->db_select(false);
        //print_r($xdb_offer_rows);
        
        return $xdb_offer_rows[0]['COUNT(id)'];
    }
    
    public function offer_get_count_by_subcategory($cat_id, $project_id)
    {
        //global $selected_language, $current_country;
        //$selected_country = strtolower($current_country);
        
        // BEFORE
        /*
        $offer = new Offer;
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->select_fields('COUNT(id)')
                                    ->set_table($offer->table)
                                    ->where(array('category' => $cat_id, 'project_id' => $project_id, 'trashed' => 0))
                                    //->db_select(true, 0, strtolower(get_class($offer)));
                                    ->db_select(false);
        
        return $xdb_offer_rows[0]['COUNT(id)'];
        */
        
        return 0;
    }
    
    public function daterange2dates($values)
    {
        $daterange = $values['voucher_validity'];
        $daterange_explode = explode(' - ', $daterange);
        if (isset($daterange_explode[0]))
        {
            $return_daterange['voucher_validity_from'] = $daterange_explode[0];
        }
        else
        {
            $return_daterange['voucher_validity_from'] = '';
        }
        
        if (isset($daterange_explode[1]))
        {
            $return_daterange['voucher_validity_to'] = $daterange_explode[1];
        }
        else
        {
            $return_daterange['voucher_validity_to'] = '';
        }
        
        $values['voucher_validity_from'] = $return_daterange['voucher_validity_from'];
        $values['voucher_validity_to'] = $return_daterange['voucher_validity_to'];
        return $values;
    }
    
    public function prices2json($values, $project_id)
    {
        $values = str_replace('ANDPARAMETER', '&', $values);
        //$values = str_replace('PLUSSIGN', '+', $values);
        //echo $values;
        //$values = str_replace('&amp%3B', '%26', $values);
        $values = str_replace('%3Cbr+style%3D%22%22%3E', '', $values);
        $values = str_replace('%0D%0A%3C%2Fp%3E%3Cbr+style%3D%22%22+class%3D%22aloha-cleanme%22%3E', '', $values);
        //$values = str_replace('<br style="">', '', $values);
        //$values = str_replace('<br style="" class="aloha-cleanme">', '', $values);
        $values = str_replace(' style=""', '', $values);
        $values = str_replace(' class="aloha-cleanme"', '', $values);
        
        parse_str($values, $new_values);
        
        // check transaltions
        
        $translation = new Translations;
        $check_voucher_persons = $translation->get_translation($new_values['voucher_persons'], 'en');
        
        if (isset($new_values['seller_city']) && $new_values['seller_city'] != '')
        {
            $seller_city_explode = explode('|', $new_values['seller_city']);
            $seller_city = trim($seller_city_explode[0]);
            $seller_country = trim($seller_city_explode[2]);
            $check_seller_city = $translation->get_translation($seller_city, 'en');
            $check_seller_country = $translation->get_translation($seller_country, 'en');
        }
        // end check translations
        
        $new_values = Offer::daterange2dates($new_values);
        
        $prices['original_price'] = number_format(str_replace(',', '.', $new_values['original_price']), 2, '.', '');
        unset($new_values['original_price']);
        
        $prices['discount_price'] = number_format(str_replace(',', '.', $new_values['discount_price']), 2, '.', '');
        unset($new_values['discount_price']);
        
        $prices['discount'] = $new_values['discount'];
        unset($new_values['discount']);
        
        $countries_currency_own = Countries::get_all_own_currency_countries($project_id);
        foreach ($countries_currency_own as $country_currency )
        {
            $curr_original_price = str_replace(',', '.', $new_values['original_price_' . strtolower($country_currency['iso_code'])]);
            if (is_numeric($curr_original_price))
            {
                $prices['original_price_' . strtolower($country_currency['iso_code'])] = number_format($curr_original_price, 2, '.', '');
            }
            else
            {
                $prices['original_price_' . strtolower($country_currency['iso_code'])] = '0.00';
            }
            unset($new_values['original_price_' . strtolower($country_currency['iso_code'])]);
            
            $curr_discount_price = str_replace(',', '.', $new_values['discount_price_' . strtolower($country_currency['iso_code'])]);
            if (is_numeric($curr_discount_price))
            {
                $prices['discount_price_' . strtolower($country_currency['iso_code'])] = number_format(str_replace(',', '.', $new_values['discount_price_' . strtolower($country_currency['iso_code'])]), 2, '.', '');
            }
            else
            {
                $prices['discount_price_' . strtolower($country_currency['iso_code'])] = '0.00';
            }
            unset($new_values['discount_price_' . strtolower($country_currency['iso_code'])]);
            
            $prices['discount_' . strtolower($country_currency['iso_code'])] = $new_values['discount_' . strtolower($country_currency['iso_code'])];
            unset($new_values['discount_' . strtolower($country_currency['iso_code'])]);
        }
        
        $prices_encoded = json_encode($prices);
        $prices_encoded = str_replace('/', '', $prices_encoded);
        $new_values['prices'] = $prices_encoded;
        $return_query = http_build_query($new_values, '', 'ANDPARAMETER'); 
        //echo $return_query;
        return $return_query;
    }
    
    public function get_saving_subcategory_id($values)
    {
        $values = str_replace('ANDPARAMETER', '&', $values);
        $values = str_replace('%3Cbr+style%3D%22%22%3E', '', $values);
        $values = str_replace('%0D%0A%3C%2Fp%3E%3Cbr+style%3D%22%22+class%3D%22aloha-cleanme%22%3E', '', $values);
        $values = str_replace('<br style="">', '', $values);
        $values = str_replace('<br style="" class="aloha-cleanme">', '', $values);
        parse_str($values, $new_values);
        
        return $new_values['category'];
    }
    
    public function get_subcategory_parent_id($id)
    {
        $offer = new Offer;
        $xdb_offer_categories = new Xdb;
        $xdb_offer_category_rows = $xdb_offer_categories->set_table($offer->category_table)
                                                        ->where(array('id' => $id))
                                                        ->db_select(false);
        //echo $id;
        //print_r($xdb_offer_category_rows);
        return $xdb_offer_category_rows[0]['parent_id'];
    }
    
    public function update_category_offer_count($subcategory_id, $project_id)
    {
        $offer = new Offer;
        $category_id = $offer->get_subcategory_parent_id($subcategory_id);
        
        $offer_subcategory_count = Offer::offer_get_count_by_subcategory($subcategory_id, $project_id);
        $offer_category_count = Offer::offer_get_count_by_category($category_id, $project_id);
        
        $xdb_subcategory_update = new Xdb;
        $update_subcategory_count = $xdb_subcategory_update->set_table($offer->category_table)
                                                           ->simple_update_fields(array('offer_count' => $offer_subcategory_count))
                                                           ->where(array('id' => $subcategory_id))
                                                           ->db_update();
        
        $xdb_category_update = new Xdb;
        $update_category_count = $xdb_category_update->set_table($offer->category_table)
                                                     ->simple_update_fields(array('offer_count' => $offer_category_count))
                                                     ->where(array('id' => $category_id))
                                                     ->db_update(strtolower(get_class($offer)));
    }
    
    public function offer_save($id, $project_id, $new_values)
    {
        $offer = new Offer;
        
        $the_values = $offer->prices2json($new_values, $project_id);
        //die($the_values);
        
        $xdb_offer_update = new Xdb;
        $update = $xdb_offer_update->set_table($offer->table)
                                   ->update_fields($project_id, $offer->fields, $the_values, 'update')
                                   ->where(array('id' => $id))
                                   ->db_update(strtolower(get_class($offer)), array('trashed', 'category', 'seller_city'));
        $xdb_offer_update->update_permanent_cache_single($offer->table, $id);
        
        // update offer category count
        $subcategory_id = $offer->get_saving_subcategory_id($new_values);
        Offer::update_category_offer_count($subcategory_id, $project_id);
        
        $all_languages = Languages::get_all_www_enabled_languages($project_id);
        
        foreach ($all_languages as $language)
        {
            $selected_language = $language['code'];
            
            Offer::get_all_offer_categories_cached($project_id, true, $selected_language);
            Offer::get_all_offer_cities_cached($project_id, true, $selected_language);
            Offer::get_all_offer_count_cached($project_id, true, $selected_language);
        }
        
        // write history
        
        $admin_session = $_SESSION['admin_user'][0];
        unset($admin_session['password']);
        
        $current_time = new DateTime();
        $now = $current_time->format('Y-m-d H:i:s');
        
        $offer->history_fields['static_fields']['project_id']['value'] = $project_id;
        $offer->history_fields['static_fields']['offer_id']['value'] = $id;
        $offer->history_fields['static_fields']['time_changed']['value'] = $now;
        $offer->history_fields['static_fields']['user']['value'] = json_encode($admin_session);
        
        $xdb_offer_history_insert = new Xdb;
        $insert_new = $xdb_offer_history_insert->db_insert_content($project_id, $offer->history_table, $offer->history_fields, strtolower(get_class($offer)));
    }
    
    public function offer_insert_new_category($project_id, $parent_id, $position, $title, $relation)
    {
        $offer = new Offer;
        
        $xdb_offer_categories = new Xdb;
        $xdb_offer_category_rows = $xdb_offer_categories->select_fields('MAX(position)')
                                                        ->set_table($offer->category_table)
                                                        ->where(array('project_id' => $project_id, 'parent_id' => $parent_id))
                                                        ->db_select(false);
        
        $new_position = $xdb_offer_category_rows[0]['MAX(position)'] + 1;
        
        $offer->category_fields['static_fields']['project_id']['value'] = $project_id;
        $offer->category_fields['static_fields']['parent_id']['value'] = $parent_id;
        $offer->category_fields['static_fields']['position']['value'] = $new_position;
        $offer->category_fields['static_fields']['title']['value'] = $title;
        $offer->category_fields['static_fields']['relation']['value'] = $relation;
        
        $xdb_offer_category_insert = new Xdb;
        $insert_new = $xdb_offer_category_insert->db_insert_content($project_id, $offer->category_table, $offer->category_fields, strtolower(get_class($offer)));
        
        $last_id = $insert_new;
        
        $all_languages = Languages::get_all_www_enabled_languages($project_id);
        
        foreach ($all_languages as $language)
        {
            $selected_language = $language['code'];
            
            Offer::get_all_offer_categories_cached($project_id, true, $selected_language);
        }
        
        return $last_id;
    }
    
    public function offer_rename_category($project_id, $id, $new_title)
    {
        $offer = new Offer;
        $xdb_offer_categories = new Xdb;
        $xdb_offer_category_rows = $xdb_offer_categories->set_table($offer->category_table)
                                                        ->where(array('id' => $id))
                                                        ->db_select(false);
        $old_title = $xdb_offer_category_rows[0]['title'];
        
        $the_values = 'title=' . $new_title;
        
        $xdb_offer_category_update = new Xdb;
        $update = $xdb_offer_category_update->set_table($offer->category_table)
                                            ->update_fields($project_id, $offer->category_fields, $the_values, 'update')
                                            ->where(array('id' => $id))
                                            ->db_update(strtolower(get_class($offer)));
        
        $translation = new Translations;
        $check_translation = $translation->get_translation($new_title, 'en');
        
        $all_languages = Languages::get_all_www_enabled_languages($project_id);
        
        foreach ($all_languages as $language)
        {
            $selected_language = $language['code'];
            
            Offer::get_all_offer_categories_cached($project_id, true, $selected_language);
        }
    }
    
    public function offer_delete_category($id)
    {
        $offer = new Offer;
        $xdb_offer_category_delete = new Xdb;
        $delete = $xdb_offer_category_delete->set_table($offer->category_table)
                                            ->where(array('id' => $id))
                                            ->db_delete(strtolower(get_class($offer)));
        
        $all_languages = Languages::get_all_www_enabled_languages($project_id);
        
        foreach ($all_languages as $language)
        {
            $selected_language = $language['code'];
            
            Offer::get_all_offer_categories_cached($project_id, true, $selected_language);
        }
    }
    
    public function get_all_offer_categories($project_id)
    {
        $offer = new Offer;
        $xdb_offer_categories = new Xdb;
        $xdb_offer_category_rows = $xdb_offer_categories->set_table($offer->category_table)
                                                        ->where(array('project_id' => $project_id))
                                                        ->order_by('id')
                                                        //->db_select(true, 0, strtolower(get_class($offer)));
                                                        ->db_select(false);
        $categories = $xdb_offer_category_rows;
        
        // rearrange categories
        $categories_arranged = array();
        $parent_position = array();
        if (is_array($categories))
        {
            foreach ($categories as $category)
            {
                if ($category['relation'] == 'folder')
                {
                    //$offer_count = count(Offer::offer_get_by_category($category['id']));
                    //$offer_count = 1;
                    $categories_arranged[$category['position']] = array('id' => $category['id'],'title' => $category['title'], 'offer_count' => $category['offer_count']);
                    $parent_position[$category['id']] = $category['position'];
                }
                elseif ($category['relation'] == 'default')
                {
                    //$offer_count = count(Offer::offer_get_by_subcategory($category['id']));
                    //$offer_count = 1;
                    if (isset($parent_position[$category['parent_id']]))
                    {
                        $get_parent_position = $parent_position[$category['parent_id']];
                        $categories_arranged[$get_parent_position]['sub'][$category['position']] = array('id' => $category['id'],'title' => $category['title'], 'offer_count' => $category['offer_count']);
                    }
                }
            }
        }
        
        return $categories_arranged;
    }
    
    public function get_all_offer_categories_cached($project_id, $rewrite = false, $language = false)
    {
        global $categories_arranged, $project_slug, $selected_language, $project_template, $cities;
        
        if ($language)
        {
            $selected_language = $language;
        }
        
        $memcache = new xMemcache;
        $categories_loaded = $memcache->get_memcache('categories_' . $selected_language);
        
        if (!$categories_loaded || $rewrite)
        {
            if (!isset($project_slug) || !isset($project_template))
            {
                $project_info = Projects::get_project_info_by_id($project_id);
                
                $project_slug = $project_info['project_slug'];
                $project_template = Projects::get_project_template($project_id);
            }
            
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/props/categories_' . $selected_language . '.tpl') || $rewrite)
            {
                $offer = new Offer;
                $xdb_offer_categories = new Xdb;
                $xdb_offer_category_rows = $xdb_offer_categories->set_table($offer->category_table)
                                                                ->where(array('project_id' => $project_id))
                                                                ->order_by('id')
                                                                //->db_select(true, 0, strtolower(get_class($offer)));
                                                                ->db_select(false);
                $categories = $xdb_offer_category_rows;
                
                // rearrange categories
                $categories_arranged = array();
                $parent_position = array();
                if (is_array($categories))
                {
                    foreach ($categories as $category)
                    {
                        if ($category['relation'] == 'folder')
                        {
                            //$offer_count = count(Offer::offer_get_by_category($category['id']));
                            //$offer_count = 1;
                            //$categories_arranged[$category['position']] = array('id' => $category['id'],'title' => $category['title'], 'offer_count' => $category['offer_count']);
                            
                            $categories_arranged[$category['position']] = array('id' => $category['id'],'title' => $category['title'], 'offer_count' => Offer::offer_get_count_by_category($category['id'], $project_id));
                            
                            $parent_position[$category['id']] = $category['position'];
                        }
                        elseif ($category['relation'] == 'default')
                        {
                            //$offer_count = count(Offer::offer_get_by_subcategory($category['id']));
                            //$offer_count = 1;
                            if (isset($parent_position[$category['parent_id']]))
                            {
                                $get_parent_position = $parent_position[$category['parent_id']];
                                //$categories_arranged[$get_parent_position]['sub'][$category['position']] = array('id' => $category['id'],'title' => $category['title'], 'offer_count' => $category['offer_count']);
                                $categories_arranged[$get_parent_position]['sub'][$category['position']] = array('id' => $category['id'],'title' => $category['title'], 'offer_count' => Offer::offer_get_count_by_subcategory($category['id'], $project_id));
                            }
                        }
                    }
                }
                
                $cities = $offer->offers_get_cities($project_id);
                
                if ($language)
                {
                    $selected_language = $language;
                }
                
                $return_categories = $offer->load_prop('offer_categories_prop');
                
                $file = new File($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/props/categories_' . $selected_language . '.tpl');
                $file->set_writable()->write_to_file($return_categories)->set_unwritable();
                
                if ($categories_loaded)
                {
                    $memcache->replace_memcache('categories_' . $selected_language, $return_categories);
                }
                else
                {
                    $memcache->set_memcache('categories_' . $selected_language, $return_categories);
                }
                
                /*
                $return_categories = $offer->load_prop('categories');
                
                $check_dir = $_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/props/';
                if (!is_dir($check_dir)) {
                    mkdir($check_dir);         
                }
                
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/props/categories_' . $selected_language . '.tpl', $return_categories);
                */
            }
            else
            {
                //$return_categories = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/props/categories_' . $selected_language . '.tpl');
                
                /*
                ob_start();
                readfile($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/props/categories_' . $selected_language . '.tpl');
                $return_categories = ob_get_clean();
                */
                $return_categories = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/props/categories_' . $selected_language . '.tpl');
                
                if ($categories_loaded)
                {
                    $memcache->replace_memcache('categories_' . $selected_language, $return_categories);
                }
                else
                {
                    $memcache->set_memcache('categories_' . $selected_language, $return_categories);
                }
            }
        }
        else
        {
            $return_categories = $categories_loaded;
        }
        //return $categories_arranged;
        
        return $return_categories;
    }
    
    public function get_all_offer_cities_cached($project_id, $rewrite = false, $language = false)
    {
        global $project_slug, $selected_language, $project_template, $cities;
        
        if ($language)
        {
            $selected_language = $language;
        }
        
        $memcache = new xMemcache;
        $cities_loaded = $memcache->get_memcache('cities_' . $selected_language);
        
        if (!$cities_loaded || $rewrite)
        {
            if (!isset($project_slug) || !isset($project_template))
            {
                $project_info = Projects::get_project_info_by_id($project_id);
                
                $project_slug = $project_info['project_slug'];
                $project_template = Projects::get_project_template($project_id);
            }
            
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/props/cities_' . $selected_language . '.tpl') || $rewrite)
            {
                $offer = new Offer;
                $cities = $offer->offers_get_cities($project_id);
                
                $return_cities = $offer->load_prop('offer_cities_prop');
                
                $file = new File($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/props/cities_' . $selected_language . '.tpl');
                $file->set_writable()->write_to_file($return_cities)->set_unwritable();
                
                if ($cities_loaded)
                {
                    $memcache->replace_memcache('cities_' . $selected_language, $return_cities);
                }
                else
                {
                    $memcache->set_memcache('cities_' . $selected_language, $return_cities);
                }
            }
            else
            {
                $return_cities = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/props/cities_' . $selected_language . '.tpl');
                
                if ($cities_loaded)
                {
                    $memcache->replace_memcache('cities_' . $selected_language, $return_cities);
                }
                else
                {
                    $memcache->set_memcache('cities_' . $selected_language, $return_cities);
                }
            }
        }
        else
        {
            $return_cities = $cities_loaded;
        }
        
        return $return_cities;
    }
    
    public function get_all_offer_count_cached($project_id, $rewrite = false, $language = false)
    {
        global $project_slug, $selected_language, $project_template, $offer_count;
        
        if ($language)
        {
            $selected_language = $language;
        }
        
        $memcache = new xMemcache;
        $count_loaded = $memcache->get_memcache('count_' . $selected_language);
        
        if (!$count_loaded || $rewrite)
        {
            if (!isset($project_slug) || !isset($project_template))
            {
                $project_info = Projects::get_project_info_by_id($project_id);
                
                $project_slug = $project_info['project_slug'];
                $project_template = Projects::get_project_template($project_id);
            }
            
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/props/count_' . $selected_language . '.tpl') || $rewrite)
            {
                $offer = new Offer;
                $offer_count = $offer->offers_www_get_count($project_id, $selected_language);
                
                $return_count = $offer->load_prop('offer_count_prop');
                
                $file = new File($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/props/count_' . $selected_language . '.tpl');
                $file->set_writable()->write_to_file($return_count)->set_unwritable();
                
                if ($count_loaded)
                {
                    $memcache->replace_memcache('count_' . $selected_language, $return_count);
                }
                else
                {
                    $memcache->set_memcache('count_' . $selected_language, $return_count);
                }
            }
            else
            {
                $return_count = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/props/count_' . $selected_language . '.tpl');
                
                if ($count_loaded)
                {
                    $memcache->replace_memcache('count_' . $selected_language, $return_count);
                }
                else
                {
                    $memcache->set_memcache('count_' . $selected_language, $return_count);
                }
            }
        }
        else
        {
            $return_count = $count_loaded;
        }
        
        return $return_count;
    }
    
    public function load_prop($prop_name)
    {
        global $selected_language, $current_country, $currency, $offer_class, $offer, $image_size, $trim, $title_trim, $offer_counter;
        //echo $offer_counter . ' ';
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.modules/props.' . strtolower(get_called_class()) . '/' . $prop_name . '.php');
        $prop = ob_get_contents();
        ob_end_clean();
        return $prop;
    }
    
    public function offer_to_trash($id)
    {
        $offer = new Offer;
        $projects = Projects::get_all_projects();
        $trashed_object['static_fields']['trashed'] = $offer->fields['static_fields']['trashed'];
        $trashed_object['static_fields']['trashed']['value'] = 1;
        foreach ($projects as $project)
        {
            $project_id = $project['id'];
            $xdb_offer_update = new Xdb;
            $update = $xdb_offer_update->set_table($offer->table)
                                       //->update_fields($project_id, $trashed_object)
                                       ->simple_update_fields(array('trashed' => 1))
                                       ->where(array('id' => $id))
                                       ->db_update(strtolower(get_class($offer)), array('trashed', 'category', 'seller_city'));
            $xdb_offer_update->update_permanent_cache_single($offer->table, $id);
            
            $xdb_offer = new Xdb;
            $xdb_offer_rows = $xdb_offer->select_fields('id, category')
                                        ->set_table($offer->table)
                                        ->where(array('id' => $id))
                                        ->db_select(false);
            
            $subcategory_id = $xdb_offer_rows[0]['category'];
            Offer::update_category_offer_count($subcategory_id, $project_id);
            
            $all_languages = Languages::get_all_www_enabled_languages($project_id);
            
            foreach ($all_languages as $language)
            {
                $selected_language = $language['code'];
                
                Offer::get_all_offer_categories_cached($project_id, true, $selected_language);
                Offer::get_all_offer_cities_cached($project_id, true, $selected_language);
                Offer::get_all_offer_count_cached($project_id, true, $selected_language);
            }
        }
    }
    
    public function offer_to_renewed($id)
    {
        $offer = new Offer;
        $projects = Projects::get_all_projects();
        $trashed_object['static_fields']['trashed'] = $offer->fields['static_fields']['trashed'];
        $trashed_object['static_fields']['trashed']['value'] = 0;
        foreach ($projects as $project)
        {
            $project_id = $project['id'];
            $xdb_offer_update = new Xdb;
            $update = $xdb_offer_update->set_table($offer->table)
                                       //->update_fields($project_id, $trashed_object)
                                       ->simple_update_fields(array('trashed' => 0))
                                       ->where(array('id' => $id))
                                       ->db_update(strtolower(get_class($offer)), array('trashed', 'category', 'seller_city'));
            $xdb_offer_update->update_permanent_cache_single($offer->table, $id);
            
            $xdb_offer = new Xdb;
            $xdb_offer_rows = $xdb_offer->select_fields('id, category')
                                        ->set_table($offer->table)
                                        ->where(array('id' => $id))
                                        ->db_select(false);
            
            $subcategory_id = $xdb_offer_rows[0]['category'];
            Offer::update_category_offer_count($subcategory_id, $project_id);
            
            $all_languages = Languages::get_all_www_enabled_languages($project_id);
        
            foreach ($all_languages as $language)
            {
                $selected_language = $language['code'];
                
                Offer::get_all_offer_categories_cached($project_id, true, $selected_language);
                Offer::get_all_offer_cities_cached($project_id, true, $selected_language);
                Offer::get_all_offer_count_cached($project_id, true, $selected_language);
            }
        }
    }
    
    public function get_top_10_offers()
    {
        $offer = new Offer;
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->select_fields('id, title_sl, images')
                                    ->set_table($offer->table)
                                    ->where(array('project_id' => 1, 'trashed' => 0))
                                    ->order_by('voucher_used', 'DESC')
                                    ->limit(10)
                                    ->db_select(true, 0, strtolower(get_class($offer)));
        return $xdb_offer_rows;
    }
    
    public function category_get_by_id($id)
    {
        $category = new Offer;
        $xdb_category = new Xdb;
        $xdb_category_rows = $xdb_category->set_table($category->category_table)
                                          ->db_select_by_id($id, strtolower(get_class($category)));
        
        return $xdb_category_rows;
    }
    
    public function category_save($id, $project_id, $new_values)
    {
        $offer = new Offer;
        
        $xdb_offer_update = new Xdb;
        $update = $xdb_offer_update->set_table($offer->category_table)
                                   ->update_fields($project_id, $offer->category_fields, $new_values, 'update')
                                   ->where(array('id' => $id))
                                   ->db_update(strtolower(get_class($offer)));
        $xdb_offer_update->update_permanent_cache_single($offer->category_table, $id);
    }
    
    public function category_www_get_by_id($id)
    {
        global $selected_language, $current_country, $project_id, $exclusion_list;
        $selected_country = strtolower($current_country);
        $offer = new Offer;
        $xdb_offer = new Xdb;
        $xdb_offer_rows = $xdb_offer->set_table($offer->category_table)
                                    //->db_select_by_id($id, strtolower(get_class($offer)));
                                    ->db_select_www_by_id($id, strtolower(get_class($offer)));
        
        if (isset($xdb_offer_rows[0]))
        {
            
            foreach($offer->category_fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
            {
                $xdb_offer_rows[0]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_offer_rows[0][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                unset($xdb_offer_rows[0][$dynamic_field . '_' . $selected_language]);
            }
            
            if (isset($xdb_offer_rows[0]['exclusion_' . $selected_language]) && is_array($xdb_offer_rows[0]['exclusion_' . $selected_language]))
            {
                $exclusion_list = $xdb_offer_rows[0]['exclusion_' . $selected_language];
            }
            
            return $xdb_offer_rows[0];
        }
        else
        {
            return false;
        }
    }
    
    public function get_nearby_offers($lat, $lng)
    {
        global $selected_language, $current_country;
        $selected_country = $current_country;
        
        $offer = new Offer;
        $xdb_offers = new Xdb;
        
        $xdb_offer_rows = $xdb_offers//->select_fields('id, title_sl, intro_sl, prices, images, seller_brandname, seller_address, seller_city, voucher_persons, ( 6371 * acos( cos( radians(' . $lng . ') ) * cos( radians( seller_lat ) ) * cos( radians( seller_lng ) - radians(' . $lat . ') ) + sin( radians(' . $lng . ') ) * sin( radians( seller_lat ) ) ) ) AS distance')
                                     ->select_fields('id, title_sl, intro_sl, prices, images, seller_brandname, seller_address, seller_city, voucher_persons, ( 6371 * acos( cos( radians(' . $lat . ') ) * cos( radians( seller_lat ) ) * cos( radians( seller_lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( seller_lat ) ) ) ) AS distance')
                                      ->set_table($offer->table)
                                      //->where(array('trashed' => 0, 'project_id' => 1, 'distance::<' => 25))
                                      ->where(array('trashed' => 0, 'project_id' => 1))
                                      //->where(array('trashed' => 0))
                                      //->having('distance < 25')
                                      ->order_by('distance')
                                      ->limit(50)
                                      ->db_select(false);
        
        $translation = new Translations;
        //$counter = 0;
        foreach ($xdb_offer_rows as $offer_key => $offer_row)
        {
            $seller_city_info = explode('|', $offer_row['seller_city']);
            
            if (isset($seller_city_info[0]))
            {
                $xdb_offer_rows[$offer_key]['city_info']['city_name'] = $translation->get_translation($seller_city_info[0], $selected_language);
            }
            else
            {
                $xdb_offer_rows[$offer_key]['city_info']['city_name'] = '';
            }
            
            if (isset($seller_city_info[2]))
            {
                $xdb_offer_rows[$offer_key]['city_info']['country_name'] = $translation->get_translation($seller_city_info[2], $selected_language);
            }
            else
            {
                $xdb_offer_rows[$offer_key]['city_info']['country_name'] = '';
            }
            
            // images
            $images = json_decode(str_replace('&quot;', '"', $offer_row['images']), true);
            unset($xdb_offer_rows[$offer_key]['images']);
            $xdb_offer_rows[$offer_key]['images'] = $images;
            $xdb_offer_rows[$offer_key]['main_image'] = $images[0];
            
            //logo
            if (isset($xdb_offer_rows[$offer_key]['seller_logo']))
            {
                $logo = json_decode(str_replace('&quot;', '"', $offer_row['seller_logo']), true);
                unset($xdb_offer_rows[$offer_key]['seller_logo']);
                $xdb_offer_rows[$offer_key]['logo_image'] = $logo[0];
            }
            
            // prices
            $prices = json_decode(str_replace('&quot;', '"', $offer_row['prices']), true);
            unset($xdb_offer_rows[$offer_key]['prices']);
            $xdb_offer_rows[$offer_key]['prices'] = $prices;
            if (isset($xdb_offer_rows[$offer_key]['prices']['original_price_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_original_price'] = $xdb_offer_rows[$offer_key]['prices']['original_price_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_original_price'] = $xdb_offer_rows[$offer_key]['prices']['original_price'];
            if (isset($xdb_offer_rows[$offer_key]['prices']['discount_price_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_discount_price'] = $xdb_offer_rows[$offer_key]['prices']['discount_price_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_discount_price'] = $xdb_offer_rows[$offer_key]['prices']['discount_price'];
            if (isset($xdb_offer_rows[$offer_key]['prices']['discount_' . $selected_country])) $xdb_offer_rows[$offer_key]['view_discount'] = $xdb_offer_rows[$offer_key]['prices']['discount_' . $selected_country]; else $xdb_offer_rows[$offer_key]['view_discount'] = $xdb_offer_rows[$offer_key]['prices']['discount'];
            
            foreach($offer->fields['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
            // foreach($offer->fields['dynamic_fields'] as $dynamic_field)
            {
                if (isset($xdb_offer_rows[$offer_key][$dynamic_field . '_' . $selected_language]))
                {
                    $xdb_offer_rows[$offer_key]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_offer_rows[$offer_key][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                    unset($xdb_offer_rows[$offer_key][$dynamic_field . '_' . $selected_language]);
                }
            }
            
            //$offer_key++;
        }
        
        return $xdb_offer_rows;
    }
}

?>