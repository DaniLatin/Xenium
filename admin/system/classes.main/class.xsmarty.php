<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class xSmarty
{
    protected $smarty;
    protected $smarty_cache = array();
    
    public $table;
    public $fields = array();
    
    public function __construct($project_slug, $project_template, $caching = 2, $compile_check = false)
    {
        /*
        $relations_cache = new xMemcache;
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
        }*/
        
        /*
        $this->table = 'cache_smarty';
        $this->fields =
        array(
        'static_fields' => 
            array(
                'project_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'cache' => array(
                    'value' => '', 
                    //'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'field_type' => 'LONGBLOB NOT NULL', 
                    'admin_editable' => false,
                )
            ),
        );
        */
        
        $memcache = new xMemcache;
        $memcache_smarty = $memcache->get_memcache('smarty_' . $project_slug);
        
        include ($_SERVER['DOCUMENT_ROOT'] . '/admin/system/smarty/Smarty.class.php');
        $smarty = new Smarty;
        
        if (!$memcache_smarty)
        {
            $smarty->template_dir = 'projects/' .$project_slug . '/templates/' . $project_template;
            $smarty->compile_dir = 'projects/' .$project_slug . '/templates/' . $project_template . '/compile';
            $smarty->cache_dir = 'projects/' .$project_slug . '/templates/' . $project_template . '/cache';
            $smarty->config_dir = 'projects/' .$project_slug . '/templates/' . $project_template . '/configs';
            $smarty->caching = $caching;
            $smarty->compile_check = $compile_check;
            $smarty->assign('project_slug', $project_slug);
            $smarty->assign('project_template', $project_template);
            $smarty->assign('project_folder', '/projects/' .$project_slug);
            $smarty->assign('project_template_folder', '/projects/' . $project_slug . '/templates/' . $project_template);
            $this->smarty = $smarty;
            
            $memcache->set_memcache('smarty_' . $project_slug, serialize($smarty));
        }
        else
        {
            $this->smarty = unserialize($memcache_smarty);
        }
        
        return $this;
    }
    /*
    public function smarty_setup($project_slug, $project_template)
    {
        include ($_SERVER['DOCUMENT_ROOT'] . '/admin/system/smarty/Smarty.class.php');
        $smarty = new Smarty;
        $smarty->template_dir = 'projects/' .$project_slug . '/templates/' . $project_template;
        $smarty->compile_dir = 'projects/' .$project_slug . '/templates/' . $project_template . '/compile';
        $smarty->cache_dir = 'projects/' .$project_slug . '/templates/' . $project_template . '/cache';
        $smarty->config_dir = 'projects/' .$project_slug . '/templates/' . $project_template . '/configs';
        $smarty->caching = 0;
        $smarty->compile_check = true;
        return $this;
    }
    */
    /*
    public function smarty_display($page_element)
    {
        global $smarty_cache_id;
        $cache_id = sha1(implode($smarty_cache_id));
        $this->smarty->display($page_element, $cache_id);
    }
    */
    
    public function get_cache_info()
    {
        global $project_id, $smarty_cache_info_loaded, $smarty_cache_info;
        
        if (isset($project_id) && !$smarty_cache_info_loaded)
        {
            if (!$smarty_cache_info_loaded)
            {
                $smarty_cache_info_loaded = true;
                
                $memcache = new xMemcache;
                $smarty_memcache = $memcache->get_memcache('smarty_memcache_' . $project_id);
                
                if (isset($smarty_memcache) && $smarty_memcache)
                {
                    $smarty_cache_info = unserialize(gzdecode($smarty_memcache));
                    //print_r($smarty_cache_info);
                }
                else
                {
                    $smarty_table = 'cache_smarty';
                    $smarty_fields =
                    array(
                    'static_fields' => 
                        array(
                            'project_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                            'smarty_cache_info' => array(
                                'value' => '', 
                                //'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                                'field_type' => 'LONGBLOB NOT NULL', 
                                'admin_editable' => false,
                            )
                        ),
                    );
                    
                    $xdb_smarty = new Xdb;
                    $xdb_smarty_rows = $xdb_smarty->select_fields('smarty_cache_info')
                                                  ->set_table($smarty_table)
                                                  ->where(array('project_id' => $project_id))
                                                  ->limit(1)
                                                  ->db_select(false);
                    
                    if (is_array($xdb_smarty_rows) && !empty($xdb_smarty_rows))
                    {
                        //$memcache->set_memcache('smarty_memcache_' . $project_id, gzencode(serialize($xdb_smarty_rows[0]['smarty_cache_info']), 9));
                        $memcache->set_memcache('smarty_memcache_' . $project_id, $xdb_smarty_rows[0]['smarty_cache_info']);
                        $smarty_cache_info = unserialize(gzdecode($xdb_smarty_rows[0]['smarty_cache_info']));
                    }
                    else
                    {
                        $smarty_cache_info = array();
                        $memcache->set_memcache('smarty_memcache_' . $project_id, gzencode(serialize($smarty_cache_info), 9));
                        
                        $smarty_fields['static_fields']['project_id']['value'] = $project_id;
                        $smarty_fields['static_fields']['smarty_cache_info']['value'] = gzencode(serialize(array()), 9);
                        
                        $xdb_smarty_insert = new Xdb;
                        $last_smarty_id = $xdb_smarty_insert->db_insert($smarty_table, $smarty_fields);
                    }
                }
                
                //$this->smarty_cache = $smarty_cache_info;
                
            }
            
            
        }
        
        return $smarty_cache_info;
    }
    
    public function write_cache_info()
    {
        global $smarty_cache_id, $smarty_cache_queryes, $smarty_cache_info, $project_id, $cache_id;
        //print_r($smarty_cache_queryes);
        
        if (isset($project_id) && $project_id && is_numeric($project_id))
        {
            if (!isset($cache_id))
            {
                $cache_id = sha1(implode($smarty_cache_id));
            }
            
            $smarty_cache_initial_info = $smarty_cache_info;
            
            if (is_array($smarty_cache_queryes))
            {
                foreach ($smarty_cache_queryes as $smarty_query)
                {//echo $cache_id . ' ';
                    if (!isset($smarty_cache_info[$smarty_query][$cache_id]))
                    {
                        $smarty_cache_info[$smarty_query][$cache_id] = $cache_id;
                    }
                }
                
                if ($smarty_cache_info != $smarty_cache_initial_info)
                {
                    $smarty_info_compressed = gzencode(serialize($smarty_cache_info), 9);
                    
                    $memcache = new xMemcache;
                    $memcache->replace_memcache('smarty_memcache_' . $project_id, $smarty_info_compressed);
                    
                    $update_smarty_settings = new Xdb;
                    $update = $update_smarty_settings->set_table('cache_smarty')
                                                     ->simple_update_fields(array('smarty_cache_info' => $smarty_info_compressed))
                                                     ->where(array('project_id' => $project_id))
                                                     ->db_update();
                }
            }
        }
    }
    
    public function smarty_display($project_settings)
    {
        global $smarty_cache_id, $smarty_cache_queryes, $cahce_id;
        //print_r($smarty_cache_queryes);
        
        $cache_id = sha1(implode($smarty_cache_id));
        
        if (!$this->smarty_cached('index.tpl', $cache_id))
        {
            $this->write_cache_info();
            xMemcache::write_memcache_settings();
        }
        
        
        
        // SMARTY CACHE WRITE
        /*
        foreach ($smarty_cache_queryes as $smarty_query)
        {
            if (!isset($this->smarty_cache[$smarty_query][$cache_id]))
            {
                $this->smarty_cache[$smarty_query][$cache_id] = $cache_id;
                        
                $print_smarty_cache = var_export($this->smarty_cache, true);
                
                $write_smarty_cache = new xMemcache;
                $current_cache = $write_smarty_cache->get_memcache('smarty_cache');
                if ($current_cache)
                {
                    $write_smarty_cache->replace_memcache('smarty_cache', $this->smarty_cache);
                }
                else
                {
                    $write_smarty_cache->set_memcache('smarty_cache', $this->smarty_cache);
                }
                
                $stringDataSmarty = '<?php $smarty_queries = ' . $print_smarty_cache . '; ?>';
                $smarty_cache_file = $_SERVER['DOCUMENT_ROOT'] . '/admin/system/relations/smarty_cache.php';
                        
                $smarty_write_cache = new File($smarty_cache_file);
                $smarty_write_cache->set_writable()->write_to_file($stringDataSmarty)->set_unwritable();
            }
        }
        */
        
        if (isset($_GET['page']) && $_GET['page'] == 'ios')
        {
            $project_status = 'ios';
        }
        else
        {
            $project_status = $project_settings['status'];
        }
        
        switch ($project_status)
        {
            case 'open': // the website is displayed with no limitations
                $display_page = 'index.tpl';
                break;
            case 'coming-soon': // the coming soon website is displayed, preview only if admin logged in
                if (isset($_SESSION['admin_user']) || $_SERVER['REMOTE_ADDR'] == '193.189.160.24' || ($_SERVER['REMOTE_ADDR']=="213.229.249.103") || ($_SERVER['REMOTE_ADDR']=="213.229.249.104") || ($_SERVER['REMOTE_ADDR']=="213.229.249.117"))
                {
                    $display_page = 'index.tpl';
                }
                else
                {
                    $display_page = 'status/coming-soon/index.tpl';
                }
                break;
            case 'updating':
                if (isset($_SESSION['admin_user']))
                {
                    $display_page = 'index.tpl';
                }
                else
                {
                    $display_page = 'status/updating/index.tpl';
                }
                break;
            case 'ios':
                $display_page = 'status/ios/index.tpl';
                break;
            default:
                $display_page = 'index.tpl';
                break;
        }
        $smarty_cache_id['status'] = $display_page;
        
        //$this->smarty->loadFilter('output','trimwhitespace');
        //$this->smarty->loadFilter('output','gzip');
        $this->smarty->display($display_page, $cache_id);
    }
    
    public function smarty_clear_cache()
    {/*
        $projects = Projects::get_all_projects();
        foreach ($projects as $project)
        {
            $project_info = Projects::get_project_info_by_id($project['id']);
            $project_slug = $project_info['project_slug'];
            $project_template = Projects::get_project_template($project['id']);
            //$xSmarty = new xSmarty($project_slug, $project_template);
            //$xSmarty->smarty_clear($clear_smarty_cache);
            
            $files = glob($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/*.index.tpl.php');
            
            foreach ($files as $file)
            {
                //print_r($smarty_cache);
                //$filename = $_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/' . $file;
                unlink($file);
            }
        }
    */}
    
    public function smarty_cached($template_file, $cache_id)
    {
        return $this->smarty->isCached($template_file, $cache_id);
    }
    
    public function smarty_assign($assign_key, $assign_value)
    {
        $this->smarty->assign($assign_key, $assign_value);
    }
    
    public function load_commands($project_slug)
    {
        global $selected_language, $smarty_cache_id, $project_id, $project_slug, $encryption_key, $salt;
        /*
        foreach (glob($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/commands/*.php') as $filename)
        {
            if ($filename != $_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/commands/commands.ajax.php')
            {
                include $filename;
            }
        }
        */
        include($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/commands/commands.content.php');
    }
}

?>