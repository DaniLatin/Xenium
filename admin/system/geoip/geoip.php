<?php

/**
 * @author Danijel
 * @copyright 2010
 */
 
 
 
$ip=$_SERVER['REMOTE_ADDR'];
//echo "<b>IP Address= $ip</b>";

// include functions
include($_SERVER['DOCUMENT_ROOT'] . "/admin/system/geoip/geoip.inc");

// read GeoIP database
$handle = geoip_open($_SERVER['DOCUMENT_ROOT'] . "/admin/system/geoip/GeoIP.dat", GEOIP_STANDARD);

// map IP to country
//echo "IP address $ip located in " . geoip_country_name_by_addr($handle, $ip) . " (country code " . geoip_country_code_by_addr($handle, $ip) . ")";

$geoip_country_name = geoip_country_name_by_addr($handle, $ip);
$geoip_country_code = geoip_country_code_by_addr($handle, $ip);

// close database handler
geoip_close($handle);

// print compulsory license notice
//echo "<p> -- This product includes GeoIP data created by MaxMind, available from http://maxmind.com/ --";

?>