<?php

/**
 * @author Danijel
 * @copyright 2013
 * @paymentType PaymentOrder
 * @paymentName Payment order
 * @paymentMethod Payment order
 */

class plugin_PaymentOrder
{
    public $paymentorder_settings_structure = array();
    
    public function __construct()
    {
        $this->paymentorder_settings_structure = array();
    }
    
    public function paymentorder_settings_structure()
    {
        return json_encode($this->paymentorder_settings_structure);
    }
    
    public function go_to_paymentorder()
    {
        global $language_selected, $current_country;
        
        $payment_url = 'http://' . $_SERVER['HTTP_HOST'] . '/payments/paymentorder/response/';
        
        $message = array('result' => 1, 'redirect' => $payment_url);
        return json_encode($message);
    }
    
    public function paymentorder_purchase()
    {
        global $selected_language, $current_country, $currency;
        
        $currency = Countries::get_country_currency();
        
        if (isset($_SESSION['payment']))
        {
            $reference_info = $_SESSION['payment']['reference_info'];
            $payment_info = $_SESSION['payment'];
            
            $reference_id = $reference_info['reference'];
            
            $email = $_SESSION['logged_in_user']['email'];
            
            $product_id = $payment_info['product_id'];
            $product_type = $payment_info['product_type'];
        
            $get_product_info = call_user_func(array($product_type, 'get_product_info'), $product_id);
            if (isset($get_product_info['prices']['discount_price_' . strtolower($current_country)]))
            {
                $price_to_pay = $get_product_info['prices']['discount_price_' . strtolower($current_country)];
            }
            else
            {
                $price_to_pay = $get_product_info['prices']['discount_price'];
            }
            
            $product_title = $get_product_info['title_' . $selected_language];
            
            $user_info = Users::get_user_info();
            $user_name = $user_info['name'];
            $user_surname = $user_info['surname'];
            $user_address = $user_info['address'];
            
            $mailer = new xMailer;
            $mailer_result = $mailer->send_mail('Payment order instructions email', $email, array('*reference_id*' => $reference_id, '*price_to_pay*' => $price_to_pay, '*currency*' => $currency, '*user_name*' => $user_name, '*user_surname*' => $user_surname, '*user_address*' => $user_address));
            
            unset($_SESSION['payment']);
            
            $response['result'] = 'success';
            $response['load_template'] = 'modules/Payment/PaymentOrderViewDisplay.tpl';
        }
        else
        {
            $response['result'] = 'error';
            $response['load_template'] = 'modules/Payment/PaymentFailureViewDisplay.tpl';
        }
        
        return $response;
    }
}

?>