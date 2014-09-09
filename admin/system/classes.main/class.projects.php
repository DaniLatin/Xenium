<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class Projects
{
    public function get_all_projects()
    {
        $xdb_all_projects = new Xdb;
        $xdb_projects_rows = $xdb_all_projects->set_table('www_projects')
                                              ->db_select(true, 0, 'www_projects');
        return $xdb_projects_rows;
    }
    
    public function get_domain_project_info($domain = '')
    {
        if (!$domain)
        {
            $domain = $_SERVER['HTTP_HOST'];
        }
        
        $memcache = new xMemcache;
        $loaded_project_info = $memcache->get_memcache('project_' . $domain);
        
        if (!$loaded_project_info)
        {
            $xdb_project_by_domain = new Xdb;
            $xdb_projects_rows = $xdb_project_by_domain->set_table('www_domains')
                                                       //->left_join('www_templates', array('www_domains.project_id' => 'www_templates.project_id'))
                                                       ->left_join('www_projects', array('www_domains.project_id' => 'www_projects.id'))
                                                       ->where(array('domain' => $domain))
                                                       ->limit(1)
                                                       ->db_select(false);
            unset($xdb_projects_rows['debug']);
            //print_r($xdb_projects_rows);
            $xdb_projects_rows[0]['project_id'] = $xdb_projects_rows[0]['id'];
            
            $memcache->set_memcache('project_' . $domain, $xdb_projects_rows[0]);
            
            return $xdb_projects_rows[0];
        }
        else
        {
            return $loaded_project_info;
        }
        
        //$output = array_slice($xdb_projects_rows, 0, 1);
        //print_r($output);
        //return $output[0];
    }
    
    public function get_project_by_domain($domain = '')
    {
        if (!$domain)
        {
            $domain = $_SERVER['HTTP_HOST'];
        }
        $xdb_project_by_domain = new Xdb;
        $xdb_projects_rows = $xdb_project_by_domain->set_table('www_domains')
                                                   ->left_join('www_projects', array('www_domains.project_id' => 'www_projects.id'))
                                                   ->where(array('domain' => $domain))
                                                   ->limit(1)
                                                   ->db_select();
        unset($xdb_projects_rows['debug']);
        
        return $xdb_projects_rows;
    }
    
    public function get_project_id()
    {
        $project_array = Projects::get_project_by_domain();
        //return $project_array[0]['project_id'];
        $output = array_slice($project_array, 0, 1);
        return $output[0]['project_id'];
    }
    
    public function get_project_info_by_id($project_id)
    {
        $xdb_project = new Xdb;
        $xdb_project_rows = $xdb_project->set_table('www_projects')
                                        ->db_select_by_id($project_id);
        
        //return $xdb_project_rows[0];
        $output = array_slice($xdb_project_rows, 0, 1);
        return $output[0];
    }
    
    public function get_project_slug()
    {
        $project_array = Projects::get_project_by_domain();
        return $project_array[0]['project_slug'];
    }
    
    public function get_project_name()
    {
        $project_array = Projects::get_project_by_domain();
        return $project_array[0]['project_name'];
    }
    
    public function get_project_settings()
    {
        $project_array = Projects::get_project_by_domain();
        $settings_json_array = $project_array[0]['project_settings'];
        $settings_array = json_decode($settings_json_array, true);
        return $settings_array;
    }
    
    public function get_project_template($project_id = null)
    {
        if (!$project_id)
        {
            $project_id = Projects::get_project_id();
        }
        
        $xdb_project_template = new Xdb;
        $xdb_project_template_rows = $xdb_project_template->set_table('www_templates')
                                                          ->where(array('project_id' => $project_id))
                                                          ->limit(1)
                                                          ->db_select(true, 0, 'single');
        if (isset($xdb_project_template_rows[0]))
        {
            $template = $xdb_project_template_rows[0]['template_slug'];
        }
        else
        {
            $template = 'default';
        }
        
        return $template;
    }
    
    public function get_template($loaded_template)
    {
        if (isset($loaded_template) && $loaded_template != '')
        {
            return $loaded_template;
        }
        else
        {
            return 'default';
        }
    }
}

?>