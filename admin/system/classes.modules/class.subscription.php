<?php

/**
 * @author Danijel
 * @copyright 2013
 * 
 * @moduleCategory application
 * @moduleName Subscription
 */

class Subscription extends Module
{
    protected $table;
    public $fields = array();
    
    protected $table_packages;
    public $fields_packages = array();
    
    public function __construct()
    {
        $this->table = 'www_subscriptions';
        $this->fields = array();
        
        $this->table_packages = 'www_packages';
        $this->fields_packages = array(
            'static_fields' => array(
                'project_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'title' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'duration_months' => array('value' => '', 'field_type' => 'INT( 3 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'prices' => array('value' => '', 'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => false),
                'published' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'trashed' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false)
            ),
            'dynamic_fields' => array(
                'title' => array(
                    'value' => '', 
                    'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => true,
                    'html' => array(
                        'type' => 'input', 
                        'label' => 'Title:'), 
                    'html_attributes' => array(
                        'class' => 'input-xxlarge html_edit_simple', 
                        'type' => 'text')),
                'description' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => true,
                    'html' => array(
                        'type' => 'textarea', 
                        'label' => 'Description:'), 
                    'html_attributes' => array(
                        'class' => 'span12 html_edit_advanced', 
                        'rows' => 16,
                        'type' => 'text')),
            )
        );
        
        $this->cache_setting = true;
    }
    
    public function subscription_menu_add()
    {
        return '<li class="nav-header">Subscriptions</li>
        <li><a href="/admin/interface/applications/purchased_subscriptions/" class="follow">Purchased subscriptions</a></li>
        <li><a href="/admin/interface/applications/subscription_packages/" class="follow">Subscription packages</a></li>';
    }
    
    public function packages_get_all()
    {
        $package = new Subscription;
        $xdb_packages = new Xdb;
        $xdb_packages_rows = $xdb_packages->set_table($package->table_packages)
                                          ->where(array('trashed' => 0))
                                          ->group_by('title')
                                          //->db_select(true, 0, 'subscription_packages');
                                          ->db_select(false);
        return $xdb_packages_rows;
    }
    
    public function packages_www_get_all()
    {
        global $selected_language, $current_country, $project_id;
        $selected_country = strtolower($current_country);
        $package = new Subscription;
        $xdb_packages = new Xdb;
        $xdb_packages_rows = $xdb_packages->set_table($package->table_packages)
                                          ->where(array('trashed' => 0, 'project_id' => $project_id))
                                          ->db_select(true, 0, strtolower(get_class($package)));
        
        if (is_array($xdb_packages_rows))
        {
            $counter = 0;
            foreach ($xdb_packages_rows as $row => $value)
            {
                $prices_decode = str_replace('&quot;', '"', $xdb_packages_rows[$row]['prices']);
                unset($xdb_packages_rows[$row]['prices']);
                $xdb_packages_rows[$row]['prices'] = json_decode($prices_decode, true);
                if (isset($xdb_packages_rows[$row]['prices']['original_price_' . $selected_country])) $xdb_packages_rows[$row]['view_original_price'] = $xdb_packages_rows[$row]['prices']['original_price_' . $selected_country]; else $xdb_packages_rows[$row]['view_original_price'] = $xdb_packages_rows[$row]['prices']['original_price'];
                if (isset($xdb_packages_rows[$row]['prices']['discount_price_' . $selected_country])) $xdb_packages_rows[$row]['view_discount_price'] = $xdb_packages_rows[$row]['prices']['discount_price_' . $selected_country]; else $xdb_packages_rows[$row]['view_discount_price'] = $xdb_packages_rows[$row]['prices']['discount_price'];
                if (isset($xdb_packages_rows[$row]['prices']['discount_' . $selected_country])) $xdb_packages_rows[$row]['view_discount'] = $xdb_packages_rows[$row]['prices']['discount_' . $selected_country]; else $xdb_packages_rows[$row]['view_discount'] = $xdb_packages_rows[$row]['prices']['discount'];
                
                foreach($package->fields_packages['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
                // foreach($offer->fields['dynamic_fields'] as $dynamic_field)
                {
                    //$xdb_packages_rows[$counter]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_packages_rows[$counter][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                    //unset($xdb_packages_rows[$counter][$dynamic_field . '_' . $selected_language]);
                    
                    $xdb_packages_rows[$row]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_packages_rows[$row][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                    unset($xdb_packages_rows[$row][$dynamic_field . '_' . $selected_language]);
                }
                
                $counter++;
            }
        }
        
        return $xdb_packages_rows;
    }
    
    public function package_www_get_by_id($id)
    {
        global $selected_language, $current_country, $project_id;
        $selected_country = strtolower($current_country);
        $package = new Subscription;
        $xdb_packages = new Xdb;
        $xdb_packages_rows = $xdb_packages->set_table($package->table_packages)
                                          ->where(array('trashed' => 0, 'id' => $id))
                                          ->limit(1)
                                          ->db_select(true, 0, strtolower(get_class($package)));
        
        if (isset($xdb_packages_rows[0]))
        {
            $prices_decode = str_replace('&quot;', '"', $xdb_packages_rows[0]['prices']);
            //print_r(json_decode($prices_decode, true));
            unset($xdb_packages_rows[0]['prices']);
            $xdb_packages_rows[0]['prices'] = json_decode($prices_decode, true);
            if (isset($xdb_packages_rows[0]['prices']['original_price_' . $selected_country])) $xdb_packages_rows[0]['view_original_price'] = $xdb_packages_rows[0]['prices']['original_price_' . $selected_country]; else $xdb_packages_rows[0]['view_original_price'] = $xdb_packages_rows[0]['prices']['original_price'];
            if (isset($xdb_packages_rows[0]['prices']['discount_price_' . $selected_country])) $xdb_packages_rows[0]['view_discount_price'] = $xdb_packages_rows[0]['prices']['discount_price_' . $selected_country]; else $xdb_packages_rows[0]['view_discount_price'] = $xdb_packages_rows[0]['prices']['discount_price'];
            if (isset($xdb_packages_rows[0]['prices']['discount_' . $selected_country])) $xdb_packages_rows[0]['view_discount'] = $xdb_packages_rows[0]['prices']['discount_' . $selected_country]; else $xdb_packages_rows[0]['view_discount'] = $xdb_packages_rows[0]['prices']['discount'];
            
            $xdb_packages_rows[0]['view_price_without_vat'] = number_format($xdb_packages_rows[0]['view_discount_price'] / 1.08, 2);
            $xdb_packages_rows[0]['view_vat'] = number_format($xdb_packages_rows[0]['view_discount_price'] - $xdb_packages_rows[0]['view_price_without_vat'], 2);
            
            foreach($package->fields_packages['dynamic_fields'] as $dynamic_field => $dynamic_field_value)
            // foreach($offer->fields['dynamic_fields'] as $dynamic_field)
            {
                $xdb_packages_rows[0]['dynamic_' . $dynamic_field] = html_entity_decode($xdb_packages_rows[0][$dynamic_field . '_' . $selected_language], ENT_QUOTES, 'UTF-8');
                unset($xdb_packages_rows[0][$dynamic_field . '_' . $selected_language]);
            }
            
            return $xdb_packages_rows[0];
        }
        else
        {
            return false;
        }
    }
    
    public function package_get_by_id($id)
    {
        $package = new Subscription;
        $xdb_packages = new Xdb;
        $xdb_packages_rows = $xdb_packages->set_table($package->table_packages)
                                          ->where(array('trashed' => 0, 'id' => $id))
                                          ->limit('1')
                                          //->db_select(true, 0, 'subscription_packages');
                                          ->db_select(false);
        
        if (isset($xdb_packages_rows[0]))
        {
            $prices_decode = str_replace('&quot;', '"', $xdb_packages_rows[0]['prices']);
            //print_r(json_decode($prices_decode, true));
            unset($xdb_packages_rows[0]['prices']);
            $xdb_packages_rows[0]['prices'] = json_decode($prices_decode, true);
            
            return $xdb_packages_rows[0];
        }
        else
        {
            return false;
        }
    }
    
    public function package_insert_new($title, $edit_for_project)
    {
        $package = new Subscription;
        $projects = Projects::get_all_projects();
        foreach ($projects as $project)
        {
            $package->fields_packages['static_fields']['project_id']['value'] = $project['id'];
            $package->fields_packages['static_fields']['title']['value'] = $title;
            
            $xdb_package_insert = new Xdb;
            $insert_new = $xdb_package_insert->db_insert_content($project['id'], $package->table_packages, $package->fields_packages, strtolower(get_class($package)));
            
            if ($edit_for_project == $project['id'])
            {
                $last_id = $insert_new;
            }
            
            return $last_id;
        }
    }
    
    public function prices2json($values, $project_id)
    {
        $values = str_replace('ANDPARAMETER', '&', $values);
        //$values = str_replace('PLUSSIGN', '+', $values);
        //echo $values;
        //$values = str_replace('&amp%3B', '%26', $values);
        $values = str_replace('%3Cbr+style%3D%22%22%3E', '', $values);
        $values = str_replace('%0D%0A%3C%2Fp%3E%3Cbr+style%3D%22%22+class%3D%22aloha-cleanme%22%3E', '', $values);
        $values = str_replace('<br style="">', '', $values);
        $values = str_replace('<br style="" class="aloha-cleanme">', '', $values);
        parse_str($values, $new_values);
        
        $prices['original_price'] = number_format(str_replace(',', '.', $new_values['original_price']), 2, '.', '');
        unset($new_values['original_price']);
        
        $prices['discount_price'] = number_format(str_replace(',', '.', $new_values['discount_price']), 2, '.', '');
        unset($new_values['discount_price']);
        
        $prices['discount'] = $new_values['discount'];
        unset($new_values['discount']);
        
        $countries_currency_own = Countries::get_all_own_currency_countries($project_id);
        foreach ($countries_currency_own as $country_currency )
        {
            $prices['original_price_' . strtolower($country_currency['iso_code'])] = number_format(str_replace(',', '.', $new_values['original_price_' . strtolower($country_currency['iso_code'])]), 2, '.', '');
            unset($new_values['original_price_' . strtolower($country_currency['iso_code'])]);
            
            $prices['discount_price_' . strtolower($country_currency['iso_code'])] = number_format(str_replace(',', '.', $new_values['discount_price_' . strtolower($country_currency['iso_code'])]), 2, '.', '');
            unset($new_values['discount_price_' . strtolower($country_currency['iso_code'])]);
            
            $prices['discount_' . strtolower($country_currency['iso_code'])] = $new_values['discount_' . strtolower($country_currency['iso_code'])];
            unset($new_values['discount_' . strtolower($country_currency['iso_code'])]);
        }
        
        $prices_encoded = json_encode($prices);
        $prices_encoded = str_replace('/', '', $prices_encoded);
        $new_values['prices'] = $prices_encoded;
        $return_query = http_build_query($new_values, '', 'ANDPARAMETER'); 
        //echo $return_query;
        return $return_query;
    }
    
    public function package_save($id, $project_id, $new_values)
    {
        $package = new Subscription;
        
        $the_values = $package->prices2json($new_values, $project_id);
        //die($the_values);
        
        $xdb_package_update = new Xdb;
        $update = $xdb_package_update->set_table($package->table_packages)
                                     ->update_fields($project_id, $package->fields_packages, $the_values, 'update')
                                     ->where(array('id' => $id))
                                     ->db_update(strtolower(get_class($package)), array('trashed'));
        $xdb_package_update->update_permanent_cache_single($package->table_packages, $id);
    }
    
    public function check_user_db_subscription()
    {
        $user_session = $_SESSION['logged_in_user'];
        $user_id = $user_session['id'];
        
        $user = new Users;
        $check_user = new Xdb;
        $check_user_subscription = $check_user->select_fields('subscription_start_date, subscription_end_date')
                                              ->set_table($user->table)
                                              ->where(array('id' => $user_id))
                                              ->db_select(false);
        
        $db_start_date = $check_user_subscription[0]['subscription_start_date'];
        $db_end_date = $check_user_subscription[0]['subscription_end_date'];
        
        $_SESSION['logged_in_user']['subscription_start_date'] = $db_start_date;
        $_SESSION['logged_in_user']['subscription_end_date'] = $db_end_date;
        
        $current_time = new DateTime();
        $now = $current_time->format('Y-m-d');
        
        if ($db_start_date != '0000-00-00' && $db_end_date != '0000-00-00' && $now >= $db_start_date && $now <= $db_end_date)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function check_user_session_subscription()
    {
        if (isset($_SESSION['logged_in_user']))
        {
            $user_session = $_SESSION['logged_in_user'];
            //$language_selected = $_COOKIE['language'];
            
            $current_time = new DateTime();
            $now = $current_time->format('Y-m-d');
            
            if (isset($user_session['subscription_start_date']) && isset($user_session['subscription_end_date']) && $user_session['subscription_start_date'] != '0000-00-00' && $user_session['subscription_end_date'] != '0000-00-00' && $now <= $user_session['subscription_end_date'])
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
    public function check_user_subscription()
    {
        if (isset($_SESSION['logged_in_user']))
        {
            $user_session = $_SESSION['logged_in_user'];
            $language_selected = $_COOKIE['language'];
            
            $current_time = new DateTime();
            $now = $current_time->format('Y-m-d');
            
            if (!isset($user_session['subscription_start_date']) || !isset($user_session['subscription_end_date']) || $user_session['subscription_start_date'] == '0000-00-00' || $user_session['subscription_end_date'] == '0000-00-00' || $current_time > $user_session['subscription_end_date'])
            {
                // check in database
                $user_id = $user_session['id'];
                
                $user = new Users;
                $check_user = new Xdb;
                $check_user_subscription = $check_user->select_fields('subscription_start_date, subscription_end_date')
                                                      ->set_table($user->table)
                                                      ->where(array('id' => $user_id))
                                                      ->db_select(false);
                
                $db_start_date = $check_user_subscription[0]['subscription_start_date'];
                $db_end_date = $check_user_subscription[0]['subscription_end_date'];
                
                $_SESSION['logged_in_user']['subscription_start_date'] = $db_start_date;
                $_SESSION['logged_in_user']['subscription_end_date'] = $db_end_date;
                
                if ($db_start_date != '0000-00-00' && $db_end_date != '0000-00-00' && $now >= $db_start_date && $now <= $db_end_date)
                {
                    $message = array('result' => 1);
                    echo json_encode($message);
                }
                else
                {
                    $message = array('result' => 0, 'error' => 'no subscription', 'redirect' => 'http://' . $_SERVER['HTTP_HOST'] . '/' . $language_selected . '/subscription/packages/');
                    throw new Exception(json_encode($message));
                }
            }
            else
            {
                $current_time = new DateTime();
                $now = $current_time->format('Y-m-d');
                
                if ($user_session['subscription_start_date'] != '0000-00-00' && $user_session['subscription_end_date'] != '0000-00-00' && ($now >= $user_session['subscription_start_date']) && ($now <= $user_session['subscription_end_date'])) 
                {
                    $message = array('result' => 1);
                    //throw new Exception(json_encode($message));
                    echo json_encode($message);
                }
                else
                {
                    $message = array('result' => 0, 'error' => 'no subscription', 'redirect' => 'http://' . $_SERVER['HTTP_HOST'] . '/' . $language_selected . '/subscription/packages/');
                    throw new Exception(json_encode($message));
                }
            }
        }
        else
        {
            $message = array('result' => 0, 'error' => 'not logged in', 'redirect' => 'showModal()');
            throw new Exception(json_encode($message));
        }
    }
    
    public function set_subscription_package($package_name, $project_id)
    {
        if (isset($_SESSION['logged_in_user']))
        {
            $package_name_explode = explode('-', $package_name);
            $_SESSION['logged_in_user']['selected_package']['package_name'] = $package_name;
            $_SESSION['logged_in_user']['selected_package']['package_id'] = $package_name_explode[1];
            
            $_SESSION['payment']['project_id'] = $project_id;
            $_SESSION['payment']['product_id'] = $package_name_explode[1];
            $_SESSION['payment']['product_type'] = 'Subscription';
            
            $message = array('result' => 1);
            echo json_encode($message);
        }
        else
        {
            $message = array('result' => 0, 'error' => 'not logged in', 'redirect' => 'showModal()');
            throw new Exception(json_encode($message));
        }
    }
    
    public function set_payment($payment_name)
    {
        if (isset($_SESSION['logged_in_user']))
        {
            $_SESSION['payment']['payment_name'] = $payment_name;
            $_SESSION['payment']['payment_info'] = Payment::get_selected_payment_info($payment_name);
            
            $message = array('result' => 1);
            echo json_encode($message);
        }
        else
        {
            $message = array('result' => 0, 'error' => 'not logged in', 'redirect' => 'showModal()');
            throw new Exception(json_encode($message));
        }
    }
    
    public function get_product_info($package_id)
    {
        return Subscription::package_get_by_id($package_id);
    }
    
    public function package_to_trash($id)
    {
        $package = new Subscription;
        $projects = Projects::get_all_projects();
        $trashed_object['static_fields']['trashed'] = $package->fields_packages['static_fields']['trashed'];
        $trashed_object['static_fields']['trashed']['value'] = 1;
        foreach ($projects as $project)
        {
            $project_id = $project['id'];
            $xdb_package_update = new Xdb;
            $update = $xdb_package_update->set_table($package->table_packages)
                                         ->update_fields($project_id, $trashed_object)
                                         ->where(array('id' => $id))
                                         ->db_update(strtolower(get_class($package)), array('trashed'));
            $xdb_package_update->update_permanent_cache_single($package->table_packages, $id);
        }
    }
    
    public function payment_confirmed_action($payment_id)
    {
        $payment = new Payment;
        $offline_payment_xdb = new Xdb;
        $offline_payment_row = $offline_payment_xdb->select_fields('*')
                                                   ->set_table($payment->table)
                                                   ->where(array('id' => $payment_id))
                                                   ->db_select(false);
        $payment_info = $offline_payment_row[0];
        
        $user_id = $payment_info['user_id'];
        $user_info = json_decode($payment_info['user_info'], true);
        $product_info = json_decode($payment_info['product_info'], true);
        
        $add_months = $product_info['title'];
        //$modified_time = date('Y-m-d', strtotime('+' . $add_months));
        
        $subscription_start_date = $user_info['subscription_start_date'];
        $subscription_end_date = $user_info['subscription_end_date'];
        
        $current_time = new DateTime();
        $now = $current_time->format('Y-m-d');
        
        //if (isset($user_info['subscription_start_date']) && isset($user_info['subscription_end_date']) && $user_info['subscription_start_date'] != '0000-00-00' && $user_info['subscription_end_date'] != '0000-00-00' && $current_time <= strtotime($user_info['subscription_end_date']))
        if (isset($user_info['subscription_start_date']) && isset($user_info['subscription_end_date']) && $user_info['subscription_start_date'] != '0000-00-00' && $user_info['subscription_end_date'] != '0000-00-00' && $now <= $user_info['subscription_end_date'])
        {
            $new_start_date = $user_info['subscription_start_date'];
            
            $date = new DateTime($user_info['subscription_end_date']);
            $date->modify('+' . $add_months);
            $new_end_date = $date->format('Y-m-d');
            
            $payment_subscription_start = $user_info['subscription_end_date'];
            $payment_subscription_end = $new_end_date;
        }
        else
        {
            $new_start_date = $current_time->format('Y-m-d');
            $new_end_date = date('Y-m-d', strtotime('+' . $add_months));
            
            $payment_subscription_start = $new_start_date;
            $payment_subscription_end = $new_end_date;
        }
        
        $user = new Users;
        $update_user = new Xdb;
        $update = $update_user->set_table($user->table)
                              ->simple_update_fields(array('subscription_start_date' => $new_start_date, 'subscription_end_date' => $new_end_date))
                              ->where(array('id' => $user_id))
                              ->db_update();
        
        $update_payment = new Xdb;
        $update_time = $update_payment->set_table($payment->table)
                                      ->simple_update_fields(array('subscription_start_date' => $payment_subscription_start, 'subscription_end_date' => $payment_subscription_end, 'subscription_used' => 0))
                                      ->where(array('id' => $payment_id))
                                      ->db_update();
        
        $user_language = $user_info['language'];
        $selected_language = $user_info['language'];
        $user_email = $user_info['email'];
        
        $product_int = $product_info['title'];
        
        $product_title = $product_info['title_' . $user_language];
        $price = $payment_info['discount_currency_price'];
        $currency = $payment_info['currency'];
        
        $payment_type = $payment_info['payment_type'];
        
        $payment_method_xdb = new Xdb;
        $payment_method_row = $payment_method_xdb->select_fields('payment_method')
                                                 ->set_table($payment->settings_table)
                                                 ->where(array('payment_plugin' => $payment_type))
                                                 ->db_select(false);
        
        $payment_method = translate($payment_method_row[0]['payment_method']);
        
        $start_subscription = new DateTime($payment_subscription_start);
        $start_subscription_date = $start_subscription->format('d.m.Y');
        
        $end_subscription = new DateTime($payment_subscription_end);
        $end_subscription_date = $end_subscription->format('d.m.Y');
        
        if ($product_int == '12 months' && $now < '2014-02-15')
        {
            $mailer = new xMailer;
            $mailer_result = $mailer->send_mail('Valentine - Payment confirmed email', $user_email, array('*product_title*' => $product_title, '*price*' => $price, '*currency*' => $currency, '*payment_method*' => $payment_method, '*start_subscription_date*' => $start_subscription_date, '*end_subscription_date*' => $end_subscription_date));
        }
        else
        {
            $mailer = new xMailer;
            $mailer_result = $mailer->send_mail('Payment confirmed email', $user_email, array('*product_title*' => $product_title, '*price*' => $price, '*currency*' => $currency, '*payment_method*' => $payment_method, '*start_subscription_date*' => $start_subscription_date, '*end_subscription_date*' => $end_subscription_date));
        }
    }
}

 ?>