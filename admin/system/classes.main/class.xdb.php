<?php

/**
 * @author Danijel
 * @copyright 2012
 */

class Xdb
{
    protected $db;
    protected $capture = false;
    protected $select_query;
    protected $update_query;
    protected $delete_query;
    protected $named_placeholders = array();
    protected $update_table;
    
    protected $limit_query;
    
    protected $cache_tables;
    
    protected $add_field;
    protected $insert_fields;
    
    protected $updating_fields;
    
    protected $permanent_cache = array();
    protected $smarty_cache = array();
    protected $alter_table = array();
    protected $update_query_clauses = array();
    
    protected $where_clauses_combined = array();
    
    public $function_relations = array();
    protected $update_parent_queryes = array();
    
    public function __construct()
    {
        // get memcache permanent cached queries
        $relations_cache = new xMemcache;
        $permanent_cache_queries = $relations_cache->get_memcache('permanent_cache');
        if ($permanent_cache_queries)
        {
            $this->permanent_cache = $permanent_cache_queries;
        }
        else
        {
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/admin/system/relations/permanent_cache.php'))
            {
                include ($_SERVER['DOCUMENT_ROOT'] . '/admin/system/relations/permanent_cache.php');
                if (!empty($cached_queries))
                {
                    $this->permanent_cache = $cached_queries;
                    $relations_cache->set_memcache('permanent_cache', $cached_queries);
                }
                else
                {
                    $this->permanent_cache = array();
                }
            }
            else
            {
                $this->permanent_cache = array();
            }
        }
        
        //xMemcache::get_memcache_settings();
        
        // get smarty cached queries
        /*
        $smarty_cache_files = $relations_cache->get_memcache('smarty_cache');
        if ($smarty_cache_files)
        {
            $this->smarty_cache = $smarty_cache_files;
        }
        else
        {
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/admin/system/relations/smarty_cache.php'))
            {
                include ($_SERVER['DOCUMENT_ROOT'] . '/admin/system/relations/smarty_cache.php');
                if (!empty($smarty_queries))
                {
                    $this->smarty_cache = $smarty_queries;
                    $relations_cache->set_memcache('smarty_cache', $smarty_queries);
                }
                else
                {
                    $this->smarty_cache = array();
                }
            }
            else
            {
                $this->smarty_cache = array();
            }
        }
        */
        
        
        
        
        // get function relations
        $functions_cache = $relations_cache->get_memcache('function_relations');
        if ($functions_cache)
        {
            $this->function_relations = $functions_cache;
        }
        else
        {
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/admin/system/relations/function_relations.php'))
            {
                include ($_SERVER['DOCUMENT_ROOT'] . '/admin/system/relations/function_relations.php');
                if (!empty($functions))
                {
                    $this->function_relations = $functions;
                    $relations_cache->set_memcache('function_relations', $functions);
                }
                else
                {
                    $this->function_relations = array();
                }
            }
            else
            {
                $this->function_relations = array();
            }
        }
    }
    
    public function db_connect()
    {
        $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'", PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        //$this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        //$this->db->query('SET NAMES utf8');
    }
    
    public function db_disconnect()
    {
        $this->db = null;
    }
    
    public function select_fields($fields)
    {
        $this->select_query .= 'SELECT ' . $fields;
        return $this;
    }
    
    public function set_table($tables)
    {
        $this->cache_tables = $tables;
        $this->update_table = $tables;
        
        if (!$this->select_query)
        {
            $this->select_fields('*');
        }
        
        $this->select_query .= ' FROM ' . $tables;
        $this->update_query = 'UPDATE ' . $tables . ' SET ';
        $this->delete_query = 'DELETE FROM ' . $tables . ' ';
        return $this;
    }
    
    public function left_join($join_table, $on_clause)
    {
        $this->select_query .= ' LEFT JOIN ' . $join_table . ' ON ';
        
        foreach ($on_clause as $left_field => $right_field)
        {
            $on_clauses[] = $left_field . ' = ' . $right_field;
        }
        $this->select_query .= implode(' AND ', $on_clauses);
        return $this;
    }
    
    public function arrange_fields($project_id, $values_array = array(), $new_values_array, $func_type = 'none')
    {
        //echo urldecode($new_values_array);
        
        if ($new_values_array)
        {
            /*if (is_object(json_decode($new_values_array)))
            {
                $new_values_array = json_decode($new_values_array, true);
            }*/
            //echo $new_values_array;
            $new_values_array = str_replace('ANDPARAMETER', '&', $new_values_array);
            //$new_values_array = str_replace('&amp', urlencode('&amp'), $new_values_array);
            //echo $new_values_array;
            $new_values_array = str_replace('%3Cbr+style%3D%22%22%3E', '', $new_values_array);
            $new_values_array = str_replace('%0D%0A%3C%2Fp%3E%3Cbr+style%3D%22%22+class%3D%22aloha-cleanme%22%3E', '', $new_values_array);
            //$new_values_array = str_replace('<br style="">', '', $new_values_array);
            //$new_values_array = str_replace('<br style="" class="aloha-cleanme">', '', $new_values_array);
            $new_values_array = str_replace(' style=""', '', $new_values_array);
            $new_values_array = str_replace(' class="aloha-cleanme"', '', $new_values_array);
            
            // bof automatic prep processing functions by modules
            $filename = $_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.modules/class.*.php';
            foreach (glob($filename) as $filefound)
            {
                //echo "$filefound size " . filesize($filefound) . "\n";
                $tokens = token_get_all(file_get_contents($filefound));
                $comments = array();
                foreach($tokens as $token) {
                    if($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT) {
                        $comments[] = $token[1];
                        $module_name_pattern = "/@moduleName (.*?)\n/";
                        $module_prep_function = "/@prepProcessContentSaveFunction (.*?)\n/";
                        //preg_match($module_category_pattern, $token[1], $category_matches);
                        preg_match($module_name_pattern, $token[1], $name_matches);
                        preg_match($module_prep_function, $token[1], $prep_matches);
                        //print_r($name_matches);
                        //print_r($text_editor_matches);
                        
                        if (isset($prep_matches[1]))
                        {
                            if ((double)phpversion() >= 5.3)
                            {
                                $new_values_array = call_user_func_array(preg_replace('/\s+/', '', $name_matches[1]) .'::' . preg_replace('/\s+/', '', strtolower($prep_matches[1])), array($new_values_array));
                            }
                            else
                            {
                                $new_values_array = call_user_func_array(array(preg_replace('/\s+/', '', $name_matches[1]), preg_replace('/\s+/', '', strtolower($prep_matches[1]))), array($new_values_array));
                            }
                        }
                    }
                }
            }
            // eof automatic prep processing functions by modules
            
            parse_str($new_values_array, $new_values);
        }
        //print_r($new_values);
        
        $strings_replace = array('AMPERSAND', 'PLUSSIGN', 'SPACESIGN', '&scaron;', '&Scaron;');
        $signs_replace = array('&amp;', '+', '&nbsp;', 'š', 'Š');
        
        if (isset($values_array['static_fields']))
        {
            foreach ($values_array['static_fields'] as $static_field => $properties)
            {
                $this->add_field .= ', ' . $static_field . ' ' . $properties['field_type'];
                $this->insert_fields[$static_field] = ':crud_' . $static_field;
                
                if (isset($new_values[$static_field]))
                {
                    $this->named_placeholders[':crud_' . $static_field] = str_replace($strings_replace, $signs_replace, htmlentities(urldecode($new_values[$static_field]), ENT_QUOTES, "UTF-8"));
                    $this->update_query_clauses[] = $static_field . ' = :crud_' . $static_field;
                    
                    $this->updating_fields[$static_field] = str_replace($strings_replace, $signs_replace, urldecode($new_values[$static_field]));
                }
                //elseif (isset($properties['value']) && $properties['value'] != '')
                elseif (isset($properties['value']) && $func_type == 'none')
                {
                    $this->named_placeholders[':crud_' . $static_field] = $properties['value'];
                    $this->update_query_clauses[] = $static_field . ' = :crud_' . $static_field;
                }
                
                $this->alter_table[$static_field] = $properties['field_type'];
            }
        }
        
        if (isset($values_array['dynamic_fields']))
        {
            if ($project_id)
            {
                $languages = Languages::get_all_www_languages($project_id);
            }
            else
            {
                $languages = Languages::get_all_www_languages_list();
            }
            
            unset($languages['debug']);
            foreach ($languages as $language)
            {
                foreach ($values_array['dynamic_fields'] as $dynamic_field => $properties)
                {
                    if ($dynamic_field == 'slug')
                    {
                        $new_values[$dynamic_field . '_' . $language['code']] = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $new_values['title_' . $language['code']])), '-'));
                    }
                    
                    $this->add_field .= ', ' . $dynamic_field . '_' . $language['code'] . ' ' . $properties['field_type'];
                    
                    if (!isset($properties['value_' . $language['code']]))
                    {
                        $properties['value_' . $language['code']] = null;
                    }
                    
                    if (isset($new_values[$dynamic_field . '_' . $language['code']]))
                    {
                        $this->named_placeholders[':crud_' . $dynamic_field . '_' . $language['code']] = str_replace($strings_replace, $signs_replace, htmlentities(urldecode($new_values[$dynamic_field . '_' . $language['code']]), ENT_QUOTES, "UTF-8"));
                        $this->update_query_clauses[] = $dynamic_field . '_' . $language['code'] . ' = :crud_' . $dynamic_field . '_' . $language['code'];
                        
                        $this->updating_fields[$dynamic_field . '_' . $language['code']] = str_replace($strings_replace, $signs_replace, urldecode($new_values[$dynamic_field . '_' . $language['code']]));
                    }
                    //elseif (isset($properties['value_' . $language['code']]) && $properties['value_' . $language['code']] != '')
                    elseif (isset($properties['value_' . $language['code']]) && $func_type == 'none')
                    {
                        $this->named_placeholders[':crud_' . $dynamic_field . '_' . $language['code']] = $properties['value_' . $language['code']];
                        $this->update_query_clauses[] = $dynamic_field . '_' . $language['code'] . ' = :crud_' . $dynamic_field . '_' . $language['code'];
                        
                        $this->updating_fields[$dynamic_field . '_' . $language['code']] = $properties['value_' . $language['code']];
                    }
                    else
                    {
                        if ($func_type == 'none')
                        {
                            if (!isset($properties['value'])) $properties['value'] = null;
                            $this->named_placeholders[':crud_' . $dynamic_field . '_' . $language['code']] = $properties['value'];
                            
                            $this->updating_fields[$dynamic_field . '_' . $language['code']] = $properties['value'];
                        }
                    }
                    
                    
                    $this->insert_fields[$dynamic_field . '_' . $language['code']] = ':crud_' . $dynamic_field . '_' . $language['code'];
                    
                    $this->alter_table[$dynamic_field . '_' . $language['code']] = $properties['field_type'];
                }
            }
        }
        
        return $this;
    }
    
    public function simple_update_fields($values_array = array())
    {
        $this->db_connect();
        
        foreach ($values_array as $value_key => $value)
        {
            $this->named_placeholders[':crud_' . $value_key] = $value;
            $this->update_query_clauses[] = $value_key . ' = :crud_' . $value_key;
            
            $this->updating_fields[$value_key] = $value;
        }
        
        $this->update_query .= implode(', ', $this->update_query_clauses);
        
        return $this;
    }
    
    public function update_fields($project_id, $values_array = array(), $new_values_array = array(), $func_type = 'none')
    {
        $this->db_connect();
        
        //$this->arrange_fields($project_id, $values_array, $new_values_array, 'update');
        $this->arrange_fields($project_id, $values_array, $new_values_array, $func_type);
        
        $this->update_query .= implode(', ', $this->update_query_clauses);
        
        try
        {
            $table_exists = $this->db->query('SELECT 1 FROM ' . $this->update_table);
            
            foreach ($this->alter_table as $alter_field => $alter_field_properties)
            {
                if (!count($this->db->query('SHOW COLUMNS FROM ' . $this->update_table . ' WHERE Field = \'' . $alter_field . '\'')->fetchAll()))
                {
                    $this->db->query('ALTER TABLE ' . $this->update_table . ' ADD ' . $alter_field . ' ' . $alter_field_properties);
                }
            }
        }
        catch(PDOException $ex) 
        {
            $this->db->query('CREATE TABLE IF NOT EXISTS ' . $this->update_table . ' (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY' . $this->add_field . ')');
        }
        
        return $this;
        
    }
    
    public function having($having_clause)
    {
        $this->select_query .= ' HAVING ' . $having_clause;
        
        return $this;
    }
    
    public function where($where_clauses = array())
    {
        $clauses_number = count($where_clauses);
        $clauses_counter = 0;
        
        if (!empty($where_clauses))
        {
            $this->where_clauses_combined['where'] = $where_clauses;
            
            $this->select_query .=  ' WHERE';
            $this->update_query .=  ' WHERE';
            $this->delete_query .=  ' WHERE';
            foreach ($where_clauses as $field => $value)
            {
                //if (!preg_match('/(?P<name>\w+)\::(?P<operator>=|LIKE)/', $field, $field_funcs)) preg_match('/(?P<prefix>AND|OR)\::(?P<name>\w+)\::(?P<operator>=|LIKE)/', $field, $field_funcs);
                //if (!preg_match('/(?P<prefix>AND|OR)\::(?P<name>\w+)\::(?P<operator>=|<|>|<=|>=|!=|<>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs)) preg_match('/(?P<name>\w+)\::(?P<operator>=|<|>|<=|>=|!=|<>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs);
                /*
                if (!preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs))
                { 
                    preg_match('/(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs);
                }
                else
                {
                    preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs);
                }
                */
                /*
                $preg_match_1 = preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)/', $field);
                $preg_match_2 = preg_match('/(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator><=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field);
                $preg_match_3 = preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator><=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field);
                */
                $preg_match_1 = preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field);
                $preg_match_2 = preg_match('/(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field);
                $preg_match_3 = preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)/', $field);
                
                //echo 'pm1_' . $preg_match_1 . '<br>';
                //echo 'pm2_' . $preg_match_2 . '<br>';
                //echo 'pm3_' . $preg_match_3 . '<br>';
                if ($preg_match_1 == 1)
                {
                    //preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)/', $field, $field_funcs);
                    preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs);
                }
                elseif ($preg_match_2 == 1)
                {
                    //preg_match('/(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator><=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs);
                    preg_match('/(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs);
                }
                elseif ($preg_match_3 == 1)
                {
                    //preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator><=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs);
                    preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)/', $field, $field_funcs);
                }
                else
                {
                    $field_funcs['prefix'] = 'AND';
                    $field_funcs['name'] = $field;
                    $field_funcs['operator'] = '=';
                }
                
                
                
                
                
                if (!isset($field_funcs['prefix'])) $field_funcs['prefix'] = 'AND';
                if (!isset($field_funcs['name'])) $field_funcs['name'] = $field;
                if (!isset($field_funcs['operator'])) $field_funcs['operator'] = '=';
                
                $clauses_counter++;
                
                if ($clauses_number >= $clauses_counter && $clauses_counter > 1)
                {
                    $this->select_query .= ' ' . $field_funcs['prefix'];
                    $this->update_query .= ' ' . $field_funcs['prefix'];
                    $this->delete_query .= ' ' . $field_funcs['prefix'];
                }
                
                $named_placeholder_number[$field_funcs['name']] = 0;
                
                if (isset($this->named_placeholders[':' . $field_funcs['name'] . $named_placeholder_number[$field_funcs['name']]]))
                {
                    $named_placeholder_number[$field_funcs['name']]++;
                }
                
                if (is_array($value))
                {
                    $in_counter = 0;
                    $in_statement = array();
                    foreach ($value as $in_value)
                    {
                        $this->named_placeholders[':' . str_replace('.', '_', $field_funcs['name']) . $named_placeholder_number[$field_funcs['name']] . $in_counter] = $in_value;
                        $in_statement[$in_counter] = ':' . str_replace('.', '_', $field_funcs['name']) . $named_placeholder_number[$field_funcs['name']] . $in_counter;
                        $in_counter++;
                    }
                    $in_statement_string = '(' . implode(', ', $in_statement) . ')';
                    $this->select_query .= ' ' . $field_funcs['name'] . ' ' . $field_funcs['operator'] . ' ' . $in_statement_string;
                    $this->update_query .= ' ' . $field_funcs['name'] . ' ' . $field_funcs['operator'] . ' ' . $in_statement_string;
                    $this->delete_query .= ' ' . $field_funcs['name'] . ' ' . $field_funcs['operator'] . ' ' . $in_statement_string;
                }
                else
                {
                    $this->named_placeholders[':' . str_replace('.', '_', $field_funcs['name']) . $named_placeholder_number[$field_funcs['name']]] = $value;
                    $this->select_query .= ' ' . $field_funcs['name'] . ' ' . $field_funcs['operator'] . ' ' . ':' . str_replace('.', '_', $field_funcs['name']) . $named_placeholder_number[$field_funcs['name']];
                    $this->update_query .= ' ' . $field_funcs['name'] . ' ' . $field_funcs['operator'] . ' ' . ':' . str_replace('.', '_', $field_funcs['name']) . $named_placeholder_number[$field_funcs['name']];
                    $this->delete_query .= ' ' . $field_funcs['name'] . ' ' . $field_funcs['operator'] . ' ' . ':' . str_replace('.', '_', $field_funcs['name']) . $named_placeholder_number[$field_funcs['name']];
                }
                
            }
        }
        $this->select_query;
        return $this;
    }
    
    public function and_where_group($where_clauses = array())
    {
        $clauses_number = count($where_clauses);
        $clauses_counter = 0;
        
        if (!empty($where_clauses))
        {
            $this->where_clauses_combined['and_where'] = $where_clauses;
            
            $this->select_query .=  ' AND (';
            $this->update_query .=  ' AND (';
            $this->delete_query .=  ' AND (';
            foreach ($where_clauses as $field => $value)
            {
                //if (!preg_match('/(?P<name>\w+)\::(?P<operator>=|LIKE)/', $field, $field_funcs)) preg_match('/(?P<prefix>AND|OR)\::(?P<name>\w+)\::(?P<operator>=|LIKE)/', $field, $field_funcs);
                //if (!preg_match('/(?P<prefix>AND|OR)\::(?P<name>\w+)\::(?P<operator>=|<|>|<=|>=|!=|<>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs)) preg_match('/(?P<name>\w+)\::(?P<operator>=|<|>|<=|>=|!=|<>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs);
                //if (!preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs)) preg_match('/(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs);
                
                $preg_match_1 = preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field);
                //$preg_match_1 = preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)/', $field);
                $preg_match_2 = preg_match('/(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field);
                //$preg_match_3 = preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field);
                $preg_match_3 = preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)/', $field);
                
                //echo 'pm1_' . $preg_match_1 . '<br>';
                //echo 'pm2_' . $preg_match_2 . '<br>';
                //echo 'pm3_' . $preg_match_3 . '<br>';
                if ($preg_match_1 == 1)
                {
                    preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs);
                    //preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)/', $field, $field_funcs);
                }
                elseif ($preg_match_2 == 1)
                {
                    preg_match('/(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs);
                }
                elseif ($preg_match_3 == 1)
                {
                    //preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)\::(?P<operator>=|<=|>=|!=|<>|<|>|LIKE|NOT LIKE|IN|NOT IN|BETWEEN|NOT BETWEEN|REGEXP|NOT REGEXP)/', $field, $field_funcs);
                    preg_match('/(?P<prefix>AND|OR)\::(?P<name>[a-zA-Z0-9-_.]+)/', $field, $field_funcs);
                }
                else
                {
                    $field_funcs['prefix'] = 'AND';
                    $field_funcs['name'] = $field;
                    $field_funcs['operator'] = '=';
                }
                
                if (!isset($field_funcs['prefix'])) $field_funcs['prefix'] = 'AND';
                if (!isset($field_funcs['name'])) $field_funcs['name'] = $field;
                if (!isset($field_funcs['operator'])) $field_funcs['operator'] = '=';
                //print_r($field_funcs);
                $clauses_counter++;
                
                if ($clauses_number >= $clauses_counter && $clauses_counter > 1)
                {
                    $this->select_query .= ' ' . $field_funcs['prefix'];
                    $this->update_query .= ' ' . $field_funcs['prefix'];
                    $this->delete_query .= ' ' . $field_funcs['prefix'];
                }
                
                $named_placeholder_number[$field_funcs['name']] = 0;
                
                if (isset($this->named_placeholders[':and_where_' . $field_funcs['name'] . $named_placeholder_number[$field_funcs['name']]]))
                {
                    $named_placeholder_number[$field_funcs['name']]++;
                }
                
                if (is_array($value))
                {
                    $in_counter = 0;
                    $in_statement = array();
                    foreach ($value as $in_value)
                    {
                        $this->named_placeholders[':and_where_' . str_replace('.', '_', $field_funcs['name']) . $named_placeholder_number[$field_funcs['name']] . $in_counter] = $in_value;
                        $in_statement[$in_counter] = ':and_where_' . str_replace('.', '_', $field_funcs['name']) . $named_placeholder_number[$field_funcs['name']] . $in_counter;
                        $in_counter++;
                    }
                    $in_statement_string = '(' . implode(', ', $in_statement) . ')';
                    $this->select_query .= ' ' . $field_funcs['name'] . ' ' . $field_funcs['operator'] . ' ' . $in_statement_string;
                    $this->update_query .= ' ' . $field_funcs['name'] . ' ' . $field_funcs['operator'] . ' ' . $in_statement_string;
                    $this->delete_query .= ' ' . $field_funcs['name'] . ' ' . $field_funcs['operator'] . ' ' . $in_statement_string;
                }
                else
                {
                    $this->named_placeholders[':and_where_' . str_replace('.', '_', $field_funcs['name']) . $named_placeholder_number[$field_funcs['name']]] = $value;
                    $this->select_query .= ' ' . $field_funcs['name'] . ' ' . $field_funcs['operator'] . ' ' . ':and_where_' . str_replace('.', '_', $field_funcs['name']) . $named_placeholder_number[$field_funcs['name']];
                    $this->update_query .= ' ' . $field_funcs['name'] . ' ' . $field_funcs['operator'] . ' ' . ':and_where_' . str_replace('.', '_', $field_funcs['name']) . $named_placeholder_number[$field_funcs['name']];
                    $this->delete_query .= ' ' . $field_funcs['name'] . ' ' . $field_funcs['operator'] . ' ' . ':and_where_' . str_replace('.', '_', $field_funcs['name']) . $named_placeholder_number[$field_funcs['name']];
                }
                
            }
            
            $this->select_query .=  ')';
            $this->update_query .=  ')';
            $this->delete_query .=  ')';
        
        }
        
        return $this;
    }
    
    public function limit($limit_number)
    {
        $this->limit_query = $limit_number;
        $this->select_query .= ' LIMIT ' . $this->limit_query;
        return $this;
    }
    
    public function group_by($group_by_field)
    {
        $this->select_query .= ' GROUP BY ' . $group_by_field;
        return $this;
    }
    
    public function order_by($order_by_field, $order_type = 'ASC')
    {
        $this->select_query .= ' ORDER BY ' . $order_by_field . ' ' . $order_type;
        return $this;
    }
    
    public function update_permanent_cache_groups($update_groups = array())
    {
        global $project_id;
        
        $this->db_connect();
        $reindex = true;
        
        if (!empty($update_groups) && isset($this->permanent_cache[$update_groups]))
        {
            $counter = 0;
            //$clear_smarty_cache = array();
            foreach ($this->permanent_cache[$update_groups] as $tables)
            {
                $get_key = array_keys($tables);
                //$memcache_key = $get_key[0];
                //print_r($tables);
                foreach ($tables as $memcache_id)
                {
                    //$this->db_connect();
                    
                    $memcache_key = $get_key[$counter];
                    $counter++;
                    $memcache_query = $memcache_id['query'];
                    $memcache_placeholders = $memcache_id['placeholders'];
                    
                    try 
                    {
                        //echo $memcache_query . '\n';
                        $memcache_result[$counter] = $this->db->prepare($memcache_query);
                        $memcache_result[$counter]->execute($memcache_placeholders);
                        $memcache_rows[$counter] = $memcache_result[$counter]->fetchAll(PDO::FETCH_ASSOC);
                        
                    } catch(PDOException $ex) {
                        echo "An Error occured!"; //user friendly message
                        //some_logging_function($ex->getMessage());
                        //echo $this->select_query;
                        echo $ex->getMessage();
                    }
                    
                    
                    
                    //$memcache_rows[$counter] = $this->post_process_rows($memcache_rows[$counter], true, $memcache_key);
                    
                    $memcache[$counter] = new xMemcache;
                    if ($memcache[$counter]->get_memcache($memcache_key))
                    {
                        $memcache[$counter]->replace_memcache($memcache_key, $memcache_rows[$counter], MEMCACHE_COMPRESSED, 0);
                    }
                    else
                    {
                        $memcache[$counter]->set_memcache($memcache_key, $memcache_rows[$counter], MEMCACHE_COMPRESSED, 0);
                    }
                    
                    // clear smarty cache
                    //$memcache_key
                    
                    //$clear_smarty_cache = array();
                    //echo $memcache_key . '     ';
                    if (isset($this->smarty_cache[$memcache_key]))
                    {
                        foreach ($this->smarty_cache[$memcache_key] as $smarty_id => $smarty_value)
                        {
                            //array_push($clear_smarty_cache, $this->smarty_cache[$memcache_key]);
                            //array_push($clear_smarty_cache, $smarty_value);
                            $clear_smarty_cache[$smarty_value] = $smarty_value;
                        }
                    }
                    /*
                    if (isset($project_id))
                    {
                        $project_info = Projects::get_project_info_by_id($project_id);
                        $project_slug = $project_info['project_slug'];
                        $project_template = Projects::get_project_template($project_id);
                        //$xSmarty = new xSmarty($project_slug, $project_template);
                        //$xSmarty->smarty_clear($clear_smarty_cache);
                    }
                    */
                    
                }
            }
            //print_r($clear_smarty_cache);
            /*
            $projects = Projects::get_all_projects();
            foreach ($projects as $project)
            {
                if (isset($clear_smarty_cache) && is_array($clear_smarty_cache))
                {
                    $project_info = Projects::get_project_info_by_id($project['id']);
                    $project_slug = $project_info['project_slug'];
                    $project_template = Projects::get_project_template($project['id']);
                    //$xSmarty = new xSmarty($project_slug, $project_template);
                    //$xSmarty->smarty_clear($clear_smarty_cache);
                    
                    foreach ($clear_smarty_cache as $smarty_cache)
                    {
                        //print_r($smarty_cache);
                        $filename = $_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/' . $smarty_cache . '^*.index.tpl.php';
                        foreach (glob($filename) as $filefound)
                        {
                            //echo $filefound;
                            unlink($filefound);
                        }
                    }
                }
            }
            */
            xSmarty::smarty_clear_cache();
            $this->db_disconnect();
        }
    }
    
    public function post_process_parent($table, $id)
    {
        global $project_id;
        
        if (isset($this->function_relations[$table . '_' . $id]))
        {
            $projects = Projects::get_all_projects();
            $relations_array = $this->function_relations[$table . '_' . $id];
            
            foreach ($relations_array as $relation_value)
            {
                foreach ($projects as $project)
                {
                    $project_id = $project['id'];
                    $project_slug = $project['project_slug'];
                    $project_template = Projects::get_project_template($project['id']);
                    
                    $memcache = new xMemcache;
                    $cache_group = $memcache->get_memcache_group($relation_value['parent_group'], 'single');
                    //echo $relation_value['parent_group'];
                    
                    $parent_table = $relation_value['parent_table'];
                    $parent_id = $relation_value['parent_id'];
                    //if (isset($cache_group[$parent_table])) print_r( $cache_group[$parent_table]['id_arrays'][$parent_id] );
                    
                    foreach ($cache_group as $key => $val)
                    {
                        echo $key . ' ';
                    }
                    
                    //echo $parent_table . ' ';
                    
                    /*if (isset($cache_group[$parent_table]))
                    {*/
                        $cache_rows = $cache_group[$parent_table]['id_arrays'][$parent_id];
                    
                    
                    foreach ($cache_rows as $query_hash => $query_values)
                    {
                        $check_memcache = $memcache->get_memcache($query_hash);
                        
                        if ($check_memcache)
                        {
                            $process_rows = $check_memcache;
                        }
                        else
                        {
                            $xdb_parent = new Xdb;
                            $xdb_parent->db_connect();
                            try 
                            {
                                //print_r($xdb_parent);
                                $memcache_result = $xdb_parent->db->prepare($query_values['query']);
                                $memcache_result->execute($query_values['query_placeholders']);
                                $process_rows = $memcache_result->fetchAll(PDO::FETCH_ASSOC);
                                
                            } catch(PDOException $ex) {
                                echo "An Error occured!"; //user friendly message
                                //some_logging_function($ex->getMessage());
                                //echo $this->select_query;
                                echo $ex->getMessage();
                            }
                        }
                        
                        //$processed_rows = $this->post_process_rows($process_rows, true, $query_hash, $relation_value['parent_group'], $parent_table, $parent_id);
                        $processed_rows = $this->post_process_rows($process_rows, true, $query_hash, $relation_value['parent_group'], $parent_table, $parent_id);
                        
                        $check_post_memcache = $memcache->get_memcache('post_' . $query_hash);
                        
                        if ($check_post_memcache)
                        {
                            $memcache->replace_memcache('post_' . $query_hash, $processed_rows);
                        }
                        else
                        {
                            $memcache->set_memcache('post_' . $query_hash, $processed_rows);
                        }
                        
                        // clear smarty cache files
                        if (isset($smarty_cache_info_update[$query_hash]) && is_array($smarty_cache_info_update[$query_hash]))
                        {
                            foreach ($smarty_cache_info_update[$query_hash] as $cached_file)
                            {
                                $files = glob($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/' . $cached_file . '^*.index.tpl.php');
                                
                                foreach ($files as $file)
                                {
                                    unlink($file);
                                }
                            }
                        }
                    }
                    //}
                }
            }
        }
    }
    
    public function update_permanent_cache_single_parent($table, $id)
    {/*
        $reindex = true;
        
        $xdb_parent = new Xdb;
        $xdb_parent->db_connect();
        
        if (isset($xdb_parent->permanent_cache['single'][$table][$id]))
        {
            foreach ($xdb_parent->permanent_cache['single'][$table][$id] as $key => $value)
            {
                //$get_key = array_keys($this->permanent_cache['single'][$table][$id]);
                $get_key = $key;
                //print_r($get_key);
                //$memcache_key = $get_key[0];
                $memcache_key = $get_key;
                //$memcache_query = $this->permanent_cache['single'][$table][$id][$memcache_key]['query'];
                $memcache_query = $value['query'];
                //$memcache_placeholders = $this->permanent_cache['single'][$table][$id][$memcache_key]['placeholders'];
                $memcache_placeholders = $value['placeholders'];
                //echo $memcache_query;
                //print_r($memcache_placeholders);
                try 
                {
                    $memcache_result = $xdb_parent->db->prepare($memcache_query);
                    $memcache_result->execute($memcache_placeholders);
                    $memcache_rows = $memcache_result->fetchAll(PDO::FETCH_ASSOC);
                    
                } catch(PDOException $ex) {
                    echo "An Error occured!"; //user friendly message
                    //some_logging_function($ex->getMessage());
                    //echo $this->select_query;
                    echo $ex->getMessage();
                }
                //print_r($memcache_rows);
                //echo $memcache_key;
                
                $post_memcache_rows = $xdb_parent->post_process_rows($memcache_rows, true, $memcache_key, $table, $memcache_rows[0]['id']);
                
                $memcache = new xMemcache;
                //$memcache->replace_memcache($memcache_key, $memcache_rows, MEMCACHE_COMPRESSED, 0);
                if ($memcache->get_memcache($memcache_key))
                {
                    $memcache->replace_memcache($memcache_key, $memcache_rows, MEMCACHE_COMPRESSED, 0);
                }
                else
                {
                    $memcache->set_memcache($memcache_key, $memcache_rows, MEMCACHE_COMPRESSED, 0);
                }
                
                if ($memcache_rows != $post_memcache_rows)
                {
                    if ($memcache->get_memcache('post_' . $memcache_key))
                    {
                        $memcache->replace_memcache('post_' . $memcache_key, $post_memcache_rows, MEMCACHE_COMPRESSED, 0);
                    }
                    else
                    {
                        $memcache->set_memcache('post_' . $memcache_key, $post_memcache_rows, MEMCACHE_COMPRESSED, 0);
                    }
                }
                
                if (isset($this->smarty_cache[$memcache_key]))
                {
                    foreach ($this->smarty_cache[$memcache_key] as $smarty_id => $smarty_value)
                    {
                        //array_push($clear_smarty_cache, $this->smarty_cache[$memcache_key]);
                        //array_push($clear_smarty_cache, $smarty_value);
                        $clear_smarty_cache[$smarty_value] = $smarty_value;
                    }
                }
            }
            
            xSmarty::smarty_clear_cache();
        }
        $this->db_disconnect();*/
    }
    
    public function update_permanent_cache_single($table, $id)
    {
        //global $reindex;
        /*$reindex = true;
        
        $this->db_connect();
        
        if (isset($this->permanent_cache['single'][$table][$id]))
        //if (isset($this->permanent_cache['single'][$table]))
        {
            foreach ($this->permanent_cache['single'][$table][$id] as $key => $value)
            {
                //$get_key = array_keys($this->permanent_cache['single'][$table][$id]);
                $get_key = $key;
                //print_r($get_key);
                //$memcache_key = $get_key[0];
                $memcache_key = $get_key;
                //$memcache_query = $this->permanent_cache['single'][$table][$id][$memcache_key]['query'];
                $memcache_query = $value['query'];
                //$memcache_placeholders = $this->permanent_cache['single'][$table][$id][$memcache_key]['placeholders'];
                $memcache_placeholders = $value['placeholders'];
                //echo $memcache_query;
                //print_r($memcache_placeholders);
                try 
                {
                    $memcache_result = $this->db->prepare($memcache_query);
                    $memcache_result->execute($memcache_placeholders);
                    $memcache_rows = $memcache_result->fetchAll(PDO::FETCH_ASSOC);
                    
                } catch(PDOException $ex) {
                    echo "An Error occured!"; //user friendly message
                    //some_logging_function($ex->getMessage());
                    //echo $this->select_query;
                    echo $ex->getMessage();
                }
                //print_r($memcache_rows);
                //echo $memcache_key;
                
                if (isset($memcache_rows[0]))
                {
                
                    $post_memcache_rows = $this->post_process_rows($memcache_rows, true, $memcache_key, $table, $memcache_rows[0]['id']);
                    
                    $memcache = new xMemcache;
                    //$memcache->replace_memcache($memcache_key, $memcache_rows, MEMCACHE_COMPRESSED, 0);
                    if ($memcache->get_memcache($memcache_key))
                    {
                        $memcache->replace_memcache($memcache_key, $memcache_rows, MEMCACHE_COMPRESSED, 0);
                    }
                    else
                    {
                        $memcache->set_memcache($memcache_key, $memcache_rows, MEMCACHE_COMPRESSED, 0);
                    }
                    
                    if ($memcache_rows != $post_memcache_rows)
                    {
                        if ($memcache->get_memcache('post_' . $memcache_key))
                        {
                            $memcache->replace_memcache('post_' . $memcache_key, $post_memcache_rows, MEMCACHE_COMPRESSED, 0);
                        }
                        else
                        {
                            $memcache->set_memcache('post_' . $memcache_key, $post_memcache_rows, MEMCACHE_COMPRESSED, 0);
                        }
                    }
                    
                    if (isset($this->smarty_cache[$memcache_key]))
                    {
                        foreach ($this->smarty_cache[$memcache_key] as $smarty_id => $smarty_value)
                        {
                            //array_push($clear_smarty_cache, $this->smarty_cache[$memcache_key]);
                            //array_push($clear_smarty_cache, $smarty_value);
                            $clear_smarty_cache[$smarty_value] = $smarty_value;
                        }
                    }
                
                }
            }
            
            
            xSmarty::smarty_clear_cache();
        }
        
        $this->db_disconnect();
        
        $parent_xdb = new Xdb;
        
        //print_r($this->function_relations[$table . '_' . $id]);
        //print_r($this->update_function_parent_queryes); echo 'neki';
        if (isset($this->function_relations[$table . '_' . $id]))
        {
            foreach ($this->function_relations[$table . '_' . $id] as $update_parent)
            {
                //$this->update_permanent_cache_single_parent($update_parent['parent_table'], $update_parent['parent_id']);
                $parent_xdb->update_permanent_cache_single_parent($update_parent['parent_table'], $update_parent['parent_id']);
            }
        }*/
    }
    
    public function db_select_get_memcache_key()
    {
        return sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')');
    }
    
    public function update_function_relations()
    {
        $print_functions = var_export($this->function_relations, true);
        
        $write_function_relations = new xMemcache;
        $current_cache = $write_function_relations->get_memcache('function_relations');
        if ($current_cache)
        {
            $write_function_relations->replace_memcache('function_relations', $this->function_relations);
        }
        else
        {
            $write_function_relations->set_memcache('function_relations', $this->function_relations);
        }
        
        $stringData = '<?php $functions = ' . $print_functions . '; ?>';
        $functions_file = $_SERVER['DOCUMENT_ROOT'] . '/admin/system/relations/function_relations.php';
                    
        $write_functions = new File($functions_file);
        $write_functions->set_writable()->write_to_file($stringData)->set_unwritable();
    }
    
    public function post_process_rows($rows, $caching, $memcache_key, $group_name, $parent_table = null, $parent_id = null)
    {
        //global $reindex, $exclusion_list, $group_name;
        global $reindex, $exclusion_list;
        //echo 'reindex ' . $memcache_key . ' ';
        // bof post process rows
        $post_memcache_key = 'post_' . $memcache_key;
        $non_processed_rows = $rows;
        $post_memcache = new xMemcache;
        
        $admin_request = strpos($_SERVER['REQUEST_URI'], '/admin/');
        if ($admin_request === false)
        {
            
            //print_r($post_memcache->get_memcache($post_memcache_key));
            if ($caching && !$reindex && $post_memcache->get_memcache($post_memcache_key))
            //if ($caching && $post_memcache->get_memcache($post_memcache_key))
            {
                $rows = $post_memcache->get_memcache($post_memcache_key);
            }
            else
            {
                global $selected_language, $current_country, $currency;
                
                $row_counter = 0;
                foreach ($rows as $row)
                {
                    foreach ($row as $row_key => $row_value)
                    {
                        $description_pos = strpos($row_key, 'description');
                        $seo_description_pos = strpos($row_key, 'seo_description');
                        //echo $row_key . $description_pos . $seo_description_pos . '<br>';
                        if ($description_pos !== false && $seo_description_pos === false) // limit functions only to dynamic description fields
                        {
                            /*$get_lang_from_key = explode('_', $row_key);
                            $selected_language = $get_lang_from_key[1];
                            $language_id = Languages::get_language_id($selected_language);
                            //echo $selected_language . '_' . $language_id;
                            $current_country = Countries::get_country_by_language($language_id);
                            $currency = Countries::get_country_currency();*/
                            
                            $filename = $_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.modules/class.*.php';
                            foreach (glob($filename) as $filefound)
                            {
                                //echo "$filefound size " . filesize($filefound) . "\n";
                                $tokens = token_get_all(file_get_contents($filefound));
                                $comments = array();
                                foreach($tokens as $token) 
                                {
                                    if($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT) {
                                        $comments[] = $token[1];
                                        $module_name_pattern = "/@moduleName (.*?)\n/";
                                        $module_post_function = "/@postProcessContentViewFunction (.*?)\n/";
                                        //preg_match($module_category_pattern, $token[1], $category_matches);
                                        preg_match($module_name_pattern, $token[1], $name_matches);
                                        preg_match($module_post_function, $token[1], $post_matches);
                                        //print_r($name_matches);
                                        //print_r($text_editor_matches);
                                        
                                        if (isset($post_matches[1]))
                                        {
                                            $row_function_id = preg_replace('/\s+/', '', $name_matches[1]) .'::' . preg_replace('/\s+/', '', strtolower($post_matches[1])) . '_' . sha1($row_value);
                                            
                                            //$this->function_relations[$memcache_key][$parent_table . '_' . $parent_id] = array('parent_table' => $parent_table, 'parent_id' => $parent_id);
                                            //$this->update_function_relations();
                                            
                                            if (isset($this->function_relations[$memcache_key]))
                                            {
                                                $this->update_function_parent_queryes[$parent_table . '_' . $parent_id] = array('parent_class' => preg_replace('/\s+/', '', $name_matches[1]), 'parent_table' => $parent_table, 'parent_id' => $parent_id);
                                            }
                                            
                                            if ((double)phpversion() >= 5.3)
                                            {
                                                //$rows[$row_counter][$row_key] = call_user_func_array(preg_replace('/\s+/', '', $name_matches[1]) .'::' . preg_replace('/\s+/', '', strtolower($post_matches[1])), array($row_value, $group_name, $parent_table, $parent_id));
                                                $rows[$row_counter][$row_key] = call_user_func_array(preg_replace('/\s+/', '', $name_matches[1]) .'::' . preg_replace('/\s+/', '', strtolower($post_matches[1])), array($rows[$row_counter][$row_key], $group_name, $parent_table, $parent_id));
                                                $rows[$row_counter]['exclusion_' . $selected_language] = $exclusion_list;
                                            }
                                            else
                                            {
                                                $rows[$row_counter][$row_key] = call_user_func_array(array(preg_replace('/\s+/', '', $name_matches[1]), preg_replace('/\s+/', '', strtolower($post_matches[1]))), array($row_value, $group_name, $parent_table, $parent_id));
                                                $rows[$row_counter]['exclusion_' . $selected_language] = $exclusion_list;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    
                    }
                    
                    $row_counter++;
                }
            }
        // eof post process rows
        }
        else
        {
            global $selected_language, $current_country, $currency;
                $row_counter = 0;
                foreach ($rows as $row)
                {
                    foreach ($row as $row_key => $row_value)
                    {
                        $description_pos = strpos($row_key, 'description');
                        $seo_description_pos = strpos($row_key, 'seo_description');
                        //echo $row_key . $description_pos . $seo_description_pos . '<br>';
                        if ($description_pos !== false && $seo_description_pos === false) // limit functions only to dynamic description fields
                        {
                            $get_lang_from_key = explode('_', $row_key);
                            $selected_language = $get_lang_from_key[1];
                            $language_id = Languages::get_language_id($selected_language);
                            //echo $selected_language . '_' . $language_id;
                            $current_country = Countries::get_country_by_language($language_id);
                            $currency = Countries::get_country_currency();
                            
                            //echo $row_key . ' here';
                            $filename = $_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.modules/class.*.php';
                            foreach (glob($filename) as $filefound)
                            {
                                //echo "$filefound size " . filesize($filefound) . "\n";
                                $tokens = token_get_all(file_get_contents($filefound));
                                $comments = array();
                                foreach($tokens as $token) 
                                {
                                    if($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT) {
                                        $comments[] = $token[1];
                                        $module_name_pattern = "/@moduleName (.*?)\n/";
                                        $module_post_function = "/@postProcessContentViewFunction (.*?)\n/";
                                        //preg_match($module_category_pattern, $token[1], $category_matches);
                                        preg_match($module_name_pattern, $token[1], $name_matches);
                                        preg_match($module_post_function, $token[1], $post_matches);
                                        //print_r($name_matches);
                                        //print_r($text_editor_matches);
                                        
                                        if (isset($post_matches[1]))
                                        {
                                            $row_function_id = preg_replace('/\s+/', '', $name_matches[1]) .'::' . preg_replace('/\s+/', '', strtolower($post_matches[1])) . '_' . sha1($row_value);
                                            
                                            //$this->function_relations[$memcache_key][$parent_table . '_' . $parent_id] = array('parent_table' => $parent_table, 'parent_id' => $parent_id);
                                            //$this->update_function_relations();
                                            
                                            //if (isset($this->function_relations[$memcache_key]))
                                            //{
                                                //$this->update_function_parent_queryes[$parent_table . '_' . $parent_id] = array('parent_table' => $parent_table, 'parent_id' => $parent_id);
                                            //}
                                            
                                            if ($parent_table && $parent_id)
                                            {
                                                if ((double)phpversion() >= 5.3)
                                                {
                                                    //echo preg_replace('/\s+/', '', $name_matches[1]) .'::' . preg_replace('/\s+/', '', strtolower($post_matches[1]));
                                                    //$rows[$row_counter][$row_key] = call_user_func_array(preg_replace('/\s+/', '', $name_matches[1]) .'::' . preg_replace('/\s+/', '', strtolower($post_matches[1])), array($row_value, $group_name, $parent_table, $parent_id));
                                                    $rows[$row_counter][$row_key] = call_user_func_array(preg_replace('/\s+/', '', $name_matches[1]) .'::' . preg_replace('/\s+/', '', strtolower($post_matches[1])), array($rows[$row_counter][$row_key], $group_name, $parent_table, $parent_id));
                                                    $rows[$row_counter]['exclusion_' . $selected_language] = $exclusion_list;
                                                    
                                                    if ($selected_language == 'en')
                                                    {
                                                        //echo $row_counter . '...' .  $rows[$row_counter][$row_key];
                                                    }
                                                }
                                                else
                                                {
                                                    $rows[$row_counter][$row_key] = call_user_func_array(array(preg_replace('/\s+/', '', $name_matches[1]), preg_replace('/\s+/', '', strtolower($post_matches[1]))), array($row_value, $group_name, $parent_table, $parent_id));
                                                    $rows[$row_counter]['exclusion_' . $selected_language] = $exclusion_list;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            
                            
                            // BOF input rows (surround blocks with rows)
                            /*$rowing_text = $rows[$row_counter][$row_key];
                            
                            $dom = new DOMDocument('1.0', 'UTF-8');
                            @$dom->loadHTML('<?xml encoding="UTF-8">' . $rowing_text); 
                            $dom->encoding = 'utf-8';
                            $dom->preserveWhiteSpace = false;
                            $dom->formatOutput = false;
                            $divs = $dom->getElementsByTagName('div');
                            
                            //$rowing_text = $dom->saveXML();
                            
                            $column_counter = 0;
                            $output_text = '';
                            
                            foreach($divs as $div)
                            {
                                $class = $div->getAttribute('class');
                                
                                //echo $class . ' :: ';
                                
                                if (strpos($class,'three') !== false)
                                {
                                    $add_counter = 3;
                                }
                                
                                if (strpos($class,'three') !== false)
                                {
                                    $add_counter = 6;
                                }
                                
                                if (strpos($class,'nine') !== false)
                                {
                                    $add_counter = 9;
                                }
                                
                                if (strpos($class,'twelve') !== false)
                                {
                                    $add_counter = 12;
                                }
                                
                                if (strpos($class,'four') !== false)
                                {
                                    $add_counter = 4;
                                }
                                
                                if (strpos($class,'eight') !== false)
                                {
                                    $add_counter = 8;
                                }
                                
                                $unfinished = 0;
                                
                                if (isset($add_counter) && $add_counter)
                                {
                                    if ($column_counter == 0)
                                    {
                                        $output_text .= '<div class="row">' . $dom->saveXML($div);
                                        $column_counter = $column_counter + $add_counter;
                                        $unfinished = 1;
                                    }
                                    else
                                    {
                                        $column_counter = $column_counter + $add_counter;
                                        
                                        if ($column_counter > 12)
                                        {
                                            $output_text .= $dom->saveXML($div) . '</div>';
                                            $column_counter = 0;
                                            $unfinished = 0;
                                        }
                                        else
                                        {
                                            $output_text .= $dom->saveXML($div);
                                        }
                                        
                                    }
                                }
                                
                                
                                
                            }
                            
                            if (isset($unfinished) && $unfinished)
                            {
                                $rows[$row_counter][$row_key] = $output_text . '</div>';
                            }
                            else
                            {
                                $rows[$row_counter][$row_key] = $output_text;
                            }
                            
                            
                            // EOF input rows (surround blocks with rows)*/
                            
                        }
                    
                    }
                    
                    $row_counter++;
                }
            
        }
        //print_r($rows);
        //echo $rows[0]['description_en'];
        return $rows;
        //return $this;
    }
    
    public function db_select($caching = true, $cache_time = 0, $cache_group = 'group', $post_process = true, $debug = false)
    {
        global $reindex, $project_id, $smarty_cache_id, $smarty_cache_queryes, $group_name, $smarty_cache_info_loaded;
        
        $group_name = $cache_group;
        $reindex = false;
        //print_r($this->named_placeholders);
        //echo $this->select_query;
        
        if (!$smarty_cache_info_loaded && $caching)
        {
            $smarty_loaded_cache = xSmarty::get_cache_info();
        }
        
        $permanent_cache_changes_made = false;
        
        $memcache = new xMemcache;
        $memcache_key = sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')');
        
        /*
        if ($this->limit_query == 1)
        {
            xMemcache::set_query_settings($cache_group, 'single', $project_id, $this->cache_tables, $memcache_key, $this->select_query, $this->named_placeholders);
        }
        else
        {
            xMemcache::set_query_settings($cache_group, 'group', $project_id, $this->cache_tables, $memcache_key, $this->select_query, $this->named_placeholders);
        }
        */
        
        if (isset($project_id) && isset($smarty_cache_id) && is_array($smarty_cache_id) && $caching)
        {
            //$smarty_cache_queryes = array();
            //echo $this->select_query . '(' . implode(',', $this->named_placeholders) . ')<br>';
            //$smarty_cache_queryes[] = sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')');
            $smarty_cache_queryes[] = $memcache_key;
            
        }
        
        if (!$caching && $memcache->get_memcache($memcache_key))
        {
            $memcache->delete_memcache($memcache_key);
        }
        
        $admin_request = strpos($_SERVER['REQUEST_URI'], '/admin/');
        
        if ($admin_request === false && ($memcache->get_memcache('post_' . $memcache_key) || $memcache->get_memcache($memcache_key)))
        {
            if ($memcache->get_memcache('post_' . $memcache_key))
            {
                $rows = $memcache->get_memcache('post_' . $memcache_key);
            }
            else
            {
                $rows = $memcache->get_memcache($memcache_key);
            }
            //echo 'loaded postmemcache<br>';
        }
        elseif ($admin_request !== false && $memcache->get_memcache($memcache_key))
        {
            $rows = $memcache->get_memcache($memcache_key);
            //echo 'loaded memcache<br>';
        }
        else
        {
            //echo 'loaded database ('.$this->select_query.' - '.implode('|',$this->named_placeholders).')<br>';
            $this->db_connect();
            
            try {
                $db_result = $this->db->prepare($this->select_query);
                $db_result->execute($this->named_placeholders);
                //$rows = $db_result->fetchAll(PDO::FETCH_ASSOC);
                $fetched_rows = $db_result->fetchAll(PDO::FETCH_ASSOC);
                if ($debug)
                {
                    $rows['debug']['loaded'] = 'from database';
                    $rows['debug']['query'] = $this->select_query;
                    $rows['debug']['named_placeholders'] = $this->named_placeholders;
                    $rows['debug']['cache_id'] = $memcache_key;
                };
                
                // get current cache arrays
                $update_cache = $this->permanent_cache;
                
                $counted_fetched_rows = count($fetched_rows);
                
                // reorganize array
                $rows = array();
                if (isset($fetched_rows[0]['id']) && $counted_fetched_rows > 1 && $this->limit_query != 1)
                {
                    if (!empty($fetched_rows) && is_array($fetched_rows))
                    {
                        foreach ($fetched_rows as $row_value)
                        {
                            $rows[$row_value['id']] = $row_value;
                        }
                    }
                }
                elseif ((isset($fetched_rows[0]['id']) && $counted_fetched_rows == 1 && $this->limit_query == 1) || (!isset($fetched_rows[0]['id']) && $counted_fetched_rows == 1))
                {
                    $rows[0] = $fetched_rows[0];
                }
                else
                {
                    $rows = $fetched_rows;
                }
                
                if ($caching && $counted_fetched_rows)
                {
                    // set memcache
                    if ($memcache->get_memcache($memcache_key))
                    {
                        $memcache->replace_memcache($memcache_key, $rows, MEMCACHE_COMPRESSED, $cache_time);
                    }
                    else
                    {
                        $memcache->set_memcache($memcache_key, $rows, MEMCACHE_COMPRESSED, $cache_time);
                    }
                    
                    if ($cache_time == 0)
                    {
                        //if ($this->limit_query == 1 || $cache_group == 'single')
                        //if ($this->limit_query == 1 || $counted_fetched_rows == 1)
                        // BEFORE
                        //if ($counted_fetched_rows == 1 && $this->limit_query == 1)
                        if ((isset($fetched_rows[0]['id']) && $counted_fetched_rows == 1 && $this->limit_query == 1) || (!isset($fetched_rows[0]['id']) && $counted_fetched_rows == 1))
                        {
                            if (isset($fetched_rows[0]['id'])) $single_id = $fetched_rows[0]['id']; else $single_id = 'no-id';
                            //echo $cache_group;
                            xMemcache::set_query_settings($cache_group, 'single', $project_id, $this->cache_tables, $memcache_key, $this->select_query, $this->named_placeholders, $single_id);
                            /*
                            $update_cache['single'][$this->cache_tables][$rows[0]['id']][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]['query'] = $this->select_query;
                            //$update_cache['single'][$this->cache_tables][$rows[0]['id']][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]['placeholders'] = implode(',', $this->named_placeholders);
                            $update_cache['single'][$this->cache_tables][$rows[0]['id']][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]['placeholders'] = $this->named_placeholders;
                            */
                            //$update_cache['single'][$this->cache_tables][$rows[0]['id']][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]['query'] = $this->select_query;
                            //$update_cache['single'][$this->cache_tables][$rows[0]['id']][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]['placeholders'] = implode(',', $this->named_placeholders);
                            //$update_cache['single'][$this->cache_tables][$rows[0]['id']][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]['placeholders'] = $this->named_placeholders;
                        }
                        else
                        {
                            //if ($cache_group == 'staticcontent') echo $memcache_key;
                            xMemcache::set_query_settings($cache_group, 'group', $project_id, $this->cache_tables, $memcache_key, $this->select_query, $this->named_placeholders);
                            /*$update_cache[$cache_group][$this->cache_tables][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]['query'] = $this->select_query;
                            //$update_cache[$cache_group][$this->cache_tables][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]['placeholders'] = implode(',', $this->named_placeholders);
                            $update_cache[$cache_group][$this->cache_tables][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]['placeholders'] = $this->named_placeholders;*/
                        }
                        
                        $permanent_cache_changes_made = true;
                        
                    }/*
                    else
                    {
                        if ($this->limit_query == 1 && isset($update_cache['single'][$this->cache_tables][$rows[0]['id']][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]))
                        {
                            unset($update_cache['single'][$this->cache_tables][$rows[0]['id']][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]);
                            $permanent_cache_changes_made = true;
                        }
                        elseif (isset($update_cache[$cache_group][$this->cache_tables][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]))
                        {
                            unset($update_cache[$cache_group][$this->cache_tables][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]);
                            $permanent_cache_changes_made = true;
                        }
                    }*/
                }
                
                /*
                if (isset($rows[0]['id']))
                {
                    if (!$caching && (isset($update_cache['single'][$this->cache_tables][$rows[0]['id']][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]) || isset($update_cache[$cache_group][$this->cache_tables][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')])))
                    {
                        unset($update_cache['single'][$this->cache_tables][$rows[0]['id']][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]);
                        unset($update_cache[$cache_group][$this->cache_tables][sha1($this->select_query . '(' . implode(',', $this->named_placeholders) . ')')]);
                        $permanent_cache_changes_made = true;
                    }
                }
                */
                
                // if permanent cache changes were made, write the to the permanent cache file      
                /*
                if ($permanent_cache_changes_made)
                {
                    $print_update_cache = var_export($update_cache, true);
                    
                    $write_permanent_cache = new xMemcache;
                    $current_cache = $write_permanent_cache->get_memcache('permanent_cache');
                    if ($current_cache)
                    {
                        $write_permanent_cache->replace_memcache('permanent_cache', $update_cache);
                    }
                    else
                    {
                        $write_permanent_cache->set_memcache('permanent_cache', $update_cache);
                    }
                    
                    $stringData = '<?php $cached_queries = ' . $print_update_cache . '; ?>';
                    $cache_file = $_SERVER['DOCUMENT_ROOT'] . '/admin/system/relations/permanent_cache.php';
                    
                    $write_cache = new File($cache_file);
                    $write_cache->set_writable()->write_to_file($stringData)->set_unwritable();
                }     
                */  
                
            } catch(PDOException $ex) {
                return false;
                echo "An Error occured!"; //user friendly message
                //some_logging_function($ex->getMessage());
                //echo $this->select_query;
                echo $ex->getMessage();
            }
            
            //$this->db_disconnect();
            
            if (isset($rows[0]) && count($rows) == 1 && $this->limit_query == 1)
            {
                $non_processed_rows = $rows;
                
                if (isset($rows[0]['id']))
                {
                    $processed_rows = $this->post_process_rows($rows, $caching, $memcache_key, $cache_group, $this->cache_tables, $rows[0]['id']);
                    //echo $this->select_query;
                    //print_r($this->named_placeholders);
                    //print_r($processed_rows);
                    //echo ':::::::::::::::<br>';
                    //if ($non_processed_rows != $processed_rows && $caching)
                    if ($non_processed_rows != $processed_rows)
                    {
                        //echo 'here' . $this->cache_tables . $rows[0]['id'] . $caching;
                        $post_memcache = new xMemcache;
                        $post_memcache_key = 'post_' . $memcache_key;
                        
                        if ($post_memcache->get_memcache($post_memcache_key))
                        {
                            //echo $this->cache_tables . ' ' . $post_memcache_key . ' ' . $rows[0]['id'] . '<br>';
                            $post_memcache->replace_memcache($post_memcache_key, $processed_rows, MEMCACHE_COMPRESSED, $cache_time);
                        }
                        else
                        {
                            //echo 'always processing and saving<br>';
                            $post_memcache->set_memcache($post_memcache_key, $processed_rows, MEMCACHE_COMPRESSED, $cache_time);
                        }
                        
                        $admin_request = strpos($_SERVER['REQUEST_URI'], '/admin/');
                        if ($admin_request === false)
                        {
                            //echo 'admin request';
                            $rows = $processed_rows;
                        }
                    }
                }
            }
            $this->db_disconnect();
        }
        /*if (count($rows) == 1 && $this->update_table == 'www_offers_categories')
        {
        echo '<b>' . $this->select_query . ' ' . $memcache_key . '</b>
        ';
        print_r($this->named_placeholders);
        echo '
        ';
        print_r($rows);
        echo '
        :::::::::::::::::::::::::::::::::::
        ';
        }*/
        return $rows;
    }
    
    public function db_select_by_id($id, $group_name = 'single', $caching = true, $cache_time = 0)
    {
        $this->where(array('id' => $id))->limit(1);
        return $this->db_select($caching, $cache_time, $group_name);
        //return $this->db_select(false);
    }
    
    public function db_select_by_id_not_trashed($id, $group_name = 'single', $caching = true, $cache_time = 0, $post_process = true)
    {
        $this->where(array('id' => $id, 'trashed' => 0))->limit(1);
        return $this->db_select($caching, $cache_time, $group_name, $post_process);
    }
    
    public function db_select_www_by_id($id, $group_name = 'single', $caching = true, $cache_time = 0, $post_process = true)
    {
        $this->where(array('id' => $id))->limit(1);
        return $this->db_select($caching, $cache_time, $group_name, $post_process);
    }
    
    public function update_cache_group($cache_group, $table_name, $where_clauses = array(), $new_data = array(), $matching_fields = array())
    {
        global $project_id;
        
        $projects = Projects::get_all_projects();
        
        foreach ($projects as $project)
        {
            $project_id = $project['id'];
            $project_slug = $project['project_slug'];
            //$project_template = Projects::get_project_template($project['id']);
            $project_template = $project['template_slug'];
            
            $smarty_cache_info_update = xSmarty::get_cache_info();
            
            $memcache = new xMemcache;
            $group_arrays = $memcache->get_memcache_group($cache_group, 'group');
            $single_arrays = $memcache->get_memcache_group($cache_group, 'single');
            //print_r($single_arrays);
            
            if (isset($group_arrays[$table_name]))
            {
                $table_group_arrays = $group_arrays[$table_name];
            }
            else
            {
                $table_group_arrays = array();
            }
            
            if (isset($single_arrays[$table_name]))
            {
                $table_single_arrays = $single_arrays[$table_name];
            }
            else
            {
                $table_single_arrays = array();
            }
            
            if (isset($where_clauses['where']))
            {
                $where = $where_clauses['where'];
            }
            else
            {
                $where = array();
            }
            
            if (isset($where_clauses['and_where']))
            {
                $and_where = $where_clauses['and_where'];
            }
            else
            {
                $and_where = array();
            }
            
            $get_additional_fields = '';
            if (count($matching_fields)) $get_additional_fields = ', ' . implode(', ', $matching_fields);
            
            $check_xdb = new Xdb;
            $check_xdb_rows = $check_xdb->select_fields('id, title' . $get_additional_fields)
                                        ->set_table($table_name)
                                        ->where($where)
                                        ->and_where_group($and_where)
                                        ->db_select(false);
            //echo $table_name . ' ' . $get_additional_fields;
            //print_r($and_where);
            //print_r($check_xdb_rows);
            $counted_rows = count($check_xdb_rows);
            if ($counted_rows == 1)
            { //print_r($check_xdb_rows);
                $id = $check_xdb_rows[0]['id'];
                $title = $check_xdb_rows[0]['title'];
                
                foreach ($matching_fields as $match)
                {
                    $add_field[$match] = $check_xdb_rows[0][$match];
                }
            }
            else
            {
                $id = false;
            }
            //echo 'id: ' . $id . ' :id';
            
            if (is_array($table_group_arrays) && !empty($table_group_arrays))
            {//print_r($table_group_arrays);
                foreach ($table_group_arrays as $cached_key => $cached_row)
                { //echo $cached_key;
                    $stored_data = $memcache->get_memcache($cached_key);
                    //print_r($new_data);
                    //print_r($stored_data[$cached_key][$id]);
                    //print_r($stored_data[$id]);
                    
                    //print_r($cached_row['query_placeholders']);
                    //print_r($where_clauses);
                    
                    $insert_check = false;
                    $removal_check = false;
                    /*
                    echo '
                    
                    ' . $cached_key . '
                    
                    ';
                    */
                    /*
                    echo '
                    
                    ' . $cached_row['query'] . '
                    
                    ';
                    print_r($cached_row['query_placeholders']);
                    */
                    
                    $index_matches = 0;
                    $value_matches = 0;
                    
                    foreach ($matching_fields as $match)
                    {
                        if (isset($cached_row['query_placeholders'][':' . $match . '0']) || isset($cached_row['query_placeholders'][':' . $match . '00']))
                        {
                            $index_matches++;
                            
                            foreach ($cached_row['query_placeholders'] as $check_key => $check_value)
                            {
                                $check_key_existance = trim(str_replace(range(0,9), '', $check_key));
                                $check_key_existance = str_replace(':', '', $check_key_existance);
                                
                                if ($check_value == $add_field[$match])
                                {
                                    $value_matches++;
                                    break;
                                }
                            }
                            
                            /*
                            foreach ($cached_row['query_placeholders'] as $check_key => $check_value)
                            {
                                $check_key_existance = trim(str_replace(range(0,9), '', $check_key));
                                $check_key_existance = str_replace(':', '', $check_key_existance);
                                
                                if (in_array($check_key_existance, $matching_fields))
                                {
                                    echo $check_key . '|' . $check_value . ' == ' . $add_field[$match] . '
                                    ';
                                    if ($check_value == $add_field[$match] && !isset($stored_data[$id]))
                                    {
                                        $insert_check = true; echo 'adding ';
                                        break;
                                    }
                                    else
                                    {
                                        if (isset($stored_data[$id]))
                                        {
                                            $removal_check = true; echo 'removing ';
                                        }
                                        $insert_check = false;
                                    }
                                }
                                
                                if ($insert_check)
                                {
                                    break;
                                }
                            }
                            
                            if ($insert_check)
                            {
                                continue;
                            }
                            */
                            
                            
                            /*
                            if (isset($cached_row['query_placeholders'][':' . $match . '0']))
                            {
                                //$compare_field = $cached_row['query_placeholders'][':' . $match . '0'];
                                
                                foreach ($cached_row['query_placeholders'] as $check_key => $check_value)
                                {
                                    if ($check_value == $add_field[$match])
                                    {
                                        $insert_check = true;
                                        break;
                                    }
                                }
                            }
                            else
                            {
                                $compare_field = $cached_row['query_placeholders'][':' . $match . '00'];
                            }
                            
                            if ($compare_field == $add_field[$match])
                            {
                                $insert_check = true;
                                break;
                            }*/
                        }
                    }
                    
                    /*
                    echo $index_matches . ' = ' . $value_matches . '
                    ';
                    */
                    
                    if ((!$index_matches && !$value_matches) || ($index_matches != $value_matches))
                    {
                        $insert_check = false;
                        if (isset($stored_data[$id]))
                        {
                            $removal_check = true;
                        }
                    }
                    elseif ($index_matches == $value_matches)
                    {
                        $insert_check = true;
                    }
                    
                    /*
                    echo $insert_check . '
                    ';
                    */
                    
                    //print_r($stored_data);
                    if ($stored_data && is_array($stored_data))
                    { //echo 'here';
                        if (isset($stored_data[$id]))
                        {
                            //print_r($stored_data[$id]);
                            //$stored_data[$id] = $new_data;
                            
                            if ($removal_check)
                            {
                                unset($stored_data[$id]); //echo 'removing';
                            }
                            else
                            {
                                foreach ($new_data as $new_key => $new_value)
                                {
                                    $stored_data[$id][$new_key] = $new_value;
                                } //echo 'changing';
                            }
                            
                            $memcache->replace_memcache($cached_key, $stored_data);
                            //$memcache->delete_memcache('post_' . $cached_key);
                            
                            // delete smarty cached files
                            if (isset($smarty_cache_info_update[$cached_key]) && is_array($smarty_cache_info_update[$cached_key]))
                            {
                                foreach ($smarty_cache_info_update[$cached_key] as $cached_file)
                                {
                                    $files = glob($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/' . $cached_file . '^*.index.tpl.php');
                                    
                                    foreach ($files as $file)
                                    {
                                        //echo $file;
                                        unlink($file);
                                    }
                                }
                            }
                        }
                        elseif(!isset($stored_data[$id]) && $id && $insert_check)
                        { //echo 'adding';
                            $last_stored_value = end($stored_data); //print_r($last_stored_value['position']);
                            
                            $new_data_row = array();
                            /*
                            $stored_data[$id]['id'] = $id;
                            $stored_data[$id]['title'] = $title;
                            $stored_data[$id]['trashed'] = 0;
                            */
                            $new_data_row[$id]['id'] = $id;
                            $new_data_row[$id]['title'] = $title;
                            $new_data_row[$id]['trashed'] = 0;
                            
                            foreach ($new_data as $new_key => $new_value)
                            {
                                //$stored_data[$id][$new_key] = $new_value;
                                $new_data_row[$id][$new_key] = $new_value;
                            }
                            
                            //array_unshift($stored_data , $new_data_row);
                            
                            //$stored_data = krsort($stored_data);
                            
                            //$stored_data = $new_data_row + $stored_data;
                            $stored_data[$id] = $new_data_row[$id];
                            
                            
                            
                            if (isset($stored_data[0]))
                            {
                                $arranged_data = array();
                                
                                foreach ($stored_data as $stored_key => $stored_value)
                                {
                                    $arranged_data[$stored_value['id']] = $stored_value;
                                }
                                
                                $stored_data = $arranged_data;
                            }
                            
                            if (!isset($last_stored_value['position']))
                            {// echo 'uksorting';
                                uksort($stored_data, function ($a, $b) { return $b - $a; });
                                //uksort($stored_data, function ($a, $b) { return $b > $a; });
                            }
                            else
                            {// echo 'sorting';
                                uasort($stored_data, function ($a, $b) { return $a['position'] - $b['position']; });
                            }
                            
                            $memcache->replace_memcache($cached_key, $stored_data);
                            
                            // delete smarty cached files
                            if (isset($smarty_cache_info_update[$cached_key]) && is_array($smarty_cache_info_update[$cached_key]))
                            {
                                foreach ($smarty_cache_info_update[$cached_key] as $cached_file)
                                {
                                    $files = glob($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/' . $cached_file . '^*.index.tpl.php');
                                    
                                    foreach ($files as $file)
                                    {
                                        unlink($file);
                                    }
                                }
                            }
                        }
                    }
                    /*
                    $row_key = key($cached_row);
                    if (isset($check_xdb_rows[$row_key]))
                    {
                        $stored_result = $memcache->get_memcache($cached_key);
                        foreach ($stored_result as $key_stored => $value_stored)
                        {
                            $stored_result[$key_stored] = $new_data[$key_stored];
                        }
                        print_r($stored_result);
                        $memcache->replace_memcache($cached_key, $stored_result);
                    }*/
                }
            }
            
            if (isset($table_single_arrays['noid_arrays']) && count($table_single_arrays['noid_arrays']))
            {
                $this->db_connect();
                
                foreach ($table_single_arrays['noid_arrays'] as $cached_key => $cached_row)
                {
                    try 
                    {
                            $db_result = $this->db->prepare($cached_row['query']);
                            $db_result->execute($cached_row['query_placeholders']);
                            $new_query_result = $db_result->fetchAll(PDO::FETCH_ASSOC);
                            
                            $memcache->replace_memcache($cached_key, $new_query_result);
                            //$memcache->delete_memcache('post_' . $cached_key);
                    } 
                    catch(PDOException $ex) 
                    {
                            echo "An Error occured!"; //user friendly message
                            //some_logging_function($ex->getMessage());
                            //echo $this->update_query;
                            echo $ex->getMessage();
                    }
                    
                    // clear smarty cache files
                    if (isset($smarty_cache_info_update[$cached_key]) && is_array($smarty_cache_info_update[$cached_key]))
                    {
                        foreach ($smarty_cache_info_update[$cached_key] as $cached_file)
                        {
                            $files = glob($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/' . $cached_file . '^*.index.tpl.php');
                            
                            foreach ($files as $file)
                            {
                                unlink($file);
                            }
                        }
                    }
                }
                $this->db_disconnect();
            }
            
            //print_r($table_single_arrays);
            
            if ($id && isset($table_single_arrays['id_arrays'][$id]))
            {
                foreach ($table_single_arrays['id_arrays'][$id] as $query_hash => $query_values)
                {
                    //$memcache_data = $memcache->get_memcache($table_single_arrays['id_arrays'][$id]['query_hash']);
                    $memcache_data = $memcache->get_memcache($query_hash);
                    //print_r($memcache_data);
                    foreach ($new_data as $new_key => $new_value)
                    {
                        $memcache_data[0][$new_key] = $new_value;
                    }
                    //print_r($memcache_data);
                    
                    //$memcache->replace_memcache($table_single_arrays['id_arrays'][$id]['query_hash'], $memcache_data);
                    $memcache->replace_memcache($query_hash, $memcache_data);
                    //$memcache->delete_memcache('post_' . $query_hash);
                    
                    // replace post processed rows
                    $processed_rows = $this->post_process_rows($memcache_data, true, $query_hash, $cache_group, $this->cache_tables, $id);
                    $memcache->replace_memcache('post_' . $query_hash, $processed_rows);
                    
                    // post process parent if any
                    $this->post_process_parent($this->cache_tables, $id);
                    
                    // clear smarty cache files
                    if (isset($smarty_cache_info_update[$query_hash]) && is_array($smarty_cache_info_update[$query_hash]))
                    {
                        foreach ($smarty_cache_info_update[$query_hash] as $cached_file)
                        {
                            $files = glob($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/' . $cached_file . '^*.index.tpl.php');
                            
                            foreach ($files as $file)
                            {
                                unlink($file);
                            }
                        }
                    }
                }
            }
        }
        /*
        foreach ($check_xdb_rows as $db_row)
        {
            
        }
        */
    }
    
    public function db_insert_content($project_id, $table, $values_array = array(), $update_groups = '', $func_type = 'none')
    {
        $this->db_connect();
        $this->update_table = $table;
        
        $this->update_fields($project_id, $values_array, $func_type);
        
        //echo "INSERT INTO " . $table . " (" . implode(', ', array_keys($this->insert_fields)) . ") VALUES (" . implode(', ', $this->insert_fields) . ")";
        //print_r($this->named_placeholders);
        $db_insert = $this->db->prepare("INSERT INTO " . $table . " (" . implode(', ', array_keys($this->insert_fields)) . ") VALUES (" . implode(', ', $this->insert_fields) . ")");
        //print_r($this->named_placeholders);
        $db_insert->execute($this->named_placeholders);
        
        $last_id = $this->db->lastInsertId();
        
        $this->db_disconnect();
        
        // update cache group if any determined
        $this->update_permanent_cache_groups($update_groups);
        
        return $last_id;
        
    }
    
    public function db_insert($table, $values_array = array(), $update_groups = '')
    {
        $this->db_connect();
        $this->update_table = $table;
        
        $project_id = 0;
        
        $this->update_fields($project_id, $values_array);
        
        
        //print_r($this->named_placeholders);
        $db_insert = $this->db->prepare("INSERT INTO " . $table . " (" . implode(', ', array_keys($this->insert_fields)) . ") VALUES (" . implode(', ', $this->insert_fields) . ")");
        //echo "INSERT INTO " . $table . " (" . implode(', ', array_keys($this->insert_fields)) . ") VALUES (" . implode(', ', $this->insert_fields) . ")";
        //print_r($this->named_placeholders);
        $db_insert->execute($this->named_placeholders);
        
        $last_id = $this->db->lastInsertId();
        
        $this->db_disconnect();
        
        // update cache group if any determined
        $this->update_permanent_cache_groups($update_groups);
        
        return $last_id;
        
    }
    
    public function db_update($update_groups = '', $matching_fields = array())
    {
        //$this->db_connect();
        //print_r($this->named_placeholders);
        //echo $this->update_query;
        try 
        {
                $db_result = $this->db->prepare($this->update_query);
                $db_result->execute($this->named_placeholders);
        } 
        catch(PDOException $ex) 
        {
                echo "An Error occured!"; //user friendly message
                //some_logging_function($ex->getMessage());
                //echo $this->update_query;
                echo $ex->getMessage();
        }
        
        $this->db_disconnect();
        
        // update cache group if any determined
        
        //$this->update_permanent_cache_groups($update_groups);
        
        $this->update_cache_group($update_groups, $this->cache_tables, $this->where_clauses_combined, $this->updating_fields, $matching_fields);
    }
    
    public function db_delete($update_groups = '')
    {
        $this->db_connect();
        //print_r($this->named_placeholders);
        //echo $this->update_query;
        try 
        {
                $db_result = $this->db->prepare($this->delete_query);
                $db_result->execute($this->named_placeholders);
        } 
        catch(PDOException $ex) 
        {
                echo "An Error occured!"; //user friendly message
                //some_logging_function($ex->getMessage());
                //echo $this->update_query;
                echo $ex->getMessage();
        }
        
        $this->db_disconnect();
        
        // update cache group if any determined
        $this->update_permanent_cache_groups($update_groups);
    }
}

?>