<?php

/**
 * @author Danijel
 * @copyright 2014
 */


header("Content-Type:text/csv");
header('Content-Disposition: attachement; filename="first_used_vouchers.csv"');
    
require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');

class CSVcommand
{
    public static function get_first_vouchers()
    {
        $user = new Users;
        
        $check_user = new Xdb;
        $check_user_data = $check_user//->select_fields('*')
                                      ->set_table($user->table)
                                      ->left_join('www_payments', array('www_users.id' => 'www_payments.user_id'))
                                      //->where(array('activated' => 1, 'subscription_start_date::!=' => '0000-00-00', 'personal_stats::!=' => '[]'))
                                      ->where(array('activated' => 1, 'personal_stats::!=' => '[]'))
                                      ->db_select(false);
        
        $keys = array();
        $csv = array();
        
        $counter = 0;
        
        foreach ($check_user_data as $user_data)
        {
            $json_history = json_decode($user_data['personal_stats'], true);
            if (is_array($json_history))
            {
                foreach ($json_history as $key => $value)
                {
                    $first_date = $key;
                    
                    foreach ($value as $offer_id => $prices)
                    {
                        $first_offer = $offer_id;
                        $first_original_price = $prices['original_price'];
                        $first_discount_price = $prices['discount_price'];
                        $first_discount = $prices['discount'];
                        
                        $xdb_offer = new Xdb;
                        $xdb_offer_rows = $xdb_offer->select_fields('title_sl')
                                                    ->set_table('www_offers')
                                                    ->where(array('id' => $offer_id))
                                                    ->db_select(false);
                        
                        $first_offer_title = str_replace('&Scaron;', 'Š', str_replace('&scaron;', 'š', $xdb_offer_rows[0]['title_sl']));
                        
                        break;
                    }
                    
                    break;
                }
                
                $product_info = json_decode($user_data['product_info'], true);
                $bought_subscription = $product_info['title'];
                
                $csv[$counter] = array('user_id' => $user_data['user_id'], 
                             'email' => '"' . $user_data['email'] . '"', 
                             'datetime_registration' => $user_data['datetime_registration'], 
                             'subscription_start_date' => $user_data['subscription_start_date'], 
                             'first_print_date' => $first_date, 
                             'first_offer_id' => $first_offer, 
                             'first_offer_title' => '"' . $first_offer_title . '"',
                             'first_offer_original_price' => $first_original_price, 
                             'first_offer_discount_price' => $first_discount_price, 
                             'first_offer_discount' => $first_discount,
                             'bought_subscription' => '"' . $bought_subscription . '"',
                             'subscription_original_price' => $user_data['original_price'],
                             'subscription_discount_price' => $user_data['discount_price'],
                             'subscription_discount' => $user_data['discount']);
                $counter++;
            }
        }
        
        $csv_export = implode(';', array_keys($csv[0])) . '
';
        
        foreach ($csv as $csv_line)
        {
            //print_r($csv_line);
            $csv_export .= implode(';', $csv_line) . '
';
        }
        
        //return $check_user_data;
        return $csv_export;
    }
}

if (isset($_SESSION['admin_user']))
{
    echo CSVcommand::get_first_vouchers();
}
?>