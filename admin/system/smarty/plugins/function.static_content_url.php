<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.static_content_url.php
 * Type:     function
 * Name:     static_content_url
 * Purpose:  return url of given static content
 * -------------------------------------------------------------
 */

function smarty_function_static_content_url($params, &$smarty)
{
    global $project_id, $smarty_cache_queryes, $cache_id;
    //echo $cache_id;
    $static_content_title = $params['title'];
    
    if (isset($params['project_domain']))
    {
        $project_domain = $params['project_domain'];
    }
    else
    {
        $project_domain = '';
    }
    
    $sc = new StaticContent;
    $output = $sc->static_contents_generate_url($static_content_title, $project_domain);
    
    //$fSmarty = new xSmarty($project_slug, $project_template);
    //print_r($smarty_cache_queryes);
    xSmarty::write_cache_info();
    xMemcache::write_memcache_settings();
    
    return $output;
}
?>