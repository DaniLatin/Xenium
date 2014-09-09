<?php

/**
 * @author Danijel
 * @copyright 2013
 */

require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/admin/theme/js/jAPI-CORE.php');

if (!isset($_GET['pass']) || $_GET['pass'] != 'avant')
{
    if (!isset($_SESSION['security_token'])) die();
    
    if (isset($_SERVER['HTTP_REFERER']))
    {
        $referrer = $_SERVER['HTTP_REFERER'];
        $referrer_explode = explode('/', str_replace('http://', '', $referrer));
        $referrer_domain = $referrer_explode[0];
        //$project_id = Projects::get_project_id();
        $project_info = Projects::get_domain_project_info();
        $project_id = $project_info['project_id'];
        $project_slug = $project_info['project_slug'];
        $project_name = $project_info['project_name'];
        Domains::check_referrer_domain($project_id, $referrer_domain);
    }
    else
    {
        die();
    }
}

if (isset($_COOKIE['language']))
{
    $language_selected = $_COOKIE['language'];
}
else
{
    $language_selected = $_GET['language'];
}
$language_id = Languages::get_language_id($language_selected);
$current_country = Countries::get_country_by_language($language_id);

use UnitedPrototype\GoogleAnalytics as GoogleAnalytics;

class AjaxCommand
{
    public function interval_function()
    {
        $combine_message = '';
        
        $project_info = Projects::get_domain_project_info();
        $project_id = $project_info['project_id'];
        $project_slug = $project_info['project_slug'];
        
        $domains = Domains::get_domains_by_project($project_id);
        foreach ($domains as $domain_key => $domain)
        {
            if ($domain != $_SERVER['HTTP_HOST'])
            {
                unset($domains[$domain_key]);
                $combine_message .= '<img class="cross-img" src="http://' . $domain . '/projects/' . $project_slug . '/commands/commands.crossdomain.php" width="1" height="1" style="display: none;" />';
            }
        }
        //return true;
        echo $combine_message;
    }
    
    public function user_registration($email, $password, $password_reentered, $security_token, $register_agree)
    {
        $new_user = new Users;
        try 
        {
            $result = $new_user->register_user($email, $password, $password_reentered, trim($security_token), $register_agree);
            echo $result;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    public function user_login($email, $password, $security_token, $remember_me)
    {
        $login_user = new Users;
        try
        {
            $result = $login_user->login_user($email, $password, trim($security_token), $remember_me);
            echo $result;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    public function user_forgotten_password($email, $security_token)
    {
        $forgotten_password_user = new Users;
        try
        {
            $result = $forgotten_password_user->forgotten_password_user($email, trim($security_token));
            echo $result;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    public function user_change_password($old_password, $new_password, $new_password_re, $security_token)
    {
        $change_user = new Users;
        try
        {
            $result = $change_user->change_password_user($old_password, $new_password, $new_password_re, trim($security_token));
            echo $result;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    public function add_voucher()
    {
        $subscription = new Subscription;
        try
        {
            $result = $subscription->check_user_subscription();
            echo $result;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    public function set_package($package_name)
    {
        global $project_id;
        $subscription = new Subscription;
        try
        {
            $result = $subscription->set_subscription_package($package_name, $project_id);
            echo $result;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    public function is_offer_used_today($offer_id)
    {
        $now = new DateTime();
        $today = $now->format('Y-m-d');
        
        if (isset($_SESSION['logged_in_user']['personal_stats'][$today][$offer_id]))
        {
            echo 'true';
        }
        else
        {
            echo 'false';
        }
    }
    
    public function subscription_add_offer_to_cart($object_id, $object_view_title, $object_price, $object_discount_price, $object_url, $object_savings, $total_savings)
    {
        if (isset($_SESSION['logged_in_user']))
        {
            $object = array('id' => $object_id, 'view_title' => $object_view_title, 'price' => $object_price, 'discount_price' => $object_discount_price, 'url' => $object_url, 'savings' => $object_savings);
            $_SESSION['logged_in_user']['cart_vouchers']['objects'][$object_id] = $object;
            //$_SESSION['logged_in_user']['cart_vouchers']['total_savings'] = $total_savings;
            $_SESSION['logged_in_user']['cart_vouchers']['total_savings'] = $object_savings;
            $_SESSION['logged_in_user']['subscription_saved_total'] = $total_savings;
            
            //self::event_tracking('Kuponi', 'Dodaj v kosarico', $object_view_title, $object_discount_price);
        }
    }
    
    public function subscription_delete_offer_from_cart($object_id, $total_savings)
    {
        if (isset($_SESSION['logged_in_user']))
        {
            unset($_SESSION['logged_in_user']['cart_vouchers']['objects'][$object_id]);
            $_SESSION['logged_in_user']['cart_vouchers']['total_savings'] = $total_savings;
        }
    }
    
    public function set_payment($payment_name)
    {
        global $project_id;
        $subscription = new Subscription;
        try
        {
            $result = $subscription->set_payment($payment_name);
            echo $result;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    public function go_to_step_two()
    {
        $user_logged_in = Users::is_user_logged_in();
        $user_filled_all_data = Users::user_filled_all_data();
        $language_selected = $_COOKIE['language'];
        if ($user_logged_in){
            if (isset($_SESSION['logged_in_user']['selected_package']) && $user_filled_all_data)
            {
                $message = array('result' => 1, 'redirect' => 'http://' . $_SERVER['HTTP_HOST'] . '/' . $language_selected . '/subscription/payment/');
                echo json_encode($message);
            }
            elseif(isset($_SESSION['logged_in_user']['selected_package']) && !$user_filled_all_data)
            {
                $message = array('result' => 1, 'redirect' => 'http://' . $_SERVER['HTTP_HOST'] . '/' . $language_selected . '/subscription/additional-data/');
                echo json_encode($message);
            }
            elseif(!isset($_SESSION['logged_in_user']['selected_package']))
            {
                $message = array('result' => 0, 'error' => 'no package selected');
                echo json_encode($message);
            }
        }
        else
        {
            $message = array('result' => 0, 'error' => 'not_logged_in');
            echo json_encode($message);
        }
    }
    
    public function go_to_step_three($gender, $name, $surname, $address, $city, $security_token, $birth_day, $birth_month, $birth_year)
    {
        $selected_language = $_COOKIE['language'];
        
        if (($gender && $gender != 'undefined') && ($name && $name != 'undefined') && ($surname && $surname != 'undefined') && ($address && $address != 'undefined') && ($city && $city != 'undefined') && ($birth_day && $birth_day != 'undefined') && ($birth_month && $birth_month != 'undefined') && ($birth_year && $birth_year != 'undefined'))
        {
            $update_user = new Users;
            try
            {
                $result = $update_user->payment_update_user($gender, $name, $surname, $address, $city, trim($security_token), $birth_day, $birth_month, $birth_year);
                echo $result;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }
        else
        {
            $message = array('result' => 0, 'error' => 'missing data', 'token' => Users::get_security_token(), 'show_message' => translate('Please fill all fields.'));
            echo json_encode($message);
        }
    }
    
    public function go_to_step_four()
    {
        $selected_language = $_COOKIE['language'];
        $message = array('result' => 1, 'redirect' => 'http://' . $_SERVER['HTTP_HOST'] . '/' . $selected_language . '/subscription/summary/');
        echo json_encode($message);
    }
    
    public function go_to_payment($payment_name)
    {
        global $selected_language, $current_country;
        //$selected_language = $_COOKIE['language'];
        
        $pay_subscription = new Subscription;
        $payment = new Payment;
        try
        {
            //$result = $pay_subscription->set_payment($payment_name);
            $result = $payment->go_to_payment();
            echo $result;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    public function event_tracking($category, $action, $label, $value)
    {
        
        $tracker = new GoogleAnalytics\Tracker('MO-52191711-1', 'xenium.org');
        
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
        $event->setCategory($category);    //string, required
        $event->setAction($action);        //string, required
        $event->setLabel($label);          //string, not required
        $event->setNoninteraction('true');
        
        if (isset($value) && $value)
        {
            $event->setValue($value);  
        }
        
        //track event
        $tracker->trackEvent($event,$session,$visitor);
    }
    
    public function pageview_tracking($request_uri, $page_title, $referer)
    {
        global $encryption_key, $salt, $project_id, $project_slug;
        
        $tracker = new GoogleAnalytics\Tracker('MO-52191711-1', 'xenium.org');
        
        // Assemble Visitor information
        // (could also get unserialized from database)
        
        //$visitor = new GoogleAnalytics\Visitor();
        /*
        if( !isset($_SESSION['SSGA_UniqueID3']) || !isset($_SESSION['SSGA_visitor']) )
        {
            $visitor = new GoogleAnalytics\Visitor();
            $_SESSION['SSGA_UniqueID3'] = sha1(rand(10000000,20000000) . rand(10000000,20000000));
        }
        else
        {
            $visitor = unserialize($_SESSION['SSGA_visitor']);
        }
        */
        
        $crossdomain_data = array();
        
        if(  !isset($_COOKIE['SSGA_UniqueID3']) || !isset($_COOKIE['SSGA_visitor']) )
        {
            $visitor = new GoogleAnalytics\Visitor();
            //$unique_id = sha1(rand(10000000,20000000) . rand(10000000,20000000));
            $unique_id = rand(10000000,2147483647);
            $_SESSION['SSGA_UniqueID3'] = $unique_id;
        }
        else
        {
            $visitor = unserialize($_COOKIE['SSGA_visitor']);
            $unique_id = $_COOKIE['SSGA_UniqueID3'];
        }
        
        $crossdomain_data['SSGA_UniqueID3'] = 'cookie:::' . $unique_id;
        $crossdomain_data['SSGA_visitor'] = 'cookie:::' . serialize($visitor);
        
        //$visitor->setUniqueId($_SESSION['SSGA_UniqueID3']);
        $visitor->setUniqueId($unique_id);
        $visitor->setIpAddress($_SERVER['REMOTE_ADDR']);
        if (isset($_SERVER['HTTP_USER_AGENT']))
        {
            $visitor->setUserAgent($_SERVER['HTTP_USER_AGENT']);
        }
        $visitor->setScreenResolution('1024x768');
        
        // Assemble Session information
        // (could also get unserialized from PHP session)
        if (isset($_SESSION['SSGA_session']))
        {
            $session = unserialize($_SESSION['SSGA_session']);
        }
        else
        {
            $session = new GoogleAnalytics\Session();
            $_SESSION['SSGA_session'] = serialize($session);
        }
        /*
        $crossdomain_data['SSGA_session'] = 'session:::' . serialize($session);
        
        $generate_cross_key = '';
        foreach ($crossdomain_data as $data_key => $data_string)
        {
            $generate_cross_key .= $data_key . $data_string;
        }
        $generate_cross_key .= $salt . $encryption_key;
        
        $crossdomain_data['key'] = sha1($generate_cross_key);
        */
        
        $visitor->addSession($session);
        
        $host = $_SERVER['HTTP_HOST'];
        preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
        if (isset($matches[0]))
        {
            $domain_name = $matches[0];
            setcookie("SSGA_visitor", serialize($visitor), time()+3600*24*356, "/", $domain_name);
            setcookie("SSGA_UniqueID3", $unique_id, time()+3600*24*356, "/", $domain_name);
        }
        else
        {
            setcookie("SSGA_visitor", serialize($visitor), time()+3600*24*356, "/");
            setcookie("SSGA_UniqueID3", $unique_id, time()+3600*24*356, "/");
        }
        /*
        $crossdomain_query = '?';
        foreach ($crossdomain_data as $cross_key => $cross_data)
        {
            if ($crossdomain_query == '?')
            {
                $crossdomain_query .= $cross_key . '=' . urlencode($cross_data);
            }
            else
            {
                $crossdomain_query .= '&' . $cross_key . '=' . urlencode($cross_data);
            }
        }
        
        $crossdomain_images = '';
        
        $domains = Domains::get_domains_by_project($project_id);
        foreach ($domains as $domain_key => $domain)
        {
            if ($domain == $_SERVER['HTTP_HOST'])
            {
                unset($domains[$domain_key]);
            }
            else
            {
                $crossdomain_images .= '<img src="http://' . $domain . '/projects/' . $project_slug . '/commands/commands.crossdomain.light.php' . $crossdomain_query . '" width="1" height="1" style="display: none;" />';
            }
        }
        
        */
        
        
        // Assemble Page information
        //if (isset($_SERVER['REQUEST_URI'])) $request_uri = $_SERVER['REQUEST_URI']; else $request_uri = '/'; 
        //echo $request_uri;
        $page = new GoogleAnalytics\Page($request_uri);
        $page->setTitle($page_title);
        $page->setReferrer($referer);
        
        // Track page view
        $tracker->trackPageview($page, $session, $visitor);
        
        
        //echo $crossdomain_images;
    }
    
    public function user_logout()
    {
        $logout_user = new Users;
        $result = $logout_user->logout_user();
        echo $result;
    }
}

new jAPIBaseClass('AjaxCommand');
xMemcache::write_memcache_settings();

?>