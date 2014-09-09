<?php

/**
 * @author Danijel
 * @copyright 2013
 */

require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');

$offers = Offer::offers_get_all_nocache();

foreach ($offers as $offer)
{
    if (isset($offer['seller_address']) && $offer['seller_address'])
    {
        echo $offer['seller_address'] . '<br />';
        $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=" . urlencode($offer['seller_address']) ."&sensor=false");
        $json = json_decode($json);
        
        if (isset($json->{'results'}[0]))
        {
            $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
            $lng = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
            
            echo $lat . ' --- ' . $lng . '<br /><br />';
            
            $the_offer = new Offer;
            
            $xdb_subcategory_update = new Xdb;
            $update_subcategory_count = $xdb_subcategory_update->set_table('www_offers')
                                                               ->simple_update_fields(array('seller_lat' => $lat, 'seller_lng' => $lng))
                                                               ->where(array('id' => $offer['id']))
                                                               ->db_update();
        }
        
        sleep(1);
    }
}

?>