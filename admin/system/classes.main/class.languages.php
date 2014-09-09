<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class Languages
{
    protected $current_language;
    
    public function get_all_languages()
    {
        $languages_all_xdb = new Xdb;
        $languages_all_rows = $languages_all_xdb->select_fields('*')
                                                ->set_table('settings_languages')
                                                ->db_select(true, 0, 'languages_all');
        return $languages_all_rows;
    }
    
    public function get_all_www_languages_list()
    {
        $languages_all_www_xdb = new Xdb;
        $languages_all_www_rows = $languages_all_www_xdb->select_fields('*')
                                                        ->set_table('www_languages')
                                                        ->group_by('code')
                                                        ->order_by('id')
                                                        ->db_select(true, 0, 'www_languages_all');
        return $languages_all_www_rows;
    }
    
    public function get_all_www_languages($project_id)
    {
        $languages_all_www_xdb = new Xdb;
        $languages_all_www_rows = $languages_all_www_xdb->select_fields('*')
                                                        ->set_table('www_languages')
                                                        ->where(array('project_id' => $project_id))
                                                        ->db_select(true, 0, 'www_languages_all');
        return $languages_all_www_rows;
    }
    
    public function get_all_www_enabled_languages($project_id)
    {
        $languages_all_www_enabled_xdb = new Xdb;
        $languages_all_www_enabled_rows = $languages_all_www_enabled_xdb->select_fields('*')
                                                                        ->set_table('www_languages')
                                                                        ->where(array('project_id' => $project_id, 'enabled' => true))
                                                                        ->db_select(true, 0, 'www_languages_all');
        return $languages_all_www_enabled_rows;
    }
    
    public function get_language_id($language)
    {
        $memcache = new xMemcache;
        $cached_language_id = $memcache->get_memcache('language_id_' . $language);
        
        if (!$cached_language_id)
        {
            $language_xdb = new Xdb;
            $language_row = $language_xdb->select_fields('*')
                                         ->set_table('www_languages')
                                         ->where(array('code' => $language))
                                         ->limit(1)
                                         ->db_select(false);
            
            $memcache->set_memcache('language_id_' . $language, $language_row[0]['id']);
            return $language_row[0]['id'];
        }
        else
        {
            return $cached_language_id;
        }
        //$output = array_slice($language_row, 0, 1);
        //print_r($output);
        //return $output[0]['id'];
    }
    
    public function get_language_name($language)
    {
        $language_xdb = new Xdb;
        $language_row = $language_xdb->select_fields('id, name')
                                     ->set_table('www_languages')
                                     ->where(array('code' => $language))
                                     ->limit(1)
                                     ->db_select(true, 0, 'www_languages_all');
        return $language_row[0]['name'];
    }
    
    public function get_language_by_country($project_id, $country_code)
    {
        $xdb_language_by_country = new Xdb;
        $xdb_language_by_country_rows = $xdb_language_by_country->set_table('www_countries')
                                                   ->left_join('www_languages', array('www_countries.language_id' => 'www_languages.id'))
                                                   ->where(array('iso_code' => $country_code, 'country_project_id' => $project_id))
                                                   ->limit(1)
                                                   ->db_select();
        
        if (isset($xdb_language_by_country_rows[0]) && is_array($xdb_language_by_country_rows[0]))
        {
            return $xdb_language_by_country_rows[0]['code'];
        }
        else
        {
            return 'sl';
        }
        
    }
    
    public function get_current_language($project_id)
    {
        global $smarty_cache_id, $current_country;
        
        $current_language = '';
        
        if (isset($_COOKIE['language']))
        {
            if (preg_match('/[^a-z]/', $_COOKIE['language'])) die();
            $current_language = $_COOKIE['language'];
        }
        
        if (isset($_GET['language']))
        {
            if (preg_match('/[^a-z]/', $_GET['language'])) die();
            $current_language = $_GET['language'];
        }
        
        // GEIP lang check --- return
        /*
        if (!$current_language)
        {
            include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/geoip/geoip.php');
            //echo $geoip_country_code;
            if (!$geoip_country_code)
            {
                $current_language = 'sl';
            }
            else
            {
                $current_language = Languages::get_language_by_country($project_id, $geoip_country_code);
            }
        }
        */
        
        // remove when multilanguage
        $current_language = 'en';
        
        $current_country = Countries::get_country_by_language(Languages::get_language_id($current_language));
        
        $host = $_SERVER['HTTP_HOST'];
        preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
        if (isset($matches[0]))
        {
            $domain_name = $matches[0];
            setcookie('language', $current_language, time()+3600*24*365, '/', $domain_name);
            setcookie('country', $current_country, time()+3600*24*365, '/', $domain_name);
        }
        else
        {
            //$domain_name = $host;
            setcookie('language', $current_language, time()+3600*24*365, '/');
            setcookie('country', $current_country, time()+3600*24*365, '/');
        }
        
        //setcookie('language', $current_language, time()+3600*24*365, '/', $domain_name);
        //setcookie('country', $current_country, time()+3600*24*365, '/', $domain_name);
        //setcookie('language', $current_language, time()+3600*24*365, '/');
        //setcookie('country', $current_country, time()+3600*24*365, '/');
        $smarty_cache_id[] = $current_language;
        $smarty_cache_id[] = $current_country;
        return $current_language;
    }
    
    public function language_redirect($language)
    {
        if ($_SERVER['REQUEST_URI'] == '/')
        {
            header('Location: /' . $language . '/');
            die();
        }
    }
}

?>