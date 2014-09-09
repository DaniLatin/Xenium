<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class Translations
{
    protected $table;
    public $fields = array();
    
    public function __construct()
    {
        $this->table = 'www_translations';
        
        $this->fields = 
        array(
        'dynamic_fields' =>
            array(
                'translation' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL', 
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
    }
    
    public function rearrange_translations($translations_array)
    {
        if ($translations_array)
        {
           foreach ($translations_array as $translation)
            {
                $default_translation = $translation['translation_en'];
                
                foreach ($translation as $translation_key => $translation_value)
                {
                    $translation_arrangement[$default_translation] = array($translation_key => $translation_value);
                }
            }
            
            return $translation_arrangement; 
        }
        else
        {
            return false;
        }
    }
    
    public function rearrange_translations_by_id($translations_array)
    {
        if ($translations_array)
        {
           foreach ($translations_array as $translation)
            {
                $translation_id = $translation['id'];
                
                foreach ($translation as $translation_key => $translation_value)
                {
                    $translation_arrangement[$translation_id] = array($translation_key => $translation_value);
                }
            }
            
            return $translation_arrangement; 
        }
        else
        {
            return false;
        }
    }
    
    public function get_all_translations()
    {
        $translation_array = new Translations;
        $xdb_translations = new Xdb;
        $xdb_translations_rows = $xdb_translations->set_table($translation_array->table)
                                                  ->db_select(true, 0, 'translations');
        
        return $translation_array->rearrange_translations($xdb_translations_rows);
        /*
        $xdb_translations_key = new Xdb;
        $memcache_key = $xdb_translations_key->set_table($this->table)
                                             ->db_select_get_memcache_key();
        
        $memcache = new xMemcache;
        if ($memcache->get_memcache('f_post_' . $memcache_key))
        {
            return $memcache->get_memcache('f_post_' . $memcache_key);
        }
        else
        {
            $new_rows = $this->rearrange_translations($xdb_translations_rows);
            $memcache->set_memcache('f_post_' . $memcache_key, $new_rows, MEMCACHE_COMPRESSED, 0);
            return $new_rows;
        }*/
    }
    
    public function get_all_translations_by_id()
    {
        $xdb_translations = new Xdb;
        $xdb_translations_rows = $xdb_translations->set_table($this->table)
                                                  ->db_select(true, 0, 'translations');
        
        return $this->rearrange_translations_by_id($xdb_translations_rows);
    }
    
    public function get_all_translations_unarranged()
    {
        $xdb_translations = new Xdb;
        $xdb_translations_rows = $xdb_translations->set_table($this->table)
                                                  ->db_select(true, 0, 'translations');
        
        return $xdb_translations_rows;
    }
    
    public function get_all_translations_admin()
    {
        $tr = new Translations;
        $xdb_translations = new Xdb;
        $xdb_translations_rows = $xdb_translations->set_table($tr->table)
                                                  ->db_select(true, 0, 'translations');
        
        return $xdb_translations_rows;
    }
    
    public function add_new_translation($string)
    {
        $value['dynamic_fields']['translation']['field_type'] = $this->fields['dynamic_fields']['translation']['field_type'];
        $value['dynamic_fields']['translation']['value_en'] = $string;
        $xdb_translations_insert = new Xdb;
        $insert_new = $xdb_translations_insert->db_insert($this->table, $value, 'translations');
    }
    
    public function get_translation($default_string, $language)
    {
        global $translations;
        
        if (!isset($translations) || !is_array($translations))
        {
            $translations = Translations::get_all_translations();
        }
        
        $all_translations = $translations;
        //$all_translations = $this->get_all_translations();
        if (!isset($all_translations[$default_string]))
        {
            $this->add_new_translation($default_string);
            $translations = Translations::get_all_translations();
        }
        if (isset($all_translations[$default_string]['translation_' . $language]))
        {
            $output = html_entity_decode($all_translations[$default_string]['translation_' . $language]);
        }
        else
        {
            $output = html_entity_decode($default_string);
        }
        
        return $output;
    }
    
    public function get_all_modified_translations()
    {
        return xMemcache::get_memcache('modified_translations');
    }
    
    public function get_modified_translation($default_string, $language, $modifier)
    {
        global $modified_translations, $translations;
        
        $all_translations = $modified_translations;
        
        if (isset($all_translations[$default_string]['translation_' . $language . '_' . $modifier]))
        {
            return $all_translations[$default_string]['translation_' . $language . '_' . $modifier];
        }
        else
        {
            $all_translations[$default_string]['translation_' . $language . '_' . $modifier] = call_user_func($modifier, $translations[$default_string]['translation_' . $language]);
            $return_value = call_user_func($modifier, $translations[$default_string]['translation_' . $language]);
            //$update_translations = Translations::get_all_translations_unarranged();
            
            if (class_exists('Memcache'))
            {
                if (xMemcache::get_memcache('modified_translations'))
                {
                    xMemcache::replace_memcache('modified_translations', $all_translations);
                }
                else
                {
                    xMemcache::set_memcache('modified_translations', $all_translations);
                }
            }
            
            return $return_value;
        }
    }
    
    public function translations_save($language_to, $new_values)
    {
        $unarranged_translations = $this->get_all_translations_unarranged();
        $current_translations = $this->get_all_translations_by_id();
        parse_str($new_values, $new_translations);
        
        $translation = new Translations;
        
        $translation_counter = 0;
        
        foreach ($current_translations as $current_translation => $current_trnslation_value)
        {
            if (isset($new_translations['translation_' . $language_to . '-' . $current_translation]) && $current_translations[$current_translation]['translation_' . $language_to] != $new_translations['translation_' . $language_to . '-' . $current_translation])
            {
                // save to database, update unarranged array
                
                $xdb_translation_update = new Xdb;
                $update = $xdb_translation_update->set_table($translation->table)
                                                 ->simple_update_fields(array('translation_' . $language_to => htmlentities($new_translations['translation_' . $language_to . '-' . $current_translation], ENT_QUOTES, "UTF-8")))
                                                 ->where(array('id' => $current_translation))
                                                 ->db_update();
                
                $unarranged_translations[$translation_counter]['translation_' . $language_to] =  $new_translations['translation_' . $language_to . '-' . $current_translation];
            }
            $translation_counter++;
        }
        
        $xdb_translations = new Xdb;
        $memcache_key = $xdb_translations->set_table($translation->table)
                                         ->db_select_get_memcache_key();
        // save changes to memcache
        $memcache = new xMemcache;
        //print_r($unarranged_translations);
        if ($memcache->get_memcache($memcache_key))
        {
            $memcache->replace_memcache($memcache_key, $unarranged_translations, MEMCACHE_COMPRESSED, 0);
        }
        
        xSmarty::smarty_clear_cache();
    }
}

?>