<?php

/**
 * @author Danijel
 * @copyright 2013
 */

require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');

if (!isset($_SESSION['security_token'])) die();

if (!isset($_GET['login_method'])) die();
$login_method = sanitize($_GET['login_method']);
if (preg_match('/[^a-z]/', $login_method)) die();

$project_id = 1;

$xdb_social_settings = new Xdb;
$xdb_social_settings_rows = $xdb_social_settings->set_table('settings_social')
                                                ->db_select(true, 0, 'social');
$social_settings = $xdb_social_settings_rows[0];

switch ($login_method)
{
    case 'facebook':
        //echo 'facebook login';
        
        $config = array(
        "base_url" => "http://" . $_SERVER['HTTP_HOST'] . "/admin/system/hybridauth/",
        "providers" => array (
        "Facebook" => array (
        "enabled" => true,
        //"keys" => array ( "id" => "1408418619372506", "secret" => "fabf4525bfb36091d8711909879eb9b9" ),
        "keys" => array ( "id" => $social_settings['fb_id'], "secret" => $social_settings['fb_secret'] ),
        //"scope" => "email, user_about_me, user_birthday, user_hometown, user_website, offline_access, read_stream, publish_stream, read_friendlists, user_location", // optional
        "scope" => "email, user_about_me, user_birthday, user_hometown, user_website, read_friendlists, user_location, user_hometown", // optional
        "display" => "popup" // optional
        )));
        require_once( $_SERVER['DOCUMENT_ROOT'] . '/admin/system/hybridauth/Hybrid/Auth.php' );
        $hybridauth = new Hybrid_Auth( $config );
        $adapter = $hybridauth->authenticate( "Facebook" );
        $user_profile = $adapter->getUserProfile(); 
        
        $user = new Users;
        $user->social_login_user('facebook', $user_profile);
        break;
    case 'twitter':
        //echo 'twitter login';
        
        $config = array(
        "base_url" => "http://" . $_SERVER['HTTP_HOST'] . "/admin/system/hybridauth/",
        "providers" => array (
        "Twitter" => array (
        "enabled" => true,
        "keys" => array ( "key" => "uZGUz06r3fxh9oBHf9cZvw", "secret" => "S6u3dOQw21Hw0caNYE5jV8OyrtVoqvyisW6ZHxxNL8" ),
        //"scope" => "email, user_about_me, user_birthday, user_hometown", // optional
        //"display" => "popup" // optional
        )));
        require_once( $_SERVER['DOCUMENT_ROOT'] . '/admin/system/hybridauth/Hybrid/Auth.php' );
        $hybridauth = new Hybrid_Auth( $config );
        $adapter = $hybridauth->authenticate( "Twitter" );
        $user_profile = $adapter->getUserProfile(); 
        //print_r($user_profile);
        $user_profile_array = (array)$user_profile;
        //$user_profile_json =  json_encode($user_profile_array);
        //echo base64_encode($user_profile_json);
        break;
    case 'google';
        //echo 'google login';
        
        $config = array(
        "base_url" => "http://" . $_SERVER['HTTP_HOST'] . "/admin/system/hybridauth/",
        "providers" => array (
        "Google" => array (
        "enabled" => true,
        //"keys" => array ( "id" => "353863899546.apps.googleusercontent.com", "secret" => "w_fCPv9pD3KoHh7K5mSHumE7" ),
        "keys" => array ( "id" => $social_settings['google_id'], "secret" => $social_settings['google_secret'] ),
        "scope" => "https://www.googleapis.com/auth/userinfo.profile ". // optional
        "https://www.googleapis.com/auth/userinfo.email" , // optional
        "access_type" => "offline", // optional
        "approval_prompt" => "force", // optional
        //"hd" => "domain.com" // optional
        )));
        require_once(  $_SERVER['DOCUMENT_ROOT'] . '/admin/system/hybridauth/Hybrid/Auth.php'  );
        $hybridauth = new Hybrid_Auth( $config );
        $adapter = $hybridauth->authenticate( "Google" );
        $user_profile = $adapter->getUserProfile(); 
        
        $user = new Users;
        $user->social_login_user('google', $user_profile);
        break;
}

?>