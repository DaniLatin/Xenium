<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class plugin_reference_SI
{
    function calcSIControlNumber()
    {
        global $calc_nmb;
        $n = $calc_nmb;
        list($i, $v, $p, $c, $n) = array(
            0, 0, 2, NULL, strval($n)
        );
    
        for ($i = strlen($n); $i >= 1; $i--) 
        {
            $c = substr($n, $i-1, 1);
    
            if (is_numeric($v) && $v >= 0) 
            {
                $v += $p * $c;
                $p += 1;
            }
            else 
            {
                $v = -1;
                break;
            }
        }
    
        if ($v>=0) 
        {
            $v = 11 - ($v % 11);
            if ($v > 9) 
            {
                $v = 0;
            }
        }
    
        return $v;
    }
    
    public function generate_reference_si()
    {
        global $current_country, $project_info, $purchase_id, $calc_nmb;
        
        $ref_platform_id = $project_info['ref_id'];
        $year = substr(date("Y"), -1);
        $purchase_id_len = strlen($purchase_id);
        $purchase_id_num_zeros_missing = 6 - $purchase_id_len;
        $ref_country_id = Countries::get_country_ref_id();
        $add_nmb = '10';
        
        if (strlen($ref_country_id) == 1)
        {
            $ref_country_id = '0' . $ref_country_id;
        }
        
        $purchase_id_add_zeros = '';
        
        for ( $counter = 1; $counter <= $purchase_id_num_zeros_missing; $counter += 1) 
        {
            $purchase_id_add_zeros .= '0';
        }
        
        $ref_purchase_id = $add_nmb . $year . $purchase_id_add_zeros . $purchase_id;
        $payment = new Payment;
        $calc_nmb = $add_nmb . $year . $purchase_id_add_zeros . $purchase_id . $ref_platform_id . $ref_country_id;
        $control_number = $payment->calcSIControlNumber();
        
        $reference['reference'] = 'SI12' . $ref_purchase_id . $ref_platform_id . $ref_country_id . $control_number;
        $reference['reference_model'] = 'SI12';
        $reference['reference_id'] = $year . $purchase_id_add_zeros . $purchase_id . $ref_platform_id . $ref_country_id;
        $reference['reference_no'] = $ref_purchase_id . $ref_platform_id . $ref_country_id . $control_number;
        
        return $reference;
    }
}

?>