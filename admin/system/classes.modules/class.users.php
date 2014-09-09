<?php

/**
 * @author Danijel
 * @copyright 2013
 */

use UnitedPrototype\GoogleAnalytics as GoogleAnalytics;

class Users extends Module
{
    public $table;
    public $fields = array();
    public $session_fields;
    
    public function __construct()
    {
        $this->table = 'www_users';
        
        $this->session_fields = 'id, email, name, surname, activated, personal_stats';
        
        $this->fields = 
        array(
        'static_fields' => 
            array(
                'email' => array('value' => '', 'field_type' => 'VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'password' => array('value' => '', 'field_type' => 'VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'name' => array('value' => '', 'field_type' => 'VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'surname' => array('value' => '', 'field_type' => 'VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'birth_date' => array('value' => '', 'field_type' => 'DATE NOT NULL', 'index' => true, 'admin_editable' => false),
                'country' => array('value' => '', 'field_type' => 'VARCHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'language' => array('value' => '', 'field_type' => 'VARCHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'post_number' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'city' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'address' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'gender' => array('value' => '', 'field_type' => 'VARCHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'project_id' => array('value' => '', 'field_type' => 'INT( 1 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'activated' => array('value' => '', 'field_type' => 'INT( 1 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'activation_id' => array('value' => '', 'field_type' => 'VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'datetime_registration' => array('value' => '', 'field_type' => 'DATETIME NOT NULL', 'index' => true, 'admin_editable' => false),
                'datetime_activation' => array('value' => '', 'field_type' => 'DATETIME NOT NULL', 'index' => true, 'admin_editable' => false),
                'datetime_last_change' => array('value' => '', 'field_type' => 'DATETIME NOT NULL', 'index' => true, 'admin_editable' => false),
                'datetime_last_syscheck' => array('value' => '', 'field_type' => 'DATETIME NOT NULL', 'index' => true, 'admin_editable' => false),
                'system' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
                'trashed' => array('value' => '', 'field_type' => 'INT( 1 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                // Facebook
                'fb_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'fb_profile_url' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
                'fb_photo_url' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
                'fb_datetime_registration' => array('value' => '', 'field_type' => 'DATETIME NOT NULL', 'index' => true, 'admin_editable' => false),
                // Google
                'google_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'google_profile_url' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
                'google_photo_url' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
                'google_datetime_registration' => array('value' => '', 'field_type' => 'DATETIME NOT NULL', 'index' => true, 'admin_editable' => false),
                'personal_stats' => array(
                    'value' => '[]', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
            )
        );
        
        $this->load_plugins();
    }
    
    public function get_security_token()
    {
        global $salt;
        $token = sha1($salt . '_' . rand() . '-' . rand() . '-' . rand());
        $_SESSION['security_token'] = $token;
        return $token;
    }
    
    public function register_user($email, $password, $password_reentered, $security_token, $register_agree)
    {
        global $salt, $selected_language;
        if (!isset($selected_language) || !$selected_language)
        {
            $selected_language = $_COOKIE['language'];
        }
        $project_id = Projects::get_project_id();
        
        $message = array();
        
        if ($email && $password && $password_reentered)
        {
            if ($security_token != $_SESSION['security_token'] || !isset($_SESSION['security_token']))
            {
                $message = array('result' => 0, 'error' => 'unauthorized session', 'token' => self::get_security_token(), 'show_message' => translate('Your session is not authorized.'));
                throw new Exception(json_encode($message));
            }
            else
            {
                if ($register_agree != 'Y')
                {
                    $message = array('result' => 0, 'error' => 'must agree', 'token' => self::get_security_token(), 'show_message' => translate('Your must agree to our terms and conditions.'));
                    die(json_encode($message));
                }
                
                if (trim($password != $password_reentered))
                {
                    $message = array('result' => 0, 'error' => 'passwords do not match', 'token' => self::get_security_token(), 'show_message' => translate('The passwords you entered do not match.'));
                    throw new Exception(json_encode($message));
                }
                else
                {
                    $email = trim($email);
                    $send_password = trim($password);
                    $password = sha1($salt . trim($password));
                    
                    if (filter_var($email, FILTER_VALIDATE_EMAIL))
                    {
                        // all data provided, email is valid
                        $check_email = new Xdb;
                        $check_email_exists = $check_email->select_fields('COUNT(email)')
                                                          ->set_table($this->table)
                                                          ->where(array('email' => $email))
                                                          ->db_select(false);
                        
                        $email_exists = $check_email_exists[0]['COUNT(email)'];
                        
                        if (!$email_exists)
                        {
                            $this->fields['static_fields']['email']['value'] = $email;
                            $this->fields['static_fields']['password']['value'] = $password;
                            $this->fields['static_fields']['project_id']['value'] = $project_id;
                            
                            $language_id = Languages::get_language_id($selected_language);
                            $current_country = Countries::get_country_by_language($language_id);
                            $this->fields['static_fields']['country']['value'] = $current_country;
                            $this->fields['static_fields']['language']['value'] = $selected_language;
                    
                            $bc = new Browscap($_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.third.party/browscap.cache/');
                            $current_browser = $bc->getBrowser(null, true);
                            $this->fields['static_fields']['system']['value'] = json_encode($current_browser);
                            
                            $date_time = new DateTime();
                            $save_date_time = $date_time->format('Y-m-d H:i:s');
                            
                            $this->fields['static_fields']['datetime_registration']['value'] = $save_date_time;
                            $this->fields['static_fields']['datetime_last_change']['value'] = $save_date_time;
                            $this->fields['static_fields']['datetime_last_syscheck']['value'] = $save_date_time;
                            
                            $activation_id = sha1($email . $password . $project_id . $save_date_time);
                            $activation_url = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $selected_language . '/activation_' . $activation_id . '/';
                            $this->fields['static_fields']['activation_id']['value'] = $activation_id;
                            
                            $mailer = new xMailer;
                            $mailer_result = $mailer->send_mail('Registration activation email', $email, array('*user_email*' => $email, '*user_password*' => $send_password, '*activation_id*' => $activation_id, '*activation_url*' => '<a href="' . $activation_url . '">' . $activation_url . '</a>'));
                            
                            if ($mailer_result)
                            {
                                $xdb_user_insert = new Xdb;
                                $last_user_id = $xdb_user_insert->db_insert($this->table, $this->fields);
                                //$message = array('result' => 1, 'show_message' => translate('Your registration was successfull. Please check your email for further instructions.'));
                                $message = array('result' => 1);
                                self::get_security_token();
                                
                                //Stats::write_stats('Users', 'registered');
                                //Stats::write_stats('Users', 'country_' . $current_country);
                                
                                Stats::write_stats('Users', array('registered', 'country_registered_' . $current_country));
                                
                                // start GA tracking
                                $tracker = new GoogleAnalytics\Tracker('MO-42907887-1', 'avantbon.si');
                                
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
                                $event->setCategory('Uporabnik');    //string, required
                                $event->setAction('Registracija');        //string, required
                                $event->setLabel($last_user_id);          //string, not required
                                $event->setNoninteraction('true');
                                //$event->setValue($offer['view_discount_price']);  
                            
                                //track event
                                $tracker->trackEvent($event,$session,$visitor);
                                // end GA tracker
                                
                                return json_encode($message);
                            }
                            else
                            {
                                $message = array('result' => 0, 'error' => 'mail send error', 'token' => self::get_security_token(), 'show_message' => translate('Error when sending email. Please try again later.'));
                                throw new Exception(json_encode($message));
                            }
                        }
                        else
                        {
                            $message = array('result' => 0, 'error' => 'email exists', 'token' => self::get_security_token(), 'show_message' => translate('Your email is already registered.'));
                            throw new Exception(json_encode($message));
                        }
                    }
                    else
                    {
                        $message = array('result' => 0, 'error' => 'invalid email', 'token' => self::get_security_token(), 'show_message' => translate('You used an invalid email.'));
                        throw new Exception(json_encode($message));
                    }
                }
            }
        }
        else
        {
            $message = array('result' => 0, 'error' => 'missing data', 'token' => self::get_security_token(), 'show_message' => translate('Please fill all fields.'));
            throw new Exception(json_encode($message));
        }
    }
    
    public function activate_user($activation_id)
    {
        global $salt, $encryption_key, $selected_language;
        
        $check_user = new Xdb;
        $check_user_exists = $check_user->select_fields($this->session_fields)
                                        ->set_table($this->table)
                                        ->where(array('activation_id' => $activation_id))
                                        ->db_select(false);
        
        if ($check_user_exists && is_array($check_user_exists))
        {
            $user_session = $check_user_exists[0];
            
            if ($user_session['activated'] == 0)
            {
                $user_session['activated'] = 1;
                $_SESSION['logged_in_user'] = $user_session;
                
                $host = $_SERVER['HTTP_HOST'];
                preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
                if (isset($matches[0]))
                {
                    $domain_name = $matches[0];
                    setcookie("user_registered", 'true', time()+3600*24*356, "/", $domain_name);
                }
                else
                {
                    setcookie("user_registered", 'true', time()+3600*24*356, "/");
                }
                
                
                $date_time = new DateTime();
                $save_date_time = $date_time->format('Y-m-d H:i:s');
                
                $update_user_activated = new Xdb;
                $update = $update_user_activated->set_table($this->table)
                                                ->simple_update_fields(array('activated' => 1, 'datetime_activation' => $save_date_time))
                                                ->where(array('activation_id' => $activation_id))
                                                ->db_update();
                
                // crossdomain login
                
                $project_info = Projects::get_domain_project_info();
                $project_id = $project_info['id'];
                $project_slug = $project_info['project_slug'];
                
                $domains = Domains::get_domains_by_project($project_id);
                foreach ($domains as $domain_key => $domain)
                {
                    if ($domain == $_SERVER['HTTP_HOST'])
                    {
                        unset($domains[$domain_key]);
                    }
                }
                
                $session_fields = $this->session_fields;
                $session_fields_array = explode(', ', $session_fields);
        
                $create_session = array();
                
                $crossdomain_login_query = '?';
                $crossdomain_login_hash = '';
                
                foreach ($session_fields_array as $field)
                {
                    if ($field != 'personal_stats')
                    {
                        $create_session[$field] = $user_session[$field];
                        
                        if ($crossdomain_login_query == '?')
                        {
                            $crossdomain_login_query .= $field . '=' . $user_session[$field];
                        }
                        else
                        {
                            $crossdomain_login_query .= '&' . $field . '=' . $user_session[$field];
                        }
                        
                        $crossdomain_login_hash .= $field . $user_session[$field];
                    }
                }
                
                $crossdomain_login_hash .= $salt . $encryption_key;
                $crossdomain_login_hashed = sha1($crossdomain_login_hash);
                $crossdomain_login_query .= '&key=' . $crossdomain_login_hashed;
                
                $combine_message = '';
                foreach ($domains as $domain)
                {
                    $combine_message .= '<img src="http://' . $domain . '/projects/' . $project_slug . '/commands/commands.crossdomain.php' . $crossdomain_login_query . '" width="1" height="1" style="display: none;" />';
                }
                
                // end crossdomain login
                
                //Stats::write_stats('Users', 'activated');
                $language_id = Languages::get_language_id($selected_language);
                $current_country = Countries::get_country_by_language($language_id);
                
                Stats::write_stats('Users', array('activated', 'country_activated_' . $current_country));
                
                //return true;
                return $combine_message;
            }
            else
            {
                return false;
            }
        }
    }
    
    public function admin_activate_user($user_id)
    {
        $user = new Users;
        
        $date_time = new DateTime();
        $save_date_time = $date_time->format('Y-m-d H:i:s');
        $save_date_time_view = $date_time->format('d.m.Y H:i:s');
        
        $update_user_activated = new Xdb;
        $update = $update_user_activated->set_table($user->table)
                                        ->simple_update_fields(array('activated' => 1, 'datetime_activation' => $save_date_time))
                                        ->where(array('id' => $user_id))
                                        ->db_update();
        return $save_date_time_view;
    }
    
    public function forgotten_password_user($email, $security_token)
    {
        global $salt, $selected_language;
        if (!isset($selected_language) || !$selected_language)
        {
            $selected_language = $_COOKIE['language'];
        }
        $project_id = Projects::get_project_id();
        
        $message = array();
        
        if ($email)
        {
            if ($security_token != $_SESSION['security_token'] || !isset($_SESSION['security_token']))
            {
                $message = array('result' => 0, 'error' => 'unauthorized session', 'token' => self::get_security_token(), 'show_message' => translate('Your session is not authorized.'));
                throw new Exception(json_encode($message));
            }
            else
            {
                $email = trim($email);
                
                if (filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $check_user = new Xdb;
                    $check_user_exists = $check_user->select_fields('id, email')
                                                    ->set_table($this->table)
                                                    ->where(array('email' => $email))
                                                    ->db_select(false);
                    
                    if ($check_user_exists && is_array($check_user_exists) && isset($check_user_exists[0]))
                    {
                        $new_password = randomPassword();
                        $new_hashed_password = sha1($salt . $new_password);
                        
                        $user_id = $check_user_exists[0]['id'];
                        
                        
                        
                        $mailer = new xMailer;
                        $mailer_result = $mailer->send_mail('Forgotten password email', $email, array('*new_password*' => $new_password));
                        
                        if ($mailer_result)
                        {
                            $date_time = new DateTime();
                            $save_date_time = $date_time->format('Y-m-d H:i:s');
                            
                            $update_user_password = new Xdb;
                            $update = $update_user_password->set_table($this->table)
                                                           ->simple_update_fields(array('password' => $new_hashed_password, 'datetime_last_change' => $save_date_time))
                                                           ->where(array('id' => $user_id))
                                                           ->db_update();
                            
                            $message = array('result' => 1, 'token' => self::get_security_token(), 'show_message' => translate('The new password was successfully sent to your email.'));
                            //self::get_security_token();
                            return json_encode($message);
                        }
                        else
                        {
                            $message = array('result' => 0, 'error' => 'mail send error', 'token' => self::get_security_token(), 'show_message' => translate('Error when sending email. Please try again later.'));
                            throw new Exception(json_encode($message));
                        }
                    }
                    else
                    {
                        $message = array('result' => 0, 'error' => 'no result', 'token' => self::get_security_token(), 'show_message' => translate('There is no such email in our database.'));
                        throw new Exception(json_encode($message));
                    }
                }
                else
                {
                    $message = array('result' => 0, 'error' => 'invalid email', 'token' => self::get_security_token(), 'show_message' => translate('You used an invalid email.'));
                    throw new Exception(json_encode($message));
                }
            }
        }
        else
        {
            $message = array('result' => 0, 'error' => 'missing data', 'token' => self::get_security_token(), 'show_message' => translate('Please fill all fields.'));
            throw new Exception(json_encode($message));
        }
    }
    
    public function login_user($email, $password, $security_token, $remember_me)
    {
        global $salt, $encryption_key, $selected_language;
        if (!isset($selected_language) || !$selected_language)
        {
            $selected_language = $_COOKIE['language'];
        }
        $project_id = Projects::get_project_id();
        
        $message = array();
        
        if ($email && $password)
        {
            if ($security_token != $_SESSION['security_token'] || !isset($_SESSION['security_token']))
            {
                $message = array('result' => 0, 'error' => 'unauthorized session', 'token' => self::get_security_token(), 'show_message' => translate('Your session is not authorized.'));
                throw new Exception(json_encode($message));
            }
            else
            {
                $email = trim($email);
                $password = sha1($salt . trim($password));
                
                if (filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $check_user = new Xdb;
                    $check_user_exists = $check_user->select_fields($this->session_fields)
                                                    ->set_table($this->table)
                                                    ->where(array('email' => $email, 'password' => $password))
                                                    ->db_select(false);
                    
                    if ($check_user_exists && is_array($check_user_exists) && isset($check_user_exists[0]))
                    {
                        $user_session = $check_user_exists[0];
                        
                        if ($user_session['activated'])
                        {
                            $personal_stats = json_decode($user_session['personal_stats'], true);
                            $today = new DateTime();
                            $today_date = $today->format('Y-m-d');
                            if (isset($personal_stats[$today_date]))
                            {
                                unset($user_session['personal_stats']);
                                $user_session['personal_stats'][$today_date] = $personal_stats[$today_date];
                            }
                            else
                            {
                                unset($user_session['personal_stats']);
                            }
                            
                            $_SESSION['logged_in_user'] = $user_session;
                            
                            // crossdomain login
                            
                            $project_info = Projects::get_domain_project_info();
                            $project_slug = $project_info['project_slug'];
                            
                            $domains = Domains::get_domains_by_project($project_id);
                            foreach ($domains as $domain_key => $domain)
                            {
                                if ($domain == $_SERVER['HTTP_HOST'])
                                {
                                    unset($domains[$domain_key]);
                                }
                            }
                            
                            $session_fields = $this->session_fields;
                            $session_fields_array = explode(', ', $session_fields);
                    
                            $create_session = array();
                            
                            $crossdomain_login_query = '?';
                            $crossdomain_login_hash = '';
                            
                            foreach ($session_fields_array as $field)
                            {
                                if ($field != 'personal_stats')
                                {
                                    $create_session[$field] = $user_session[$field];
                                    
                                    if ($crossdomain_login_query == '?')
                                    {
                                        $crossdomain_login_query .= $field . '=' . $user_session[$field];
                                    }
                                    else
                                    {
                                        $crossdomain_login_query .= '&' . $field . '=' . $user_session[$field];
                                    }
                                    
                                    $crossdomain_login_hash .= $field . $user_session[$field];
                                }
                            }
                            
                            $crossdomain_login_hash .= $salt . $encryption_key;
                            $crossdomain_login_hashed = sha1($crossdomain_login_hash);
                            $crossdomain_login_query .= '&key=' . $crossdomain_login_hashed;
                            
                            $combine_message = '';
                            foreach ($domains as $domain)
                            {
                                $combine_message .= '<img src="http://' . $domain . '/projects/' . $project_slug . '/commands/commands.crossdomain.php' . $crossdomain_login_query . '" width="1" height="1" style="display: none;" />';
                            }
                            
                            // end crossdomain login
                            
                            //$message = array('result' => 1, 'show_message' => translate('Your registration was successfull. Please check your email for further instructions.'));
                            $message = array('result' => 1, 'show_message' => $combine_message, 'authentication_token' => array('email' => $email, 'security_token' => sha1($email . $salt)));
                            
                            $host = $_SERVER['HTTP_HOST'];
                            preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
                            if (isset($matches[0]))
                            {
                                $domain_name = $matches[0];
                                setcookie("user_registered", 'true', time()+3600*24*356, "/", $domain_name);
                            }
                            else
                            {
                                setcookie("user_registered", 'true', time()+3600*24*356, "/");
                            }
                            
                            if ($remember_me == 'Y')
                            {
                                $user_remember['email'] = $user_session['email'];
                                $user_remember['password'] = $password;
                                $user_remember_string = $user_session['email'] . '<===>' . $password;
                                
                                $host = $_SERVER['HTTP_HOST'];
                                preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
                                if (isset($matches[0]))
                                {
                                    $domain_name = $matches[0];
                                    setcookie("user_remember", encrypt($user_remember_string), time()+3600*24*356, "/", $domain_name);
                                }
                                else
                                {
                                    setcookie("user_remember", encrypt($user_remember_string), time()+3600*24*356, "/");
                                }
                            }
                            
                            return json_encode($message);
                        }
                        else
                        {
                            $message = array('result' => 0, 'error' => 'not activated', 'token' => self::get_security_token(), 'show_message' => translate('Your email address is not yet activated.'));
                            throw new Exception(json_encode($message));
                        }
                        
                    }
                    else
                    {
                        $message = array('result' => 0, 'error' => 'wrong data', 'token' => self::get_security_token(), 'show_message' => translate('You entered a wrong email or password.'));
                        throw new Exception(json_encode($message));
                    }
                }
                else
                {
                    $message = array('result' => 0, 'error' => 'invalid email', 'token' => self::get_security_token(), 'show_message' => translate('You used an invalid email.'));
                    throw new Exception(json_encode($message));
                }
            }
        }
        else
        {
            $message = array('result' => 0, 'error' => 'missing data', 'token' => self::get_security_token(), 'show_message' => translate('Please fill all fields.'));
            throw new Exception(json_encode($message));
        }
    }
    
    public function login_remembered_user()
    {
        global $salt, $encryption_key;
        
        //$return_message = '';
        
        if (isset($_COOKIE['user_remember']) && !isset($_SESSION['logged_in_user']))
        //if (isset($_COOKIE['user_remember']))
        {
            $remembered_info = decrypt($_COOKIE['user_remember']);
            $remembered_array = explode('<===>', $remembered_info);
            
            $email = $remembered_array[0];
            $password = $remembered_array[1];
            
            //if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/[^0-9a-z]/', $password))
            //if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !ctype_alnum($password))
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-z0-9]+$/', trim($password)))
            {
                $host = $_SERVER['HTTP_HOST'];
                preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
                if (isset($matches[0]))
                {
                    $domain_name = $matches[0];
                    setcookie("user_remember", '', time()-3600*24*356*2, "/", $domain_name);
                }
                else
                {
                    setcookie("user_remember", '', time()-3600*24*356*2, "/");
                }
                
                //die();
            } 
            else
            {
                $user = new Users;
                $check_user = new Xdb;
                $check_user_exists = $check_user->select_fields($user->session_fields)
                                                ->set_table($user->table)
                                                ->where(array('email' => $email, 'password' => $password))
                                                ->db_select(false);
                
                $host = $_SERVER['HTTP_HOST'];
                preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
                
                if ($check_user_exists && is_array($check_user_exists) && isset($check_user_exists[0]))
                {
                    $user_session = $check_user_exists[0];
                    $_SESSION['logged_in_user'] = $user_session;
                    
                    // crossdomain login
                    
                    $project_info = Projects::get_domain_project_info();
                    $project_id = $project_info['id'];
                    $project_slug = $project_info['project_slug'];
                    
                    $domains = Domains::get_domains_by_project($project_id);
                    foreach ($domains as $domain_key => $domain)
                    {
                        if ($domain == $_SERVER['HTTP_HOST'])
                        {
                            unset($domains[$domain_key]);
                        }
                    }
                    
                    $session_fields = $user->session_fields;
                    $session_fields_array = explode(', ', $session_fields);
            
                    $create_session = array();
                    
                    $crossdomain_login_query = '?';
                    $crossdomain_login_hash = '';
                    
                    foreach ($session_fields_array as $field)
                    {
                        if ($field != 'personal_stats')
                        {
                            $create_session[$field] = $user_session[$field];
                            
                            if ($crossdomain_login_query == '?')
                            {
                                $crossdomain_login_query .= $field . '=' . $user_session[$field];
                            }
                            else
                            {
                                $crossdomain_login_query .= '&' . $field . '=' . $user_session[$field];
                            }
                            
                            $crossdomain_login_hash .= $field . $user_session[$field];
                        }
                    }
                    
                    $crossdomain_login_hash .= $salt . $encryption_key;
                    $crossdomain_login_hashed = sha1($crossdomain_login_hash);
                    $crossdomain_login_query .= '&key=' . $crossdomain_login_hashed;
                    /*
                    $return_message = '';
                    foreach ($domains as $domain)
                    {
                        $return_message .= '<img src="' . $domain . '/projects/' . $project_slug . '/commands/commands.crossdomain.php' . $crossdomain_login_query . '" width="1" height="1" style="display: none" />';
                        //$return_message .= '<img src="http://' . $domain . '/projects/' . $project_slug . '/commands/commands.crossdomain.php' . $crossdomain_login_query . '" width="1" height="1" style="display: none;" />';
                        //$return_message .= $domain;
                    }*/
                    //return $return_message;
                    // end crossdomain login
                    //echo $combine_message;
                    //$return_message = $combine_message;
                    
                    if (isset($matches[0]))
                    {
                        $domain_name = $matches[0];
                        setcookie("user_remember", $_COOKIE['user_remember'], time()+3600*24*356, "/", $domain_name);
                    }
                    else
                    {
                        setcookie("user_remember", $_COOKIE['user_remember'], time()+3600*24*356, "/");
                    }
                    
                    //return $return_message;
                }
                else
                {
                    if (isset($matches[0]))
                    {
                        $domain_name = $matches[0];
                        setcookie("user_remember", '', time()-3600*24*356*2, "/", $domain_name);
                    }
                    else
                    {
                        setcookie("user_remember", '', time()-3600*24*356*2, "/");
                    }
                    
                }
            }
            //print_r($project_slug);
            //return $return_message;
            
            $return_message = '';
                    
            foreach ($domains as $domain)
            {
                $return_message .= '<img src="http://' . $domain . '/projects/' . $project_slug . '/commands/commands.crossdomain.php' . $crossdomain_login_query . '" width="1" height="1" style="display: none" />';
                //$return_message .= '<img src="http://' . $domain . '/projects/' . $project_slug . '/commands/commands.crossdomain.php' . $crossdomain_login_query . '" width="1" height="1" style="display: none;" />';
                //$return_message .= $domain;
            }
            
            return $return_message;
        }
        
        
    }
    
    public function social_login_user($social_network, $data_object)
    {
        global $salt, $encryption_key, $selected_language;
        $selected_language = $_COOKIE['language'];
        
        $project_id = Projects::get_project_id();
        
        if (!isset($selected_language) || !$selected_language)
        {
            $selected_language = Languages::get_current_language($project_id);
        }
        
        $domains = Domains::get_domains_by_project($project_id);
        
        $project_info = Projects::get_domain_project_info();
        $project_slug = $project_info['project_slug'];
        
        foreach ($domains as $domain_key => $domain)
        {
            if ($domain == $_SERVER['HTTP_HOST'])
            {
                unset($domains[$domain_key]);
            }
        }
        
        $language_id = Languages::get_language_id($selected_language);
        $current_country = Countries::get_country_by_language($language_id);
        
        $message = array();
        
        $check_user = new Xdb;
        $check_user_exists = $check_user->select_fields('*')
                                        ->set_table($this->table)
                                        ->where(array('email' => $data_object->email))
                                        ->db_select(false);
        
        if ($check_user_exists && is_array($check_user_exists))
        {
            $user_session = $check_user_exists[0];
            
            // Facebook
            if ($social_network == 'facebook')
            {
                if (isset($check_user_exists[0]['fb_id']) && $check_user_exists[0]['fb_id'] == $data_object->identifier)
                {
                    $session_fields = $this->session_fields;
                    $session_fields_array = explode(', ', $session_fields);
                    
                    $create_session = array();
                    
                    $crossdomain_login_query = '?';
                    $crossdomain_login_hash = '';
                    
                    foreach ($session_fields_array as $field)
                    {
                        if ($field != 'personal_stats')
                        {
                            $create_session[$field] = $user_session[$field];
                            
                            if ($crossdomain_login_query == '?')
                            {
                                $crossdomain_login_query .= $field . '=' . $user_session[$field];
                            }
                            else
                            {
                                $crossdomain_login_query .= '&' . $field . '=' . $user_session[$field];
                            }
                            
                            $crossdomain_login_hash .= $field . $user_session[$field];
                        }
                    }
                    
                    $crossdomain_login_hash .= $salt . $encryption_key;
                    $crossdomain_login_hashed = sha1($crossdomain_login_hash);
                    $crossdomain_login_query .= '&key=' . $crossdomain_login_hashed;
                    
                    foreach ($domains as $domain)
                    {
                        echo '<iframe src="http://' . $domain . '/projects/' . $project_slug . '/commands/commands.crossdomain.php' . $crossdomain_login_query . '" width="1" height="1" style="display: none;"></iframe>';
                    }
                    
                    $_SESSION['logged_in_user'] = $create_session;
                    //echo '<script type="text/javascript"> window.opener.location.reload(); window.close(); </script>';
                    
                    echo '<script type="text/javascript">setTimeout(function(){ window.opener.location.href = "' . $_COOKIE['window_reload'] . '"; window.close(); }, 1000); </script>';
                }
                else
                {
                    $update_array = array();
                    
                    $date_time = new DateTime();
                    $save_date_time = $date_time->format('Y-m-d H:i:s');
                    
                    $update_array['fb_id'] = $data_object->identifier;
                    $update_array['fb_datetime_registration'] = $save_date_time;
                    $update_array['fb_profile_url'] = $data_object->profileURL;
                    $update_array['fb_photo_url'] = $data_object->photoURL;
                    $update_array['datetime_last_change'] = $save_date_time;
                    if (!$user_session['name']) $update_array['name'] = $data_object->firstName;
                    if (!$user_session['surname']) $update_array['surname'] = $data_object->lastName;
                    if (!$user_session['gender'])
                    {
                        if ($data_object->gender == 'male')
                        {
                            $update_array['gender'] = 'm';
                        }
                        else
                        {
                            $update_array['gender'] = 'f';
                        }
                    }
                    
                    if (!$user_session['activated'])
                    {
                        $update_array['activated'] = 1;
                        $update_array['datetime_activation'] = $save_date_time;
                    } 
                    
                    if ($data_object->address && !$user_session['address']) $update_array['address'] = $data_object->address;
                    if ($data_object->city && $data_object->zip && !$user_session['city']) $update_array['city'] = $data_object->zip . ' ' . $data_object->city;
                    
                    $update_user_data = new Xdb;
                    $update = $update_user_data->set_table($this->table)
                                               ->simple_update_fields($update_array)
                                               ->where(array('id' => $user_session['id']))
                                               ->db_update();
                    
                    $session_fields = $this->session_fields;
                    $session_fields_array = explode(', ', $session_fields);
                    
                    $create_session = array();
                    
                    $crossdomain_login_query = '?';
                    $crossdomain_login_hash = '';
                    
                    foreach ($session_fields_array as $field)
                    {
                        if ($field != 'personal_stats')
                        {
                            $create_session[$field] = $user_session[$field];
                            
                            if ($crossdomain_login_query == '?')
                            {
                                $crossdomain_login_query .= $field . '=' . $user_session[$field];
                            }
                            else
                            {
                                $crossdomain_login_query .= '&' . $field . '=' . $user_session[$field];
                            }
                            
                            $crossdomain_login_hash .= $field . $user_session[$field];
                        }
                    }
                    
                    $create_session['activated'] = 1;
                    //$crossdomain_login_query .= '&activated=' . 1;
                    //$crossdomain_login_hash .= 'activated1';
                    $crossdomain_login_query = str_replace('&activated=0', '&activated=1', $crossdomain_login_query);
                    $crossdomain_login_hash = str_replace('activated0', 'activated1', $crossdomain_login_hash);
                    
                    $crossdomain_login_hash .= $salt . $encryption_key;
                    $crossdomain_login_hashed = sha1($crossdomain_login_hash);
                    $crossdomain_login_query .= '&key=' . $crossdomain_login_hashed;
                    
                    foreach ($domains as $domain)
                    {
                        echo '<iframe src="http://' . $domain . '/projects/' . $project_slug . '/commands/commands.crossdomain.php' . $crossdomain_login_query . '" width="1" height="1" style="display: none;"></iframe>';
                    }
                    
                    
                    //Stats::write_stats('Users', 'fb_updated');
                    Stats::write_stats('Users', array('fb_updated', 'country_fb_updated_' . $current_country));
                    
                    $_SESSION['logged_in_user'] = $create_session;
                    //echo '<script type="text/javascript"> setTimeout(function(){ window.opener.location.reload(); window.close(); }, 1000); </script>';
                    echo '<script type="text/javascript">setTimeout(function(){ window.opener.location.href = "' . $_COOKIE['window_reload'] . '"; window.close(); }, 1000); </script>';
                    
                }
            }
            
            // Google
            if ($social_network == 'google')
            {
                if (isset($check_user_exists[0]['google_id']) && $check_user_exists[0]['google_id'] == $data_object->identifier)
                {
                    $session_fields = $this->session_fields;
                    $session_fields_array = explode(', ', $session_fields);
                    
                    $create_session = array();
                    
                    $crossdomain_login_query = '?';
                    $crossdomain_login_hash = '';
                    
                    foreach ($session_fields_array as $field)
                    {
                        if ($field != 'personal_stats')
                        {
                            $create_session[$field] = $user_session[$field];
                            
                            if ($crossdomain_login_query == '?')
                            {
                                $crossdomain_login_query .= $field . '=' . $user_session[$field];
                            }
                            else
                            {
                                $crossdomain_login_query .= '&' . $field . '=' . $user_session[$field];
                            }
                            
                            $crossdomain_login_hash .= $field . $user_session[$field];
                        }
                    }
                    
                    $crossdomain_login_hash .= $salt . $encryption_key;
                    $crossdomain_login_hashed = sha1($crossdomain_login_hash);
                    $crossdomain_login_query .= '&key=' . $crossdomain_login_hashed;
                    
                    foreach ($domains as $domain)
                    {
                        echo '<iframe src="http://' . $domain . '/projects/' . $project_slug . '/commands/commands.crossdomain.php' . $crossdomain_login_query . '" width="1" height="1" style="display: none;"></iframe>';
                    }
                    
                    $_SESSION['logged_in_user'] = $create_session;
                    echo '<script type="text/javascript">setTimeout(function(){ window.opener.location.href = "' . $_COOKIE['window_reload'] . '"; window.close(); }, 1000); </script>';
                }
                else
                {
                    $update_array = array();
                    
                    $date_time = new DateTime();
                    $save_date_time = $date_time->format('Y-m-d H:i:s');
                    
                    $update_array['google_id'] = $data_object->identifier;
                    $update_array['google_datetime_registration'] = $save_date_time;
                    $update_array['google_profile_url'] = $data_object->profileURL;
                    if ($data_object->photoURL) $update_array['google_photo_url'] = $data_object->photoURL;
                    $update_array['datetime_last_change'] = $save_date_time;
                    if (!$user_session['name']) $update_array['name'] = $data_object->firstName;
                    if (!$user_session['surname']) $update_array['surname'] = $data_object->lastName;
                    if (!$user_session['gender'])
                    {
                        if ($data_object->gender == 'male')
                        {
                            $update_array['gender'] = 'm';
                        }
                        else
                        {
                            $update_array['gender'] = 'f';
                        }
                    }
                    
                    if (!$user_session['activated'])
                    {
                        $update_array['activated'] = 1;
                        $update_array['datetime_activation'] = $save_date_time;
                    } 
                    
                    if ($data_object->address && !$user_session['address']) $update_array['address'] = $data_object->address;
                    if ($data_object->city && $data_object->zip && !$user_session['city']) $update_array['city'] = $data_object->zip . ' ' . $data_object->city;
                    
                    $update_user_data = new Xdb;
                    $update = $update_user_data->set_table($this->table)
                                               ->simple_update_fields($update_array)
                                               ->where(array('id' => $user_session['id']))
                                               ->db_update();
                    
                    $session_fields = $this->session_fields;
                    $session_fields_array = explode(', ', $session_fields);
                    
                    $create_session = array();
                    
                    $crossdomain_login_query = '?';
                    $crossdomain_login_hash = '';
                    
                    foreach ($session_fields_array as $field)
                    {
                        if ($field != 'personal_stats')
                        {
                            $create_session[$field] = $user_session[$field];
                            
                            if ($crossdomain_login_query == '?')
                            {
                                $crossdomain_login_query .= $field . '=' . $user_session[$field];
                            }
                            else
                            {
                                $crossdomain_login_query .= '&' . $field . '=' . $user_session[$field];
                            }
                            
                            $crossdomain_login_hash .= $field . $user_session[$field];
                        }
                    }
                    
                    $create_session['activated'] = 1;
                    //$crossdomain_login_query .= '&activated=' . 1;
                    //$crossdomain_login_hash .= 'activated1';
                    
                    $crossdomain_login_query = str_replace('&activated=0', '&activated=1', $crossdomain_login_query);
                    $crossdomain_login_hash = str_replace('activated0', 'activated1', $crossdomain_login_hash);
                    
                    $crossdomain_login_hash .= $salt . $encryption_key;
                    $crossdomain_login_hashed = sha1($crossdomain_login_hash);
                    $crossdomain_login_query .= '&key=' . $crossdomain_login_hashed;
                    
                    foreach ($domains as $domain)
                    {
                        echo '<iframe src="http://' . $domain . '/projects/' . $project_slug . '/commands/commands.crossdomain.php' . $crossdomain_login_query . '" width="1" height="1" style="display: none;"></iframe>';
                    }
                    
                    //Stats::write_stats('Users', 'google_updated');
                    Stats::write_stats('Users', array('google_updated', 'country_google_updated_' . $current_country));
                    
                    $_SESSION['logged_in_user'] = $create_session;
                    echo '<script type="text/javascript">setTimeout(function(){ window.opener.location.href = "' . $_COOKIE['window_reload'] . '"; window.close(); }, 1000); </script>';
                    
                }
            }
        }
        else
        {
            $this->fields['static_fields']['email']['value'] = $data_object->email;
            $this->fields['static_fields']['password']['value'] = sha1($salt . '-' . rand() . '-' . rand() . '-' . rand());
            $this->fields['static_fields']['project_id']['value'] = $project_id;
            
            $language_id = Languages::get_language_id($selected_language);
            $current_country = Countries::get_country_by_language($language_id);
            $this->fields['static_fields']['country']['value'] = $current_country;
            $this->fields['static_fields']['language']['value'] = $selected_language;
    
            $bc = new Browscap($_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.third.party/browscap.cache/');
            $current_browser = $bc->getBrowser(null, true);
            $this->fields['static_fields']['system']['value'] = json_encode($current_browser);
            
            $date_time = new DateTime();
            $save_date_time = $date_time->format('Y-m-d H:i:s');
            
            $this->fields['static_fields']['datetime_registration']['value'] = $save_date_time;
            $this->fields['static_fields']['datetime_last_change']['value'] = $save_date_time;
            $this->fields['static_fields']['datetime_last_syscheck']['value'] = $save_date_time;
            $this->fields['static_fields']['datetime_activation']['value'] = $save_date_time;
            
            $this->fields['static_fields']['activated']['value'] = 1;
            
            if ($social_network == 'facebook')
            {
                $this->fields['static_fields']['fb_id']['value'] = $data_object->identifier;
                $this->fields['static_fields']['fb_profile_url']['value'] = $data_object->profileURL;
                if (isset($data_object->photoURL) && $data_object->photoURL) $this->fields['static_fields']['fb_photo_url']['value'] = $data_object->photoURL;
                $this->fields['static_fields']['fb_datetime_registration']['value'] = $save_date_time;
                
                $this->fields['static_fields']['name']['value'] = $data_object->firstName;
                $this->fields['static_fields']['surname']['value'] = $data_object->lastName;
                
                if (isset($data_object->gender) && $data_object->gender)
                {
                    if ($data_object->gender == 'male')
                    {
                        $this->fields['static_fields']['gender']['value'] = 'm';
                    }
                    else
                    {
                        $this->fields['static_fields']['gender']['value'] = 'f';
                    }
                }
                
                if (isset($data_object->address) && $data_object->address) $this->fields['static_fields']['address']['value'] = $data_object->address;
                if (isset($data_object->city) && $data_object->city && $data_object->zip) $this->fields['static_fields']['city']['value'] = $data_object->zip . ' ' . $data_object->city;
                /*
                Stats::write_stats('Users', 'fb_registered');
                Stats::write_stats('Users', 'registered');
                Stats::write_stats('Users', 'activated');
                */
                
                Stats::write_stats('Users', array('fb_registered', 'country_fb_registered_' . $current_country, 'registered', 'country_registered_' . $current_country, 'activated', 'country_activated_' . $current_country));
            }
            
            if ($social_network == 'google')
            {
                $this->fields['static_fields']['google_id']['value'] = $data_object->identifier;
                $this->fields['static_fields']['google_profile_url']['value'] = $data_object->profileURL;
                if (isset($data_object->photoURL) && $data_object->photoURL) $this->fields['static_fields']['google_photo_url']['value'] = $data_object->photoURL;
                $this->fields['static_fields']['google_datetime_registration']['value'] = $save_date_time;
                
                $this->fields['static_fields']['name']['value'] = $data_object->firstName;
                $this->fields['static_fields']['surname']['value'] = $data_object->lastName;
                
                if (isset($data_object->gender) && $data_object->gender)
                {
                    if ($data_object->gender == 'male')
                    {
                        $this->fields['static_fields']['gender']['value'] = 'm';
                    }
                    else
                    {
                        $this->fields['static_fields']['gender']['value'] = 'f';
                    }
                }
                
                if ($data_object->address) $this->fields['static_fields']['address']['value'] = $data_object->address;
                if ($data_object->city && $data_object->zip) $this->fields['static_fields']['city']['value'] = $data_object->zip . ' ' . $data_object->city;
                /*
                Stats::write_stats('Users', 'google_registered');
                Stats::write_stats('Users', 'registered');
                Stats::write_stats('Users', 'activated');
                */
                
                Stats::write_stats('Users', array('google_registered', 'country_google_registered_' . $current_country, 'registered', 'country_registered_' . $current_country, 'activated', 'country_activated_' . $current_country));
            }
            
            $xdb_user_insert = new Xdb;
            $last_user_id = $xdb_user_insert->db_insert($this->table, $this->fields);
            
            // start GA tracking
            $tracker = new GoogleAnalytics\Tracker('MO-42907887-1', 'avantbon.si');
            
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
            $event->setCategory('Uporabnik');    //string, required
            $event->setAction('Registracija');        //string, required
            $event->setLabel($last_user_id);          //string, not required
            $event->setNoninteraction('true');
            //$event->setValue($offer['view_discount_price']);  
        
            //track event
            $tracker->trackEvent($event,$session,$visitor);
            // end GA tracker
            
            //Stats::write_stats('Users', 'country_' . $current_country);
            
            $session_fields = $this->session_fields;
            $session_fields_array = explode(', ', $session_fields);
            
            $create_session = array();
            
            $crossdomain_login_query = '?';
            $crossdomain_login_hash = '';
            
            foreach ($session_fields_array as $field)
            {
                if (isset($this->fields['static_fields'][$field]['value']))
                {
                    $create_session[$field] = $this->fields['static_fields'][$field]['value'];
                    
                    if ($crossdomain_login_query == '?')
                    {
                        $crossdomain_login_query .= $field . '=' . $this->fields['static_fields'][$field]['value'];
                    }
                    else
                    {
                        $crossdomain_login_query .= '&' . $field . '=' . $this->fields['static_fields'][$field]['value'];
                    }
                    
                    $crossdomain_login_hash .= $field . $this->fields['static_fields'][$field]['value'];
                }
            }
            
            $create_session['id'] = $last_user_id;
            $crossdomain_login_query .= '&id=' . $last_user_id;
            $crossdomain_login_hash .= 'id' . $last_user_id;
            
            $crossdomain_login_hash .= $salt . $encryption_key;
            $crossdomain_login_hashed = sha1($crossdomain_login_hash);
            $crossdomain_login_query .= '&key=' . $crossdomain_login_hashed;
            
            foreach ($domains as $domain)
            {
                echo '<iframe src="http://' . $domain . '/projects/' . $project_slug . '/commands/commands.crossdomain.php' . $crossdomain_login_query . '" width="1" height="1" style="display: none;"></iframe>';
            }
            
            $_SESSION['logged_in_user'] = $create_session;
            
            echo "<script type='text/javascript'>
var fb_param = {};
fb_param.pixel_id = '6009391399049';
fb_param.value = '0.00';
fb_param.currency = 'EUR';
(function(){
  var fpw = document.createElement('script');
  fpw.async = true;
  fpw.src = '//connect.facebook.net/en_US/fp.js';
  var ref = document.getElementsByTagName('script')[0];
  ref.parentNode.insertBefore(fpw, ref);
})();
</script>
<noscript><img height='1' width='1' alt='' style='display:none' src='https://www.facebook.com/offsite_event.php?id=6009391399049&amp;value=0&amp;currency=EUR' /></noscript>";
            
            echo '<script type="text/javascript">setTimeout(function(){ window.opener.location.href = "' . $_COOKIE['window_reload'] . '"; window.close(); }, 1000); </script>';
        }
    }
    
    public function change_password_user($old_password, $new_password, $new_password_re, $security_token)
    {
        global $salt;
        
        if (!isset($_SESSION['logged_in_user']))
        {
            $message = array('result' => 0, 'error' => 'no session', 'token' => self::get_security_token(), 'show_message' => '');
            throw new Exception(json_encode($message));
        }
        else
        {
            if ($security_token != $_SESSION['security_token'] || !isset($_SESSION['security_token']))
            {
                $message = array('result' => 0, 'error' => 'unauthorized session', 'token' => self::get_security_token(), 'show_message' => translate('Your session is not authorized.'));
                die(json_encode($message));
            }
            
            if ($old_password == '' || $new_password == '' || $new_password_re == '')
            {
                $message = array('result' => 0, 'error' => 'missing data', 'token' => self::get_security_token(), 'show_message' => translate('Please fill all fields.'));
                die(json_encode($message));
            }
            
            $user_info = self::get_user_info();
            
            if (sha1($salt . $old_password) != $user_info['password'])
            {
                $message = array('result' => 0, 'error' => 'invalid old password', 'token' => self::get_security_token(), 'show_message' => translate('You entered a wrong old password.'));
                throw new Exception(json_encode($message));
            }
            else
            {
                if ($new_password != $new_password_re)
                {
                    $message = array('result' => 0, 'error' => 'passwords dont match', 'token' => self::get_security_token(), 'show_message' => translate('New passwords do not match.'));
                    throw new Exception(json_encode($message));
                }
                else
                {
                    $new_password_hashed = sha1($salt . trim($new_password));
                    $user_id = $_SESSION['logged_in_user']['id'];
                    
                    $date_time = new DateTime();
                    $save_date_time = $date_time->format('Y-m-d H:i:s');
                    
                    $update_user = new Users;
                    $update_user_data = new Xdb;
                    $update = $update_user_data->set_table($update_user->table)
                                               ->simple_update_fields(array('password' => $new_password_hashed, 'datetime_last_change' => $save_date_time))
                                               ->where(array('id' => $user_id))
                                               ->db_update();
                    
                    $message = array('result' => 1, 'token' => self::get_security_token(), 'show_message' => translate('Your password was changed successfully.'));
                    return json_encode($message);
                }
            }
        }
    }
    
    public function check_security_token_ios($email, $security_token)
    {
        global $salt;
        
        if (sha1($email . $salt) == $security_token)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function change_password_user_ios($email, $old_password, $new_password, $new_password_re, $security_token)
    {
        global $salt;
        
        if (!self::check_security_token_ios($email, $security_token))
        {
            $message = array('result' => 0, 'error' => 'unauthorized session', 'token' => self::get_security_token(), 'show_message' => translate('Your session is not authorized.'));
            die(json_encode($message));
        }
        
        if ($old_password == '' || $new_password == '' || $new_password_re == '')
        {
            $message = array('result' => 0, 'error' => 'missing data', 'token' => self::get_security_token(), 'show_message' => translate('Please fill all fields.'));
            die(json_encode($message));
        }
        
        $user_info = self::get_user_info();
        
        if (sha1($salt . $old_password) != $user_info['password'])
        {
            $message = array('result' => 0, 'error' => 'invalid old password', 'token' => self::get_security_token(), 'show_message' => translate('You entered a wrong old password.'));
            throw new Exception(json_encode($message));
        }
        else
        {
            if ($new_password != $new_password_re)
            {
                $message = array('result' => 0, 'error' => 'passwords dont match', 'token' => self::get_security_token(), 'show_message' => translate('New passwords do not match.'));
                throw new Exception(json_encode($message));
            }
            else
            {
                $new_password_hashed = sha1($salt . trim($new_password));
                //$user_id = $_SESSION['logged_in_user']['id'];
                
                $date_time = new DateTime();
                $save_date_time = $date_time->format('Y-m-d H:i:s');
                
                $update_user = new Users;
                $update_user_data = new Xdb;
                $update = $update_user_data->set_table($update_user->table)
                                           ->simple_update_fields(array('password' => $new_password_hashed, 'datetime_last_change' => $save_date_time))
                                           ->where(array('email' => $email))
                                           ->db_update();
                
                $message = array('result' => 1, 'token' => self::get_security_token(), 'show_message' => translate('Your password was changed successfully.'));
                return json_encode($message);
            }
        }
    }
    
    public function get_user_info()
    {
        $user = new Users;
        $user_id = $_SESSION['logged_in_user']['id'];
        $check_user = new Xdb;
        $check_user_data = $check_user->select_fields('*')
                                      ->set_table($user->table)
                                      ->where(array('id' => $user_id))
                                      ->db_select(false);
        
        unset($check_user_data[0]['system']);
        return $check_user_data[0];
    }
    
    public function get_user_info_ios($email, $security_token)
    {
        global $salt;
        
        //if (!self::check_security_token_ios($email, $security_token))
        if (sha1($email . $salt) != $security_token)
        {
            $message = array('result' => 0, 'error' => 'unauthorized session', 'token' => self::get_security_token(), 'show_message' => translate('Your session is not authorized.'), sha1($email . $salt) => $security_token);
            die(json_encode($message));
        }
        
        $user = new Users;
        //$user_id = $_SESSION['logged_in_user']['id'];
        $check_user = new Xdb;
        $check_user_data = $check_user->select_fields('email, name, surname, country, language, post_number, city, address, gender, subscription_start_date, subscription_end_date, subscription_saved_total, subscription_offers_total, fb_photo_url, google_photo_url, personal_stats')
                                      ->set_table($user->table)
                                      ->where(array('email' => $email))
                                      ->db_select(false);
        
        //unset($check_user_data[0]['system']);
        
        $for_today = json_decode($check_user_data[0]['personal_stats'], true);
        
        $today = new DateTime();
        $today_date = $today->format('Y-m-d');
        
        if (isset($for_today[$today_date]))
        {
            $personal_stats_today = $for_today[$today_date];
        }
        else
        {
            $personal_stats_today = array();
        }
        
        $check_user_data[0]['personal_stats_today'] = $personal_stats_today;
        
        return json_encode($check_user_data[0]);
    }
    
    public function user_filled_all_data()
    {
        $user = new Users;
        $user_id = $_SESSION['logged_in_user']['id'];
        $check_user = new Xdb;
        $check_user_data = $check_user->select_fields('gender, name, surname, address, city')
                                      ->set_table($user->table)
                                      ->where(array('id' => $user_id, 'gender::!=' => '', 'name::!=' => '', 'surname::!=' => '', 'address::!=' => '', 'city::!=' => ''))
                                      ->db_select(false);
        
        if (is_array($check_user_data) && isset($check_user_data[0]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function payment_update_user($gender, $name, $surname, $address, $city, $security_token, $birth_day, $birth_month, $birth_year)
    {
        global $salt, $selected_language;
        if (!isset($selected_language) || !$selected_language)
        {
            $selected_language = $_COOKIE['language'];
        }
        
        if ($security_token != $_SESSION['security_token'] || !isset($_SESSION['security_token']))
        {
            $message = array('result' => 0, 'error' => 'unauthorized session', 'token' => self::get_security_token(), 'show_message' => translate('Your session is not authorized.'));
            throw new Exception(json_encode($message));
        }
        else
        {
            $user_id = $_SESSION['logged_in_user']['id'];
            $date_time = new DateTime();
            $save_date_time = $date_time->format('Y-m-d H:i:s');
            
            $birth_date = $birth_year . '-' . $birth_month . '-' . $birth_day;
            
            $update_user = new Users;
            $update_user_data = new Xdb;
            $update = $update_user_data->set_table($update_user->table)
                                       ->simple_update_fields(array('gender' => $gender, 'name' => $name, 'surname' => $surname, 'address' => $address, 'city' => $city, 'datetime_last_change' => $save_date_time, 'birth_date' => $birth_date))
                                       ->where(array('id' => $user_id))
                                       ->db_update();
            
            $message = array('result' => 1, 'redirect' => 'http://' . $_SERVER['HTTP_HOST'] . '/' . $selected_language . '/subscription/payment/');
            return json_encode($message);
        }
    }
    
    public function logout_user()
    {
        unset($_SESSION['logged_in_user']);
        unset($_SESSION['payment']);
        
        $host = $_SERVER['HTTP_HOST'];
        preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
        if (isset($matches[0]))
        {
            $domain_name = $matches[0];
            setcookie("user_remember", '', time()-3600*24*356*2, "/", $domain_name);
        }
        else
        {
            setcookie("user_remember", '', time()-3600*24*356*2, "/");
        }
        
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
                $combine_message .= '<img src="http://' . $domain . '/projects/' . $project_slug . '/commands/commands.crossdomain.php?action=remove" width="1" height="1" style="display: none;" />';
            }
        }
        
        $message = array('result' => 1, 'show_message' => $combine_message);
        return json_encode($message);
    }
    
    public function is_user_logged_in()
    {
        if (isset($_SESSION['logged_in_user']) && isset($_SESSION['logged_in_user']['id']))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
    // admin functions
    
    public function users_get_count()
    {
        $user = new Users;
        $xdb_users = new Xdb;
        $xdb_users_rows = $xdb_users->select_fields('COUNT(*)')
                                    ->set_table($user->table)
                                    ->where(array('trashed' => 0))
                                    //->group_by('title')
                                    ->db_select(false);
        
        return $xdb_users_rows[0]['COUNT(*)'];
    }
    
    public function users_get_simple_search_count($search_term)
    {
        $user = new Users;
        $xdb_users = new Xdb;
        $search_term_decoded = base64_decode($search_term);
        
        $search_array = array('email::LIKE' => '%' . $search_term_decoded . '%', 'OR::name::LIKE' => '%' . $search_term_decoded . '%', 'OR::surname::LIKE' => '%' . $search_term_decoded . '%', 'OR::address::LIKE' => '%' . $search_term_decoded . '%', 'OR::city::LIKE' => '%' . $search_term_decoded . '%');
        
        $xdb_users_rows = $xdb_users->select_fields('COUNT(*)')
                                      ->set_table($user->table)
                                      ->where(array('trashed' => 0))
                                      ->and_where_group($search_array)
                                      //->group_by('title')
                                      ->db_select(false);
        
        return $xdb_users_rows[0]['COUNT(*)'];
    }
    
    public function users_get_all_limit_search($limit_start, $limit, $search_type, $search_term)
    {
        $user = new Users;
        $xdb_users = new Xdb;
        if ($search_type == 'undefined')
        {
            $xdb_users_rows = $xdb_users->select_fields('id, project_id, name, surname, email, country, fb_photo_url, google_photo_url, datetime_registration, datetime_activation, datetime_last_change, fb_id, google_id, activated')
                                        ->set_table($user->table)
                                        ->where(array('trashed' => 0))
                                        ->order_by('datetime_registration', 'DESC')
                                        ->limit($limit_start . ', ' . $limit)
                                        ->db_select(false);
        }
        elseif ($search_type == 'search-simple')
        {
            $search_term_decoded = base64_decode($search_term);
            
            $search_array = array('email::LIKE' => '%' . $search_term_decoded . '%', 'OR::name::LIKE' => '%' . $search_term_decoded . '%', 'OR::surname::LIKE' => '%' . $search_term_decoded . '%', 'OR::address::LIKE' => '%' . $search_term_decoded . '%', 'OR::city::LIKE' => '%' . $search_term_decoded . '%');
            
            $xdb_users_rows = $xdb_users->select_fields('id, project_id, name, surname, email, country, fb_photo_url, google_photo_url, datetime_registration, datetime_activation, datetime_last_change, fb_id, google_id, activated')
                                        ->set_table($user->table)
                                        ->where(array('trashed' => 0))
                                        ->and_where_group($search_array)
                                        ->order_by('datetime_registration', 'DESC')
                                        ->limit($limit_start . ', ' . $limit)
                                        ->db_select(false);
        }
        
        if (is_array($xdb_users_rows))
        {
            foreach ($xdb_users_rows as $user_key => $user)
            {
                // datetime registration
                $datetime_registration = new DateTime($user['datetime_registration']);
                unset($xdb_users_rows[$user_key]['datetime_registration']);
                $xdb_users_rows[$user_key]['datetime_registration'] = $datetime_registration->format('d.m.Y H:i:s');
                
                // datetime activation
                if ($user['activated'] == 1)
                {
                    $datetime_activation = new DateTime($user['datetime_activation']);
                    unset($xdb_users_rows[$user_key]['datetime_activation']);
                    $xdb_users_rows[$user_key]['datetime_activation'] = $datetime_activation->format('d.m.Y H:i:s');
                }
                else
                {
                    unset($xdb_users_rows[$user_key]['datetime_activation']);
                    $xdb_users_rows[$user_key]['datetime_activation'] = 'not activated';
                }
                
                // datetime last change
                $datetime_last_change = new DateTime($user['datetime_last_change']);
                unset($xdb_users_rows[$user_key]['datetime_last_change']);
                $xdb_users_rows[$user_key]['datetime_last_change'] = $datetime_last_change->format('d.m.Y H:i:s');
            }
            return $xdb_users_rows;
        }
        else
        {
            return false;
        }
    }
    
    public function get_users_stats($period = 'day')
    {
        global $today_count, $today_activated, $week_count, $week_activated, $month_count, $month_activated;
        
        $stats_xdb = new Xdb;
        $stats_rows = $stats_xdb->set_table('stats_users')
                                ->db_select(false);
        
        $current_date = new DateTime();
        $current_day = $current_date->format('Y-m-d');
        $current_week = $current_date->format('Y-W');
        $current_month = $current_date->format('Y-m');
        
        $week_count = 0;
        $week_activated = 0;
        
        $month_count = 0;
        $month_activated = 0;
        
        $stats_rearranged = array();
        foreach ($stats_rows as $row_key => $row)
        {
            // set globals
            if ($row['date'] == $current_day)
            {
                $today_count = $row['counter_registered'];
                $today_activated = $row['counter_activated'];
            }
            else
            {
                $today_count = 0;
                $today_activated = 0;
            }
            
            $get_week = new DateTime($row['date']);
            $the_week = $get_week->format('Y-W');
            
            if ($the_week == $current_week)
            {
                $week_count = $week_count + $row['counter_registered'];
                $week_activated = $week_activated + $row['counter_activated'];
            }
            
            $get_month = new DateTime($row['date']);
            $the_month = $get_month->format('Y-m');
            
            if ($the_month == $current_month)
            {
                $month_count = $month_count + $row['counter_registered'];
                $month_activated = $month_activated + $row['counter_activated'];
            }
            // end set globals
            
            if ($period == 'day')
            {
                $not_formatted_date = $row['date'];
                $to_day_time = new DateTime($row['date']);
                $day_time_view = $to_day_time->format('d.m.Y');
                $row['date'] = $day_time_view;
                $stats_rearranged[$not_formatted_date] = $row;
                //unset($stats_rearranged[$row['date']]['date']);
                
                if (!isset($stats_rearranged[$not_formatted_date]['counter_fb_registered']))
                {
                    $stats_rearranged[$not_formatted_date]['counter_fb_registered'] = 0;
                }
                if (!isset($stats_rearranged[$not_formatted_date]['counter_google_registered']))
                {
                    $stats_rearranged[$not_formatted_date]['counter_google_registered'] = 0;
                }
                
                $stats_rearranged[$not_formatted_date]['counter_classic_registered'] = $stats_rearranged[$not_formatted_date]['counter_registered'] - $stats_rearranged[$not_formatted_date]['counter_fb_registered'] - $stats_rearranged[$not_formatted_date]['counter_google_registered'];
            }
            if ($period == 'week')
            {
                $to_week_time = new DateTime($row['date']);
                $week_time = $to_week_time->format('Y-W');
                $week_time_view = $to_week_time->format('W Y');
                $row['date'] = str_replace(' ', 'w ', $week_time_view);
                if (!isset($stats_rearranged[$week_time]))
                {
                    $stats_rearranged[$week_time] = $row;
                }
                else
                {
                    foreach ($row as $row_key => $row_value)
                    {
                        if ($row_key != 'id' && $row_key != 'project_id' && $row_key != 'date')
                        {
                            //$row[$row_key] = $row[$row_key] + $row_value;
                            $row[$row_key] = $stats_rearranged[$week_time][$row_key] + $row_value;
                        }
                    }
                    
                    $stats_rearranged[$week_time] = $row;
                }
                
                if (!isset($stats_rearranged[$week_time]['counter_fb_registered']))
                {
                    $stats_rearranged[$week_time]['counter_fb_registered'] = 0;
                }
                if (!isset($stats_rearranged[$week_time]['counter_google_registered']))
                {
                    $stats_rearranged[$week_time]['counter_google_registered'] = 0;
                }
                
                $stats_rearranged[$week_time]['counter_classic_registered'] = $stats_rearranged[$week_time]['counter_registered'] - $stats_rearranged[$week_time]['counter_fb_registered'] - $stats_rearranged[$week_time]['counter_google_registered'];
            }
            if ($period == 'month')
            {
                $to_month_time = new DateTime($row['date']);
                $month_time = $to_month_time->format('Y-m');
                $month_time_view = $to_month_time->format('m Y');
                $row['date'] = str_replace(' ', 'm ', $month_time_view);
                if (!isset($stats_rearranged[$month_time]))
                {
                    $stats_rearranged[$month_time] = $row;
                }
                else
                {
                    foreach ($row as $row_key => $row_value)
                    {
                        if ($row_key != 'id' && $row_key != 'project_id' && $row_key != 'date')
                        {
                            $row[$row_key] = $stats_rearranged[$month_time][$row_key] + $row_value;
                        }
                    }
                    $stats_rearranged[$month_time] = $row;
                }
                
                if (!isset($stats_rearranged[$month_time]['counter_fb_registered']))
                {
                    $stats_rearranged[$month_time]['counter_fb_registered'] = 0;
                }
                if (!isset($stats_rearranged[$month_time]['counter_google_registered']))
                {
                    $stats_rearranged[$month_time]['counter_google_registered'] = 0;
                }
                
                $stats_rearranged[$month_time]['counter_classic_registered'] = $stats_rearranged[$month_time]['counter_registered'] - $stats_rearranged[$month_time]['counter_fb_registered'] - $stats_rearranged[$month_time]['counter_google_registered'];
            }
        }
        
        if ($period == 'day')
        {
            $begin_time = new DateTime($stats_rows[$row_key]['date']);
            $begin_date = $begin_time->format('Y-m-d');
            
            $today_time = new DateTime();
            $today_date = $today_time->format('Y-m-d');
            
            $date_range = createDateRangeArray($begin_date, $today_date);
            
            $return_array = array();
            $keys_array = array();
            $counter = 0;
            foreach ($date_range as $date)
            {
                if (isset($stats_rearranged[$date]))
                {
                    foreach ($stats_rearranged[$date] as $key => $value)
                    {
                        $return_array[$counter][$key] = $value;
                        $keys_array[$key] = $key;
                    }
                }
                else
                {
                    foreach ($keys_array as $key)
                    {
                        if ($key != 'date')
                        {
                            $return_array[$counter][$key] = 0;
                        }
                        else
                        {
                            $convert_time = new DateTime($date);
                            $date_formatted = $convert_time->format('d.m.Y');
                            $return_array[$counter][$key] = $date_formatted;
                        }
                    }
                }
                $counter++;
            }
        }
        
        if ($period == 'week')
        {
            $begin_time = new DateTime($stats_rows[0]['date']);
            $begin_date = $begin_time->format('Y');
            $begin_week = $begin_time->format('W');
            
            $comparison_begin = $begin_date . $begin_week;
            
            $today_time = new DateTime();
            $today_date = $today_time->format('Y');
            $end_week = $today_time->format('W');
            
            $return_array = array();
            $keys_array = array();
            $counter = 0;
            
            for ( $year = $begin_date; $year <= $today_date; $year += 1) 
            {
                $week_number = date("W", mktime(0,0,0,12,28,2013));
                
                if ($year < $today_date)
                {
                    $end_week_compare = $week_number;
                }
                else
                {
                    $end_week_compare = $end_week;
                }
                
                for ($week = 1; $week <= $week_number && $week <= $end_week_compare; $week += 1)
                {
                    $comparison_week = str_pad($week, 2, "0", STR_PAD_LEFT);
                    $comparison_current = $year . $comparison_week;
                    
                    if ($comparison_current >= $comparison_begin)
                    {
                        $date = $year . '-' . $comparison_week;
                        if (isset($stats_rearranged[$date]))
                        {
                            foreach ($stats_rearranged[$date] as $key => $value)
                            {
                                $return_array[$counter][$key] = $value;
                                $keys_array[$key] = $key;
                            }
                            $counter++;
                        }
                        else
                        {
                            foreach ($keys_array as $key)
                            {
                                if ($key != 'date')
                                {
                                    $return_array[$counter][$key] = 0;
                                }
                                else
                                {
                                    $return_array[$counter][$key] = $week . 'w ' . $year;
                                }
                            }
                            $counter++;
                        }
                    }
                }
            }
        }
        
        if ($period == 'month')
        {
            $begin_time = new DateTime($stats_rows[0]['date']);
            $begin_date = $begin_time->format('Y');
            $begin_month = $begin_time->format('m');
            
            $comparison_begin = $begin_date . $begin_month;
            
            $today_time = new DateTime();
            $today_date = $today_time->format('Y');
            $end_month = $today_time->format('m');
            
            $return_array = array();
            $keys_array = array();
            $counter = 0;
            
            for ( $year = $begin_date; $year <= $today_date; $year += 1) 
            {
                if ($year < $today_date)
                {
                    $end_month_compare = 12;
                }
                else
                {
                    $end_month_compare = $end_month;
                }
                
                for ($month = 1; $month <= 12 && $month <= $end_month_compare; $month += 1)
                {
                    $comparison_month = str_pad($month, 2, "0", STR_PAD_LEFT);
                    $comparison_current = $year . $comparison_month;
                    
                    if ($comparison_current >= $comparison_begin)
                    {
                        $date = $year . '-' . $comparison_month;
                        if (isset($stats_rearranged[$date]))
                        {
                            foreach ($stats_rearranged[$date] as $key => $value)
                            {
                                $return_array[$counter][$key] = $value;
                                $keys_array[$key] = $key;
                            }
                            $counter++;
                        }
                        else
                        {
                            foreach ($keys_array as $key)
                            {
                                if ($key != 'date')
                                {
                                    $return_array[$counter][$key] = 0;
                                }
                                else
                                {
                                    $return_array[$counter][$key] = $month . 'm ' . $year;
                                }
                            }
                            $counter++;
                        }
                    }
                }
            }
        }
        
        return json_encode($return_array);
    }
    
    public function check_saved_total()
    {
        if (isset($_SESSION['user_logged_in']) && !isset($_SESSION['user_logged_in']['subscription_saved_total']))
        {
            $user = new Users;
            $check_savings = new Xdb;
            $check_savings_exists = $check_savings->select_fields('subscription_saved_total')
                                                  ->set_table($user->table)
                                                  ->where(array('id' => $_SESSION['user_logged_in']['id']))
                                                  ->db_select(false);
            
            $_SESSION['user_logged_in']['subscription_saved_total'] = $check_savings_exists[0]['subscription_saved_total'];
        }
    }
    
    public function ios_fb_login($access_token)
    {
        global $salt, $encryption_key, $selected_language;
        $selected_language = 'sl';
        $current_country = 'SI';
        
        $project_id = Projects::get_project_id();
        
        $user_info_json = file_get_contents('https://graph.facebook.com/me?access_token=' . $access_token);
        $user_info = json_decode($user_info_json, true);
        $data_object = json_decode($user_info_json, false);
        
        $message = array();
        
        if (!isset($user_info['error']))
        {
            $check_user = new Xdb;
            $check_user_exists = $check_user->select_fields('id, fb_id, name, surname, gender, activated, address, city')
                                            ->set_table($this->table)
                                            ->where(array('email' => $data_object->email))
                                            ->db_select(false);
            
            $user_profile_pic_json = json_decode(file_get_contents('http://graph.facebook.com/' . $user_info['id'] . '/picture?redirect=0&height=200&width=200&type=normal'), true);
            $user_profile_pic = $user_profile_pic_json['data']['url'];
            
            if (is_array($check_user_exists) && isset($check_user_exists[0]['id']) && isset($check_user_exists[0]['fb_id']) && $check_user_exists[0]['fb_id'] != 0)
            {
                $message = array('result' => 1, 'show_message' => 'logged_in successfully', 'authentication_token' => array('email' => $data_object->email, 'security_token' => sha1($data_object->email . $salt)));
            }
            elseif (is_array($check_user_exists) && isset($check_user_exists[0]['id']) && isset($check_user_exists[0]['fb_id']) && $check_user_exists[0]['fb_id'] == 0)
            {
                $user_session = $check_user_exists[0];
                
                $update_array = array();
                
                $date_time = new DateTime();
                $save_date_time = $date_time->format('Y-m-d H:i:s');
                
                $update_array['fb_id'] = $data_object->id;
                $update_array['fb_datetime_registration'] = $save_date_time;
                $update_array['fb_profile_url'] = $data_object->link;
                $update_array['fb_photo_url'] = $user_profile_pic;
                $update_array['datetime_last_change'] = $save_date_time;
                if (!$user_session['name']) $update_array['name'] = $data_object->first_name;
                if (!$user_session['surname']) $update_array['surname'] = $data_object->last_name;
                if (!$user_session['gender'])
                {
                    if ($data_object->gender == 'male')
                    {
                        $update_array['gender'] = 'm';
                    }
                    else
                    {
                        $update_array['gender'] = 'f';
                    }
                }
                
                if (!$user_session['activated'])
                {
                    $update_array['activated'] = 1;
                    $update_array['datetime_activation'] = $save_date_time;
                } 
                
                //if ($data_object->address && !$user_session['address']) $update_array['address'] = $data_object->address;
                //if ($data_object->city && $data_object->zip && !$user_session['city']) $update_array['city'] = $data_object->zip . ' ' . $data_object->city;
                
                $update_user_data = new Xdb;
                $update = $update_user_data->set_table($this->table)
                                           ->simple_update_fields($update_array)
                                           ->where(array('id' => $user_session['id']))
                                           ->db_update();
                
                $message = array('result' => 1, 'show_message' => 'logged_in successfully', 'authentication_token' => array('email' => $data_object->email, 'security_token' => sha1($data_object->email . $salt)));
            }
            else
            {
                $user_session = $check_user_exists[0];
                
                $this->fields['static_fields']['email']['value'] = $data_object->email;
                $this->fields['static_fields']['password']['value'] = sha1($salt . '-' . rand() . '-' . rand() . '-' . rand());
                $this->fields['static_fields']['project_id']['value'] = $project_id;
                
                $language_id = Languages::get_language_id($selected_language);
                $current_country = Countries::get_country_by_language($language_id);
                $this->fields['static_fields']['country']['value'] = $current_country;
                $this->fields['static_fields']['language']['value'] = $selected_language;
        
                $bc = new Browscap($_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.third.party/browscap.cache/');
                $current_browser = $bc->getBrowser(null, true);
                $this->fields['static_fields']['system']['value'] = json_encode($current_browser);
                
                $date_time = new DateTime();
                $save_date_time = $date_time->format('Y-m-d H:i:s');
                
                $this->fields['static_fields']['datetime_registration']['value'] = $save_date_time;
                $this->fields['static_fields']['datetime_last_change']['value'] = $save_date_time;
                $this->fields['static_fields']['datetime_last_syscheck']['value'] = $save_date_time;
                $this->fields['static_fields']['datetime_activation']['value'] = $save_date_time;
                
                $this->fields['static_fields']['activated']['value'] = 1;
                
                
                $this->fields['static_fields']['fb_id']['value'] = $data_object->id;
                $this->fields['static_fields']['fb_profile_url']['value'] = $data_object->link;
                if (isset($data_object->photoURL) && $data_object->photoURL) $this->fields['static_fields']['fb_photo_url']['value'] = $user_profile_pic;
                $this->fields['static_fields']['fb_datetime_registration']['value'] = $save_date_time;
                
                $this->fields['static_fields']['name']['value'] = $data_object->first_name;
                $this->fields['static_fields']['surname']['value'] = $data_object->last_name;
                
                if (isset($data_object->gender) && $data_object->gender)
                {
                    if ($data_object->gender == 'male')
                    {
                        $this->fields['static_fields']['gender']['value'] = 'm';
                    }
                    else
                    {
                        $this->fields['static_fields']['gender']['value'] = 'f';
                    }
                }
                
                //if (isset($data_object->address) && $data_object->address) $this->fields['static_fields']['address']['value'] = $data_object->address;
                //if (isset($data_object->city) && $data_object->city && $data_object->zip) $this->fields['static_fields']['city']['value'] = $data_object->zip . ' ' . $data_object->city;
                /*
                Stats::write_stats('Users', 'fb_registered');
                Stats::write_stats('Users', 'registered');
                Stats::write_stats('Users', 'activated');
                */
                
                Stats::write_stats('Users', array('fb_registered', 'country_fb_registered_' . $current_country, 'registered', 'country_registered_' . $current_country, 'activated', 'country_activated_' . $current_country));
                
                $xdb_user_insert = new Xdb;
                $last_user_id = $xdb_user_insert->db_insert($this->table, $this->fields);
                
                // start GA tracking
                $tracker = new GoogleAnalytics\Tracker('MO-42907887-1', 'avantbon.si');
                
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
                $event->setCategory('Uporabnik');    //string, required
                $event->setAction('Registracija');        //string, required
                $event->setLabel($last_user_id);          //string, not required
                $event->setNoninteraction('true');
                //$event->setValue($offer['view_discount_price']);  
            
                //track event
                $tracker->trackEvent($event,$session,$visitor);
                // end GA tracker
                
                $message = array('result' => 1, 'show_message' => 'logged_in successfully', 'authentication_token' => array('email' => $data_object->email, 'security_token' => sha1($data_object->email . $salt)));
            }
        }
        else
        {
            $message = array('result' => 0, 'show_message' => $user_info['error']['message']);
        }
        
        return $message;
    }
}

?>