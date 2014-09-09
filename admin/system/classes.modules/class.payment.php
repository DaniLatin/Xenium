<?php

/**
 * @author Danijel
 * @copyright 2013
 */

use UnitedPrototype\GoogleAnalytics as GoogleAnalytics;

class Payment extends Module
{
    public $table;
    public $settings_table;
    
    public $fields = array();
    public $settings_fields = array();
    
    public function __construct()
    {
        $this->table = 'www_payments';
        
        $this->fields = array(
        'static_fields' =>
            array(
                'invoice_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'apply_to_invoice_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'project_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'product_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'product_type' => array('value' => '', 'field_type' => 'VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'product_info' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
                'user_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'user_info' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
                'user_system' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
                'original_price' => array('value' => '', 'field_type' => 'DECIMAL( 10, 2 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'discount_price' => array('value' => '', 'field_type' => 'DECIMAL( 10, 2 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'discount' => array('value' => '', 'field_type' => 'DECIMAL( 10, 2 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'original_currency_price' => array('value' => '', 'field_type' => 'DECIMAL( 10, 2 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'discount_currency_price' => array('value' => '', 'field_type' => 'DECIMAL( 10, 2 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'discount_currency' => array('value' => '', 'field_type' => 'DECIMAL( 10, 2 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'currency' => array('value' => '', 'field_type' => 'VARCHAR( 4 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'language' => array('value' => '', 'field_type' => 'VARCHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'country' => array('value' => '', 'field_type' => 'VARCHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'payment_type' => array('value' => '', 'field_type' => 'VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'datetime_created' => array('value' => '', 'field_type' => 'DATETIME NOT NULL', 'index' => true, 'admin_editable' => false),
                'datetime_payed' => array('value' => '', 'field_type' => 'DATETIME NOT NULL', 'index' => true, 'admin_editable' => false),
                'datetime_error' => array('value' => '', 'field_type' => 'DATETIME NOT NULL', 'index' => true, 'admin_editable' => false),
                'datetime_cancelled' => array('value' => '', 'field_type' => 'DATETIME NOT NULL', 'index' => true, 'admin_editable' => false),
                'reference' => array('value' => '', 'field_type' => 'VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'reference_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'reference_no' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'reference_model' => array('value' => '', 'field_type' => 'VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'status' => array('value' => '', 'field_type' => 'VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'error_report' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
                'invoice' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
                'SSGA_session' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
                'SSGA_visitor' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
                'SSGA_unique_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
            )
        );
        
        $this->settings_table = 'www_payments_settings';
        
        $this->settings_fields = array(
        'static_fields' => 
            array(
                'project_id' => array('value' => '', 'field_type' => 'INT NOT NULL', 'index' => true, 'admin_editable' => false),
                'payment_plugin' => array('value' => '', 'field_type' => 'VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'payment_method' => array('value' => '', 'field_type' => 'VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'payment_url' => array('value' => '', 'field_type' => 'VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'payment_settings' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
                'payment_countries' => array(
                    'value' => '', 
                    'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 
                    'admin_editable' => false
                ),
            )
        );
        
        $this->load_plugins();
        
        $this->cache_setting = true;
    }
    
    public function new_payment()
    {
        global $language_selected, $current_country, $reference, $session, $visitor, $unique_id;
        
        $user_session = $_SESSION['logged_in_user'];
        $payment_session = $_SESSION['payment'];
        
        $payment = new Payment;
        $payment->fields['static_fields']['user_id']['value'] = $user_session['id'];
        $payment->fields['static_fields']['user_info']['value'] = json_encode(Users::get_user_info());
        
        $bc = new Browscap($_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.third.party/browscap.cache/');
        $current_browser = $bc->getBrowser(null, true);
        $payment->fields['static_fields']['user_system']['value'] = json_encode($current_browser);
        
        $payment->fields['static_fields']['project_id']['value'] = $payment_session['project_id'];
        $payment->fields['static_fields']['product_id']['value'] = $payment_session['product_id'];
        $payment->fields['static_fields']['product_type']['value'] = $payment_session['product_type'];
        
        $get_product_info = call_user_func(array($payment_session['product_type'], 'get_product_info'), $payment_session['product_id']);
        
        $payment->fields['static_fields']['original_price']['value'] = $get_product_info['prices']['original_price'];
        $payment->fields['static_fields']['discount_price']['value'] = $get_product_info['prices']['discount_price'];
        $payment->fields['static_fields']['discount']['value'] = $get_product_info['prices']['discount'];
        
        if (isset($get_product_info['prices']['original_price_' . strtolower($current_country)]) && isset($get_product_info['prices']['discount_price_' . strtolower($current_country)]) && isset($get_product_info['prices']['discount_' . strtolower($current_country)]))
        {
            $payment->fields['static_fields']['original_currency_price']['value'] = $get_product_info['prices']['original_price_' . strtolower($current_country)];
            $payment->fields['static_fields']['discount_currency_price']['value'] = $get_product_info['prices']['discount_price_' . strtolower($current_country)];
            $payment->fields['static_fields']['discount_currency']['value'] = $get_product_info['prices']['discount_' . strtolower($current_country)];
        }
        else
        {
            $payment->fields['static_fields']['original_currency_price']['value'] = $get_product_info['prices']['original_price'];
            $payment->fields['static_fields']['discount_currency_price']['value'] = $get_product_info['prices']['discount_price'];
            $payment->fields['static_fields']['discount_currency']['value'] = $get_product_info['prices']['discount'];
        }
        
        $payment->fields['static_fields']['product_info']['value'] = json_encode($get_product_info);
        
        $payment->fields['static_fields']['payment_type']['value'] = $payment_session['payment_name'];
        
        $payment->fields['static_fields']['language']['value'] = $language_selected;
        $payment->fields['static_fields']['country']['value'] = $current_country;
        $payment->fields['static_fields']['currency']['value'] = Countries::get_country_currency();
        
        $payment->fields['static_fields']['status']['value'] = 'pending';
        
        $date_time = new DateTime();
        $create_date_time = $date_time->format('Y-m-d H:i:s');
        
        $payment->fields['static_fields']['datetime_created']['value'] = $create_date_time;
        
        // SSGA data
        if (isset($_SESSION['SSGA_session'])) $payment->fields['static_fields']['SSGA_session']['value'] = $_SESSION['SSGA_session'];
        if (isset($_COOKIE['SSGA_visitor'])) $payment->fields['static_fields']['SSGA_visitor']['value'] = $_COOKIE['SSGA_visitor'];
        if (isset($_COOKIE['SSGA_UniqueID3'])) $payment->fields['static_fields']['SSGA_unique_id']['value'] = $_COOKIE['SSGA_UniqueID3'];
        // end SSGA data
        
        $xdb_payment_insert = new Xdb;
        $last_payment_id = $xdb_payment_insert->db_insert($payment->table, $payment->fields);
        
        $_SESSION['payment']['payment_id'] = $last_payment_id;
        
        $reference_info = $payment->generate_reference($last_payment_id);
        
        $reference = $reference_info['reference'];
        
        $_SESSION['payment']['reference_info'] = $reference_info;
        
        $update_payment_reference = new Xdb;
        $update = $update_payment_reference->set_table($payment->table)
                                           ->simple_update_fields(array('reference' => $reference_info['reference'], 'reference_id' => $reference_info['reference_id'], 'reference_no' => $reference_info['reference_no'], 'reference_model' => $reference_info['reference_model']))
                                           ->where(array('id' => $last_payment_id))
                                           ->db_update();
        
        Stats::write_stats('Payment', array(
                                        'pending_payment', 
                                        'pending_payment_amount' => array('value' => $get_product_info['prices']['discount_price']), 
                                        'pending_payment_' . $payment_session['payment_name'],
                                        'pending_payment_' . $payment_session['payment_name'] . '_amount' => array('value' => $get_product_info['prices']['discount_price']),
                                        'pending_payment_' . $current_country,
                                        'pending_payment_' . $current_country . '_amount' => array('value' => $get_product_info['prices']['discount_price']),
                                        'pending_payment_' . str_replace(' ', '_', $get_product_info['title']),
                                        'pending_payment_' . str_replace(' ', '_', $get_product_info['title']) . '_amount' => array('value' => $get_product_info['prices']['discount_price']),
                                      ));
    }
    
    public function check_payment_plugins()
    {
        $projects = Projects::get_all_projects();
        $installed_payments = Payment::get_all_payments();
        
        $filename = $_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.modules/plugins.payment/plugin_*.php';
        foreach (glob($filename) as $filefound)
        {
            $tokens = token_get_all(file_get_contents($filefound));
            $comments = array();
            foreach($tokens as $token) {
                if($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT) {
                    $comments[] = $token[1];
                    $payment_type_pattern = "/@paymentType (.*?)\n/";
                    $payment_name_pattern = "/@paymentName (.*?)\n/";
                    $payment_method_pattern = "/@paymentMethod (.*?)\n/";
                    //preg_match($module_category_pattern, $token[1], $category_matches);
                    preg_match($payment_type_pattern, $token[1], $payment_type);
                    preg_match($payment_name_pattern, $token[1], $payment_name);
                    preg_match($payment_method_pattern, $token[1], $payment_method);
                    //print_r($name_matches);
                    //print_r($text_editor_matches);
                    
                    if (isset($payment_type[1]))
                    {
                        $set_payment_type = trim($payment_type[1]);
                        $set_payment_name = trim($payment_name[1]);
                        $set_payment_method = trim($payment_method[1]);
                        
                        //echo trim($payment_method[1]);
                        foreach ($projects as $project)
                        {
                            if (!isset($installed_payments[$set_payment_type . '_' . $project['id']]))
                            {
                                Payment::new_payment_plugin($project['id'], $set_payment_type, $set_payment_method);
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function new_payment_plugin($project_id, $payment_module, $payment_method)
    {
        $payment = new Payment;
        $payment_insert = new Xdb;
        
        $payment->settings_fields['static_fields']['project_id']['value'] = $project_id;
        $payment->settings_fields['static_fields']['payment_plugin']['value'] = $payment_module;
        $payment->settings_fields['static_fields']['payment_method']['value'] = $payment_method;
        //$payment->settings_fields['static_fields']['payment_settings']['value'] = call_user_func('Payment::' . strtolower($payment_name) . '_settings_structure');
        $payment->settings_fields['static_fields']['payment_settings']['value'] = call_user_func(array($payment, strtolower($payment_module) . '_settings_structure'));
        
        $xdb_payment_insert = new Xdb;
        $insert_new = $xdb_payment_insert->db_insert_content($project_id, $payment->settings_table, $payment->settings_fields, strtolower(get_class($payment)));
    }
    
    public function get_all_payments()
    {
        $payment = new Payment;
        $payment_xdb = new Xdb;
        $payment_xdb_rows = $payment_xdb->set_table($payment->settings_table)
                                        ->db_select(true, 0, strtolower(get_class($payment)));
        
        //return $payment_xdb_rows;
        $sorted_payments = array();
        if (is_array($payment_xdb_rows))
        {
            foreach ($payment_xdb_rows as $row)
            {
                $sorted_payments[$row['payment_plugin'] . '_' . $row['project_id']] = $row;
            }
        }
        
        return $sorted_payments;
    }
    
    public function get_all_www_payments()
    {
        
    }
    
    public function get_payment_info_by_ref($reference)
    {
        $payment = new Payment;
        $payment_info = new Xdb;
        $payment_info_rows = $payment_info->set_table($payment->table)
                                          ->where(array('reference' => $reference))
                                          ->limit('1')
                                          ->db_select(false);
        
        if (isset($payment_info_rows[0]))
        {
            return $payment_info_rows[0];
        }
        else
        {
            return false;
        }
    }
    
    public function get_payment_info_by_id($id)
    {
        $payment = new Payment;
        $payment_info = new Xdb;
        $payment_info_rows = $payment_info->set_table($payment->table)
                                          ->where(array('id' => $id))
                                          ->limit('1')
                                          ->db_select(false);
        
        if (isset($payment_info_rows[0]))
        {
            return $payment_info_rows[0];
        }
        else
        {
            return false;
        }
    }
    
    public function generate_reference_default($purchase_id)
    {
        global $current_country, $project_info;
        
        $ref_platform_id = $project_info['ref_id'];
        $year = substr(date("Y"), -1);
        $purchase_id_len = strlen($purchase_id);
        $ref_country_id = Countries::get_country_ref_id();
        
        if (strlen($ref_country_id) == 1)
        {
            $ref_country_id = '0' . $ref_country_id;
        }
        
        $ref_nmb = $purchase_id . $ref_platform_id . $ref_country_id;
        
        $ref_nmb_len = strlen($ref_nmb);
        $ref_num_zeros_missing = 9 - $ref_nmb_len;
        
        $ref_num_add_zeros = '';
        
        for ( $ref_counter = 1; $ref_counter <= $ref_num_zeros_missing; $ref_counter += 1) 
        {
            $ref_num_add_zeros .= '0';
        }
        
        $reference['reference'] = $year . $ref_num_add_zeros . $ref_nmb;
        
        $reference['reference_model'] = '';
        $reference['reference_id'] = $ref_nmb;
        $reference['reference_no'] = $ref_nmb;
        
        return $reference;
    }
    
    public function generate_reference($payment_id)
    {
        global $current_country, $project_info, $purchase_id;
        $purchase_id = $payment_id;
        
        $payment_reference = new Payment;
        $country_reference_function = 'generate_reference_' . strtolower($current_country);
        
        //if (method_exists($payment_reference, $country_reference_function))
        if (call_user_func(array($payment_reference, $country_reference_function)))
        {
            $reference_number = call_user_func(array($payment_reference, $country_reference_function));
        }
        else
        {
            $reference_number = self::generate_reference_default($purchase_id);
        }
        
        return $reference_number;
    }
    
    public function get_selected_payment_info($selected_payment)
    {
        $payment = new Payment;
        $xdb_payment = new Xdb;
        //print_r($_SESSION['payment']);
        //$selected_payment = $_SESSION['payment']['payment_name'];
        //echo $selected_payment;
        $xdb_payment_rows = $xdb_payment->set_table($payment->settings_table)
                                        ->where(array('payment_plugin' => $selected_payment))
                                        ->limit(1)
                                        //->db_select(true, 0, 'single');
                                        ->db_select(false);
        return $xdb_payment_rows[0];
    }
    
    public function go_to_payment()
    {
        $payment = new Payment;
        $payment_type = $_SESSION['payment']['payment_name'];
        unset($_SESSION['payment_domain_checked']);
        $payment->new_payment();
        return call_user_func(array($payment, 'go_to_' . $payment_type));
    }
    
    public function confirm_payment_offline($id)
    {
        global $project_id, $selected_language;
        
        $payment = new Payment;
        $offline_payment_xdb = new Xdb;
        $offline_payment_row = $offline_payment_xdb->select_fields('*')
                                                   ->set_table($payment->table)
                                                   ->where(array('reference' => $id, 'OR::id' => $id))
                                                   ->db_select(false);
        $payment_info = $offline_payment_row[0];
        
        if ($payment_info['status'] == 'pending')
        {
            $project_id = $payment_info['project_id'];
            $selected_language = $payment_info['language'];
            
            $date_time = new DateTime();
            $confirmed_date_time = $date_time->format('Y-m-d H:i:s');
            
            // get last invoice id
            $last_invoice_id_xdb = new Xdb;
            $get_last_invoice_id = $last_invoice_id_xdb->select_fields('MAX(invoice_id)')
                                                       ->set_table($payment->table)
                                                       ->db_select(false);
                
            $last_invoice_id = $get_last_invoice_id[0]['MAX(invoice_id)'];
            $new_invoice_id = $last_invoice_id + 1;
            
            $payment_info['invoice_id'] = $new_invoice_id;
            $payment_info['datetime_payed'] = $confirmed_date_time;
            
            $invoice = Payment::load_invoice_prop($payment_info);
            
            $update_payment_confirmed = new Xdb;
            $update = $update_payment_confirmed->set_table($payment->table)
                                               ->simple_update_fields(array('invoice_id' => $new_invoice_id, 'datetime_payed' => $confirmed_date_time, 'status' => 'payed', 'invoice' => $invoice))
                                               ->where(array('reference' => $id, 'OR::id' => $id))
                                               ->db_update();
            
            $payment_id = $payment_info['id'];
            
            // run according function
            $run_module = $payment_info['product_type'];
            if (method_exists($run_module, 'payment_confirmed_action'))
            {
                //call_user_func($run_module . '::payment_confirmed_action');
                call_user_func_array(array($run_module, 'payment_confirmed_action'), array($payment_id));
            }
            
            unset($_SESSION['payment']);
            
            $product_info = json_decode($payment_info['product_info'], true);
            
            Stats::write_stats('Payment', array(
                                            'confirmed_payment', 
                                            'confirmed_payment_amount' => array('value' => $payment_info['discount_price']), 
                                            'confirmed_payment_' . $payment_info['payment_type'],
                                            'confirmed_payment_' . $payment_info['payment_type'] . '_amount' => array('value' => $payment_info['discount_price']),
                                            'confirmed_payment_' . $payment_info['country'],
                                            'confirmed_payment_' . $payment_info['country'] . '_amount' => array('value' => $payment_info['discount_price']),
                                            'confirmed_payment_' . str_replace(' ', '_', $product_info['title']),
                                            'confirmed_payment_' . str_replace(' ', '_', $product_info['title']) . '_amount' => array('value' => $payment_info['discount_price']),
                                          ));
            
            // Google tracking
            $tracker = new GoogleAnalytics\Tracker('MO-42907887-1', 'avantbon.si');
            $visitor = unserialize($payment_info['SSGA_visitor']);
            $session = unserialize($payment_info['SSGA_session']);
            $page = new GoogleAnalytics\Page($_SERVER['REQUEST_URI']);
            $page->setTitle('Avantbon - Ecommerce tracking');
            
            $order_id = $payment_id;
            
            $item = new GoogleAnalytics\Item();
            $item->setOrderId($order_id);
            $item->setSku($product_info['id']);
            $item->setName($product_info['title'] . ' - ' . $payment_info['payment_type']);
            $item->setQuantity('1');
            $item->setPrice($payment_info['discount_price']);
            
            $transaction = new GoogleAnalytics\Transaction();
            $transaction->setOrderId($order_id);
            $transaction->setAffiliation('Avantbon');
            $transaction->setTax($payment_info['discount_price'] - $payment_info['discount_price'] / 1.08);
            $transaction->setTotal($payment_info['discount_price']);
            $transaction->setShipping('0.00');
            $transaction->setCity('Ljubljana');
            $transaction->setRegion('Ljubljana');
            $transaction->setCountry('Slovenia');
            $transaction->addItem($item);
            $tracker->trackTransaction($transaction, $session, $visitor);
            
            $event = new GoogleAnalytics\Event();
            $event->setCategory('Uporabnik');    //string, required
            $event->setAction('Potrjen nakup članarine');        //string, required
            $event->setLabel($payment_info['user_id']);          //string, not required
            $event->setNoninteraction('true');
            $event->setValue($payment_info['discount_price']);  
        
            //track event
            $tracker->trackEvent($event,$session,$visitor);
            // end Google tracking
        }
        
        unset($_SESSION['payment']);
        
        return true;
    }
    
    public function check_payment_session()
    {
        global $project_id;
        
        if (!isset($_SESSION['payment']) && !isset($_SESSION['payment_domain_checked']))
        {
            $domains = Domains::get_domains_by_project($project_id);
            $current_domain = $_SERVER['HTTP_HOST'];
            
            foreach ($domains as $domain_key => $domain)
            {
                if ($domain == $current_domain)
                {
                    $new_key = $domain_key + 1;
                    if (isset($domains[$new_key]))
                    {
                        $check_next_domain = 'http://' . $domains[$new_key] . $_SERVER['REQUEST_URI'];
                        $_SESSION['payment_domain_checked'] = true;
                        header('Location: /' . $check_next_domain . '/');
                        die();
                    }
                    else
                    {
                        $check_next_domain = 'http://' . $domains[0] . $_SERVER['REQUEST_URI'];
                    }
                }
            }
        }
    }
    
    public function confirm_payment($reference = '')
    {
        global $tracker, $session, $visitor;
        
        if (isset($_SESSION['payment']))
        {
            $payment = new Payment;
            $payment_id = $_SESSION['payment']['payment_id'];
            
            $date_time = new DateTime();
            $confirmed_date_time = $date_time->format('Y-m-d H:i:s');
            
            // get last invoice id
            $last_invoice_id_xdb = new Xdb;
            $get_last_invoice_id = $last_invoice_id_xdb->select_fields('MAX(invoice_id)')
                                                       ->set_table($payment->table)
                                                       ->db_select(false);
            
            $last_invoice_id = $get_last_invoice_id[0]['MAX(invoice_id)'];
            $new_invoice_id = $last_invoice_id + 1;
            
            $offline_payment_xdb = new Xdb;
            $offline_payment_row = $offline_payment_xdb->select_fields('*')
                                                       ->set_table($payment->table)
                                                       ->where(array('id' => $payment_id))
                                                       ->db_select(false);
            $payment_info = $offline_payment_row[0];
            
            $payment_info['invoice_id'] = $new_invoice_id;
            $payment_info['datetime_payed'] = $confirmed_date_time;
            
            $invoice = Payment::load_invoice_prop($payment_info);
            
            $payment_update_array = array('invoice_id' => $new_invoice_id, 'datetime_payed' => $confirmed_date_time, 'status' => 'payed', 'invoice' => $invoice);
            
            if ($reference)
            {
                $payment_update_array['reference'] = $reference;
            }
            
            $update_payment_confirmed = new Xdb;
            $update = $update_payment_confirmed->set_table($payment->table)
                                               ->simple_update_fields($payment_update_array)
                                               ->where(array('id' => $payment_id))
                                               ->db_update();
            // run according function
            $payment_session = $_SESSION['payment'];
            $run_module = $payment_session['product_type'];
            if (method_exists($run_module, 'payment_confirmed_action'))
            {
                //call_user_func($run_module . '::payment_confirmed_action');
                call_user_func_array(array($run_module, 'payment_confirmed_action'), array($payment_id));
            }
            
            unset($_SESSION['payment']);
            unset($_SESSION['payment_domain_checked']);
            
            $product_info = json_decode($payment_info['product_info'], true);
        
            Stats::write_stats('Payment', array(
                                            'confirmed_payment', 
                                            'confirmed_payment_amount' => array('value' => $payment_info['discount_price']), 
                                            'confirmed_payment_' . $payment_info['payment_type'],
                                            'confirmed_payment_' . $payment_info['payment_type'] . '_amount' => array('value' => $payment_info['discount_price']),
                                            'confirmed_payment_' . $payment_info['country'],
                                            'confirmed_payment_' . $payment_info['country'] . '_amount' => array('value' => $payment_info['discount_price']),
                                            'confirmed_payment_' . str_replace(' ', '_', $product_info['title']),
                                            'confirmed_payment_' . str_replace(' ', '_', $product_info['title']) . '_amount' => array('value' => $payment_info['discount_price']),
                                          ));
            
            // Google tracking
            $tracker = new GoogleAnalytics\Tracker('MO-42907887-1', 'avantbon.si');
            if (isset($_COOKIE['SSGA_visitor']))
            {
                $visitor = unserialize($_COOKIE['SSGA_visitor']);
            }
            else
            {
                $visitor = unserialize($payment_info['SSGA_visitor']);
            }
            if (isset($_SESSION['SSGA_session']))
            {
                $session = unserialize($_SESSION['SSGA_session']);
            }
            else
            {
                $session = unserialize($payment_info['SSGA_session']);
            }
            $page = new GoogleAnalytics\Page($_SERVER['REQUEST_URI']);
            $page->setTitle('Avantbon - Ecommerce tracking');
            
            $order_id = $payment_id;
            
            $item = new GoogleAnalytics\Item();
            $item->setOrderId($order_id);
            $item->setSku($product_info['id']);
            $item->setName($product_info['title'] . ' - ' . $payment_info['payment_type']);
            $item->setQuantity('1');
            $item->setPrice($payment_info['discount_price']);
            
            $transaction = new GoogleAnalytics\Transaction();
            $transaction->setOrderId($order_id);
            $transaction->setAffiliation('Avantbon');
            $transaction->setTax($payment_info['discount_price'] - $payment_info['discount_price'] / 1.08);
            $transaction->setTotal($payment_info['discount_price']);
            $transaction->setShipping('0.00');
            $transaction->setCity('Ljubljana');
            $transaction->setRegion('Ljubljana');
            $transaction->setCountry('Slovenia');
            $transaction->addItem($item);
            $tracker->trackTransaction($transaction, $session, $visitor);
            
            $event = new GoogleAnalytics\Event();
            $event->setCategory('Uporabnik');    //string, required
            $event->setAction('Potrjen nakup članarine');        //string, required
            $event->setLabel($payment_info['user_id']);          //string, not required
            $event->setNoninteraction('true');
            $event->setValue($payment_info['discount_price']);  
        
            //track event
            $tracker->trackEvent($event,$session,$visitor);
            // end Google tracking
            
            return true;
        }
        else
        {
            unset($_SESSION['payment']);
            Payment::check_payment_session();
            return false;
        }
    }
    
    public function load_invoice_prop($invoice_info = array())
    {
        //global $selected_language, $current_country, $currency, $offer_class, $offer, $image_size, $trim, $title_trim;
        
        $project_id = $invoice_info['project_id'];
        $selected_language = $invoice_info['language'];
        $project_info = Projects::get_project_info_by_id($project_id);
        $project_template = Projects::get_project_template($project_id);
        
        $user_info = json_decode($invoice_info['user_info'], true);
        $product_info = json_decode($invoice_info['product_info'], true);
        
        $invoice_confirmed_time = new DateTime($invoice_info['datetime_payed']);
        $invoice_year = $invoice_confirmed_time->format('Y');
        $invoice_twodigit_year = $invoice_confirmed_time->format('y');
        $invoice_date = $invoice_confirmed_time->format('d.m.Y');
        
        $payment_info = Payment::get_selected_payment_info($invoice_info['payment_type']);
        
        $add_months = $product_info['title'];
        
        $current_time = new DateTime();
        $now = $current_time->format('Y-m-d');
        
        if (isset($user_info['subscription_start_date']) && isset($user_info['subscription_end_date']) && $user_info['subscription_start_date'] != '0000-00-00' && $user_info['subscription_end_date'] != '0000-00-00' && $now <= $user_info['subscription_end_date'])
        {
            $start_date = new DateTime($user_info['subscription_end_date']);
            $new_start_date = $start_date->format('d.m.Y');
            
            $date = new DateTime($user_info['subscription_end_date']);
            $date->modify('+' . $add_months);
            $new_end_date = $date->format('d.m.Y');
            
            $payment_subscription_start = $new_start_date;
            $payment_subscription_end = $new_end_date;
        }
        else
        {
            $new_start_date = $current_time->format('d.m.Y');
            $new_end_date = date('d.m.Y', strtotime('+' . $add_months));
            
            $payment_subscription_start = $new_start_date;
            $payment_subscription_end = $new_end_date;
        }
        
        $css_folder = '/projects/' . $project_info['project_slug'] . '/templates/' . $project_template . '/modules/Payment/props/invoice/css';
        $img_folder = '/projects/' . $project_info['project_slug'] . '/templates/' . $project_template . '/modules/Payment/props/invoice/img';
        
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_info['project_slug'] . '/templates/' . $project_template . '/modules/Payment/props/invoice/invoice.php'))
        {
            $read_css_file = $_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_info['project_slug'] . '/templates/' . $project_template . '/modules/Payment/props/invoice/css/style.css';
            $css_file = file_get_contents($read_css_file);
            $read_file = $_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_info['project_slug'] . '/templates/' . $project_template . '/modules/Payment/props/invoice/invoice.php';
        }
        else
        {
            $read_file = $_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.modules/props.payment/invoice.php';
        }
        
        ob_start();
        include($read_file);
        $prop = ob_get_contents();
        ob_end_clean();
        return $prop;
    }
    
    public function get_user_invoices()
    {
        $user_id = $_SESSION['logged_in_user']['id'];
        $payment = new Payment;
        
        $user_invoices = new Xdb;
        //->select_fields('id, product_type, subscription_start_date, subscription_end_date, invoice')
        $user_invoices_rows = $user_invoices->select_fields('id, product_type, subscription_start_date, subscription_end_date')
                                            ->set_table($payment->table)
                                            ->where(array('user_id' => $user_id, 'status' => 'payed'))
                                            ->order_by('datetime_payed', 'DESC')
                                            ->db_select(false);
        //print_r($user_invoices_rows);
        if (is_array($user_invoices_rows))
        {
            //$counter = 0;
            foreach ($user_invoices_rows as $invoice_key => $invoice)
            {
                $start_date[$invoice_key] = new DateTime($invoice['subscription_start_date']);
                unset($user_invoices_rows[$invoice_key]['subscription_start_date']);
                $user_invoices_rows[$invoice_key]['subscription_start_date'] = $start_date[$invoice_key]->format('d.m.Y');
                
                $end_date[$invoice_key] = new DateTime($invoice['subscription_end_date']);
                unset($user_invoices_rows[$invoice_key]['subscription_end_date']);
                $user_invoices_rows[$invoice_key]['subscription_end_date'] = $end_date[$invoice_key]->format('d.m.Y');
                
                //$counter++;
            }
            
            return $user_invoices_rows;
        }
        else
        {
            return false;
        }
    }
    
    public function get_invoice_by_id($invoice_id)
    {
        $payment = new Payment;
        
        $user_invoices = new Xdb;
        $user_invoices_rows = $user_invoices->select_fields('invoice')
                                            ->set_table($payment->table)
                                            ->where(array('id' => $invoice_id, 'user_id' => $_SESSION['logged_in_user']['id']))
                                            ->limit(1)
                                            ->db_select(false);
        
        return $user_invoices_rows[0]['invoice'];
    }
    
    public function payments_get_count()
    {
        $payment = new Payment;
        $xdb_payments = new Xdb;
        $xdb_payments_rows = $xdb_payments->select_fields('COUNT(*)')
                                          ->set_table($payment->table)
                                          ->where(array('status::!=' => 'storno', 'AND::status::!=' => 'cm'))
                                          ->order_by('id', 'DESC')
                                          ->db_select(false);
        
        return $xdb_payments_rows[0]['COUNT(*)'];
    }
    
    public function payments_get_simple_search_count($search_term)
    {
        $payment = new Payment;
        $xdb_payments = new Xdb;
        $search_term_decoded = base64_decode($search_term);
        
        //$search_array = array('user_info::LIKE' => '%' . $search_term_decoded . '%', 'OR::discount_price::=' => $search_term_decoded, 'OR::discount::=' => $search_term_decoded, 'OR::payment_type::LIKE' => '%' . $search_term_decoded . '%', 'OR::reference::=' => $search_term_decoded, 'OR::user_system::LIKE' => '%' . $search_term_decoded . '%');
        $search_array = array('user_info::LIKE' => '%' . $search_term_decoded . '%', 'OR::payment_type::LIKE' => '%' . $search_term_decoded . '%', 'OR::reference::=' => $search_term_decoded, 'OR::user_system::LIKE' => '%' . $search_term_decoded . '%');
        
        $xdb_payments_rows = $xdb_payments->select_fields('COUNT(*)')
                                          ->set_table($payment->table)
                                          ->where(array('status::!=' => 'storno', 'AND::status::!=' => 'cm'))
                                          ->and_where_group($search_array)
                                          //->group_by('title')
                                          ->db_select(false);
        
        return $xdb_payments_rows[0]['COUNT(*)'];
    }
    
    public function payments_get_all_limit_search($limit_start, $limit, $search_type, $search_term)
    {
        $payment = new Payment;
        $xdb_payments = new Xdb;
        if ($search_type == 'undefined')
        {
            $xdb_payments_rows = $xdb_payments->select_fields('*')
                                        ->set_table($payment->table)
                                        ->where(array('status::!=' => 'storno', 'AND::status::!=' => 'cm'))
                                        ->order_by('id', 'DESC')
                                        ->limit($limit_start . ', ' . $limit)
                                        ->db_select(false);
        }
        elseif ($search_type == 'search-simple')
        {
            $search_term_decoded = base64_decode($search_term);
            
            //$search_array = array('user_info::LIKE' => '%' . $search_term_decoded . '%', 'OR::discount_price::=' => $search_term_decoded, 'OR::discount::=' => $search_term_decoded, 'OR::payment_type::LIKE' => '%' . $search_term_decoded . '%', 'OR::reference::=' => $search_term_decoded, 'OR::user_system::LIKE' => '%' . $search_term_decoded . '%');
            $search_array = array('user_info::LIKE' => '%' . $search_term_decoded . '%', 'OR::payment_type::LIKE' => '%' . $search_term_decoded . '%', 'OR::reference::=' => $search_term_decoded, 'OR::user_system::LIKE' => '%' . $search_term_decoded . '%');
            
            $xdb_payments_rows = $xdb_payments->select_fields('*')
                                        ->set_table($payment->table)
                                        ->where(array('status::!=' => 'storno', 'AND::status::!=' => 'cm'))
                                        ->and_where_group($search_array)
                                        ->order_by('id', 'DESC')
                                        ->limit($limit_start . ', ' . $limit)
                                        ->db_select(false);
        }
        
        if (is_array($xdb_payments_rows))
        {
            
            foreach ($xdb_payments_rows as $payment_key => $payment)
            {
                $user_info = $payment['user_info'];
                $user_info_decoded = json_decode($user_info, true);
                unset($xdb_payments_rows[$payment_key]['user_info']);
                $xdb_payments_rows[$payment_key]['user_info'] = $user_info_decoded;
                
                $product_info = $payment['product_info'];
                $product_info_decoded = json_decode($product_info, true);
                unset($xdb_payments_rows[$payment_key]['product_info']);
                $xdb_payments_rows[$payment_key]['product_info'] = $product_info_decoded;
                
                // datetime registration
                $datetime_created = new DateTime($payment['datetime_created']);
                unset($xdb_payments_rows[$payment_key]['datetime_created']);
                $xdb_payments_rows[$payment_key]['datetime_created'] = $datetime_created->format('d.m.Y H:i:s');
                
                // datetime activation
                if ($payment['status'] == 'payed' && $payment['datetime_payed'] != '0000-00-00 00:00:00')
                {
                    $datetime_confirmed = new DateTime($payment['datetime_payed']);
                    unset($xdb_payments_rows[$payment_key]['datetime_payed']);
                    $xdb_payments_rows[$payment_key]['datetime_payed'] = $datetime_confirmed->format('d.m.Y H:i:s');
                }
                else
                {
                    unset($xdb_payments_rows[$payment_key]['datetime_payed']);
                    $xdb_payments_rows[$payment_key]['datetime_payed'] = 'not confirmed';
                }
                /*
                // datetime last change
                $datetime_last_change = new DateTime($user['datetime_last_change']);
                unset($xdb_users_rows[$user_key]['datetime_last_change']);
                $xdb_users_rows[$user_key]['datetime_last_change'] = $datetime_last_change->format('d.m.Y H:i:s');
                */
            }
            
            return $xdb_payments_rows;
        }
        else
        {
            return false;
        }
    }
    
    public function get_overall_confirmed()
    {
        $xdb_payments = new Xdb;
        $xdb_payments_rows = $xdb_payments->select_fields('SUM(counter_confirmed_payment_amount), SUM(counter_confirmed_payment)')
                                          ->set_table('stats_payment')
                                          ->db_select(false);
        
        $payment_sum['amount'] = $xdb_payments_rows[0]['SUM(counter_confirmed_payment_amount)'];
        $payment_sum['number'] = $xdb_payments_rows[0]['SUM(counter_confirmed_payment)'];
        
        return $payment_sum;
    }
    
    public function get_payment_stats($period = 'day')
    {
        global $today_count, $today_amount, $week_count, $week_amount, $month_count, $month_amount;
        
        $stats_xdb = new Xdb;
        $stats_rows = $stats_xdb->set_table('stats_payment')
                                ->db_select(false);
        
        $current_date = new DateTime();
        $current_day = $current_date->format('Y-m-d');
        $current_week = $current_date->format('Y-W');
        $current_month = $current_date->format('Y-m');
        
        $week_count = 0;
        $week_amount = number_format(0, 2);
        
        $month_count = 0;
        $month_amount = number_format(0, 2);
        
        $stats_rearranged = array();
        foreach ($stats_rows as $row_id => $row)
        {
            // set globals
            if ($row['date'] == $current_day)
            {
                $today_count = $row['counter_confirmed_payment'];
                $today_amount = number_format($row['counter_confirmed_payment_amount'], 2);
            }
            else
            {
                $today_count = 0;
                $today_amount = '0.00';
            }
            
            $get_week = new DateTime($row['date']);
            $the_week = $get_week->format('Y-W');
            
            if ($the_week == $current_week)
            {
                $week_count = $week_count + $row['counter_confirmed_payment'];
                $week_amount = $week_amount + $row['counter_confirmed_payment_amount'];
            }
            
            $get_month = new DateTime($row['date']);
            $the_month = $get_month->format('Y-m');
            
            if ($the_month == $current_month)
            {
                $month_count = $month_count + $row['counter_confirmed_payment'];
                $month_amount = $month_amount + $row['counter_confirmed_payment_amount'];
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
            }
        }
        
        $week_amount = number_format($week_amount, 2);
        $month_amount = number_format($month_amount, 2);
        
        if ($period == 'day')
        {
            //$begin_time = new DateTime($stats_rows[0]['date']);
            $begin_time = new DateTime($stats_rows[$row_id]['date']);
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
}