<?php

/**
 * @author Danijel
 * @copyright 2013
 */

require $_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php';
require $_SERVER['DOCUMENT_ROOT'] . '/admin/system/minify/CSSmin.php';

$referrer = $_SERVER['HTTP_REFERER'];
$referrer_explode = explode('/', str_replace('http://', '', $referrer));
$referrer_domain = $referrer_explode[0];
$project_info = Projects::get_domain_project_info();
$project_id = $project_info['project_id'];
Domains::check_referrer_domain($project_id, $referrer_domain);

$file_path = sanitize($_GET['file_path']);
$memcache_key = sha1($file_path);

$memcache = new xMemcache;
if ($memcache->get_memcache($memcache_key))
{
    $min_contents = $memcache->get_memcache($memcache_key);
}
else
{
    if (strpos($file_path,'http://') !== false) 
    {
        $contents = @file_get_contents($file_path);
    }
    else
    {
        $contents = @file_get_contents($_SERVER['DOCUMENT_ROOT'] . $file_path);
    }
    
    //$compressor = new CSSmin();
    //$min_contents = $compressor->run($contents);
    $memcache->set_memcache($memcache_key, $contents);
}

header('Content-Type: text/css');
header('Expires: ' . gmdate('D, d M Y H:i:s', time()+365*24*3600) . ' GMT');
header('ETag: "' . md5($min_contents) . '"');

ob_start('ob_gzhandler');

echo $min_contents;
 
?>