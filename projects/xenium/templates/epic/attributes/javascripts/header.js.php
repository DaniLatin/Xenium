<?php

/**
 * @author Danijel
 * @copyright 2014
 */

session_cache_limiter('');
require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');
require $_SERVER['DOCUMENT_ROOT'] . '/admin/system/minify/JSMinPlus.php';

$longExpiryOffset = 315360000;
header ( "Cache-Control: public, max-age=" . $longExpiryOffset );
header ( "Expires: " . gmdate ( "D, d M Y H:i:s", time () + $longExpiryOffset ) . " GMT" );
header("Pragma: cache");
header("Content-type: text/javascript");
header('Content-Encoding: gzip');
header('Vary: Accept-Encoding');


$version = sanitize($_GET['v']);

$memcache = new xMemcache;
$header_js = $memcache->get_memcache('xenium_header_js');

if ($header_js && $header_js['version'] == $version)
{
    $last_modified_time = getlastmod();
    header("Last-Modified: ".gmdate("D, d M Y H:i:s", $last_modified_time)." GMT");
    $gzipped_code = $header_js['code'];
}
else
{
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    
    $yui = new YUICompressor($_SERVER['DOCUMENT_ROOT'] . '/admin/system/tools/yuicompressor.jar', 'temp');
    
    $files = array(
        'jquery-2.1.1.min.js',
        'modernizr.foundation.js'
    );
    
    $files_string = '';
    
    foreach ($files as $file)
    {
        $files_string .= file_get_contents($file);
    }
    
    // ADD FILES
    /*
    $yui->addFile('http://localhost/projects/xenium/templates/default/attributes/js/jquery-1.8.2.min.js');
    $yui->addFile('http://siol.sdn.si/ps/js/jquery.cookiecuttr.cookie.v11.min.js');
    $yui->addFile('http://localhost/projects/xenium/templates/default/attributes/js/siol.cookie.js');
    $yui->addFile('http://localhost/projects/xenium/templates/default/attributes/js/gemius.js');
    */
    //$yui->addFile('http://localhost/projects/xenium/templates/default/attributes/js/heap.analytics.js');
    //$yui->addFile('http://localhost/projects/xenium/templates/default/attributes/js/custom.js');
    
    // ADD STRING
    //$yui->addString($string);
    //$yui->addString($file_contents);
    
    $yui->addString($files_string);
    
    // COMPRESS
    
    $code = $yui->compress();
    
    if (!$code || $code == '' || strpos($code,'not found') !== false)
    {
        $code = JSMinPlus::minify($files_string);
    }
    
    $gzipped_code = gzencode($code, 9);
    
    $header_js_new = array('version' => $version, 'code' => $gzipped_code);
    
    if ($header_js)
    {
        $memcache->replace_memcache('xenium_header_js', $header_js_new);
    }
    else
    {
        $memcache->set_memcache('xenium_header_js', $header_js_new);
    }
}



echo $gzipped_code;

?>