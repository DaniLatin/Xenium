<?php

/**
 * @author Danijel
 * @copyright 2013
 * @paymentType G2S
 * @paymentName G2S
 * @paymentMethod Credit card
 */

class plugin_G2S
{
    public $g2s_settings_structure = array();
    
    public function __construct()
    {
        $this->g2s_settings_structure = array('merchant_id' => '', 'site_id' => '', 'secret_key' => '');
    }
    
    public function g2s_settings_structure()
    {
        return json_encode($this->g2s_settings_structure);
    }
    
    public function go_to_g2s()
    {
        global $language_selected, $current_country;
        
        $payment_settings = json_decode($_SESSION['payment']['payment_info']['payment_settings'], true);
        $payment_url = $_SESSION['payment']['payment_info']['payment_url'];
        $merchant_id = $payment_settings['merchant_id'];
        $site_id = $payment_settings['site_id'];
        $secret_key = $payment_settings['secret_key'];
        
        $timestamp = new DateTime();
        $timestamp = $timestamp->format('Y-m-d H:i:s');
        
        // get payment info and amounts
        $payment_id = $_SESSION['payment']['payment_id'];
        $g2s_payment = new Payment;
        $payment_info = $g2s_payment->get_payment_info_by_id($payment_id);
        
        $currency = 'EUR';
        
        $item = $payment_info['product_type'];
        $total_amount = $payment_info['discount_price'];
        $item_amount = $payment_info['discount_price']  / 1.08;
        if (isset($payment_info['quantity']))
        {
            $item_quantity = $payment_info['quantity'];
        }
        else
        {
            $item_quantity = 1;
        }
        
        $g2s_code = md5($secret_key . $merchant_id . $currency . $total_amount . $item . $item_amount . $item_quantity . $timestamp);
        
        $message = array('result' => 1, 'redirect' => $payment_url . '?&merchant_site_id=' . $site_id . '&merchant_id=' . $merchant_id . '&time_stamp=' . $timestamp . '&total_amount=' . $total_amount . '&currency=EUR&checksum=' . $g2s_code . '&item_name_1='.urlencode($item).'&item_amount_1=' . $item_amount . '&item_quantity_1=' . $item_quantity . '&version=3.0.0&merchantLocale=' . $language_selected . '_' . $current_country . '&total_tax=8');
        return json_encode($message);
    }
    
    public function g2s_purchase()
    {
        if (isset($_SESSION['payment']))
        {
            $payment_status = sanitize($_GET['Status']);
            if (preg_match('/[^A-Z]/', $payment_status)) die();
            
            $payment_settings = json_decode($_SESSION['payment']['payment_info']['payment_settings'], true);
            $payment_url = $_SESSION['payment']['payment_info']['payment_url'];
            $merchant_id = $payment_settings['merchant_id'];
            $site_id = $payment_settings['site_id'];
            $secret_key = $payment_settings['secret_key'];
            
            $transaction_id = sanitize($_GET['TransactionID']);
            $error_code = sanitize($_GET['ErrCode']);
            $extended_error_code = sanitize($_GET['ExErrCode']);
            
            $checksum = md5($secret_key . $transaction_id . $error_code . $extended_error_code . $payment_status);
            $response_checksum = sanitize($_GET['responsechecksum']);
            
            if ($payment_status == 'APPROVED' && $checksum == $response_checksum)
            {
                Payment::confirm_payment($transaction_id);
                
                $response['result'] = 'success';
                $response['load_template'] = 'modules/Payment/PaymentSuccessViewDisplay.tpl';
            }
            else
            {
                $response['result'] = 'error';
                $response['load_template'] = 'modules/Payment/PaymentFailureViewDisplay.tpl';
            }
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