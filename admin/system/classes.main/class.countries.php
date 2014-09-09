<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class Countries
{
    public function get_all_countries()
    {
        $countries_all_xdb = new Xdb;
        $countries_all_rows = $countries_all_xdb->select_fields('*')->set_table('settings_countries')->db_select(true, 0, 'countries_all');
        return $countries_all_rows;
    }
    
    public function get_all_own_currency_countries($project_id)
    {
        $countries_currency_all_xdb = new Xdb;
        $countries_currency_all_rows = $countries_currency_all_xdb->set_table('www_countries')
                                                                  ->where(array('country_project_id' => $project_id, 'currency_own' => 1))
                                                                  ->db_select(true, 0, 'countries');
        return $countries_currency_all_rows;
    }
    
    public function get_country_by_language($language)
    {
        $memcache = new xMemcache;
        $language_country = $memcache->get_memcache('language_country_' . $language);
        
        if (!$language_country)
        {
            $country_xdb = new Xdb;
            $country_row = $country_xdb->select_fields('*')
                                       ->set_table('www_countries')
                                       ->where(array('language_id' => $language))
                                       ->limit(1)
                                       ->db_select(false);
            
            $memcache->set_memcache('language_country_' . $language, $country_row[0]['iso_code']);
            return $country_row[0]['iso_code'];
        }
        else
        {
            return $language_country;
        }
        /*if (isset($country_row[0]))
        {
            return $country_row[0]['iso_code'];
        }
        else
        {
            return false;
        }*/
    }
    
    public function get_country_currency()
    {
        global $current_country;
        
        $memcache = new xMemcache;
        $get_country_currency = $memcache->get_memcache('currency_' . $current_country);
        
        if (!$get_country_currency)
        {
            $country_xdb = new Xdb;
            $country_row = $country_xdb->select_fields('*')
                                       ->set_table('www_countries')
                                       ->where(array('iso_code' => $current_country, 'currency_own' => 1))
                                       ->limit(1)
                                       ->db_select(false);
            if (isset($country_row[0]['currency']))
            {
                $currency = $country_row[0]['currency'];
            }
            else
            {
                $currency = '€';
            }
            
            $memcache->set_memcache('currency_' . $current_country, $currency);
            return $currency;
        }
        else
        {
            return $get_country_currency;
        }
    }
    
    public function get_country_ref_id()
    {
        global $current_country;
        $country_xdb = new Xdb;
        $country_row = $country_xdb->select_fields('*')
                                   ->set_table('www_countries')
                                   ->where(array('iso_code' => $current_country))
                                   ->limit(1)
                                   ->db_select(true, 0, 'www_countries_all');
        if (count($country_row))
        {
            $country_ref_id = $country_row[0]['ref_id'];
        }
        else
        {
            $country_ref_id = 0;
        }
        return $country_ref_id;
    }
}

?>