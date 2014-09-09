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
//header('HTTP/1.1 304 Not Modified');
header("Content-type: text/javascript");
header('Content-Encoding: gzip');
header('Vary: Accept-Encoding');


$version = sanitize($_GET['v']);

$language = sanitize($_GET['language']);
//$country = sanitize($_GET['country']);

$memcache = new xMemcache;
$footer_js = $memcache->get_memcache('xenium_footer_js' . $language);

if ($footer_js && $footer_js['version'] == $version)
{
    $last_modified_time = getlastmod();
    header("Last-Modified: ".gmdate("D, d M Y H:i:s", $last_modified_time)." GMT");
    $gzipped_code = $footer_js['code'];
}
else
{
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    
    $yui = new YUICompressor($_SERVER['DOCUMENT_ROOT'] . '/admin/system/tools/yuicompressor.jar', 'temp');
    
    $files = array(
        'zepto.min.js',
        //'jquery-2.1.1.min.js',
        'foundation.min.js',
        //'app.js',
        //'custom.js',
        'jAPI.js',
        'http://' . $_SERVER['HTTP_HOST'] . '/projects/xenium/commands/commands.ajax.php?language=' . $language . '&pass=avant'
    );
    
    $files_string = '';
    
    foreach ($files as $file)
    {
        $files_string .= file_get_contents($file);
    }
    
    // ADD FILES
    /*
    $yui->addFile('jquery.easing.1.3.js');
    $yui->addFile('jquery.cycle.all.js');
    $yui->addFile('custom.js');
    $yui->addFile('http://' . $_SERVER['HTTP_HOST'] . '/projects/xenium/commands/commands.ajax.php?language=' . $language . '&pass=avant');
    $yui->addFile('http://si.hit.gemius.pl/hmapxy.js');
    */
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
    //$gzipped_code = $code;
    
    $footer_js_new = array('version' => $version, 'code' => $gzipped_code);
    
    if ($footer_js)
    {
        $memcache->replace_memcache('xenium_footer_js' . $language, $footer_js_new);
    }
    else
    {
        $memcache->set_memcache('xenium_footer_js' . $language, $footer_js_new);
    }
}



echo $gzipped_code;

?>