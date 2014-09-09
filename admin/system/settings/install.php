<?php

/**
 * @author Danijel
 * @copyright 2014
 */

$php_config = "<?php

/**
 * @author Danijel
 * @copyright 2012
 */

session_start();

error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 1);

define('DB_HOST', 'db_host_change');
define('DB_NAME', 'db_name_change');
define('DB_USER', 'db_user_change');
define('DB_PASS', 'db_pass_change');

define('MEMCACHED_HOST', 'memcached_host_change');
define('MEMCACHED_PORT', 'memcached_port_change');

date_default_timezone_set('Europe/Ljubljana');

require ('" . $_SERVER['DOCUMENT_ROOT'] . "/admin/system/settings/functions.basic.php');
?>";

$memcache_config = "define('MEMCACHED_HOST', 'memcached_host_change');
define('MEMCACHED_PORT', 'memcached_port_change');";

$change_array = array('db_host_change', 'db_name_change', 'db_user_change', 'db_pass_change');

if (getenv("CLEARDB_DATABASE_URL"))
{
    $url=parse_url(getenv("CLEARDB_DATABASE_URL"));
    
    $first_db_name = $url["path"];
    
    $url_db_host = $url["host"];
    $url_db_name = substr($first_db_name,1);
    $url_db_user = $url["user"];
    $url_db_pass = $url["pass"];
    
    //echo 'host: ' . $url_db_host . ' db_name: ' . $url_db_name . ' db_user: ' . $url_db_user . ' db_pass: ' . $url_db_pass;
    
    $changes_array = array($url_db_host, $url_db_name, $url_db_user, $url_db_pass);
    
    $php_config = str_replace($change_array, $changes_array, $php_config);
    
    if (isset($_ENV['MEMCACHEDCLOUD_SERVERS']))
    {
        $memcache_config_replace = "$"."memcached_cloud = true;";
        
        $php_config = str_replace($memcache_config, $memcache_config_replace, $php_config);
    }
    else
    {
        $php_config = str_replace($memcache_config, '', $php_config);
    }
    
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php', $php_config);
}

?>
<input />