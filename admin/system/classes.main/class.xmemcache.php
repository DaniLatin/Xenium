<?php

/**
 * @author Danijel
 * @copyright 2012
 */

class xMemcache
{
    public $table_prefix;
    public $fields = array();
    
    public function __construct()
    {
        $this->table_prefix = 'cache_';
        //$this->table = 'cache_';
        $this->fields = 
        array(
        'static_fields' => 
            array(
                'project_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'cache_group' => array(
                    'value' => '', 
                    //'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'field_type' => 'LONGBLOB NOT NULL', 
                    'admin_editable' => false,
                ),
                'cache_single' => array(
                    'value' => '', 
                    'field_type' => 'LONGBLOB NOT NULL', 
                    'admin_editable' => false,
                ),
            ),
        );
    }
    
    public function get_memcache_group($group_name, $group_type)
    {
        global $project_id, $settings_loaded, ${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'}, ${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings_first'};
        //echo 'neki';
        //print_r( ${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'} );
        
        if ($group_name)
        {
            if (!isset($settings_loaded))
            {
                $settings_loaded = array();
            }
            
            if (!${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'} && isset($project_id))
            { //echo $group_name . ' runned :: ';
                $settings_loaded[$group_name] = $group_name;
                
                $memcache_group = $this->get_memcache($group_name . '_' . $group_type . '_' . $project_id);
                if ($memcache_group)
                {
                    //return unserialize(gzdecode($group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'));
                    //echo $group_name . $group_type;
                    //print_r(unserialize(gzdecode($memcache_group)));
                    
                    ${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'} = unserialize(gzdecode($memcache_group));
                    
                    //echo '<b>memcache</b>';
                    //print_r(${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'});
                }
                else
                {
                    $xdb_memcache = new Xdb;
                    $xdb_memcache_rows = $xdb_memcache->set_table($this->table_prefix . $group_name)
                                                      ->where(array('project_id' => $project_id))
                                                      ->limit(1)
                                                      ->db_select(false);
                    
                    
                    if (is_array($xdb_memcache_rows) && !empty($xdb_memcache_rows))
                    {
                        $first_key = key($xdb_memcache_rows);
                        
                        $this->set_memcache($group_name . '_group_' . $project_id, $xdb_memcache_rows[$first_key]['cache_group']);
                        $this->set_memcache($group_name . '_single_' . $project_id, $xdb_memcache_rows[$first_key]['cache_single']);
                        
                        //print_r(unserialize(gzdecode($xdb_memcache_rows[$first_key]['cache_group'])));
                        //print_r(unserialize(gzdecode($xdb_memcache_rows[$first_key]['cache_single'])));
                        
                        //return unserialize(gzdecode($xdb_memcache_rows[$first_key][$group_type]));
                        ${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'} = unserialize(gzdecode($xdb_memcache_rows[$first_key]['cache_' . $group_type]));
                        //echo ${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'};
                        //echo '<b>xdb</b>';
                        //print_r(${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'});
                    }
                    else
                    {
                        $new_values = array('group' => '', 'single' => '');
                        
                        /*
                        $xdb_memcache_update = new Xdb;
                        $update = $xdb_memcache_update->set_table($this->table_prefix . $group_name)
                                                      ->update_fields($project_id, $this->fields, $new_values, 'update')
                                                      ->where(array('project_id' => $project_id))
                                                      ->db_update();
                        */
                        
                        $this->fields['static_fields']['project_id']['value'] = $project_id;
                        $this->fields['static_fields']['cache_group']['value'] = gzencode(serialize(array()), 9);
                        $this->fields['static_fields']['cache_single']['value'] = gzencode(serialize(array()), 9);
                        //print_r($this->fields);
                        $xdb_memcache_insert = new Xdb;
                        //$insert_new = $xdb_memcache_insert->db_insert_content($project_id, $this->table_prefix . $group_name, $this->fields);
                        $last_memcache_id = $xdb_memcache_insert->db_insert($this->table_prefix . $group_name, $this->fields);
                        
                        //return false;
                        //${$group_name . '_' . $group_type . '_memcache_settings'} = false;
                        ${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'} = array();
                    }
                }
                ${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings_first'} = ${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'};
                
                
            }
            return ${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'};
        }
    }
    
    public function set_query_settings($group_name, $group_type, $project_id, $table_name, $query_hash, $query, $query_placeholders, $id = 0)
    {
        global ${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'}, $settings_loaded;
        
        if (!isset($settings_loaded))
        {
            $settings_loaded = array();
        }
        if (class_exists($group_name) && get_parent_class($group_name) == 'Module')
        {
            $settings_loaded[$group_name] = $group_name;
        }
        
        $memcache = new xMemcache;
        
        
        if ($group_type == 'group')
        {
            ${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'}[$table_name][$query_hash] = array('query' => $query, 'query_placeholders' => $query_placeholders);
        }
        else
        {
            if ($id == 'no-id')
            {
                ${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'}[$table_name]['noid_arrays'][$query_hash] = array('query' => $query, 'query_placeholders' => $query_placeholders);
            }
            else
            {
                ${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'}[$table_name]['id_arrays'][$id][$query_hash] = array('query' => $query, 'query_placeholders' => $query_placeholders);
                //print_r(${$group_name . '_' . $group_type . '_' . $project_id . '_memcache_settings'});
            }
        }
    }
    
    public function ajax_write_memcache_settings()
    {
        global $project_id, $settings_loaded;
        //print_r($settings_loaded);
        
        if (!empty($settings_loaded))
        {
            $memcache = new xMemcache;
            
            foreach ($settings_loaded as $group_name)
            {
                if (class_exists($group_name) && get_parent_class($group_name) == 'Module')
                {
                    global ${$group_name . '_group_' . $project_id . '_memcache_settings_load'}, ${$group_name . '_single_' . $project_id . '_memcache_settings_load'}, ${$group_name . '_group_' . $project_id . '_memcache_settings_first'}, ${$group_name . '_single_' . $project_id . '_memcache_settings_first'};
                    //echo $group_name;
                    ${$group_name . '_group_' . $project_id . '_memcache_settings_load'} = $memcache->get_memcache_group($group_name, 'group');
                    ${$group_name . '_single_' . $project_id . '_memcache_settings_load'} = $memcache->get_memcache_group($group_name, 'single');
                    
                    //echo 'first';
                    //print_r(${$group_name . '_single_' . $project_id . '_memcache_settings_first'});
                    echo 'changed <b>' . $group_name . '_group_' . $project_id . '</b>';
                    print_r(${$group_name . '_single_' . $project_id . '_memcache_settings_load'});
                }
            }
        }
    }
    
    public function write_memcache_settings()
    {
        global $project_id, $settings_loaded;
        //print_r($settings_loaded);
        
        $memcache = new xMemcache;
        //print_r($settings_loaded);
        
        if (!empty($settings_loaded))
        {
            foreach ($settings_loaded as $group_name)
            {
                global ${$group_name . '_group_' . $project_id . '_memcache_settings'}, ${$group_name . '_single_' . $project_id . '_memcache_settings'}, ${$group_name . '_group_' . $project_id . '_memcache_settings_first'}, ${$group_name . '_single_' . $project_id . '_memcache_settings_first'};
                //echo '   :::   ';
                //print_r(${$group_name . '_group_' . $project_id . '_memcache_settings'});
                //echo $group_name;
                //print_r(${$group_name . '_group_' . $project_id . '_memcache_settings_first'});
                /*echo '<b>first</b>';
                print_r(${$group_name . '_single_' . $project_id . '_memcache_settings_first'});
                echo '<b>next</b>';
                print_r(${$group_name . '_single_' . $project_id . '_memcache_settings'});*/
                
                //echo $group_name . '_group_' . $project_id . '_memcache_settings<br>';
                
                if (${$group_name . '_group_' . $project_id . '_memcache_settings_first'} != ${$group_name . '_group_' . $project_id . '_memcache_settings'} || ${$group_name . '_single_' . $project_id . '_memcache_settings_first'} != ${$group_name . '_single_' . $project_id . '_memcache_settings'})
                {
                    //echo 'writing';
                    $update_memcache_settings = new Xdb;
                    $update = $update_memcache_settings->set_table($memcache->table_prefix . $group_name)
                                                       ->simple_update_fields(array('cache_group' => gzencode(serialize(${$group_name . '_group_' . $project_id . '_memcache_settings'}), 9), 'cache_single' => gzencode(serialize(${$group_name . '_single_' . $project_id . '_memcache_settings'}), 9)))
                                                       ->where(array('project_id' => $project_id))
                                                       ->db_update();
                    //echo $group_name;
                    $cache_check = $memcache->get_memcache($group_name . '_group_' . $project_id);
                    if (isset($cache_check) && $memcache->get_memcache($group_name . '_group_' . $project_id))
                    {
                        $memcache->replace_memcache($group_name . '_group_' . $project_id, gzencode(serialize(${$group_name . '_group_' . $project_id . '_memcache_settings'}), 9));
                    }
                    else
                    {
                        $memcache->set_memcache($group_name . '_group_' . $project_id, gzencode(serialize(${$group_name . '_group_' . $project_id . '_memcache_settings'}), 9));
                    }
                    
                    if ($memcache->get_memcache($group_name . '_single_' . $project_id))
                    {
                        $memcache->replace_memcache($group_name . '_single_' . $project_id, gzencode(serialize(${$group_name . '_single_' . $project_id . '_memcache_settings'}), 9));
                    }
                    else
                    {
                        $memcache->set_memcache($group_name . '_single_' . $project_id, gzencode(serialize(${$group_name . '_single_' . $project_id . '_memcache_settings'}), 9));
                    }
                }
            }
        }
    }
    
    public function get_memcache_settings()
    {
        global $settings_loaded;
        if (!isset($settings_loaded))
        {
            $settings_loaded = array();
        }
        
        if (class_exists('Memcache'))
        {
            $children  = array();
            foreach(get_declared_classes() as $class)
            {
                //if($class instanceof Modules) 
                //if (is_subclass_of($class, 'Modules'))
                //echo get_parent_class($class) . ' ';
                //echo $class . ' ';
                
                if (!isset($settings_loaded[$class]))
                {
                    $settings_loaded[$class] = false;
                }
                
                $memcache = new xMemcache;
                if (get_parent_class($class) == 'Module' && !$settings_loaded[$class])
                {
                    $children[] = $class;
                     //echo strtolower($class);
                    $memcache->get_memcache_group(strtolower($class), 'group');
                    $memcache->get_memcache_group(strtolower($class), 'single');
                    
                    $settings_loaded[$class] = true;
                }
            }
            
        }
        //$settings_loaded = true;
    }
    
    public function connect_memcache()
    {
        global $memcached_cloud;
        //global $memcache;
        if (!$memcached_cloud)
        {
            if (class_exists('Memcache') && defined('MEMCACHED_HOST') && defined('MEMCACHED_PORT'))
            {
                $memcached = new Memcache();
                $memcached->connect(MEMCACHED_HOST, MEMCACHED_PORT) or die ("Could not connect");
                return $memcached;
            }
        }
        else
        {
            $memcached = new MemcacheSASL;
            list($host, $port) = explode(':', $_ENV['MEMCACHEDCLOUD_SERVERS']);
            $memcached->addServer($host, $port);
            $memcached->setSaslAuthData($_ENV['MEMCACHEDCLOUD_USERNAME'], $_ENV['MEMCACHEDCLOUD_PASSWORD']);
            return $memcached;
        }
    }
    
    public function set_memcache($key, $value, $compression = MEMCACHE_COMPRESSED, $expiration = 0)
    {
        global $memcached, $memcached_cloud;
        if (class_exists('Memcache') && defined('MEMCACHED_HOST') && defined('MEMCACHED_PORT') && !$memcached_cloud)
        {
            //$memcache = new Memcache();
            //$memcache->connect(MEMCACHED_HOST, MEMCACHED_PORT) or die ("Could not connect");
            
            if (!isset($memcached) && !is_object($memcached))
            {
                $memcached = xMemcache::connect_memcache();
            }
            
            $memcached->set($key, $value, $compression, $expiration);
        }
        elseif ($memcached_cloud)
        {
            if (!isset($memcached) && !is_object($memcached))
            {
                $memcached = xMemcache::connect_memcache();
            }
            
            $memcached->set($key, $value, $expiration);
        }
    }
    
    public function get_memcache($key)
    {
        global $memcached, $memcached_cloud;
        if ((class_exists('Memcache') && defined('MEMCACHED_HOST') && defined('MEMCACHED_PORT')) || $memcached_cloud)
        {
            //$memcache = new Memcache();
            //$memcache->connect(MEMCACHED_HOST, MEMCACHED_PORT) or die ("Could not connect");
            
            if (!isset($memcached) && !is_object($memcached))
            {
                $memcached = xMemcache::connect_memcache();
            }
            
            $get_result = $memcached->get($key);
            return $get_result;
        }
        else
        {
            return false;
        }
    }
    
    public function replace_memcache($key, $value, $compression = MEMCACHE_COMPRESSED, $expiration = 0)
    {
        global $memcached, $memcached_cloud;
        if (class_exists('Memcache') && defined('MEMCACHED_HOST') && defined('MEMCACHED_PORT') && !$memcached_cloud)
        {
            //$memcache = new Memcache();
            //$memcache->connect(MEMCACHED_HOST, MEMCACHED_PORT) or die ("Could not connect");
            
            if (!isset($memcached) && !is_object($memcached))
            {
                $memcached = xMemcache::connect_memcache();
            }
        
            $memcached->replace($key, $value, $compression, $expiration);
        }
        elseif ($memcached_cloud)
        {
            if (!isset($memcached) && !is_object($memcached))
            {
                $memcached = xMemcache::connect_memcache();
            }
            
            $memcached->replace($key, $value, $expiration);
        }
    }
    
    public function delete_memcache($key)
    {
        global $memcached, $memcached_cloud;
        if ((class_exists('Memcache') && defined('MEMCACHED_HOST') && defined('MEMCACHED_PORT')) || $memcached_cloud)
        {
            //$memcache = new Memcache();
            //$memcache->connect(MEMCACHED_HOST, MEMCACHED_PORT) or die ("Could not connect");
            
            if (!isset($memcached) && !is_object($memcached))
            {
                $memcached = xMemcache::connect_memcache();
            }
            
            $memcached->delete($key);
        }
    }
    
    public function flush_memcache()
    {
        global $memcached;
        if (class_exists('Memcache') && defined('MEMCACHED_HOST') && defined('MEMCACHED_PORT'))
        {
            //$memcache = new Memcache();
            //$memcache->connect(MEMCACHED_HOST, MEMCACHED_PORT) or die ("Could not connect");
            
            if (!isset($memcached) && !is_object($memcached))
            {
                $memcached = xMemcache::connect_memcache();
            }
            
            $memcached->flush();
        }
        /*
        $stringData = '<?php $cached_queries = array(); ?>';
        $cache_file = $_SERVER['DOCUMENT_ROOT'] . '/admin/system/relations/permanent_cache.php';
                    
        $write_cache = new File($cache_file);
        $write_cache->set_writable()->write_to_file($stringData)->set_unwritable();*/
    }
}

?>