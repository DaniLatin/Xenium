<?php

/**
 * @author Danijel
 * @copyright 2013
 */

require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');

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

// check key

if (isset($_GET['key']))
{
    $check_key = $_GET['key'];
    $check_sent = '';
    foreach ($_GET as $key => $value)
    {
        if ($key != 'key')
        {
            $check_sent .= $key . $value;
        }
    }
    
    $check_sent .= $salt . $encryption_key;
    $check_sent_hash = sha1($check_sent);
    
    if ($check_key == $check_sent_hash)
    {
        foreach ($_GET as $get_key => $get_value)
        {
            if ($get_key != 'key')
            {
                $_SESSION['logged_in_user'][sanitize($get_key)] = $get_value;
            }
        }
    }
    else
    {
        
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'remove')
{
    unset($_SESSION['logged_in_user']);
}

?>