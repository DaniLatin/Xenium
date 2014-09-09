<?php

/**
 * @author Danijel
 * @copyright 2013
 */

require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');

$top_10 = Offer::get_top_10_offers();
$get_3 = array_rand($top_10, 3);

$output_array = array();

$counter = 0;
foreach ($get_3 as $selected)
{
    // images
    $images = json_decode(str_replace('&quot;', '"', $top_10[$selected]['images']), true);
    
    $output_array[$counter]['title'] = strip_tags($top_10[$selected]['title_sl']);
    $output_array[$counter]['img'] = 'http://www.avantbon.si/images/138x88/' . $images[0]['fileYear'] . '/' . $images[0]['fileMonth'] . '/' . $images[0]['fileName'];
    $output_array[$counter]['link'] = 'http://www.siol.net/popusti/ponudba_' . $top_10[$selected]['id'] . '_' . seolinker($top_10[$selected]['title_sl']) . '/';
    
    $counter++;
}

echo json_encode($output_array);
?>