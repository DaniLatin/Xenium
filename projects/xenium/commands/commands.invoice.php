<?php

/**
 * @author Danijel
 * @copyright 2013
 */

require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');

if (!isset($_SESSION['security_token'])) die();

if (!isset($_SESSION['logged_in_user'])) die();

$referrer = $_SERVER['HTTP_REFERER'];
$referrer_explode = explode('/', str_replace('http://', '', $referrer));
$referrer_domain = $referrer_explode[0];
//$project_id = Projects::get_project_id();
$project_info = Projects::get_domain_project_info();
$project_id = $project_info['project_id'];
Domains::check_referrer_domain($project_id, $referrer_domain);

$invoice_id = sanitize($_GET['invoice_id']);
if (preg_match('/[^0-9]/', $invoice_id)) die();

echo Payment::get_invoice_by_id($invoice_id);

?>