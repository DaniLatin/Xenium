<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class Domains
{
    public function get_domains_by_project($project_id)
    {
        $domain_xdb = new Xdb;
        $domain_xdb_rows = $domain_xdb->select_fields('domain')
                                      ->set_table('www_domains')
                                      ->where(array('project_id' => $project_id))
                                      ->db_select(true, 0, 'domains');
        
        $domains = array();
        foreach ($domain_xdb_rows as $row)
        {
            $domains[] = $row['domain'];
        }
        return $domains;
    }
    
    public function check_referrer_domain($project_id, $domain)
    {
        $domains_array = self::get_domains_by_project($project_id);
        if (!in_array($domain, $domains_array))
        {
            die();
        }
    }
}

?>