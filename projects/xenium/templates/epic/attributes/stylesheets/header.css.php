<?php

/**
 * @author Danijel
 * @copyright 2014
 */

session_cache_limiter('');
require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');
require $_SERVER['DOCUMENT_ROOT'] . '/admin/system/minify/CSSmin.php';

$longExpiryOffset = 315360000;
header ( "Cache-Control: public, max-age=" . $longExpiryOffset );
header ( "Expires: " . gmdate ( "D, d M Y H:i:s", time () + $longExpiryOffset ) . " GMT" );
header("Content-type: text/css");
header('Content-Encoding: gzip');
header('Vary: Accept-Encoding');


$version = sanitize($_GET['v']);

$memcache = new xMemcache;
$header_css = $memcache->get_memcache('xenium_header_css');

if ($header_css && $header_css['version'] == $version)
{
    $last_modified_time = getlastmod();
    header("Last-Modified: ".gmdate("D, d M Y H:i:s", $last_modified_time)." GMT");
    $gzipped_code = $header_css['code'];
}
else
{
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    $yui = new YUICompressor($_SERVER['DOCUMENT_ROOT'] . '/admin/system/tools/yuicompressor.jar', 'temp', array('type' => 'css'));
    
    $files = array(
        'foundation.min.css',
        'main.css',
        'app.css',
        'social.css',
        'custom.css',
        'icomoon-style.css',
        'http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Playfair+Display:400italic'
    );
    
    $files_string = '';
    
    foreach ($files as $file)
    {
        $files_string .= file_get_contents($file);
    }
    
    // ADD FILES
    /*
    $yui->addFile('style.css');
    $yui->addFile('http://siol.sdn.si/ps/css/cookiecu.css');
    */
    // ADD STRING
    //$yui->addString($string);
    //$yui->addString($file_contents);
    
    $yui->addString($files_string);
    
    // COMPRESS
    
    $code = $yui->compress();
    
    if (!$code || $code == '' || strpos($code,'not found') !== false)
    {
        $CSSmin = new CSSmin;
        $code = $CSSmin->run($files_string);
    }
    
    $gzipped_code = gzencode($code, 9);
    //$gzipped_code = $code;
    
    $header_css_new = array('version' => $version, 'code' => $gzipped_code);
    
    if ($header_css)
    {
        $memcache->replace_memcache('xenium_header_css', $header_css_new);
    }
    else
    {
        $memcache->set_memcache('xenium_header_css', $header_css_new);
    }
}



echo $gzipped_code;

?>