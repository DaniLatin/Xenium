<?php 
require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');

use UnitedPrototype\GoogleAnalytics as GoogleAnalytics;
$tracker = new GoogleAnalytics\Tracker('MO-42907887-1', 'avantbon.si');

if (isset($_SERVER['HTTP_REFERER']))
{
    $referrer = $_SERVER['HTTP_REFERER'];
    $referrer_explode = explode('/', str_replace('http://', '', $referrer));
    $referrer_domain = $referrer_explode[0];
    //$project_id = Projects::get_project_id();
    $project_info = Projects::get_domain_project_info();
    $project_id = $project_info['project_id'];
    Domains::check_referrer_domain($project_id, $referrer_domain);
}
else
{
    die();
}

if(  !isset($_COOKIE['SSGA_UniqueID3']) || !isset($_COOKIE['SSGA_visitor']) )
{
    $visitor = new GoogleAnalytics\Visitor();
    $unique_id = rand(10000000,2147483647);
}
else
{
    $visitor = unserialize($_COOKIE['SSGA_visitor']);
    $unique_id = $_COOKIE['SSGA_UniqueID3'];
}
$visitor->setUniqueId($unique_id);
$visitor->setIpAddress($_SERVER['REMOTE_ADDR']);
$visitor->setUserAgent($_SERVER['HTTP_USER_AGENT']);
$visitor->setScreenResolution('1024x768');

if (isset($_SESSION['SSGA_session']))
{
    $session = unserialize($_SESSION['SSGA_session']);
}
else
{
    $session = new GoogleAnalytics\Session();
    $_SESSION['SSGA_session'] = serialize($session);
}

$event = new GoogleAnalytics\Event();
$event->setCategory(sanitize($_GET['category']));    //string, required
$event->setAction(sanitize($_GET['action']));        //string, required
$event->setLabel(sanitize($_GET['label']));          //string, not required
$event->setNoninteraction('true');
if (isset($_GET['value'])) $event->setValue(sanitize($_GET['value']));  

//track event
$tracker->trackEvent($event,$session,$visitor);

?>