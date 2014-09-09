<?php

/**
 * @author Danijel
 * @copyright 2013
 * @paymentType Moneta
 * @paymentName Moneta
 * @paymentMethod Moneta
 */

class plugin_Moneta
{
    public $moneta_settings_structure = array();
    public $moneta_table;
    public $moneta_table_fields = array();
    
    public function __construct()
    {
        $this->moneta_settings_structure = array('tariffication_id' => '', 'purchase_url' => '', 'confirmation_url' => '');
        
        $this->moneta_table = 'www_moneta';
        $this->moneta_table_fields = array(
            'static_fields' => array(
                'confirmationid' => array('value' => '', 'field_type' => 'VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'confirmationsignature' => array('value' => '', 'field_type' => 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'tarifficationerror' => array('value' => '', 'field_type' => 'INT( 10 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'startdate' => array('value' => '', 'field_type' => 'DATETIME NOT NULL', 'index' => false),
                'confirmdate' => array('value' => '', 'field_type' => 'DATETIME NOT NULL', 'index' => true, 'admin_editable' => false),
                'refreshcounter' => array('value' => '', 'field_type' => 'INT ( 10 ) NOT NULL', 'index' => true, 'admin_editable' => false),
                'purchasestatus' => array('value' => '', 'field_type' => 'VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => true, 'admin_editable' => false),
                'providerdata' => array('value' => '', 'field_type' => 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL', 'index' => false, 'admin_editable' => false)
            )
        );
    }
    
    public function moneta_settings_structure()
    {
        return json_encode($this->moneta_settings_structure);
    }
    
    public function go_to_moneta()
    {
        global $reference;
        $payment_settings = json_decode($_SESSION['payment']['payment_info']['payment_settings'], true);
        $payment_url = $_SESSION['payment']['payment_info']['payment_url'];
        $tariffication_id = $payment_settings['tariffication_id'];
        
        $payment_moneta = new Payment;
        $payment_moneta->moneta_table_fields['static_fields']['confirmationid']['value'] = $reference;
        $payment_moneta->moneta_table_fields['static_fields']['purchasestatus']['value'] = 'vobdelavi';
        
        $xdb_moneta_payment_insert = new Xdb;
        $last_payment_id = $xdb_moneta_payment_insert->db_insert($payment_moneta->moneta_table, $payment_moneta->moneta_table_fields);
        
        $message = array('result' => 1, 'redirect' => $payment_url . '?TARIFFICATIONID=' . $tariffication_id . '&ConfirmationID=' . $reference);
        return json_encode($message);
    }
    
    public function moneta_purchase()
    {
        Functions_ResponseExpires();
  
        //$sMyName = "http://www.bodbid.com/admin/modules/payment/moneta/www/nakup.php";
        //$sMyName = 'http://' . Functions_GetServerVariable('HTTP_HOST') . Functions_GetServerVariable('SCRIPT_NAME');
        $sMyName = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  
        $sConfirmationID = "";
        $sStatus         = "";
        $sProviderData   = "";
  
        $sStatus      = "";
        $sData        = "";
        $sPrice       = "";
        $sQuantity    = "";
        $sVatRate     = "";
        $sDescription = "";
        $sCurrency    = "";
        $sID_USER     = "";
        
        $return_status = '';
        
        $headers = '';
        
        // Branje parametra ConfirmationID
        $sConfirmationID = Functions_RequestString("ConfirmationID", 32);
  
        // kreiranje CMoneta objekta
        $myMoneta = new CMoneta(DB_HOST, DB_NAME, 'www_moneta', DB_USER, DB_PASS);
        
        // Iskanje ConfirmationID nakupa
        if(!$myMoneta->FindConfirmationID($sConfirmationID))
        {
            $return_status = 'error';
            $sStatus = "ConfirmationID ne obstaja.";
        }
        else
        {
            $nRefreshCounter   = $myMoneta->Get_RefreshCounter();
            $sPurchaseStatus   = $myMoneta->Get_PurchaseStatus();
            $sProviderData     = $myMoneta->Get_ProviderData();
	
            $sPrice          = Functions_GetParameterValue($sProviderData, "Price");
            $sQuantity       = Functions_GetParameterValue($sProviderData, "Quantity");
            $sVatRate        = Functions_GetParameterValue($sProviderData, "VatRate");
            $sDescription    = Functions_GetParameterValue($sProviderData, "Description");
            $sCurrency	     = Functions_GetParameterValue($sProviderData, "Currency");
            $sID_USER        = Functions_GetParameterValue($sProviderData, "ID_USER");

	
            if ($nRefreshCounter>60)
            {
                $return_status = 'error';
                $sStatus = "Potrditev ni uspela.";
      
                $sData   = '<p>Stran se bo osvežila čez 5 sekund</p>';
            }
            else if ($sPurchaseStatus=="vobdelavi")
            {
                $return_status = 'processing';
                $sStatus = "V obdelavi...";
                //$headers .= '<meta http-equiv="refresh" content="1; url=' . $sMyName . '?ConfirmationID=' . $sConfirmationID . '">';
                $headers .= '<meta http-equiv="refresh" content="1; url=' . $sMyName . '">';
            }
            else if ($sPurchaseStatus=="zavrnjeno")
            {
                $return_status = 'error';
                $sStatus = "Potrditvena stran je bila klicana s TARIFFICATIONERROR=1.";
      
                $sData   = '<p>Stran se bo osvežila čez 5 sekund</p>';
            }
            else if ($sPurchaseStatus=="potrjeno")
            {
                $return_status = 'confirmed';
                $myMoneta->SetPurchaseStatus("prikazano", $sConfirmationID);
                
                // payment is successfull
  
                $sStatus = "Potrjevanje uspešno.";
                //$sData   = '<h1>Zahvaljujemo se vam za nakup. Polnili ste za ' . $value .'</h1><p>Stran se bo osvežila čez 5 sekund</p>';
            }
            else
            {
                $return_status = 'error';
                $sStatus = "Napaka.";
                $sData   = '<p>Stran se bo osvežila čez 5 sekund</p>';
            }

            // povečaj števec osvežitev
            $myMoneta->AddRefreshCounter($sConfirmationID);
            
            //$return_answer['status'] = $return_status;
            //$return_answer['headers'] = $headers;
            
            //return $return_answer;
        }
        
        $product_info = Payment::get_payment_info_by_ref($sConfirmationID);
        
        $headers .= "\n<meta name='Price' content='" . $product_info['discount_price'] . "'>\n<meta name='Quantity' content='1'>\n<meta name='VATRate' content='8'>\n<meta name='Description' content='" . $product_info['product_type'] . "'>\n<meta name='Currency' content='EUR'>";
        
        // zapremo povezavo na podatkovno bazo
        $myMoneta->Close();
        
        $return_answer['status'] = $return_status;
        $return_answer['headers'] = $headers;
        
        //return $headers;
        return $return_answer;
    }
    
    public function moneta_confirmation()
    {
        Functions_ResponseExpires();
  
        // branje vhodnih parametrov
        $sConfirmationID        = Functions_RequestString("ConfirmationID", 32);
        $sConfirmationSignature = Functions_RequestString("ConfirmationSignature", 250);
        $nTarifficationError    = Functions_RequestNumber("TARIFFICATIONERROR", 0, 1, 1);
        $sConfirmationIDStatus  = Functions_RequestString("ConfirmationIDStatus", 32);
        $sIP                    = Functions_GetServerVariable('REMOTE_ADDR');
        $sOutput                = "<error>1</error>";
        
        // preverjanje IP Monete
        if (($sIP=="213.229.249.103") || ($sIP=="213.229.249.104") || ($sIP=="213.229.249.117"))
        {  
            // kreiranje CMoneta objekta
            $myMoneta = new CMoneta(DB_HOST, DB_NAME, 'www_moneta', DB_USER, DB_PASS);
  
            // zahtevek za status nakupa?
            if($sConfirmationIDStatus!="")
            {
                if($myMoneta->FindConfirmationID($sConfirmationIDStatus))
                {
                    $sOutput = "<status>" . $myMoneta->Get_PurchaseStatus() . "</status>";
                }
            }
            else
            {
                // Iskanje ConfirmationID nakupa in potrjevanje nakupa
                if($myMoneta->FindConfirmationID($sConfirmationID))
                {
                    $sPurchaseStatus = $myMoneta->Get_PurchaseStatus();
	
                    if($sPurchaseStatus=="vobdelavi")
                    {
                        if($nTarifficationError==0)
                        {
                            $myMoneta->ConfirmPurchase("potrjeno", $sConfirmationID, $sConfirmationSignature, $nTarifficationError);
                            $sOutput = "<error>0</error>";
            
                            // confirm purchase
                            Payment::confirm_payment_offline($sConfirmationID);
                        }
                        else
                        {
                            $myMoneta->ConfirmPurchase("zavrnjeno", $sConfirmationID, $sConfirmationSignature, $nTarifficationError);
                        }
                    }
                }
            }
	
            // zapremo povezavo na podatkovno bazo
            $myMoneta->Close();
	
        }

    // izpišemo <error> ali <status>
    die($sOutput);
    }
}

?>