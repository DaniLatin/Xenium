<?php

/**
 * @author Danijel
 * @copyright 2013
 */

//function __autoload($class_name)
//{
spl_autoload_register(function($class_name){
    $directories = array('classes.main', 'classes.modules', 'classes.third.party');
    
    foreach ($directories as $directory)
    {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/admin/system/' . $directory . '/class.' . strtolower($class_name) . '.php'))
        {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/system/' . $directory . '/class.' . strtolower($class_name) . '.php');
            
            //$cache_setting = call_user_func_array(array($class_name, 'load_cache_setting'), array($class_name));
            //echo $class_name . ' loaded ' .$cache_setting .' :: ';
            
            //if (method_exists($class_name, 'load_cache_setting') && call_user_func($class_name .'::load_cache_setting'))
            if (method_exists($class_name, 'load_cache_setting') && call_user_func_array(array($class_name, 'load_cache_setting'), array($class_name)))
            { //echo $class_name . ' loaded :: ';
                $memcache = new xMemcache;
                $memcache->get_memcache_group(strtolower($class_name), 'group');
                $memcache->get_memcache_group(strtolower($class_name), 'single');
            }
            
            return;
        }
        else
        {
            //echo 'Class file for ' . $class_name . ' does not exist';
        }
    }
});

// autoload for included files in funcions  ::  usage: spl_autoload_register('class_autoloader');
function class_autoloader($class_name)
{
    $directories = array('classes.main', 'classes.modules', 'classes.third.party');
    
    foreach ($directories as $directory)
    {
        if (strpos($class_name,'\\') !== false) 
        {
            //echo 'true';
            $class_name = str_replace('\\', '/', $class_name);
            echo $_SERVER['DOCUMENT_ROOT'] . '/admin/system/' . $directory . '/' . $class_name . '.php';
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/admin/system/' . $directory . '/' . $class_name . '.php'))
            { echo $class_name . ' included';
                require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/system/' . $directory . '/' . $class_name . '.php');
                return;
            }
            else
            {
                //continue;
                //echo 'Class file for ' . $class_name . ' does not exist';
            }
        }
        else
        {
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/admin/system/' . $directory . '/class.' . strtolower($class_name) . '.php'))
            {
                require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/system/' . $directory . '/class.' . strtolower($class_name) . '.php');
                
                if (method_exists($class_name, 'load_cache_setting') && call_user_func_array(array($class_name, 'load_cache_setting'), array($class_name)))
                { //echo $class_name . ' loaded :: ';
                    $memcache = new xMemcache;
                    $memcache->get_memcache_group(strtolower($class_name), 'group');
                    $memcache->get_memcache_group(strtolower($class_name), 'single');
                }
                
                return;
            }
            else
            {
                //echo 'Class file for ' . $class_name . ' does not exist';
            }
        }
    }
}

function sanitize($value)
{
    htmlentities( $value, ENT_QUOTES, 'utf-8');
    // other processing
    
    $search=array("\\","\0","\n","\r","\x1a","'",'"');
    $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
    
    return str_replace($search, $replace, $value);
}

function translate($string, $replace = '')
{
    global $selected_language;
    
    if ($string && $string != '')
    {
        $translate_string = $string;
        
        $translation = new Translations;
        $output = $translation->get_translation($translate_string, $selected_language);
        
        if ($replace != '')
        {
            $replace_array = explode('|', $replace);
            foreach ($replace_array as $replacement)
            {
                $replacement_value = explode('=', $replacement);
                $output = str_replace($replacement_value[0], $replacement_value[1], $output);
            }
        }
       
        return $output;
    }
    else
    {
        return $string;
    }
}

function seolinker($string)
{
    setlocale(LC_CTYPE, 'en_US.UTF8');
    $string = strip_tags($string);
    $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
    $string = preg_replace('/\\s+/', '-', $string);
    $string = str_replace('_', '-', $string);
    $string = str_replace('&Scaron;', 's', $string);
    $string = str_replace('&scaron;', 's', $string);
    $string = preg_replace("/[^a-z-\d ]/i", '', $string);
    return strtolower($string);
}

function trim_text($input, $length, $ellipses = true, $strip_html = true) {
	//strip tags, if desired
	if ($strip_html) {
		$input = strip_tags($input);
	}
 
	//no need to trim, already shorter than trim length
	if (strlen($input) <= $length) {
		return $input;
	}
 
	//find last space within length
	$last_space = strrpos(substr($input, 0, $length), ' ');
	$trimmed_text = substr($input, 0, $last_space);
 
	//add ellipses (...)
	if ($ellipses) {
		$trimmed_text .= '...';
	}
 
	return $trimmed_text;
}

function shorten_address($string)
{
    $exploded = explode(',', $string);
    if (isset($exploded[0]))
    {
        $just_address = trim($exploded[0]);
    }
    else
    {
        $just_address = '';
    }
    
    if (isset($exploded[1]))
    {
        $just_city = trim(str_replace(range(0,9),'',$exploded[1]));
    }
    else
    {
        $just_city = '';
    }
    
    if ($just_address != '' && $just_city != '')
    {
        return $just_address . ', ' . $just_city;
    }
    elseif ($just_address != '' && $just_city == '')
    {
        return $just_address;
    }
    elseif ($just_address == '' && $just_city != '')
    {
        return $just_city;
    }
    else
    {
        return '';
    }
}

if (!function_exists('apache_request_headers')) 
{
    function apache_request_headers() 
    {
        foreach($_SERVER as $key=>$value) 
        {
            if (substr($key,0,5)=="HTTP_") 
            {
                $key=str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",substr($key,5)))));
                $out[$key]=$value;
            }
            else
            {
                $out[$key]=$value;
            }
        }
    return $out;
    }
}

if (!function_exists('gzdecode')) 
{
    function gzdecode($data)
    {
       return gzinflate(substr($data,10,-8));
    }
}

if (!defined('MEMCACHE_COMPRESSED'))
{
    define('MEMCACHE_COMPRESSED', 2);
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


$filename = $_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/functions.*.php';
foreach (glob($filename) as $filefound)
{
    if ($filefound != $_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/functions.basic.php')
    {
        include($filefound);
    }
}


// encrypt decrypt functions
function encrypt($pure_string) 
{
    global $encryption_key;
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return $encrypted_string;
}

function decrypt($encrypted_string) 
{
    global $encryption_key;
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    return html_entity_decode($decrypted_string, ENT_QUOTES, 'UTF-8');
}

function randomPassword() 
{
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) 
    {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function randomSerial($limit_chars = 10, $division_every = 5)
{
    $chars = array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    $serial = '';
    $max = count($chars)-1;
    for($i=0;$i<$limit_chars;$i++){
        $serial .= (!($i % $division_every) && $i ? '-' : '').$chars[rand(0, $max)];
    }
    return $serial;
}

function createDateRangeArray($strDateFrom,$strDateTo)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            if ($iDateFrom < $iDateTo)
            {
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
    }
    return $aryRange;
}

function show_error($param1, $param2, $param3)
{
    return false;
}

// global variables
$salt = 'krneki';
$encryption_key = '!krneki';
$smarty_cache_file = '';
$smarty_cache_id = array();

?>