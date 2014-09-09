<?php

/**
 * @author Danijel
 * @copyright 2013
 */

header("Content-Type:text/json");
require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');

if (!isset($_GET['locale']))
{
    $selected_language = 'sl';
    $current_country = 'SI';
}
else
{
    $locale_info = explode('_', $_GET['locale']);
    $selected_language = $locale_info[0];
    $current_country = $locale_info[1];
}

$project_id = 1;

$security_token = 'iosloginregister';
$_SESSION['security_token'] = $security_token;

class iOScommand
{
    public static function check_post($param)
    {
        if (isset($_POST[$param]))
        {
            return sanitize($_POST[$param]);
        }
        else
        {
            return '';
        }
    }
    
    public static function check_get($param)
    {
        if (isset($_GET[$param]))
        {
            return sanitize($_GET[$param]);
        }
        else
        {
            return '';
        }
    }
    
    public static function loginUser()
    {
        global $selected_language, $security_token;
        
        $email = self::check_post('email');
        $password = self::check_post('password');
        //$security_token = self::check_post('security_token');
        $remember_me = 'N';
        
        $login_user = new Users;
        try
        {
            $result = $login_user->login_user($email, $password, $security_token, $remember_me);
            echo $result;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    public static function loginFacebookUser()
    {
        $access_token = self::check_post('access_token');
        
        $user = new Users;
        $message = $user->ios_fb_login($access_token);
        
        echo json_encode($message);
    }
    
    public static function registerUser()
    {
        global $selected_language, $security_token;
        
        $email = self::check_post('email');
        $password = self::check_post('password');
        $password_reentered = self::check_post('password_reentered');
        //$security_token = self::check_post('security_token');
        $register_agree = self::check_post('register_agree');
        
        $new_user = new Users;
        try 
        {
            $result = $new_user->register_user($email, $password, $password_reentered, $security_token, $register_agree);
            echo $result;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    public static function resetPasswordForUser()
    {
        global $selected_language, $security_token;
        
        $email = self::check_post('email');
        //$security_token = self::check_post('security_token');
        
        $forgotten_password_user = new Users;
        try
        {
            $result = $forgotten_password_user->forgotten_password_user($email, $security_token);
            echo $result;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    public static function changePasswordForUser()
    {
        global $selected_language, $security_token;
        
        //$email = self::check_post('email');
        $old_password = self::check_post('old_password');
        $new_password = self::check_post('new_password');
        $new_password_re = self::check_post('new_password_repeat');
        //$security_token = self::check_post('security_token');
        
        $email = sanitize($_POST['authentication_token']['email']);
        $security_token = sanitize($_POST['authentication_token']['security_token']);
        
        $change_user = new Users;
        try
        {
            $result = $change_user->change_password_user_ios($email, $old_password, $new_password, $new_password_re, $security_token);
            echo $result;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    public static function getUserInfo()
    {
        //$email = self::check_post('email');
        //$security_token = self::check_post('security_token');
        /*
        $info = json_decode($_POST['authentication_token'], true);
        $email = $info['email'];
        $security_token = $info['security_token'];
        */
        $email = sanitize($_POST['authentication_token']['email']);
        $security_token = sanitize($_POST['authentication_token']['security_token']);
        //echo json_encode($_POST);
        
        //print_r($_POST['authentication_token']);
        
        //echo json_encode($_POST['authentication_token']);
        /*
        $auth_token = json_decode($_POST['authentication_token'], true);
        error_log($_POST['authentication_token'], 0);
        $email = $auth_token['email'];
        $security_token = $auth_token['security_token'];*/
        
        echo Users::get_user_info_ios($email, $security_token);
    }
    
    public static function getAllCategories()
    {
        global $selected_language, $current_country, $project_id;
        
        echo json_encode(Offer::get_all_offer_categories(1));
    }
    
    public static function getOffersForCategory($cat_id)
    {
        global $selected_language, $current_country, $project_id;
        
        //echo json_encode(Offer::category_www_get_by_id($cat_id));
        echo json_encode(Offer::offer_get_by_category_ios($cat_id));
    }
    
    public static function getOfferDetails($offer_id)
    {
        global $selected_language, $current_country, $project_id;
        
        echo json_encode(Offer::offer_www_get_by_id($offer_id));
    }
    
    public static function getHomeScreenOffers()
    {
        echo json_encode(Offer::get_first_page_offers());
    }
    
    public static function useVoucher()
    {
        global $salt;
        
        //$email = self::check_post('email');
        //$security_token = self::check_post('security_token');
        
        $email = sanitize($_POST['authentication_token']['email']);
        $security_token = sanitize($_POST['authentication_token']['security_token']);
        
        $offer_id = self::check_post('offer_id');
        
        if (sha1($email . $salt) == $security_token && is_numeric($offer_id))
        {
            $offer_info = Offer::offer_www_get_by_id($offer_id);
            
            $user = new Users;
            $check_user = new Xdb;
            $check_user_exists = $check_user->select_fields('personal_stats, subscription_saved_total, subscription_offers_total')
                                            ->set_table($user->table)
                                            ->where(array('email' => $email))
                                            ->db_select(false);
            
            $date_time = new DateTime();
            $today = $date_time->format('Y-m-d');
            
            $personal_stats = json_decode($check_user_exists[0]['personal_stats'], true);
            
            if (isset($personal_stats[$today]))
            {
                $personal_stats[$today][$offer_id] = array('original_price' => $offer_info['prices']['original_price'], 'discount_price' => $offer_info['prices']['discount_price'], 'discount' => $offer_info['prices']['discount']);
            }
            else
            {
                $personal_stats[$today][$offer_id] = array($today => array($offer_id => array('original_price' => $offer_info['prices']['original_price'], 'discount_price' => $offer_info['prices']['discount_price'], 'discount' => $offer_info['prices']['discount'])));
            }
            
            $subscription_saved_total = $check_user_exists[0]['subscription_saved_total'] + ($offer_info['prices']['original_price'] - $offer_info['prices']['discount_price']);
            $subscription_offers_total = $check_user_exists[0]['subscription_offers_total'] + 1;
            
            $update_user_data = new Xdb;
            $update = $update_user_data->set_table($user->table)
                                       ->simple_update_fields(array('subscription_saved_total' => $subscription_saved_total, 'subscription_offers_total' => $subscription_offers_total, 'personal_stats' => json_encode($personal_stats)))
                                       ->where(array('email' => $email))
                                       ->db_update();
        }
    }
    
    public static function getNearbyOffers($lat, $lng)
    {
        //$lat = self::check_get();
        global $salt;
        
        //$email = self::check_post('email');
        //$security_token = self::check_post('security_token');
        
        $email = sanitize($_POST['authentication_token']['email']);
        $security_token = sanitize($_POST['authentication_token']['security_token']);
        
        //if (sha1($email . $salt) == $security_token && isset($_GET['param']) && is_array($_GET['param']))
        if (sha1($email . $salt) == $security_token)
        {
            echo json_encode(Offer::get_nearby_offers(sanitize($lat), sanitize($lng)));
        }
    }
    
    public static function searchOffers()
    {
        $search_string = sanitize($_POST['search_string']);
        
        echo json_encode(Offer::offers_www_search($search_string));
    }
    
    public static function sendInfoEmail()
    {
        $email = sanitize($_POST['authentication_token']['email']);
        
        $mailer = new xMailer;
        $mailer_result = $mailer->send_mail('IOS purchase information', $email);
    }
}

if (isset($_GET['func']))
{
    if (isset($_GET['param']))
    {
        if (is_array($_GET['param']))
        {
            call_user_func_array(array('iOScommand', $_GET['func']), $_GET['param']);
        }
        else
        {
            call_user_func_array(array('iOScommand', $_GET['func']), array($_GET['param']));
        }
    }
    else
    {
        call_user_func(array('iOScommand', $_GET['func']));
    }
}

xMemcache::write_memcache_settings();

?>