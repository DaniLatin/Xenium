<?php

/**
 * @author Danijel
 * @copyright 2013
 * @paymentType Paypal
 * @paymentName Paypal
 * @paymentMethod Credit card
 */

class plugin_paypal
{
    public $g2s_settings_structure = array();
    
    public function __construct()
    {
        $this->paypal_settings_structure = array('identity_token' => '', 'email' => '', 'return_url' => '');
    }
    
    public function paypal_settings_structure()
    {
        return json_encode($this->paypal_settings_structure);
    }
    
    public function go_to_paypal()
    {
        global $language_selected, $current_country;
        
        $payment_settings = json_decode($_SESSION['payment']['payment_info']['payment_settings'], true);
        $payment_url = $_SESSION['payment']['payment_info']['payment_url'];
        $business = $payment_settings['email'];
        $return_url = $payment_settings['return_url'];
        /*
        $merchant_id = $payment_settings['merchant_id'];
        $site_id = $payment_settings['site_id'];
        $secret_key = $payment_settings['secret_key'];
        
        $timestamp = new DateTime();
        $timestamp = $timestamp->format('Y-m-d H:i:s');
        */
        
        // get payment info and amounts
        $payment_id = $_SESSION['payment']['payment_id'];
        $paypal_payment = new Payment;
        $payment_info = $paypal_payment->get_payment_info_by_id($payment_id);
        
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
        
        //$g2s_code = md5($secret_key . $merchant_id . $currency . $total_amount . $item . $item_amount . $item_quantity . $timestamp);
        
        $message = array('result' => 1, 'redirect' => $payment_url . '?cmd=_xclick&business=' . $business . '&item_name=' . $item . '&item_number=' . $item_quantity . '&currency_code=EUR&lc=' . $current_country . '&amount=' . $total_amount . '&no_shipping=0&return=' . $return_url);
        return json_encode($message);
    }
    
    public function paypal_check_purchase()
    {
        if (isset($_SESSION['payment']))
        {
            $payment_settings = json_decode($_SESSION['payment']['payment_info']['payment_settings'], true);
            $payment_url = $_SESSION['payment']['payment_info']['payment_url'];
            
            $identity_token = $payment_settings['identity_token'];
            
            //$response['load_template'] = 'modules/Payment/PaymentProgressViewDisplay.tpl';
            header('Location: https://www.paypal.com/cgi-bin/webscr?cmd=_notify-synch&tx=' . $_GET['tx'] . '&at=' . $identity_token);
        }
        else
        {
            $response['result'] = 'error';
            $response['load_template'] = 'modules/Payment/PaymentFailureViewDisplay.tpl';
        }
    }
    
    public function paypal_purchase()
    {
        if (isset($_SESSION['payment']))
        {
            //$payment_status = sanitize($_GET['Status']);
            //if (preg_match('/[^A-Z]/', $payment_status)) die();
            
            $payment_settings = json_decode($_SESSION['payment']['payment_info']['payment_settings'], true);
            $payment_url = $_SESSION['payment']['payment_info']['payment_url'];
            
            $transaction_id = sanitize($_GET['tx']);
            $identity_token = $payment_settings['identity_token'];
            
            /*
            $merchant_id = $payment_settings['merchant_id'];
            $site_id = $payment_settings['site_id'];
            $secret_key = $payment_settings['secret_key'];
            
            $transaction_id = sanitize($_GET['TransactionID']);
            $error_code = sanitize($_GET['ErrCode']);
            $extended_error_code = sanitize($_GET['ExErrCode']);
            
            $checksum = md5($secret_key . $transaction_id . $error_code . $extended_error_code . $payment_status);
            $response_checksum = sanitize($_GET['responsechecksum']);
            */
            
            $paypal_response = file_get_contents($payment_url . '?cmd=_notify-synch&tx=' . $transaction_id . '&at=' . $identity_token);
            //echo $paypal_response;
            //$response_lines = explode('\n', $paypal_response);
            //$response_lines = explode(' ', $paypal_response);
            $response_lines = preg_split('/\s+/', $paypal_response);
            //print_r($response_lines);
            $keyarray = array();
            
            if (strcmp($response_lines[0], 'SUCCESS') == 0)
            {
                for ($i=1; $i<count($response_lines); $i++)
                {
                    if (isset($response_lines[$i]) && $response_lines[$i])
                    {
                        list($key, $val) = explode('=', $response_lines[$i]);
                        $keyarray[urldecode($key)] = urldecode($val);
                    }
                }
                
                if ($keyarray['payment_status'] == 'Completed')
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
            //echo $keyarray['payment_status'] . ' status';
            
            /*
            if ($payment_status == 'SUCCESS' && $checksum == $response_checksum)
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
            */
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